<div>

	<?php

	use AscensionShop\Affiliate\SubAffiliate;
	use AscensionShop\Lib\TemplateEngine;
	use AscensionShop\NationalManager\NationalManager;

	$affiliate_id = affwp_get_affiliate_id();
	$all_subs = true;

	if(NationalManager::isNationalManger(get_current_user_id())){

		// Only get the clients from given country
		$affiliate_id = NationalManager::getNationalManagerCountryAff( get_current_user_id() );

		if ( isset( $_GET["id"] ) ) {
			$affiliate_id = $_GET["id"];
		}
		if ( isset( $_GET["allSubs"] ) ) {
			$all_subs = $_GET["allSubs"];
		}
	}

	if ( isset( $_GET["page"] ) ) {
		$page = $_GET["page"];
	} else {
		$page = 1;
	}

	$startby = ( $page * 20 ) - 20;

	$sub             = new SubAffiliate( $affiliate_id );
	$partners        = $sub->getAllChildren( 2, true, $all_subs );
	$partners_amount = count( $partners );
	$current_partner = 1;

	printf( __( "Overzicht van alle partners van de %s shop", "ascension-shop" ), $this->lang[0] ); ?>


    <div id="affwp-affiliate-dashboard-lifetime-customers" class="printArea affwp-tab-content">

        <p>
            <b><?php echo __( "Aantal partners:", "ascension-shop" ) . ' ' . $partners_amount; ?></b>
        </p>

        <div class="partnerArea-header">
            <div class="header">
                <label><?php _e( "Naam", "ascension-shop" ); ?></label>
                <input type="text" id="searchAPartnerByName" name="searchAPartnerByName" placeholder="">

            </div>


            <div class="buttons">
                <p><a target="_blank" href="<?php echo get_site_url().'?generateReport=partners';?>"><button><?php _e("Download als XLS","ascension-shop"); ?></button></a></p>
            </div>
        </div>

		<?php if ( $partners ) : ?>

            <table id="partners-overview" class="affwp-table affwp-table-responsive">
                <thead>
                <tr>
                    <th>ID</th>
                    <th class="customer-first-name"><?php _e( 'Naam', 'ascension-shop' ); ?></th>
                    <th><?php _e("Status","ascension-shop"); ?></th>
                    <th><?php _e("Stad","ascension-shop"); ?></th>
                    <th><?php _e("Land","ascension-shop"); ?></th>
                    <th><?php _e("Sub partner van","ascension-shop"); ?></th>
                    <th class="customer-discount"><?php _e('Commissie (%)',"ascension-shop") ?></th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
				<?php foreach ( $partners as $partner ) :

				if ( $partner ): ?>


                        <tr>
                            <td><a href="?tab=commission-overview&partner=<?php echo $partner->getId(); ?>">#<?php echo $partner->getId(); ?></a><br />
                                <a href="#edit-user-<?php echo $partner->getUserId(); ?>" class="edit-user" rel="modal:open" data-id="<?php echo $partner->getUserId(); ?>"><?php _e("Bewerk","ascension-shop"); ?></a><br />
                                <a href="#adress-user-<?php echo $partner->getUserId(); ?>" class="edit-user" rel="modal:open" data-id="<?php echo $partner->getUserId(); ?>"><?php _e("Gegevens","ascension-shop"); ?></a><br />
	                            <?php
	                            $user = new \WP_User($partner->getUserId());
	                            $adt_rp_key = get_password_reset_key($user);
	                            $user_login = $user->user_login;
	                            if(!is_wp_error($adt_rp_key)) {
		                            $rp_link = '<a href="' . wp_login_url() . "?action=rp&key=$adt_rp_key&login=" . rawurlencode( $user_login ) . '" target="_blank">' . __( "Reset wachtwoord", "ascension-shop" ) . '</a>';
	                            }else{
		                            $rp_link = __("Password change not available","ascension-shop");
	                            }
	                            echo $rp_link;
	                            ?>
                                <div class="modal" id="adress-user-<?php
	                            echo $partner->getUserId(); ?>" style="display: none;">
		                            <?php echo get_user_meta( $partner->getUserId(), 'billing_phone', true ); ?><br />
		                            <?php echo $partner->getEmail(); ?><br />
		                            <?php echo get_user_meta( $partner->getUserId(), 'billing_address_1', true ); ?><br />
		                            <?php echo get_user_meta( $partner->getUserId(), 'billing_postcode', true ). ' '.get_user_meta( $partner->getUserId(), 'billing_city', true ); ?><br />
		                            <?php echo get_user_meta( $partner->getUserId(), 'billing_company', true ); ?><br />
		                            <?php echo get_user_meta( $partner->getUserId(), 'vat_number', true ); ?><br />
                                </div>

                                <div class="modal" id="user-edit-<?php echo $partner->getUserId(); ?>">
		                            <?php

		                            $t = new TemplateEngine();
		                            $t->partner = $partner;
		                            $t->affiliate_id = $affiliate_id;
		                            echo $t->display("affiliate-wp/edit-partner-form.php");

		                            ?>
                                </div>

                            </td>
                            <td class="customer-first-name" data-th="<?php _e( 'Naam', 'ascension-shop' ); ?>">
                                <div id="info-user-<?php echo $partner->getUserId(); ?>" class="partnerArea-header no-borders">
                                    <div class="header">
                                    <b><?php echo $partner->getName(); ?><br /></b>
                                    </div>
                                </div>

                            </td>
                            <td><?php
								if($partner->getStatus() == 1){
									_e("Actief","ascension-shop");
								}else{
									_e("Niet actief","ascension-shop");
								}
								?></td>
                            <td>
	                            <?php echo get_user_meta( $partner->getUserId(), 'billing_city', true ); ?>
                            </td>
                            <td>
	                            <?php
                                $country = get_user_meta( $partner->getUserId(), 'billing_country', true );

                                if($country != ''){ echo WC()->countries->countries[$country]; } ?>
                            </td>
                            <td><?php
								if($partner->getParentId() > 0){
									echo '#'.$partner->getParentId();
									echo ' '.affiliate_wp()->affiliates->get_affiliate_name($partner->getParentId());
								} ?>
                            </td>
                            <td class="customer-discount" width="20%"><?php echo $partner->getUserRate(); ?>%
                            </td>
                        </tr>

					<?php endif; ?>

				<?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th class="customer-first-name"><?php _e( 'Naam', 'ascension-shop' ); ?></th>
                    <th><?php _e("Status","ascension-shop"); ?></th>
                    <th><?php _e("Stad","ascension-shop"); ?></th>
                    <th><?php _e("Land","ascension-shop"); ?></th>
                    <th><?php _e("Sub partner van","ascension-shop"); ?></th>
                    <th class="customer-discount"><?php _e('Commissie (%)',"ascension-shop") ?></th>
                </tr>
                </tfoot>
            </table>
		<?php else : ?>
            <p><?php _e( 'Je hebt nog geen partners.', 'ascension-shop' ); ?></p>
		<?php endif; ?>

		<?php do_action("ascension-after-partners"); ?>
    </div>
</div>
<script>
    (function($){
        $(document).ready( function () {
            var thePartnersTable = $('#partners-overview ').DataTable({
                initComplete: function () {
                    this.api().columns().every( function () {
                        var column = this;

                        if(column.index() === 0){
                            return;
                        }
                        if(column.index() === 1){
                            return;
                        }

                        var select = $('<select><option value=""></option></select>')
                            .appendTo( $(column.header()))
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {

                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            });


            $('#searchAPartnerByName').on( 'keyup', function () {
                thePartnersTable
                    .columns( 1 )
                    .search( this.value )
                    .draw();
            } );

        });


    })(jQuery);
</script>
