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

{if $page_name !='index'}</div>{/if}<!-- col-md-12 -->
<footer>
	<div class="container">
		<h3>Network</h3>
		<ul class="network">
			<li>Github</li>
			<li>LinkedIn</li>
			<li>Viadeo</li>
		</ul>
		<h3>Colaborators</h3>
		<ul class="network">
			<li><a href="https://www.alexandrecarette.com/">Alexandre Carrette</a></li>
			<li><a href="http://guillaume-fradeira.com/">Guillaume Fradeira</a></li>
			<li><a href="#">Nitish Peroo</a></li>
		</ul>
		<span>Copyright © 2017</span>
	</div>
</footer>
<div class="component">
	<!-- Start Nav Structure -->
	<button class="cn-button" id="cn-button">+</button>
	<div class="cn-wrapper" id="cn-wrapper">
			<ul>
				<li><a href="#about"><span class="icon-headphones"></span></a></li>
				<li><a href="#portfolio"><span class="icon-picture"></span></a></li>
				<li><a href="#"><span class="icon-home"></span></a></li>
				<li><a href="#"><span class="icon-facetime-video"></span></a></li>
				<li><a href="#contact"><span class="icon-envelope-alt"></span></a></li>
			 </ul>
	</div>
	<div id="cn-overlay" class="cn-overlay"></div>
</div>
{include file="$tpl_dir./global.tpl"}
</div><!-- container -->
{if $page_name =='index'}
<script src="js/polyfills.js"></script>
<script src="js/circular-menu.js"></script>
<script src="js/creemson.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/grid.js"></script>
<!-- slick -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script>
	$(function() {
		Grid.init();
	});
</script>
{/if}
<!-- Modal register-->
<div class="modal fade" id="myModalRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="myModalLabel">{l s='REGISTRATION'}</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	<form action="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" method="post" id="account-creation_form" class="std box">
<div class="row">
  <div class="col-md-6">
    <div class="required form-group">
      <label for="customer_firstname">{l s='Nom'} <sup>*</sup></label>
      <input onkeyup="$('#firstname').val(this.value);" type="text" class="is_required validate form-control" data-validate="isName" id="customer_firstname" name="customer_firstname" value="{if isset($smarty.post.customer_firstname)}{$smarty.post.customer_firstname}{/if}" />
    </div>
    <div class="required form-group">
      <label for="customer_lastname">{l s='Prénom'} <sup>*</sup></label>
      <input onkeyup="$('#lastname').val(this.value);" type="text" class="is_required validate form-control" data-validate="isName" id="customer_lastname" name="customer_lastname" value="{if isset($smarty.post.customer_lastname)}{$smarty.post.customer_lastname}{/if}" />
    </div>
    <div class="presta required form-group">
      <label for="customer_fonction">{l s='Fonction'} <sup>*</sup></label>
      <input type="text" class="form-control" id="customer_fonction" name="customer_fonction" value="" />
    </div>
    <div class="required form-group">
      <label for="customer_phone">{l s='Téléphone'} <sup>*</sup></label>
      <input type="text" class="is_required validate form-control" id="customer_phone" name="customer_phone" value="" />
    </div>
    <div class="required form-group">
      <label for="email">{l s='E-mail du contact'} <sup>*</sup></label>
      <input type="email" class="is_required validate form-control" data-validate="isEmail" id="email" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" />
    </div>
    <div class="required password form-group">
      <label for="passwd">{l s='Password'} <sup>*</sup></label>
      <input type="password" class="is_required validate form-control" data-validate="isPasswd" name="passwd" id="passwd" />
      <span class="form_info">{l s='5 characters minimum'}</span>
    </div>
</div>
<div class="col-md-6">
		<div class="account_creation">
      <div class="required form-group">
  			<label for="customer_denomination">{l s='Dénomination entreprise'} <sup>*</sup></label>
  			<input type="text" class="is_required validate form-control" id="customer_denomination" name="customer_denomination" value="" />
  		</div>
  		<div class="required form-group">
  			<label for="customer_siret">{l s='SIRET'} <sup>*</sup></label>
  			<input type="text" class="is_required validate form-control" id="customer_siret" name="customer_siret" value="" />
  		</div>
  		<div class="required form-group">
  			<label for="customer_website">{l s='Site internet'} <sup>*</sup></label>
  			<input type="text" class="is_required validate form-control" id="customer_website" name="customer_website" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_address">{l s='Adresse de facturation'} <sup>*</sup></label>
  			<input type="text" class="form-control" id="customer_address" name="customer_address" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_address2">{l s='Adresse complément'} </label>
  			<input type="text" class="form-control" id="customer_address2" name="customer_address2" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_postcode">{l s='Code postal'} <sup>*</sup></label>
  			<input type="text" class="form-control" id="customer_postcode" name="customer_postcode" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_city">{l s='Ville'} <sup>*</sup></label>
  			<input type="text" class="form-control" id="customer_city" name="customer_city" value="" />
  		</div>
			<div class="required select form-group">
				<label for="id_country">Pays <sup>*</sup></label>
				<select name="id_country" id="id_country" class="form-control">

						<option value="1">France</option>

				</select>
			</div>
</div>
</div>
		</div>
		<div class="submit clearfix">
			<input type="hidden" name="email_create" value="1" />
			<input type="hidden" name="is_new_customer" value="1" />
			{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'html':'UTF-8'}" />{/if}
			<button type="submit" name="submitAccountModal" id="submitAccountModal" class="btn btn-default">
				<span>{l s='Register'}</span>
			</button>
			<p class="pull-right required"><span><sup>*</sup>{l s='Required'}</span></p>
		</div>
	</form>
 </div>

    </div>
  </div>
</div>
<!-- Modal register-->
	</body>
</html>
