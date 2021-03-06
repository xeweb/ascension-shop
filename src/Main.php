<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 24/06/2019
 * Time: 11:30
 */

namespace AscensionShop;

use AscensionShop\PartnerArea\AddClients;
use AscensionShop\Affiliate\ClientCouponManager;
use AscensionShop\Affiliate\Mails;
use AscensionShop\Affiliate\RateLevelsInit;
use AscensionShop\Affiliate\ReferralManager;
use AscensionShop\Affiliate\StandardRefferal;
use AscensionShop\Affiliate\UserOptions;
use AscensionShop\Affiliate\VisitManager;
use AscensionShop\Affiliate\Waterfall;
use AscensionShop\Affiliate\WoocommerceCheckOut;
use AscensionShop\Import\DiscountImporter;
use AscensionShop\Lib\MessageHandeling;
use AscensionShop\NationalManager\Frontend;
use AscensionShop\NationalManager\NationalManager;
use AscensionShop\PartnerArea\FrontendDashboard;
use AscensionShop\Reports\AffiliateReports;
use AscensionShop\Reports\BackendReports;
use AscensionShop\Reports\FrontendReports;
use AscensionShop\Shipping\Console;
use AscensionShop\Shipping\ConsoleApi;
use AscensionShop\Shipping\WC_Custom_Email;
use AscensionShop\Woocommerce\AdminEmails;
use AscensionShop\Woocommerce\AdminOrders;
use AscensionShop\Woocommerce\Custom_WC_emails;
use AscensionShop\Woocommerce\MyOrders;
use AscensionShop\Woocommerce\Optimalizations;
use AscensionShop\Woocommerce\OrderHooks;
use AscensionShop\Import\CustomerImporter;
// use AscensionShop\Import\DiscountImporter;

class Main
{

    public function __construct()
    {


        // Locate wc template to overwrite
        add_filter('woocommerce_locate_template', array($this, 'myplugin_woocommerce_locate_template'), 1, 1000);

        // Load global hooks
        $this->globalHooks();

        // Load backend classes
        if (is_admin()) {
            $this->loadBackend();
        } else {
            // Load frontend hooks
            //$this->loadFrontend();
        }


    }

    /**
     * Load all backend classes
     */
    public function loadBackend()
    {

	    new BackendReports();

        // Console
        new Console();

        // Admin Orders
        new AdminOrders();

        // Legacy for customer & Discount Import
        new CustomerImporter();
        new DiscountImporter();

	    new AffiliateReports();

    }

    /**
     * Hooks for frontend & backend
     */
    public function globalHooks()
    {

    	new NationalManager();

        new MessageHandeling();

        // Woocommerce checkout
        new WoocommerceCheckOut();

        // Load Rate levels
        new RateLevelsInit();

        // Manage refs
        new ReferralManager();

        // Custom Emails for woocommerce
        new WC_Custom_Email();

        // Console Api
        new ConsoleApi();

        // Woocommerce order related hooks
        new OrderHooks();

        // User options Affiliate
        new UserOptions();

        new VisitManager();

        // Get the client coupon
        new ClientCouponManager();
        
	    /**
	     * Partner Area
	     */
        // Frontend dashboard options
        new FrontendDashboard();

        // Add clients on frontend
        new AddClients();

        // Standard Refferals
        new StandardRefferal();

        new AdminEmails();

        new Optimalizations();

        new MyOrders();

        new Mails();

        // Get a report
        new FrontendReports();

	    /**
	     * National Manager Frontend
	     */
        new Frontend();

        // Custom emails from woocommerce
        new Custom_WC_emails();
    }


    /**
     * @param $template
     * @param $template_name
     * @param $template_path
     *
     * @return string
     */
    public function myplugin_woocommerce_locate_template($template, $template_name, $template_path)
    {

        global $woocommerce;
        $_template = $template;
        if (!$template_path) $template_path = $woocommerce->template_url;
        $plugin_path = XE_ASCENSION_SHOP_PLUGIN_TEMPLATE_PATH . '/woocommerce/';
        // Look within passed path within the theme - this is priority
        $template = locate_template(
            array(
                $template_path . $template_name, $template_name
            )
        );


        // Modification: Get the template from this plugin, if it exists
        if (!$template && file_exists($plugin_path . $template_name))
            $template = $plugin_path . $template_name;

        // Use default template
        if (!$template)
            $template = $_template;

        // Return what we found
        return $template;
    }

}