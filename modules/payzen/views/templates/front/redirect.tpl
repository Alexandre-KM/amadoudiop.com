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

<!doctype html>
<html lang="{$language.iso_code|escape:'html':'UTF-8'}">
  <head>
    {block name='head'}
      {include file="_partials/head.tpl"}
    {/block}
  </head>

  <body id="checkout" class="{$page.body_classes|classnames}">
    {hook h='displayAfterBodyOpeningTag'}

    <header id="header">
      {block name='header'}
        {include file="checkout/_partials/header.tpl"}
      {/block}
    </header>

    <section id="wrapper">
      <div class="container">

      {block name='content'}
        <section id="content">
          <div class="row">
            <div class="col-md-8">
              <section id="payzen_content" class="checkout-step -current">
                <h1 class="step-title h3">
                  <span class="step-number"></span>
                  {if isset($payzen_params) && $payzen_params.vads_action_mode == 'SILENT'}
                    {l s='Payment processing' mod='payzen'}
                  {else}
                    {l s='Redirection to payment gateway' mod='payzen'}
                  {/if}
                </h1>

                <div class="content">
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

                    <p class="cart_navigation clearfix">
                      <button type="submit" name="submitPayment" class="button btn btn-default standard-checkout button-medium" >
                        <span>{l s='Pay' mod='payzen'}</span>
                      </button>
                    </p>
                  </form>
                </div>
              </section>
            </div>

             <div class="col-md-4">
            </div>
          </div>
        </section>
      {/block}
      </div>
    </section>

    <footer id="footer">
      {block name='footer'}
        {include file="checkout/_partials/footer.tpl"}
      {/block}
    </footer>

    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}

    {include file="module:payzen/views/templates/front/redirect_js.tpl"}
  </body>
</html>
