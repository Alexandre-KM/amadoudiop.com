{foreach $subscriptionsList as $subscription}
	<tr>
		<td>{$subscription.firstname} {$subscription.lastname}</td>
		<td><a href="#{$subscription.id_subscription}" class="view_subscription" rel="{$subscription.id_subscription}">{$subscription.name}</a></td>
		<td class="center">
		    
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
		</td>
		<td class="center">{$subscription.date_start|date_format:"d/m/Y"}</td>
		<td class="center">{$subscription.date_check|date_format:"d/m/Y"}</td>
		<td class="center">
			{if $subscription.duration != 0}
			{$subscription.date_end|date_format:"d/m/Y"}
			{else}
			{l s='Unlimited' mod='subscriptionsmanager'}
			{/if}
		</td>
		<td class="center">{if $subscription.duration != 0}{$subscription.duration} {l s='Month' mod='subscriptionsmanager'}{else}{l s="Unlimited" mod="subscriptionsmanager"}{/if}</td>
		<td class="center">{$subscription.frequency} {l s='month' mod='subscriptionsmanager'}</td>
		<td class="center">
		    {if $subscription.is_renewable == 1}
		    <span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
		    <span class="cancelled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
		</td>
		<td class="center">
			<a href="#{$subscription.id_subscription}" class="view_subscription" rel="{$subscription.id_subscription}">
			    <i class="icon-search"></i>
			</a>
			
			{* DETAIL TPL MODAL *}
			{include file="./modal_subscription_detail.tpl"}
			
			
			
			
		</td>
		
	</tr>
	{/foreach}