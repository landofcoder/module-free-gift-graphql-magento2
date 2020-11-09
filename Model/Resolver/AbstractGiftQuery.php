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

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Quote\Model\QuoteFactory;
use Lof\GiftSaleRule\Api\ProductGiftInterface;

/**
 * Class AbstractGiftQuery
 * @package Lof\GiftSaleRuleGraphQl\Model\Resolver
 */
abstract class AbstractGiftQuery
{
    /**
     * @var ProductGiftInterface
     */
    protected $_productGift;
    
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;
    
    /**
     * @var QuoteFactory
     */
    protected $_quoteFactory;
    
    /**
     * @var int
     */
    protected $_quoteFlag;
    
    /**
     * AbstractGift constructor.
     * @param ProductGiftInterface $productGift
     * @param QuoteFactory $quoteFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductGiftInterface $productGift,
        QuoteFactory $quoteFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->_productGift = $productGift;
        $this->_quoteFactory = $quoteFactory;
        $this->_productRepository = $productRepository;
    }
    
    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    protected function validateArgs(array $args)
    {
        if ($this->_quoteFlag && !isset($args['cart_id'])) {
            throw new GraphQlInputException(__('Cart id is required.'));
        }
        if ($this->_quoteFlag) {
            $quoteModel = $this->_quoteFactory->create();
            $this->_quoteFlag = $quoteModel->load($args['cart_id'])->getId();
            if (!$this->_quoteFlag) {
                throw new GraphQlInputException(__('This cart does not exist.'));
            }
        }
    }
}
