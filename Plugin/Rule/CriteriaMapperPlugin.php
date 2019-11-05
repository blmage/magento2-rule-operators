<?php
/**
 * @package    BL_RuleOperators
 * @author     Falco Nogatz <fnogatz@gmail.com>
 * @copyright  Copyright (c) 2019 Falco Nogatz
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace BL\RuleOperators\Plugin\Rule;

use Magento\CatalogRule\Model\Rule\Condition\Combine as CombinedCondition;
use Magento\CatalogRule\Model\Rule\Condition\Product as SimpleCondition;
use Magento\CatalogRule\Model\Rule\Condition\ConditionsToSearchCriteriaMapper;
use BL\RuleOperators\Helper\Data;

class CriteriaMapperPlugin
{
    public function beforeMapConditionsToSearchCriteria(ConditionsToSearchCriteriaMapper $subject, CombinedCondition $conditions)
    {
        foreach ($conditions->getConditions() as $condition) {
            if ($condition->getType() === SimpleCondition::class) {
                switch ($condition->getOperator()) {
                    case Data::OPERATOR_START_WITH:
                        $condition->setOperator('{}');
                        $condition->setValue($condition->getValue().'%');
                        break;
                    case Data::OPERATOR_NOT_START_WITH:
                        $condition->setOperator('!{}');
                        $condition->setValue($condition->getValue().'%');
                        break;
                    case Data::OPERATOR_END_WITH:
                        $condition->setOperator('{}');
                        $condition->setValue('%'.$condition->getValue());
                        break;
                    case Data::OPERATOR_NOT_END_WITH:
                        $condition->setOperator('!{}');
                        $condition->setValue('%'.$condition->getValue());
                        break;
                    case Data::OPERATOR_MATCH_REGEX:
                    case Data::OPERATOR_NOT_MATCH_REGEX:
                        throw new InputException(
                            __('RegExp are not yet allowed in Indexer')
                        );
                        break;
                }
            }
        }

        return [$conditions];
    }
}
