<table class="table sm_list" cellspacing="0">
<thead>
    <tr>
	<th>{l s='Payment associated' mod='subscriptionsmanager'}</th>
	<th>{l s='Product attribute associated' mod='subscriptionsmanager'}</th>
	<th class="center">{l s='One time payment' mod='subscriptionsmanager'}</th>
	<th>{l s='Duration' mod='subscriptionsmanager'}</th>
	<th class="center">{l s='Is renewable' mod='subscriptionsmanager'}</th>
	<th>{l s='Frequency' mod='subscriptionsmanager'}</th>
	<th>{l s='Discount' mod='subscriptionsmanager'}</th>
	<th class="center">{l s='Stock decrementation' mod='subscriptionsmanager'}</th>
	<th>{l s='Engagement' mod='subscriptionsmanager'}</th>
	<th>{l s='Advance notice' mod='subscriptionsmanager'}</th>
	<th>{l s='Actions' mod='subscriptionsmanager'}</th>
    </tr>
</thead>
    {foreach $schemasList as $schema}
	<tr>
	    <td>{$schema.payment_name}</td>
	    <td>{$schema.name}</td>
	    <td class="center">
		{if $schema.one_shot == TRUE}
		    <span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		{else}
		    <span class="cancelled">{l s='No' mod='subscriptionsmanager'}</span>
		{/if}
	    </td>
	    <td>{if $schema.duration == 0}{l s='Unlimited' mod='subscriptionsmanager'}{else}{$schema.duration} {l s='months' mod='subscriptionsmanager'}{/if}</td>
	    <td class="center">
		{if $schema.is_renewable == TRUE}
		    <span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		{else}
		    <span class="cancelled">{l s='No' mod='subscriptionsmanager'}</span>
		{/if}
	    </td>
	    <td>{$schema.frequency} {l s='months' mod='subscriptionsmanager'}</td>
	    <td>
		{if $schema.discount_mode == NULL}
		    {l s='No' mod='subscriptionsmanager'}
		{elseif $schema.discount_mode == 'month_offer'}
		    {if $schema.discount_nb_months == 1}
			{l s='First levy free' mod='subscriptionsmanager'}
		    {else}
			{l s='First' mod='subscriptionsmanager'} {$schema.discount_nb_months} {l s='levies free' mod='subscriptionsmanager'}
		    {/if}	
		{else}			
		    <b>{if $schema.discount_type == $smarty.const.DISCOUNT_TYPE_WITHOUT_VAT}
			{$schema.discount_value|round:2}{$schema.currency} {l s='Without VAT' mod='subscriptionsmanager'}
		    {elseif $schema.discount_type == $smarty.const.DISCOUNT_TYPE_WITH_VAT}
			{$schema.discount_value|round:2}{$schema.currency} {l s='With VAT' mod='subscriptionsmanager'}
		    {elseif $schema.discount_type == $smarty.const.DISCOUNT_TYPE_PERCENT}
			{$schema.discount_value|round:2}{l s='%' mod='subscriptionsmanager'}
		    {/if}</b>
		    {l s='for the first' mod='subscriptionsmanager'} {$schema.discount_nb_months} {l s='levies' mod='subscriptionsmanager'}
		    {/if}
		</td>
		<td class="center">
		    {if $schema.stock_decrementation == TRUE || $schema.one_shot == FALSE}
			<span class="activated">{l s='Yes' mod='subscriptionsmanager'}</span>
		    {else}
			<span class="cancelled">{l s='No' mod='subscriptionsmanager'}</span>
		    {/if}
		</td>
		<td>
		    {if $schema.engagement_duration == 0}
			{l s='No' mod='subscriptionsmanager'}
		    {else}
			{$schema.engagement_duration} {l s='months' mod='subscriptionsmanager'}
		    {/if}	
		</td>
		<td>
		    {if $schema.advance_notice_duration == 0}
			{l s='No' mod='subscriptionsmanager'}
		    {else}
			{$schema.advance_notice_duration} {l s='months' mod='subscriptionsmanager'}
		    {/if}
		</td>		
		<td>
		    {if $schema.locked == 0}
			<i class="icon-check-square color_success"></i>
		    {else}
			<i class="icon-check-square color_danger"></i>
		    {/if}
		    <a href="{$request_uri}&op=editSchema&id={$schema.id_schema}"><i class="icon-edit"></i></a>
		    {if $schema.is_used == 0}
			<a onclick="return confirm('{l s='Do you really want to delete this schema ?' mod='subscriptionsmanager'}')" href="{$request_uri}&op=deleteSchema&id={$schema.id_schema}">
			    <i class="icon-trash-o"></i>
			</a>
		    {/if}
		</td>
	    </tr>
	    {/foreach}
	    </table>
	    <p>

		<a href="{$request_uri}&op=createSchema" class="btn btn-default"><i class="icon-plus-square"></i> {l s='Add a new schema' mod='subscriptionsmanager'}</a>

	    </p>