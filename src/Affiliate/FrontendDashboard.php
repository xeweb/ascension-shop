<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 28/08/2019
 * Time: 11:24
 */

namespace AscensionShop\Affiliate;


use AscensionShop\Lib\TemplateEngine;

class FrontendDashboard
{

    public function __construct()
    {

        // Add a th to the referrals table in the affiliate area.
        add_action('affwp_referrals_dashboard_th', array($this, 'totalAmount'));

        // Add a td to the referrals table in the affiliate area.
        add_action('affwp_referrals_dashboard_td', array($this, 'totalAmount_td'));

	    add_filter('affwp_affiliate_area_tabs', array($this, "addExtraTabs"));

	    add_filter('affwp_template_paths', array($this, "addCustomTemplateFolder"));

    }

    /**
     * Th for the lifetime referral column.
     *
     * @since 1.3
     */
    public function totalAmount()
    {
        ?>
        <th class="order-total-ex-btw"><?php _e('Totaal ex btw', 'ascension-shop'); ?></th>
        <th class="order-total-inc-btw"><?php _e('Totaal inc btw', 'ascension-shop'); ?></th>
        <th class="order-customer"><?php _e('Klant', 'ascension-shop'); ?></th>

        <?php
    }




    public function totalAmount_td($ref)
    {

        $order_id = $ref->reference;
        $order = new \WC_Order($order_id);
        $user = $order->get_user();

        ?>
        <td>&euro; <?php echo round($order->get_total() - $order->get_total_tax(), 2); ?></td>
        <td>&euro; <?php echo round($order->get_total(), 2); ?></td>
        <td><?php echo $user->first_name . " " . $user->last_name; ?></td>
        <?php
    }


	/**
	 * @param $tabs
	 * @return mixed
	 */
	public function addExtraTabs($tabs)
	{
		wp_enqueue_style("ascension-info-css", XE_ASCENSION_SHOP_PLUGIN_DIR . "/assets/css/refferal-order-info.min.css",null,"1.0.1.1");

		unset($tabs["referrals"]);
	    unset($tabs["lifetime-customers"]);

		$tabs["commission-overview"] = __("Commissies", "ascension-shop");
		$tabs["clients-overview"] = __("Klanten", "ascension-shop");
		$tabs["partner-overview"] = __("Partners", "ascension-shop");

		return $tabs;
	}
	/**
	 * @param $paths
	 * @return array
	 */
	public function addCustomTemplateFolder($paths)
	{
		$paths[] = XE_ASCENSION_SHOP_PLUGIN_TEMPLATE_PATH . 'affiliate-wp/';
		return $paths;
	}


}