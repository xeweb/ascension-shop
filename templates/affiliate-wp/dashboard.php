<?php

use AscensionShop\NationalManager\NationalManager;

do_action( 'woocommerce_before_account_navigation' );
?>
<div class="woocommerce">
    <div class="woocommerce-account-wrapper row">
        <div class="woodmart-my-account-sidebar col-md-3">
            <nav class="woocommerce-MyAccount-navigation">
                <ul>
					<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                        <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		</div>

		<?php do_action( 'woocommerce_after_account_navigation' ); ?>
		<?php $active_tab = affwp_get_active_affiliate_area_tab(); ?>

		<div id="affwp-affiliate-dashboard" class="col-md-9">

			<?php if ( 'pending' == affwp_get_affiliate_status( affwp_get_affiliate_id() ) ) : ?>

				<p class="affwp-notice"><?php _e( 'Your affiliate account is pending approval', 'affiliate-wp' ); ?></p>

			<?php elseif ( 'inactive' == affwp_get_affiliate_status( affwp_get_affiliate_id() ) ) : ?>

				<p class="affwp-notice"><?php _e( 'Your affiliate account is not active', 'affiliate-wp' ); ?></p>

			<?php elseif ( 'rejected' == affwp_get_affiliate_status( affwp_get_affiliate_id() ) ) : ?>

				<p class="affwp-notice"><?php _e( 'Your affiliate account request has been rejected', 'affiliate-wp' ); ?></p>

			<?php endif; ?>

			<?php if ( 'active' == affwp_get_affiliate_status( affwp_get_affiliate_id() ) ) : ?>

				<?php
				/**
				 * Fires at the top of the affiliate dashboard.
				 *
				 * @since 0.2
				 * @since 1.8.2 Added the `$active_tab` parameter.
				 *
				 * @param int|false $affiliate_id ID for the current affiliate.
				 * @param string    $active_tab   Slug for the currently-active tab.
				 */
				do_action( 'affwp_affiliate_dashboard_top', affwp_get_affiliate_id(), $active_tab );
				?>

				<?php if ( ! empty( $_GET['affwp_notice'] ) && 'profile-updated' == $_GET['affwp_notice'] ) : ?>

					<p class="affwp-notice"><?php _e( 'Your affiliate profile has been updated', 'affiliate-wp' ); ?></p>

				<?php endif; ?>

				<?php
				/**
				 * Fires inside the affiliate dashboard above the tabbed interface.
				 *
				 * @since 0.2
				 * @since 1.8.2 Added the `$active_tab` parameter.
				 *
				 * @param int|false $affiliate_id ID for the current affiliate.
				 * @param string $active_tab Slug for the currently-active tab.
				 */
				do_action( 'affwp_affiliate_dashboard_notices', affwp_get_affiliate_id(), $active_tab );
				?>


                <ul id="affwp-affiliate-dashboard-tabs">

					<?php
					if ( AscensionShop\NationalManager\NationalManager::isNationalManger( get_current_user_id() ) == true ) {

						?>
                        <li class="tab2 affwp-affiliate-dashboard-tab "><a
                                    href="../mijn-account/national-manager-area/?page=clients"><label
                                        for="tab2"><?php _e( "Klanten", "ascension-shop" ) ?> (NM)</label></a></li>
                        <li class="tab2 affwp-affiliate-dashboard-tab"><a
                                    href="../mijn-account/national-manager-area/?page=add-client"><label
                                        for="tab2"><?php _e( "Klant toevoegen", "ascension-shop" ) ?> (NM)</label></a>
                        </li>
                        <li class="tab4 affwp-affiliate-dashboard-tab"><a
                                    href="../mijn-account/national-manager-area/?page=commissions"><label
                                        for="tab4"><?php _e( "Commissies", "ascension-shop" ) ?> (NM)</label></a></li>
                        <li class="tab1 affwp-affiliate-dashboard-tab"><a
                                    href="../mijn-account/national-manager-area/?page=orders"><label
                                        for="tab1"><?php _e( "Bestellingen", "ascension-shop" ) ?> (NM)</a></label></li>
                        <li class="tab3 affwp-affiliate-dashboard-tab"><a
                                    href="../mijn-account/national-manager-area/?page=partners"><label
                                        for="tab3"><?php _e( "Partners", "ascension-shop" ) ?> (NM)</label></a></li>
                        <li class="tab4 affwp-affiliate-dashboard-tab"><a
                                    href="../mijn-account/national-manager-area/?page=add-partner"><label
                                        for="tab4"><?php _e( "Partner Toevoegen", "ascension-shop" ) ?> (NM)</label></a>
                        </li>


						<?php
					}

					$tabs = affwp_get_affiliate_area_tabs();


					if ( $tabs ) {
						foreach ( $tabs as $tab_slug => $tab_title ) : ?>
							<?php if ( affwp_affiliate_area_show_tab( $tab_slug ) ) : ?>
                                <li class="affwp-affiliate-dashboard-tab<?php echo $active_tab == $tab_slug ? ' active' : ''; ?>">
                                    <a href="<?php echo esc_url( affwp_get_affiliate_area_page_url( $tab_slug ) ); ?>"><?php echo $tab_title; ?></a>
                                </li>
							<?php endif; ?>
						<?php endforeach;
					}

					/**
					 * Fires immediately after core Affiliate Area tabs are output,
					 * but before the 'Log Out' tab is output (if enabled).
					 *
					 * @since 1.0
					 *
					 * @param int    $affiliate_id ID of the current affiliate.
					 * @param string $active_tab   Slug of the active tab.
					 */
					do_action( 'affwp_affiliate_dashboard_tabs', affwp_get_affiliate_id(), $active_tab );
					?>

					<?php if ( affiliate_wp()->settings->get( 'logout_link' ) ) : ?>
						<li class="affwp-affiliate-dashboard-tab">
							<a href="<?php echo esc_url( affwp_get_logout_url() ); ?>"><?php _e( 'Log out', 'affiliate-wp' ); ?></a>
						</li>
					<?php endif; ?>

				</ul>

				<?php
				if ( ! empty( $active_tab ) && affwp_affiliate_area_show_tab( $active_tab ) ) :
					echo affwp_render_affiliate_dashboard_tab( $active_tab );
				endif;
				?>

				<?php
				/**
				 * Fires at the bottom of the affiliate dashboard.
				 *
				 * @since 0.2
				 * @since 1.8.2 Added the `$active_tab` parameter.
				 *
				 * @param int|false $affiliate_id ID for the current affiliate.
				 * @param string    $active_tab   Slug for the currently-active tab.
				 */
				do_action( 'affwp_affiliate_dashboard_bottom', affwp_get_affiliate_id(), $active_tab );
				?>

			<?php endif; ?>

		</div>
	</div>
</div>
