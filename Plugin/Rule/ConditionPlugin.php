<?php
/**
 * @package    BL_RuleOperators
 * @author     Benoît Leulliette <benoit.leulliette@gmail.com>
 * @copyright  Copyright (c) 2018 Benoît Leulliette
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace BL\RuleOperators\Plugin\Rule;

use Magento\Rule\Model\Condition\AbstractCondition;


class ConditionPlugin
{
    const INPUT_TYPE_STRING = 'string';

    const OPERATOR_START_WITH = 'blro-^_';
    const OPERATOR_NOT_START_WITH = '!blro-^_';
    const OPERATOR_END_WITH = 'blro-_$';
    const OPERATOR_NOT_END_WITH = '!blro-_$';
    const OPERATOR_MATCH_REGEX = 'blro-regex';
    const OPERATOR_NOT_MATCH_REGEX = '!blro-regex';

    const OPERATORS = [
        self::OPERATOR_START_WITH,
        self::OPERATOR_NOT_START_WITH,
        self::OPERATOR_END_WITH,
        self::OPERATOR_NOT_END_WITH,
        self::OPERATOR_MATCH_REGEX,
        self::OPERATOR_NOT_MATCH_REGEX,
    ];

    /**
     * @param AbstractCondition $subject
     * @param array $operators
     * @return array
     */
    public function afterGetDefaultOperatorInputByType(AbstractCondition $subject, array $operators)
    {
        if (isset($operators[self::INPUT_TYPE_STRING]) && is_array($operators[self::INPUT_TYPE_STRING])) {
            $operators[self::INPUT_TYPE_STRING] = array_merge(
                $operators[self::INPUT_TYPE_STRING],
                [
                    self::OPERATOR_START_WITH,
                    self::OPERATOR_NOT_START_WITH,
                    self::OPERATOR_END_WITH,
                    self::OPERATOR_NOT_END_WITH,
                    self::OPERATOR_MATCH_REGEX,
                    self::OPERATOR_NOT_MATCH_REGEX,
                ]
            );
        }

        return $operators;
    }

    /**
     * @param AbstractCondition $subject
     * @param array $options
     * @return array
     */
    public function afterGetDefaultOperatorOptions(AbstractCondition $subject, array $options)
    {
        $options[self::OPERATOR_START_WITH] = __('starts with');
        $options[self::OPERATOR_NOT_START_WITH] = __('does not start with');
        $options[self::OPERATOR_END_WITH] = __('ends with');
        $options[self::OPERATOR_NOT_END_WITH] = __('does not end with');
        $options[self::OPERATOR_MATCH_REGEX] = __('matches regular expression');
        $options[self::OPERATOR_NOT_MATCH_REGEX] = __('does not match regular expression');
        return $options;
    }

    /**
     * @param AbstractCondition $subject
     * @param callable $proceed
     * @param mixed $validatedValue
     * @return bool
     */
    public function aroundValidateAttribute(AbstractCondition $subject, callable $proceed, $validatedValue)
    {
        $operator = $subject->getOperatorForValidate();

        if (in_array($operator, self::OPERATORS, true)) {
            if (is_object($validatedValue)) {
                return false;
            }

            $value = $subject->getValueParsed();
            $result = null;

            switch ($operator) {
                case self::OPERATOR_START_WITH:
                case self::OPERATOR_NOT_START_WITH:
                    $result = (bool) preg_match('/^' . preg_quote($value, '/') . '/iu', $validatedValue);
                    break;
                case self::OPERATOR_END_WITH:
                case self::OPERATOR_NOT_END_WITH:
                    $result = (bool) preg_match('/' . preg_quote($value, '/') . '$/iu', $validatedValue);
                    break;
                case self::OPERATOR_MATCH_REGEX:
                case self::OPERATOR_NOT_MATCH_REGEX:
                    $result = (bool) @preg_match($this->prepareMatchableRegex($value), $validatedValue);
                    break;
            }


            $isNegatedOperator = '!' === substr($operator, 0, 1);
            return (null !== $result) ? ($isNegatedOperator ? !$result : $result) : false;
        }

        return $proceed($validatedValue);
    }

    /**
     * @param string $start
     * @param string $end
     * @return bool
     */
    private function isValidRegexDelimiters($start, $end)
    {
        return ($start === $end)
            || ('(' === $start) && (')' === $end)
            || ('{' === $start) && ('}' === $end)
            || ('[' === $start) && (']' === $end)
            || ('<' === $start) && ('>' === $end);
    }

    /**
     * @param string $regex
     * @return string
     */
    private function prepareMatchableRegex($regex)
    {
        /**
         * Check whether a full PCRE regex has been provided.
         * @see http://php.net/manual/en/regexp.reference.delimiters.php
         * @see http://php.net/manual/en/reference.pcre.pattern.modifiers.php
         */
        if (preg_match('/^([^\p{L}\d\s\\\])(.+)?([^\p{L}\d\s\\\])[imsuxADJSUX]*$/iu', $regex, $matches)
            && $this->isValidRegexDelimiters($matches[1], $matches[3])
        ) {
            return $regex;
        }

        // Build a full regex otherwise, escaping any delimiter which was not already.
        return '/' . preg_replace('~((?<!\\\)(\\\{2})*/)~', '\\\$1', $regex) . '/iu';
    }
}
