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
use Lof\GiftSalesRule\Api\GiftRuleServiceInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\ProductOptionInterfaceFactory;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;

/**
 * Class AddGift
 * @package Lof\GiftSaleRuleGraphQl\Model\Resolver
 */
class AddGift implements ResolverInterface
{


    /**
     * @var ProductOptionInterfaceFactory
     */
    protected $_productOption;


    /**
     * @var MaskedCart
     */
    protected $_maskedCart;
    /**
     * @var GiftRuleServiceInterface
     */
    private $_productGift;
    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * AddByGiftId constructor.
     * @param GiftRuleServiceInterface $productGift
     * @param ProductOptionInterfaceFactory $productOption
     * @param MaskedCart $maskedCart
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        GiftRuleServiceInterface $productGift,
        ProductOptionInterfaceFactory $productOption,
        MaskedCart $maskedCart,
        CartRepositoryInterface $quoteRepository

    ) {
        $this->_productGift = $productGift;
        $this->_productOption = $productOption;
        $this->_maskedCart = $maskedCart;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $this->validateArgs($args);
        $quote = $this->quoteRepository->getActive($args['cart_id']);
        $result = $this->_productGift->addGiftProducts($quote, $args['products'], $args['gift_rule_code'], $args['gift_rule_id']);

        return [
            'status' => $result->getStatus(),
            'message' => $result->getMessage(),
            'rule_id' => $result->getRuleId(),
            'quote_id' => $result->getQuoteId(),
            'quote_item_id' => $result->getQuoteItemId(),
            'product_gift_id' => $result->getProductGiftId()
        ];
    }

    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    public function validateArgs($args)
    {
        if (!isset($args['input'])) {
            throw new GraphQlInputException(__('Required parameter "input" is missing'));
        }

        if (!isset($args['input']['cart_id'])) {
            throw new GraphQlInputException(__('Required parameter "cart_id" is missing'));
        }

        if (!isset($args['input']['gift_rule_id'])) {
            throw new GraphQlInputException(__('Required parameter "gift_rule_id" is missing'));
        }

        if (!isset($args['input']['gift_rule_code'])) {
            throw new GraphQlInputException(__('Required parameter "gift_rule_code" is missing'));
        }

        if (!isset($args['input']['products'])) {
            throw new GraphQlInputException(__('Required parameter "products" is missing'));
        }
    }

    /**
     * @param array $data
     * @param ContextInterface $context
     * @return AddGiftItemInterface
     * @throws GraphQlInputException
     */
    public function getAddGiftItem($data, $context)
    {
        $configOptions = [];
        $giftItem = $this->_addGiftItem->create();
        $productOption = $this->_productOption->create();
        $productOptionExt = $productOption->getExtensionAttributes();

        try {
            $cart = $this->_maskedCart->getCartByMaskedId((string) $data['cart_id'], $context);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }

        $giftItem->setRuleId($data['rule_id'])->setQuoteId($cart->getId())->setGiftId($data['gift_id']);
        if (isset($data['configurable_options']) && $productOptionExt) {
            foreach ($data['configurable_options'] as $item) {
                $configOption = $this->_ConfigurableItemOption->create();
                $configOption->setOptionId($item['option_id']);
                $configOption->setOptionValue($item['option_value']);
                $configOptions[] = $configOption;
            }
        }

        if (!empty($configOptions)) {
            $giftOptionExt = $productOptionExt->setConfigurableItemOptions($configOptions);
            $giftItem->setProductOption($productOption->setExtensionAttributes($giftOptionExt));
        }

        return $giftItem;
    }
}
