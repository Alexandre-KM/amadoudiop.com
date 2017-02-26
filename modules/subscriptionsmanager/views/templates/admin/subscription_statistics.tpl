<div id="stats">
    <div id="stats_info">
	<div class="inline">
	    <div class="label">{l s='Status' mod='subscriptionsmanager'} :</div>
	{if $subscription.status == 0}
			<span class="disabled">{l s='disabled' mod='subscriptionsmanager'}</span>
		    {elseif $subscription.status == 1}
			<span class="activated">{l s='active' mod='subscriptionsmanager'}</span>
		    {elseif $subscription.status == 2}
			<span class="completed">{l s='completed' mod='subscriptionsmanager'}</span>
		    {elseif $subscription.status == -2}
			<span class="cancelled">{l s='cancelled' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="other_state">{$subscription.status}</span>
		    {/if}
	</div>	
	<div class="inline">
	    <div class="label">{l s='Auto renewable' mod='subscriptionsmanager'} :</div>
		    {if $subscription.is_renewable == 1}
			<span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="disabled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='One shot payment' mod='subscriptionsmanager'} :</div>
		    {if $subscription.one_shot == 1}
			<span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="disabled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='Notification' mod='subscriptionsmanager'} :</div>
		    {if $subscription.notification_active == 1}
			<span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="disabled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='Notification sent' mod='subscriptionsmanager'} :</div>
		    {if $subscription.notification_sent == 1}
			<span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="disabled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='Cancellation request' mod='subscriptionsmanager'} :</div>
		    {if $subscription.has_stop == 1}
			<span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="disabled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='Stock decrementation' mod='subscriptionsmanager'} :</div>
		    {if $subscription.stock_decrementation == 1}
			<span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="disabled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
	</div>
    </div>
    <div id="stats_amount">
	<div class="inline">
	    <div class="label">{l s='Payment mode' mod='subscriptionsmanager'} :</div>	
			<span class="disabled">{$subscription.payment_name}</span>		   
	</div>
	<div class="inline">
	    <div class="label">{l s='Already paid amount' mod='subscriptionsmanager'} :</div>
	    {if $subscription.status == 0}
		<span class="disabled">0 {$subscription.currency}</span>
	    {else}
		<span class="disabled">{$subscription.already_paid} {$subscription.currency}</span>	
	    {/if}		   
	</div>	
	<div class="inline">
	    <div class="label">{l s='Frequency' mod='subscriptionsmanager'} :</div>
		    <span class="disabled">{$subscription.frequency} {l s='months' mod='subscriptionsmanager'}</span>
	</div>
	<div class="inline">
	    <div class="label">{l s='Duration' mod='subscriptionsmanager'} :</div>
		    {if $subscription.duration == 0}
			<span class="disabled">{l s='Unknown' mod='subscriptionsmanager'}</span>
		    {else}			
			<span class="disabled">{$subscription.duration} {l s='months' mod='subscriptionsmanager'}</span>
		    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='Number of levies' mod='subscriptionsmanager'} :</div>
		    {if $subscription.status == 0}
			<span class="disabled">0 {l s='on' mod='subscriptionsmanager'} {if $subscription.duration != 0}{$subscription.total_levies}{else}{l s='Unknown' mod='subscriptionsmanager'}{/if}</span>
		    {elseif $subscription.duration == 0}
			<span class="disabled">{$subscription.nb_levies} {l s='on' mod='subscriptionsmanager'} {l s='Unknown' mod='subscriptionsmanager'}</span>
		    {elseif $subscription.one_shot == 1}
			<span class="disabled">{$subscription.nb_levies} {l s='on' mod='subscriptionsmanager'} {$subscription.total_levies}</span>
		    {else}
			<span class="disabled">{$subscription.nb_levies} {l s='on' mod='subscriptionsmanager'} {$subscription.total_levies}</span>
		    {/if}
		    
	</div>
	<div class="inline">
	    <div class="label">{l s='Amount of levies' mod='subscriptionsmanager'} :</div>		    
			<span class="disabled">{$subscription.amount_of_levies|round:2} {$subscription.currency}</span>
		    
	</div>
	<div class="inline">
	    <div class="label">{l s='Discount' mod='subscriptionsmanager'} :</div>
		    
		     {if $subscription.discount_mode == NULL}
		   <span class="disabled">{l s='None' mod='subscriptionsmanager'}</span>
		    {elseif $subscription.discount_mode == 'month_offer'}
			{if $subscription.discount_nb_months == 1}
			    <span class="activated">{l s='First levy free' mod='subscriptionsmanager'}</span>
			{else}
			    <span class="activated">{l s='First' mod='subscriptionsmanager'} {$subscription.discount_nb_months} {l s='levies free' mod='subscriptionsmanager'}</span>
			{/if}	
		    {else}			
			{if $subscription.discount_type == $smarty.const.DISCOUNT_TYPE_WITHOUT_VAT}
			    <span class="activated">{$subscription.discount_value|round:2}{$subscription.currency} {l s='Without VAT' mod='subscriptionsmanager'}
			{elseif $subscription.discount_type == $smarty.const.DISCOUNT_TYPE_WITH_VAT}
			    <span class="activated">{$subscription.discount_value|round:2}{$subscription.currency} {l s='With VAT' mod='subscriptionsmanager'}
			{elseif $subscription.discount_type == $smarty.const.DISCOUNT_TYPE_PERCENT}
			    <span class="activated">{$subscription.discount_value|round:2}{l s='%' mod='subscriptionsmanager'}
			{/if}
			{l s='for the first' mod='subscriptionsmanager'} {$subscription.discount_nb_months} {l s='levies' mod='subscriptionsmanager'}</span>
		    {/if}
			
	</div>
    </div>
    <div id="stats_container">
    <div id="stats_dates">
	<div class="inline">
	    <div class="label">{l s='Start date' mod='subscriptionsmanager'}</div>
		   <span class="disabled">{$subscription.date_start|date_format:'%d/%m/%Y'}</span>
	</div>
	<div class="inline">
	    <div class="label">{l s='Check date' mod='subscriptionsmanager'}</div>
		   <span class="disabled">{$subscription.date_check|date_format:'%d/%m/%Y'}</span>
	</div>
	<div class="inline">
	    <div class="label">{l s='End date' mod='subscriptionsmanager'}</div>
		{if $subscription.duration != 0}
		       <span class="disabled">{$subscription.date_end|date_format:'%d/%m/%Y'}</span>
		{else}
		       <span class="disabled">{l s='Unknown' mod='subscriptionsmanager'}</span>
		{/if}
	</div>
    </div>
    <div id="stats_locking">
	<div class="inline">
	    <div class="label">{l s='Engagement duration' mod='subscriptionsmanager'}</div>
	    {if $subscription.engagement_duration == 0}
		   <span class="disabled">{l s='None' mod='subscriptionsmanager'}</span>
	    {else}
		   <span class="activated">{$subscription.engagement_duration} {l s='months' mod='subscriptionsmanager'}</span>
	    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='Advance notice duration' mod='subscriptionsmanager'}</div>
	    {if $subscription.advance_notice_duration == 0}
		   <span class="disabled">{l s='None' mod='subscriptionsmanager'}</span>
	    {else}
		   <span class="activated">{$subscription.advance_notice_duration} {l s='months' mod='subscriptionsmanager'}</span>
	    {/if}
	</div>
    </div>
    <div id="stats_groups">
	<div class="inline">
	    <div class="label">{l s='Subscriber group' mod='subscriptionsmanager'}</div>
	    {if $subscription.id_group_linked == 0}
		   <span class="disabled">{l s='None' mod='subscriptionsmanager'}</span>
	    {else}
		   <span class="activated">{$subscription.group_linked_name}</span>
	    {/if}
	</div>
	<div class="inline">
	    <div class="label">{l s='Group back' mod='subscriptionsmanager'}</div>
	    {if $subscription.id_group_linked == 0}
		   <span class="disabled">{l s='None' mod='subscriptionsmanager'}</span>
	    {else}
		   <span class="activated">{$subscription.group_back_name}</span>
	    {/if}
	</div>
    </div>
	<div id="stats_actions">
	   {if $subscription.status == 0}
		<a class="button" onClick="return confirm('{l s='Do you really want to start this subscription ?' mod='subscriptionsmanager'}')" href="{$request_uri}&op=start&id={$subscription.id_subscription}"><i class="icon-play"></i>{l s='Start subscription' mod='subscriptionsmanager'}</a>	  
	    {elseif $subscription.status == 1}
		    {if $subscription.can_stop == 1}
			<a class="button" onClick="return confirm('{l s='If you really want to cancel this subscription, it will end ' mod='subscriptionsmanager'} {$subscription.date_stop|date_format:"d/m/Y"}')" href="{$request_uri}&op=stop&id={$subscription.id_subscription}"><i class="icon-stop"></i> {l s='Stop subscription' mod='subscriptionsmanager'}</a>
		    {else}
			<span class="button"><i class="icon-stop"></i> {l s='Stop subscription' mod='subscriptionsmanager'}</span>		    
		    {/if}

		    {if $subscription.is_renewable == 1}
			<a class="button"href="{$request_uri}&op=toggleRenew&id={$subscription.id_subscription}"><i class="icon-check-square color_danger"></i> {l s='Disable automatic renewal' mod='subscriptionsmanager'}</a>
		    {else}
			<a class="button" href="{$request_uri}&op=toggleRenew&id={$subscription.id_subscription}"><i class="icon-check-square color_success"></i> {l s='Activate automatic renewal' mod='subscriptionsmanager'}</a>
		    {/if}		
	    {/if}
    </div>
    </div>
</div>
	
	
	