<?php
/**
 * @package    BL_RuleOperators
 * @author     Falco Nogatz <fnogatz@gmail.com>
 * @copyright  Copyright (c) 2019 Falco Nogatz
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace BL\RuleOperators\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
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
}
