<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 29/08/2019
 * Time: 14:19
 */

namespace AscensionShop\Affiliate;


use AscensionShop\Lib\MessageHandeling;
use AscensionShop\Lib\TemplateEngine;

class AddClients
{

    public function __construct()
    {
        add_action("ascension-after-clients", array($this, "addClientForm"));
        add_action('admin_post_ascension-save_add-client', array($this, "saveNewClient"), 10, 1);

    }

    public function addClientForm()
    {
        $t = new TemplateEngine();
        $t->affiliate_id = affwp_get_affiliate_id(get_current_user_id());
        echo $t->display("affiliate-wp/add-client-form.php");
    }

    /**
     * Save a new client
     */
    public function saveNewClient()
    {

        // Woocommerce fix
        $this->check_prerequisites();

        // Get affiliate & nonce verify
        $affiliate_id = affwp_get_affiliate_id(get_current_user_id());
        $nonce_verify = wp_verify_nonce($_REQUEST['_wpnonce'], 'ascension_add_new_customer_' . $affiliate_id);

        // Setup a username
        $username = strtolower($_REQUEST["name"] . "." . $_REQUEST["lastname"]);

        if ($nonce_verify == true && $affiliate_id > 0) {
            // Add a new user to wordpress
            $user_id = wc_create_new_customer($_REQUEST['email'], $username, wp_generate_password());

            // Add a new affiliate customer
            if (!is_wp_error($user_id)) {
                $customer = affwp_add_customer(array(
                    'first_name' => $_REQUEST["name"],
                    'last_name' => $_REQUEST["lastname"],
                    'email' => $_REQUEST["email"],
                    'user_id' => $user_id,
                    'affiliate_id' => $affiliate_id,
                    'date_created' => date()
                ));

                // Update client discount
                update_user_meta($user_id, "ascension_shop_affiliate_coupon", $_REQUEST["discount"]);

                if ($customer > 0) {
                    MessageHandeling::setMessage(__("Nieuw klant succesvol aangemaakt", "ascension-shop"), "success");
                } else {
                    MessageHandeling::setMessage(__("Er ging iets mis, probeer het opnieuw.", "ascension-shop"), "error");

                }
            } else {
                MessageHandeling::setMessage($user_id->get_error_message(), "error");
            }
        }

        wp_safe_redirect($_REQUEST["_wp_http_referer"]);

    }

    /**
     * Check any prerequisites required for our add to cart request.
     */
    private function check_prerequisites()
    {
        if (defined('WC_ABSPATH')) {
            // WC 3.6+ - Cart and notice functions are not included during a REST request.
            include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
            include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
        }

        if (null === WC()->session) {
            $session_class = apply_filters('woocommerce_session_handler', 'WC_Session_Handler');

            //Prefix session class with global namespace if not already namespaced
            if (false === strpos($session_class, '\\')) {
                $session_class = '\\' . $session_class;
            }

            WC()->session = new $session_class();
            WC()->session->init();
        }

        if (null === WC()->customer) {
            WC()->customer = new \WC_Customer(get_current_user_id(), true);
        }

        if (null === WC()->cart) {
            WC()->cart = new \WC_Cart();

            // We need to force a refresh of the cart contents from session here (cart contents are normally refreshed on wp_loaded, which has already happened by this point).
            WC()->cart->get_cart();
        }
    }
}