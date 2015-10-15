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

    public function getIntegrationJsUrl()
    {
        $retval = $this->_getTextConfigValue("general/integration_js_url");

        if ($retval == "") {
            return "//d2jjzw81hqbuqv.cloudfront.net/integration/talkable-1.0.min.js";
        } else {
            return $retval;
        }
    }

    //------------------------+
    // Post-Checkout Campaign |
    //------------------------+

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

    //-----------------+
    // Invite Campaign |
    //-----------------+

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
        return $this->_getIntegrationIframeOptions("affiliate");
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

    //--------------------+
    // Dashboard Campaign |
    //--------------------+

    /**
     * @return bool Whether or not Dashboard Integration is enabled
     */
    public function isDashboardEnabled()
    {
        return $this->_getBoolConfigValue("dashboard/enabled");
    }

    public function getDashboardCampaignTags()
    {
        return $this->_getListConfigValue("dashboard/campaign_tags");
    }

    public function getDashboardIframeOptions()
    {
        return $this->_getIntegrationIframeOptions("dashboard");
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

    private function _getIntegrationIframeOptions($path)
    {
        $width  = $this->_getTextConfigValue($path . "/iframe_width");
        $width  = strpos($width, "%") !== false ? $width : (int) $width;

        $height = $this->_getTextConfigValue($path . "/iframe_height");
        $height = strpos($height, "%") !== false ? $height : (int) $height;

        $container = $this->_getTextConfigValue($path . "/iframe_container");

        return array(
            "responsive" => $this->_getBoolConfigValue($path . "/iframe_responsive"),
            "iframe"     => array(
                "container" => $container ? $container : "talkable-container",
                "width"     => $width     ? $width     : "100%",
                "height"    => $height    ? $height    : 960,
            ),
        );
    }

}
