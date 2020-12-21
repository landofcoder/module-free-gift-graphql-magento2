<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Landofcoder
 * @package     Lof_GiftSaleRuleGraphQl
 * @copyright   Copyright (c) Landofcoder (https://landofcoder.com/)
 * @license     https://landofcoder.com/LICENSE.txt
 */

declare(strict_types=1);

namespace Lof\GiftSaleRuleGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Lof\GiftSaleRuleGraphQl\Model\Data\MaskedCart;
use Lof\GiftSalesRule\Api\ProductGiftInterface;
use Magento\Checkout\Model\Session;
/**
 * Class DeleteByQuoteItem
 * @package Lof\GiftSaleRuleGraphQl\Model\Resolver
 */
class DeleteByQuoteItem implements ResolverInterface
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;

    /**
     * @var MaskedCart
     */
    protected $_maskedCart;

    /**
     * DeleteByQuoteItem constructor.
     * @param ProductGiftInterface $productGift
     * @param MaskedCart $maskedCart
     */
    public function __construct(
        ProductGiftInterface $productGift,
        MaskedCart $maskedCart,
        Session $session
    ) {
        $this->_productGift = $productGift;
        $this->_maskedCart = $maskedCart;
        $this->session = $session;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->validateArgs($args);
        $result = $this->_productGift->deleteGiftByQuoteItemId((int) $args['cart_id'], $args['item_id']);

        if (is_object($result) && $result->getStatus() === 'error') {
            throw new GraphQlInputException($result->getMessage());
        }

        return true;
    }

    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    public function validateArgs($args)
    {
        if (!isset($args['item_id'])) {
            throw new GraphQlInputException(__('Required parameter "item_id" is missing'));
        }

        if (!isset($args['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }
    }
}
