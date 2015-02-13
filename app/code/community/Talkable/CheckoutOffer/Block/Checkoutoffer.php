<?php
/**
 * Talkable CheckoutOffer for Magento
 *
 * @package     Talkable_CheckoutOffer
 * @author      Talkable (http://www.talkable.com/)
 * @copyright   Copyright (c) 2015 Talkable (http://www.talkable.com/)
 * @license     MIT
 */

class Talkable_CheckoutOffer_Block_Checkoutoffer extends Mage_Checkout_Block_Onepage_Success
{

    public function getCheckoutOrder()
    {
        if ($this->getOrderId()) {
            return Mage::getModel("sales/order")->loadByIncrementId($this->getOrderId());
        }
    }

}
