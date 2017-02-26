<script type='text/javascript'>
    $(document).ready(function() {
	$(".button").click(function() {

	    $(this).next(".detail").toggle();
	});
    });
</script>

<table class="table sm_list table" cellspacing="0">
    <tr>
	<th>{l s='ID' mod='subscriptionsmanager'}</th>
	<th>{l s='Action made by' mod='subscriptionsmanager'}</th>
	<th>{l s='Date' mod='subscriptionsmanager'}</th>
	<th>{l s='Action' mod='subscriptionsmanager'}</th>	
	<th>{l s='Detail' mod='subscriptionsmanager'}</th>
    </tr>

    
    














    {foreach $logsList as $log}

	{if $log['action'] == "create" || $log['action'] == "renew" || $log['log_key'] == "start_SMSubscription" || $log['action'] == "createAndStart"}
	    {$class = "green-line"}
	{elseif $log['log_key'] == "start_CRON"}
	    {$class = "blue-line"}
	{elseif $log['log_key'] == "update_module"}
	    {$class = "grey1-line"}
	{elseif $log['action'] == "stop" || $log['action'] == "NotRenewedBecauseOfLocking" || $log['action'] == "end"}
	    {$class = "red-line"}
	{elseif $log['action'] == "stock"}
	    {$class = "grey2-line"}
	{elseif $log['action'] == "actionStop" || $log['action'] == "actionNoRenew" || $log['action'] == "paypalStop"}
	    {$class = "orange-line"}
	{else}
	    {$class = "white-line"}
	{/if}

	<tr>  
	    <td class="{$class}">{$log['id_log']}</td>
	    <td>{$log['author']}</td>
	    <td>{$log['log_date']|date_format:"h:i:s d/m/Y"}</td>
	    <td>
		{if $log['log_key'] == "create_SMSubscription"}
		    {l s='New subscription created' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "createAndStart_SMSubscription"}
		    {l s='New subscription created and started' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "start_SMSubscription"}
		    {l s='Subscription started' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "install_Module"}
		    {l s='Module installed' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "create_SMSchema"}
		    {l s='New schema created' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "update_SMSchema"}
		    {l s='Schema updated' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "delete_SMSchema"}
		    {l s='Schema deleted' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "actionStop_SMSubscription"}
		    {l s='Cancellation request of a subscription' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "actionNoRenew_SMSubscription"}
		    {l s='Non-renewal of a subscription' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "end_SMSubscription"}
		    {l s='Subscription ended' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "stop_SMSubscription"}
		    {l s='Subscription cancelled' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "renew_SMSubscription"}
		    {l s='Subscription renewed' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "check_SMSubscription"}
		    {l s='Subscription updated' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "stock_SMSubscription"}
		    {l s='Stock decremented' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "start_CRON"}
		    {l s='CRON started' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "update_module"}
		    {l s='Module parameters updated' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "NotRenewedBecauseOfLocking_SMSubscription"}
		    {l s='Subscription not renewed because of locking' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "renewAndMigrate_SMSubscription"}
		    {l s='Subscription renewed after migration' mod='subscriptionsmanager'}
		{elseif $log['log_key'] == "paypalStop_SMSubscription"}
		    {l s='Subscription cancelled via paypal' mod='subscriptionsmanager'}
		{else}
		    {$log['log_key']}
		{/if}
	    </td>


	    {$object = $log.log_value|unserialize}

	    <td>
		{if !empty($object)}
		  
		    <!-- Small modal -->
		    <button class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-sm-{$log.id_log}">
			<i class="icon-plus-square"></i> {l s='Show' mod='subscriptionsmanager'}
		    </button>

		    <div class="modal fade bs-example-modal-sm-{$log.id_log}" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm">
			    <div class="modal-content">	
				<div class="modal-header">
				    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    <h4 class="modal-title">{l s='Details' mod='subscriptionsmanager'}</h4>
				</div>
				<div class="modal-body">
				    <table class="table" style="width: 100%;">		   
					{foreach $object as $key_attr => $attr}
					    <tr>
						<td style="width: 50%; padding: 5px 0px;">{$key_attr}</td>
						<td style="width: 50%; text-align: right; padding: 5px 0px;">{$attr}</td>		
					    </tr>
					{/foreach}			   
				    </table>
				</div>
			    </div>
			</div>
		    </div>
		{/if}
	    </td>
	</tr>
    {/foreach}
</table>


