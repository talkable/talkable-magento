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

    public function getCheckoutOrder()
    {
        return Mage::getResourceModel("sales/order_collection")
                   ->addFieldToSelect("*")
                   ->addFieldToFilter("customer_id", Mage::getSingleton("customer/session")->getCustomer()->getId())
                   ->setOrder("created_at", "DESC")
                   ->getFirstItem();
    }

}
