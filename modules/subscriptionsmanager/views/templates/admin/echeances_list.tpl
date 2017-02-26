<h3>{l s='Echeances list' mod='subscriptionsmanager'}</h3>

<div class="echeances_list" id="echeances_list_{$subscription.id_subscription}"></div>

<script>
{literal}
$(document).ready(function(){
	
	function loadingDataAnimation(){
		$('#echeances_list_'+{/literal}{$subscription.id_subscription}{literal}).html('<tr><td colspan="10" align="center">{/literal}{l s='Please wait while loading data...' mod='subscriptionsmanager'}{literal}<i class="fa-cog icon-spin"></i></td></tr>');
	}

	loadingDataAnimation();
	$.ajax({
		cache: true,
		url: "{/literal}{$module_dir_for_ajax}{literal}/ajax.php?operation=list_echeances&id_subscription={/literal}{$subscription.id_subscription}{literal}",
	}).done(function(data) {
		$('#echeances_list_'+{/literal}{$subscription.id_subscription}{literal}).html(data);
	});	
});
{/literal}
</script>