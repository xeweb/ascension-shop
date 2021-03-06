<?php

use AscensionShop\Affiliate\Helpers;
use AscensionShop\Affiliate\SubAffiliate;
use AscensionShop\Lib\TemplateEngine;
use AscensionShop\NationalManager\NationalManager;

$affiliate_id = affwp_get_affiliate_id();

$sub = new SubAffiliate($affiliate_id);
?>

<div id="affwp-affiliate-dashboard-lifetime-customers" class="affwp-tab-content">


    <div class="partnerArea-header">
        <div class="header">

            <?php
		        // Get all affiliates
		        $all_affiliates = $sub->getAllChildren( 2, true );


		        // Build an array of affiliate IDs and names for the drop down
		        $affiliate_dropdown = array();
                $affiliate_dropdown[ $affiliate_id ] = affwp_get_affiliate_name($affiliate_id);

		        if ( $all_affiliates && ! empty( $all_affiliates ) ) {

			        foreach ( $all_affiliates as $a ) {

				        if ( $affiliate_name = $a->getName() ) {
					        $affiliate_dropdown[ $a->getId() ] = $affiliate_name;
				        }

			        }


		        }
		        ?>
                <label for="searchByPartner"><?php _e( "Filter op partner" ) ?></label>
                <select id="searchByPartner">
			        <?php foreach ( $affiliate_dropdown as $affiliate_id => $affiliate_name ) : ?>
                        <option value="<?php echo esc_attr( $affiliate_id ); ?>"><?php echo esc_html( $affiliate_name ); ?></option>
			        <?php endforeach; ?>
                </select>
                <label><?php _e( "Naam", "ascension-shop" ); ?></label>
                <input type="text" id="searchByName" name="searchByName" placeholder="">
            <label for="searchByStatus"><?php _e("Status","ascension-shop"); ?></label>
            <select id="searchByStatus">
                <option value=""><?php _e("*","ascension-shop"); ?></option>
                <option value="active"><?php _e("Actief","ascension-shop"); ?></option>
                <option value="non-active"><?php _e("Niet actief","ascension-shop"); ?></option>
            </select>
        </div>
        <div class="buttons">
            <p><a target="_blank" href="<?php echo get_site_url().'?generateReport=clients';?>"><button><?php _e("Download als XLS","ascension-shop"); ?></button></a></p>
        </div>
    </div>

    <table id="all-clients" class="affwp-table affwp-table-responsive">

        <thead>
        <tr>
            <th><?php _e("ID","ascension-shop") ?></th>
            <th width="40%"><?php _e("Gegevens","ascension-shop") ?></th>
            <th><?php _e("Status","ascension-shop") ?></th>
            <th><?php _e("Klant van","ascension-shop") ?></th>
            <th width="20%"><?php _e("Korting","ascension-shop") ?></th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>

	<?php do_action("ascension-after-clients"); ?>

</div>
