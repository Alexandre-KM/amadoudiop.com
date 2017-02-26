{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
	{assign var='current_step' value='address'}
	{capture name=path}{l s='Addresses'}{/capture}
	{assign var="back_order_page" value="order.php"}
	<h1 class="page-heading">{l s='Informations'}</h1>
	{include file="$tpl_dir./order-steps.tpl"}
	{include file="$tpl_dir./errors.tpl"}
		<form action="{$link->getPageLink($back_order_page, true)|escape:'html':'UTF-8'}" method="post">

<div class="addresses clearfix">
	<div class="row">
		<div id="ordermsg" class="col-md-12">
			<h2>Récapitulatif de votre demande</h2>
			<label>{l s='Indiquer ici de façon claire vos différentes taches.'}</label>
			<textarea class="form-control" cols="60" rows="6" name="message">{if isset($oldMessage)}{$oldMessage}{/if}</textarea>
		</div>
	</div> <!-- end row -->
	<div class="row">
		<div class="col-md-12">
			<h2>L'échange et le stockage de ces données sont cryptés.</h2>
		</div>
	<div class="col-md-6">
		<h2>Accès au backoffice de Prestashop</h2>
		<div class="required form-group">
			<label for="customer_firstname">{l s='Url du backoffice'} <sup>*</sup></label>
			<small>http://www.mondomaine.com/phpmyadmin</small>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname">{l s='E-mail'} <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname">{l s='Mot de passe'} <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
	</div>	<div class="col-md-6">
			<h2>Administration du nom de domaine</h2>
			<div class="required form-group">
				<label for="customer_firstname">{l s='Entreprise hébergeant le domaine'} <sup>*</sup></label>
				<small>http://www.mondomaine.com/phpmyadmin</small>
				<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
			</div>
			<div class="required form-group">
				<label for="customer_firstname">{l s='Compte Utilisateur'} <sup>*</sup></label>
				<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
			</div>
			<div class="required form-group">
				<label for="customer_firstname">{l s='Mot de passe'} <sup>*</sup></label>
				<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
			</div>
		</div>
	</div> <!-- end row -->
	<div class="row">
	  <div class="col-md-6">
			<h2>Serveur FTP</h2>
	    <div class="required form-group">
	      <label for="customer_firstname">{l s='Hôte'} <sup>*</sup></label>
				<small>ex: ftp.monnondedomaine.com ou IP: 126.76.9.2.65</small>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_host" name="message_host" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname">{l s='Port'} <sup>*</sup></label>
				<small>ex: 21 - 22 - autre port...</small>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname">{l s='Protocole'} <sup>*</sup></label>
				<small>ex: FTP ou SFTP</small>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname">{l s='Nom Utilisateur'} <sup>*</sup></label>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname">{l s='Mot de passe'} <sup>*</sup></label>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
		</div>
	<div class="col-md-6">
		<h2>Accès à la base de données</h2>
		<div class="required form-group">
			<label for="customer_firstname">{l s='Url de phpmyadmin'} <sup>*</sup></label>
			<small>http://www.mondomaine.com/phpmyadmin</small>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname">{l s='Nom Utilisateur'} <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname">{l s='Mot de passe'} <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
	</div>

</div><!-- row -->


</div> <!-- end addresses -->
			<p class="cart_navigation clearfix">
				<input type="hidden" class="hidden" name="step" value="2" />
				<input type="hidden" name="back" value="{$back}" />
				{foreach from=$addresses key=k item=address}
				<input type="hidden" value="{$address.id_address|intval}" name="id_address_delivery" id="id_address_delivery" />
				{/foreach}

				<button type="submit" name="processAddress" class="button btn btn-default button-medium">
					<span>{l s='Proceed to checkout'}<i class="icon-chevron-right right"></i></span>
				</button>
			</p>
		</form>

{strip}
{if !$opc}
	{addJsDef orderProcess='order'}
	{addJsDefL name=txtProduct}{l s='product' js=1}{/addJsDefL}
	{addJsDefL name=txtProducts}{l s='products' js=1}{/addJsDefL}
	{addJsDefL name=CloseTxt}{l s='Submit' js=1}{/addJsDefL}
{/if}
{capture}{if $back}&mod={$back|urlencode}{/if}{/capture}
{capture name=addressUrl}{$link->getPageLink('address', true, NULL, 'back='|cat:$back_order_page|cat:'?step=1'|cat:$smarty.capture.default)|escape:'quotes':'UTF-8'}{/capture}
{addJsDef addressUrl=$smarty.capture.addressUrl}
{capture}{'&multi-shipping=1'|urlencode}{/capture}
{addJsDef addressMultishippingUrl=$smarty.capture.addressUrl|cat:$smarty.capture.default}
{capture name=addressUrlAdd}{$smarty.capture.addressUrl|cat:'&id_address='}{/capture}
{addJsDef addressUrlAdd=$smarty.capture.addressUrlAdd}
{addJsDef formatedAddressFieldsValuesList=$formatedAddressFieldsValuesList}
{addJsDef opc=$opc|boolval}
{capture}<h3 class="page-subheading">{l s='Your billing address' js=1}</h3>{/capture}
{addJsDefL name=titleInvoice}{$smarty.capture.default|@addcslashes:'\''}{/addJsDefL}
{capture}<h3 class="page-subheading">{l s='Your delivery address' js=1}</h3>{/capture}
{addJsDefL name=titleDelivery}{$smarty.capture.default|@addcslashes:'\''}{/addJsDefL}
{capture}<a class="button button-small btn btn-default" href="{$smarty.capture.addressUrlAdd}" title="{l s='Update' js=1}"><span>{l s='Update' js=1}<i class="icon-chevron-right right"></i></span></a>{/capture}
{addJsDefL name=liUpdate}{$smarty.capture.default|@addcslashes:'\''}{/addJsDefL}
{/strip}
