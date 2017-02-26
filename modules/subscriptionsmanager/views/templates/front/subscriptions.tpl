{capture name=path}{l s='My subscriptions' mod='subscriptionsmanager'}{/capture}
{include file="$tpl_dir./breadcrumb.tpl"}


<h1>{l s='My subscriptions' mod='subscriptionsmanager'}</h1>

{if isset($error_list)}
    {$error_list}
{/if}

{if isset($smarty.get.cancel) && $smarty.get.cancel == 1}
    <div class="success">{l s='Cancellation done.' mod='subscriptionsmanager'}</div>
{/if}

{if !empty($subscriptions)}
<h2>{l s='My current subscriptions' mod='subscriptionsmanager'}</h2>

<script type="text/javascript">
    {literal}
	$(document).ready(function(){
	    $("tr.view_detail td#detail").click(function () {
			$(this).parent("tr").next("tr").toggle();
	    });   
	});
    {/literal}
</script>

{* liste des abonnements en cours *}
<table class="std subscriptionlist">
    {include file="./header_subscriptions_list.tpl"}
    <tbody> 
    {foreach from=$subscriptions item=subscription}
    {assign var="id_subscription" value=$subscription.id_subscription}
    <tr class="view_detail">
    	 <td id="detail">{$subscription.name}</td>
    	 <td id="detail">{$subscription.date_start|date_format:"d/m/Y"}</td>
    	 <td id="detail">{$subscription.frequency} {l s='months' mod='subscriptionsmanager'}</td>
    	 <td id="detail">{$subscription.date_end|date_format:"d/m/Y"}</td>
    	 <td id="detail" style="text-align: center;">
		{if $subscription.status == 1}
			<i class="icon-circle" style="color: #7CC67C;"></i>
		{else}
			<i class="icon-circle-blank" style="color: #E27171;"></i>
		{/if}
    	 </td>
    	 <td>
    	 	{* Si l'abonnement peut être stoppé, si abo plusieurs fois, si abo has_stop, si abo can stop, ou abo paypal*}
    	 	 {if ($subscription.can_stop == 1)}
    	 		<a onClick="return confirm('{if $subscription.isPaypalSubscription == 0}{l s='if you wish to cancel your subscription, it will end' mod='subscriptionsmanager'} {$subscription.date_stop|date_format:"d/m/Y"}{else}{l s='This action is irreversible' mod='subscriptionsmanager'}{/if}');" href="{$link->getModuleLink('subscriptionsmanager', 'subscriptions')|escape:'htmlall':'UTF-8'}?op=stop&id={$subscription.id_subscription}">{l s='Stop subscription' mod='subscriptionsmanager'}</a>
    	 	 {/if}
		 {if ($subscription.can_not_renew == 1)}
    	 		<a href="{$link->getModuleLink('subscriptionsmanager', 'subscriptions')|escape:'htmlall':'UTF-8'}?op=no_renew&id={$subscription.id_subscription}">{l s='Do not renew this subscription' mod='subscriptionsmanager'}</a>
    	 	 {/if}
    	 </td>
    </tr>
    <tr style="display: none">
    	<td colspan="6">
    		<table class="table" cellspacing='0' style="width: 100%">
				<thead>
					<tr>
						<th class='center'>{l s='Date' mod='subscriptionsmanager'}</th>
						<th class='center'>{l s='Status' mod='subscriptionsmanager'}</th>
						<th>{l s='Amount' mod='subscriptionsmanager'}</th>
					</tr>
				</thead>
				<tbody>
				{foreach $echeances_programmed.$id_subscription as $billing_date => $detail}				    
				<tr>
					<td class='center'>{$billing_date|date_format:"d/m/Y"} </td>
					<td class='center'>
					    {if isset($detail.state) && $detail.state == 1}
						<span class="activated">{l s='Paid' mod='subscriptionsmanager'}</span>
					    {elseif !isset($detail.state) || isset($detail.state) && $detail.state == 0}
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
    	</td>
    </tr>
    
    {/foreach}
    </tbody>
</table>
    {else}
	{l s='You have no subscription' mod='subscriptionsmanager'}
{/if}


<ul class="footer_links clearfix">
	<li><a href="{$link->getPageLink('my-account', true)|escape:'html':'UTF-8'}" class="btn btn-defaul button button-small"><span><i class="icon-chevron-left"></i> {l s='Back to your account' mod='subscriptionsmanager'}</span></a></li>
	<li><a href="{$base_dir}" class="btn btn-defaul button button-small"><span><i class="icon-chevron-left"></i> {l s='Home' mod='subscriptionsmanager'}</span></a></li>
</ul>

