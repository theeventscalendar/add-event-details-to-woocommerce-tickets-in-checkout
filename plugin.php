<?php
/**
 * Plugin Name: The Events Calendar Extension: Add Event Details to WooCommerce Tickets in Checkout
 * Description: Add event details to event tickets in WooCommerce Checkout.
 * Version: 1.0.0
 * Author: Modern Tribe, Inc.
 * Author URI: http://m.tri.be/1971
 * License: GPLv2 or later
 */

defined( 'WPINC' ) or die;

class Tribe__Extension__Add_Event_Details_to_WooCommerce_Tickets_in_Checkout {

    /**
     * The semantic version number of this extension; should always match the plugin header.
     */
    const VERSION = '1.0.0';

    /**
     * Each plugin required by this extension
     *
     * @var array Plugins are listed in 'main class' => 'minimum version #' format
     */
    public $plugins_required = array(
        'Tribe__Tickets__Main' => '4.2',
        'Tribe__Events__Main'  => '4.2'
    );

    /**
     * The constructor; delays initializing the extension until all other plugins are loaded.
     */
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ), 100 );
    }

    /**
     * Extension hooks and initialization; exits if the extension is not authorized by Tribe Common to run.
     */
    public function init() {

        // Exit early if our framework is saying this extension should not run.
        if ( ! function_exists( 'tribe_register_plugin' ) || ! tribe_register_plugin( __FILE__, __CLASS__, self::VERSION, $this->plugins_required ) ) {
            return;
        }

        add_filter( 'woocommerce_cart_item_name', array( $this, 'woocommerce_cart_item_name_event_title' ), 10, 3 );
    }

    /**
     * Example for adding event data to WooCommerce checkout for Events Calendar tickets.
     *
     * @param string $title
     * @param array $values
     * @param string $car_item_key
     * @return string
     */
    public function woocommerce_cart_item_name_event_title( $title, $values, $cart_item_key ) {

        $ticket_meta = get_post_meta( $values['product_id'] );
        
        $event_id = absint( $ticket_meta['_tribe_wooticket_for_event'][0] );
        
        if ( $event_id ) {
            $title = sprintf( '%s for <a href="%s" target="_blank"><strong>%s</strong></a>', $title, get_permalink( $event_id ), get_the_title( $event_id ) );
        }
        
        return $title;
    }
}

new Tribe__Extension__Add_Event_Details_to_WooCommerce_Tickets_in_Checkout();
