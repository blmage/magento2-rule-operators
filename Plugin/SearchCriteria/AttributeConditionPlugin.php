<?php
/**
 * @package    BL_RuleOperators
 * @author     Falco Nogatz <fnogatz@gmail.com>
 * @copyright  Copyright (c) 2019 Falco Nogatz
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace BL\RuleOperators\Plugin\SearchCriteria;

use Magento\Framework\Api\Filter;
use Magento\Catalog\Model\Api\SearchCriteria\CollectionProcessor\ConditionProcessor\ConditionBuilder\NativeAttributeCondition;

class AttributeConditionPlugin
{
    public function afterBuild(NativeAttributeCondition $subject, string $filter): string {
        $filter = preg_replace("/LIKE '%([^']+)%%'/", "LIKE '$1%'", $filter);
        $filter = preg_replace("/LIKE '%%([^']+)%'/", "LIKE '%$1'", $filter);

        return $filter;
    }
}
