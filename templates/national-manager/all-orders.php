<div class="tab1">
    <?php
    $langs = '';
        foreach ($this->lang as $l){
            $langs .= $l. ' ';
        }
    ?>
    <p><?php printf(__("Alle orders voor de %s shop","ascenion-shop"),$langs); ?><br />
	    <?php _e("Je kan alle orders filteren op naam, id, status of bedrag.","ascenion-shop"); ?></p>
    <table id="all-orders" class="affwp-table affwp-table-responsive">
        <thead>
        <tr>
            <th><input type="number" id="order-id-search" /> </th>
            <th><input type="date" id="orders-from" placeholder="<?php _e("Van","ascension-shop"); ?>"><br /><input type="date" id="orders-to" placeholder="<?php _e("Tot","ascension-shop"); ?>"> </th>
            <th></th>
            <th></th>
            <th>
                <select id="searchOrderByClient" class="searchByPartner" >
                    <option value="" selected="selected" ><?php _e("Alle klanten","ascension-shop") ?></option>
		            <?php foreach ($this->clients as $client) : ?>
			            <?php $user_data = get_userdata($client->ID);?>
                        <option value="<?php echo $client->ID ?>"><?php echo $user_data->last_name. ' '.$user_data->first_name. ' #'.$client->ID; ?></option>
		            <?php endforeach; ?>
                </select>
            </th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th><?php _e("ID","ascension-shop") ?></th>
            <th><?php _e("Datum","ascension-shop") ?></th>
            <th><?php _e("Status","ascension-shop") ?></th>
            <th><?php _e("Bedrag","ascension-shop") ?></th>
            <th><?php _e("Klant","ascension-shop") ?></th>
            <th><?php _e("Klant van","ascension-shop") ?></th>
            <th><?php _e("Acties","ascension-shop") ?></th>
        </tr>
        </thead>
    <tbody>
    </tbody>
</table>
</div>