<table class="table sm_list" cellspacing='0'>
	<thead>
		<tr>
			<th class='center'>{l s='Date' mod='subscriptionsmanager'}</th>
			<th class='center'>{l s='Status' mod='subscriptionsmanager'}</th>
			<th>{l s='Amount' mod='subscriptionsmanager'}</th>
		</tr>
	</thead>
	<tbody>
	
	
	{foreach $echeances_programmed as $billing_date => $detail}
	<tr>
		<td class='center'>{$billing_date|date_format:"d/m/Y"} </td>
		<td class='center'>
		    {if $detail.state == 1}
			<span class="activated">{l s='Paid' mod='subscriptionsmanager'}</span>
		    {elseif $detail.state == 0}
			<span class="disabled">{l s='Pending' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="cancelled">{l s='Error' mod='subscriptionsmanager'}</span>
		    {/if}
		
		</td>
		<td class='left'>{displayWtPrice p=$detail.price} {if isset($detail.is_discount) && $detail.is_discount == 1}<span class="completed">{l s='Discount' mod='subscriptionsmanager'}{/if}</span></td>
	</tr>
	{/foreach}	
	</tbody>
</table>