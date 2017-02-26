
<script type='text/javascript'>

    $(document).ready(function() {

	function transition() {

	    if ($('#combinations-list').length > 0) {

		clearInterval(intervalId);

		var NbSubscriptionsBySchema = {$NbSubscriptionsBySchema|@json_encode};


		$('#product-tab-content-Combinations .configuration thead tr > th:nth-child(2)').after('<th class="center"><span class="title_box"> {l s='Unlocking date' mod='subscriptionsmanager'} </span></th>');
		$('#product-tab-content-Combinations .configuration thead tr > th:nth-child(2)').after('<th class="center"><span class="title_box"> {l s='Subscriptions associated' mod='subscriptionsmanager'} </span></th>');


		$('#product-tab-content-Combinations .configuration tbody tr').each(function() {

		    var tr = $(this);

		    var is_locked = false;

		    var href = $(this).find('td a.edit').attr('href').split('&');

		    var id_product_attribute;


		    $.each(href, function(index, value) {
			if (value.indexOf("id_product_attribute") >= 0) {

			    id_product_attribute = value.replace('id_product_attribute=', '');

			    $.each(NbSubscriptionsBySchema, function(key, val) {

				if (id_product_attribute == key && val.nb > 0) {
				    tr.css('background-color', '#FFD8D8');
				    tr.find('td a.edit').hide();
				    tr.find('td a.delete').hide();
				    tr.find('td:nth-child(2)').after('<td class="center">' + val.date + '</td>');
				    tr.find('td:nth-child(2)').after('<td class="center">' + val.nb + '</td>');

				    is_locked = true;

				}


			    });

			    if (!is_locked) {
				tr.find('td:nth-child(2)').after('<td class="left"></td>');
				tr.find('td:nth-child(2)').after('<td class="center"></td>');
			    }
			}
		    });
		});

		$('li.tab-row #link-Combinations').show();

	    }

	}
	var intervalId = setInterval(transition, 500);




    });
</script>