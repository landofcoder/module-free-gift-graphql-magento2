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

namespace Lof\GiftSaleRuleGraphQl\Model\Data;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Quote\Model\Quote;
use Magento\QuoteGraphQl\Model\Cart\GetCartForUser;
/**
 * Class MaskedCart
 * @package Lof\GiftSaleRuleGraphQl\Model\Data
 */
class MaskedCart
{
    /**
     * @var GetCartForUser
     */
    protected $_getCartForUser;
    
    /**
     * MaskedCart constructor.
     * @param GetCartForUser $getCartForUser
     */
    public function __construct(
        GetCartForUser $getCartForUser
    ) {
        $this->_getCartForUser = $getCartForUser;
    }
    
    /**
     * @param string $maskedId
     * @param ContextInterface $context
     * @return Quote
     * @throws NoSuchEntityException
     * @throws GraphQlAuthorizationException
     * @throws GraphQlNoSuchEntityException
     */
    public function getCartByMaskedId($maskedId, $context)
    {
        $storeId = (int) $context->getExtensionAttributes()->getStore()->getId();
        return $this->_getCartForUser->execute($maskedId, $context->getUserId(), $storeId);
    }
}
