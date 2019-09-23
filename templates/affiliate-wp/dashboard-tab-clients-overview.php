<?php
$affiliate_id = affwp_get_affiliate_id();
$customers    = affiliate_wp_lifetime_commissions()->integrations->get_customers_for_affiliate( $affiliate_id );
?>

<div id="affwp-affiliate-dashboard-lifetime-customers" class="affwp-tab-content">

	<h4><?php _e( 'Klanten', 'ascension-shop' ); ?></h4>
	<p>
		<input type="text" id="searchClient" onkeyup="searchClientTable()" placeholder="<?php _e("Zoek op naam, telefoon, adres of email","ascension-shop"); ?>">
	</p>
	<p>
		<a href="#addClient"><button><?php _e("Nieuwe klant aanmaken"); ?></button></a>
	</p>
	<form method="POST" action="<?php echo admin_url('admin-post.php'); ?>">
		<?php if ( $customers ) : ?>

		<table id="clients-overview" class="affwp-table affwp-table-responsive">
			<thead>
			<tr>
				<th>ID</th>
				<th class="customer-first-name"><?php _e( 'Gegevens', 'ascension-shop' ); ?></th>
				<th><?php _e("Tools","ascension-shop"); ?></th>
				<th class="customer-discount"><?php _e('Korting %',"ascension-shop") ?></th>
			</tr>
			</thead>

			<tbody>
			<?php foreach ( $customers as $customer ) : ?>


				<?php if ( $customer ): ?>

					<tr>
						<td><a href="?tab=commission-overview&client=<?php echo $customer->customer_id; ?>">#<?php echo $customer->customer_id; ?></a></td>
						<td class="customer-first-name" data-th="<?php _e( 'Gegevens', 'ascension-shop' ); ?>">
							<?php echo $customer->first_name; ?> <?php echo $customer->last_name; ?><br />
							<?php echo get_user_meta( $customer->user_id, 'billing_address_1', true ); ?><br />
							<?php echo get_user_meta( $customer->user_id, 'billing_postcode', true ). ' '.get_user_meta( $customer->user_id, 'billing_city', true ); ?><br />
							<br />
							<?php echo get_user_meta( $customer->user_id, 'billing_phone', true ); ?><br />
							<?php echo $customer->email; ?><br />
						</td>
						<td>
							<a href="#"><button><?php _e("Bewerk","ascension-shop"); ?></button></a>
							<a href="?tab=commission-overview&client=<?php echo $customer->customer_id; ?>"><button><?php _e("Bestellingen","ascension-shop"); ?></button></a>
						</td>
						<td class="customer-discount" width="20%"><input type="number" name="customer_rate[<?php echo $customer->user_id; ?>]" value="<?php echo get_user_meta($customer->user_id,"ascension_shop_affiliate_coupon",true); ?>">
							<input type="submit" value="<?php _e("Opslaan","ascension-shop"); ?>" />

						</td>
					</tr>

				<?php endif; ?>

			<?php endforeach; ?>
			</tbody>
		</table>
		<?php wp_nonce_field( 'ascension_save_customer_discount_'.$affiliate_id ); ?>
		<input type="hidden" name="action" value="ascension-save_customer-discount">
		<input type="submit" value="<?php _e("Opslaan","ascension-shop"); ?>" />
	</form>
	<?php else : ?>
		<p><?php _e( 'You don\'t have any lifetime customers yet.', 'ascension-shop' ); ?></p>
	<?php endif; ?>

	<?php do_action("ascension-after-clients"); ?>
</div>

<script>
    function searchClientTable() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchClient");
        filter = input.value.toUpperCase();
        table = document.getElementById("clients-overview");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>