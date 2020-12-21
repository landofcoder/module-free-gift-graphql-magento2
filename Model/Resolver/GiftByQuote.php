<?php


namespace Lof\GiftSaleRuleGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class GiftByQuoteItem
 * @package Lof\GiftSaleRuleGraphQl\Model\Resolver
 */
class GiftByQuote extends AbstractGiftQuery implements ResolverInterface
{
    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->_quoteFlag = 1;
        $this->validateArgs($args);

        return $this->_productGift->getGiftsByQuoteId($args['cart_id']);
    }
}
