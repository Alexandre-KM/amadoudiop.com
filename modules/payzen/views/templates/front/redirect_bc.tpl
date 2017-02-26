{*
 * PayZen V2-Payment Module version 1.8.0 for PrestaShop 1.5-1.7. Support contact : support@payzen.eu.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * @category  payment
 * @package   payzen
 * @author    Lyra Network (http://www.lyra-network.com/)
 * @copyright 2014-2016 Lyra Network and contributors
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *}

{capture name=path}PayZen{/capture}
{if version_compare($smarty.const._PS_VERSION_, '1.6', '<')}
  {include file="$tpl_dir./breadcrumb.tpl"}
{/if}

{if isset($payzen_params) && $payzen_params.vads_action_mode == 'SILENT'}
  <h1>{l s='Payment processing' mod='payzen'}</h1>
{else}
  <h1>{l s='Redirection to payment gateway' mod='payzen'}</h1>
{/if}

{assign var='current_step' value='payment'}
{include file="$tpl_dir./order-steps.tpl"}

<div id="payzen_content" style="display: none;">
  <h3>{$payzen_title|escape:'html':'UTF-8'}</h3>

  <form action="{$payzen_url|escape:'html':'UTF-8'}" method="post" id="payzen_form" name="payzen_form">
    {foreach from=$payzen_params key='key' item='value'}
      <input type="hidden" name="{$key|escape:'html':'UTF-8'}" value="{$value|escape:'html':'UTF-8'}" />
    {/foreach}

    <p>
      <img src="{$payzen_logo|escape:'html':'UTF-8'}" alt="PayZen" style="margin-bottom: 5px" />
      <br />

      {if $payzen_params.vads_action_mode == 'SILENT'}
        {l s='Please wait a moment. Your order payment is now processing.' mod='payzen'}
      {else}
        {l s='Please wait, you will be redirected to the payment platform.' mod='payzen'}
      {/if}

      <br /> <br />
      {l s='If nothing happens in 10 seconds, please click the button below.' mod='payzen'}
      <br /><br />
    </p>

  {if version_compare($smarty.const._PS_VERSION_, '1.6', '<')}
    <p class="cart_navigation">
      <input type="submit" name="submitPayment" value="{l s='Pay' mod='payzen'}" class="exclusive" />
    </p>
  {else}
    <p class="cart_navigation clearfix">
      <button type="submit" name="submitPayment" class="button btn btn-default standard-checkout button-medium" >
        <span>{l s='Pay' mod='payzen'}</span>
      </button>
    </p>
  {/if}
  </form>
</div>

{include file="./redirect_js.tpl"}
