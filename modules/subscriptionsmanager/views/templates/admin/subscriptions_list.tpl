
<table class="table sm_list" cellspacing="0">
    <thead>
	{include file="./subscriptions_filters.tpl"}
	<tr>
	    <th>{l s='Customer' mod='subscriptionsmanager'}</th>
	    <th>{l s='Product attribute associated' mod='subscriptionsmanager'}</th>
	    <th class="center">{l s='Subscription state' mod='subscriptionsmanager'}</th>
	    <th class="center">{l s='Start date' mod='subscriptionsmanager'}</th>
	    <th class="center">{l s='Date of next levy' mod='subscriptionsmanager'}</th>
	    <th class="center">{l s='End date' mod='subscriptionsmanager'}</th>
	    <th class="center">{l s='Duration' mod='subscriptionsmanager'}</th>
	    <th class="center">{l s='Frequency' mod='subscriptionsmanager'}</th>
	    <th class="center">{l s='Auto renew' mod='subscriptionsmanager'}</th>
	    <th class="center"></th>
	</tr>

    </thead>
    <tbody id="subscriptions_list">
	{include file="./loop_subscriptions.tpl"}
    </tbody>
    <tfoot>
	<tr>
	    <td colspan="9">
		<div id="paginator">
		    {if $paginator}
			{$paginator}
		    {/if}
		</div>
	    </td>
	    <td>
		<a class="btn btn-default pull-right" href="{$admin_order_uri}"><i class="icon-plus-square"></i> {l s='Add order' mod='subscriptionsmanager'}</a>
	    </td>
	</tr>
    </tfoot>
</table>

<script>
    {literal}
    $(document).ready(function() {

	$('.datepicker').datepicker({"dateFormat": 'yy-mm-dd'});
	//$( "#datepicker" ).datepicker( "option", "dateFormat", $( this ).val() );



	function initDialogSubscription() {
	    $('.view_subscription_block').dialog({
		autoOpen: false,
		modal: true,
		width: '90%',
		draggable: true,
		closeOnEscape: true
	    });

	    $('.view_subscription').click(function() {
		var id = $(this).attr('rel');
		$('#dialog-modal_' + id).dialog('open');
	    });

	    $('.reset_filters').click(function() {
		$('#filter_subscriptions_form input[type="text"]').val('');
		$('.criteria_select').val('');
		ajax_search_subscriptions();
	    });

	    $('.pagination_link').click(function() {

		loadingDataAnimation();

		$.ajax({
		    cache: true,
		    url: $(this).attr('href') + '&' + $('#filter_subscriptions_form').serialize()
			    //url: "/modules/subscriptionsmanager/ajax.php",
			    //data: $('#filter_subscriptions_form').serialize()+'&operation=filter_subscriptions&ajax=1',
		}).done(function(data) {
		    var data = jQuery.parseJSON(data);
		    var pagination = jQuery.parseJSON(data.pagination);
		    $('#subscriptions_list').html(data.html);
		    $('#paginator').html(data.paginator);
		    initDialogSubscription();
		});

		return false;
	    });
	}

	initDialogSubscription();


	function loadingDataAnimation() {
	    $('#subscriptions_list').html('<tr><td colspan="10" align="center">{/literal}{l s='Please wait while loading data...' mod='subscriptionsmanager'}{literal} <i class="fa-cog icon-spin"></i></td></tr>');
	}

	function ajax_search_subscriptions() {
	    loadingDataAnimation();
	    $.ajax({
		url: "{/literal}{$module_dir_for_ajax}{literal}/ajax.php",
		data: $('#filter_subscriptions_form').serialize() + '&operation=filter_subscriptions&ajax=1',
		async: true
	    }).done(function(data) {
		var data = jQuery.parseJSON(data);
		var pagination = jQuery.parseJSON(data.pagination);
		$('#subscriptions_list').html(data.html);
		$('#paginator').html(data.paginator);
		initDialogSubscription();
	    });
	}

	$('.datepicker, .criteria_select').change(function() {
	    loadingDataAnimation();
	    ajax_search_subscriptions();
	});

	$('.criteria_field').keyup(function() {
	    loadingDataAnimation();
	    ajax_search_subscriptions();
	});

    });


    {/literal}
</script>