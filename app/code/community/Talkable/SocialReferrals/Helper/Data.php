<?php
/**
 * Talkable SocialReferrals for Magento
 *
 * @package     Talkable_SocialReferrals
 * @author      Talkable (http://www.talkable.com/)
 * @copyright   Copyright (c) 2015 Talkable (http://www.talkable.com/)
 * @license     MIT
 */

class Talkable_SocialReferrals_Helper_Data extends Mage_Core_Helper_Abstract
{

    //------------------+
    // Default Settings |
    //------------------+

    public function getSiteId()
    {
        return $this->_getTextConfigValue("general/site_id");
    }

    //---------------------------+
    // Post-Checkout Integration |
    //---------------------------+

    /**
     * @return bool Whether or not Post-Checkout Integration is enabled
     */
    public function isPurchaseEnabled()
    {
        return $this->_getBoolConfigValue("purchase/enabled");
    }

    public function getPurchaseCampaignTags()
    {
        return $this->_getListConfigValue("purchase/campaign_tags");
    }

    public function getPurchaseData($order)
    {
        $retval = array(
            "order_number" => $order->getIncrementId(),
            "order_date"   => $order->getCreatedAt(),
            "subtotal"     => $order->getSubtotal(),
            "coupon_code"  => $order->getCouponCode(),
            "customer_id"  => $order->getCustomerId(),
            "email"        => $order->getCustomerEmail(),
            "first_name"   => $order->getCustomerFirstname(),
            "last_name"    => $order->getCustomerLastname(),
            "items"        => array(),
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

    //------------------------+
    // Standalone Integration |
    //------------------------+

    /**
     * @return bool Whether or not Standalone Integration is enabled
     */
    public function isAffiliateEnabled()
    {
        return $this->_getBoolConfigValue("affiliate/enabled");
    }

    public function getAffiliateCampaignTags()
    {
        return $this->_getListConfigValue("affiliate/campaign_tags");
    }

    public function getAffiliateIframeOptions()
    {
        $width  = $this->_getTextConfigValue("affiliate/iframe_width");
        $width  = strpos($width, "%") !== false ? $width : (int) $width;

        $height = $this->_getTextConfigValue("affiliate/iframe_height");
        $height = strpos($height, "%") !== false ? $height : (int) $height;

        $container = $this->_getTextConfigValue("affiliate/iframe_container");

        return array(
            "responsive" => $this->_getBoolConfigValue("affiliate/iframe_responsive"),
            "iframe"     => array(
                "container" => $container ? $container : "talkable-container",
                "width"     => $width     ? $width     : "100%",
                "height"    => $height    ? $height    : 960,
            ),
        );
    }

    public function getAffiliateData()
    {
        $helper = Mage::helper("customer");

        if ($helper->isLoggedIn()) {
            $customer = $helper->getCustomer();
            return array("affiliate_member" => array(
                "email"       => $customer->getEmail(),
                "first_name"  => $customer->getFirstname(),
                "last_name"   => $customer->getLastname(),
                "customer_id" => $customer->getId(),
            ));
        } else {
            return array("affiliate_member" => array());
        }
    }

    //---------+
    // Private |
    //---------+

    private function _getBoolConfigValue($path)
    {
        return (bool) Mage::getStoreConfig("socialreferrals/" . $path);
    }

    private function _getListConfigValue($path)
    {
        return array_filter(array_map("trim", explode(",", Mage::getStoreConfig("socialreferrals/" . $path))));
    }

    private function _getTextConfigValue($path)
    {
        return trim(Mage::getStoreConfig("socialreferrals/" . $path));
    }

}
