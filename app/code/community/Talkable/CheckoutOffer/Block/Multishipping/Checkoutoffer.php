<?php
/**
 * Talkable CheckoutOffer for Magento
 *
 * @package     Talkable_CheckoutOffer
 * @author      Talkable (http://www.talkable.com/)
 * @copyright   Copyright (c) 2015 Talkable (http://www.talkable.com/)
 * @license     MIT
 */

class Talkable_CheckoutOffer_Block_Multishipping_Checkoutoffer extends Mage_Checkout_Block_Multishipping_Success
{

    public function getSiteId()
    {
        return trim(Mage::getStoreConfig("checkoutoffer/settings/site_id"));
    }

    public function getCampaignTags()
    {
        return array_filter(array_map("trim", explode(",", Mage::getStoreConfig("checkoutoffer/settings/campaign_tags"))));
    }

    public function getOrderData()
    {
        $order = Mage::getModel("sales/order")->loadByIncrementId($this->getOrderId());

        $retval = array(
            "order_number" => $order->getIncrementId(),
            "order_date"   => $order->getCreatedAt(),
            "email"        => $order->getCustomerEmail(),
            "subtotal"     => $order->getSubtotal(),
            "coupon_code"  => $order->getCouponCode(),
            "customer_id"  => $order->getCustomerId(),
            "items"        => array(),
            "first_name"   => $order->getCustomerFirstname(),
            "last_name"    => $order->getCustomerLastname(),
        );

        foreach ($order->getAllVisibleItems() as $product) {
            $retval["items"][] = array(
                "product_id" => $product->getSku(),
                "price"      => $product->getPrice(),
                "quantity"   => $product->getQtyOrdered(),
                "title"      => $product->getName(),
            );
        }

        return $retval;
    }

}
