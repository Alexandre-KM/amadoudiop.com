<script>
    {literal}
	$(document).ready(function() {

	    /** Fonction permettant de tester le mode de paiement choisi **/
	    function test_payment() {

		var id_paypal = {/literal}{if !empty($id_paypal)}{$id_paypal}{else} - 1{/if}{literal};

		if ($('#id_payment_module').val() == id_paypal) {
		    $('#is_renewable, #migrate_to, #is_renewable').attr('disabled', 'disabled');
		}


	    }

	    /** Fonction de changement des fréquences disponibles en fonction de la durée choisie **/
	    function display_frequencies(duration, selectedVal) {
		// Variation de la fr�quence
		$.ajax({
		    url: "{/literal}{$module_dir_for_ajax}{literal}/ajax.php",
		    data: {duration: duration, operation: 'check_frequencies'}
		}).done(function(data) {
		    //$( this ).addClass( "done" );
		    $('#frequency').html(data);
		    $('#frequency').val(selectedVal);
		});

	    }

	    // Premier appel du fichier AJAX au chargement de la page
	    display_frequencies($('#duration').val(), $('#frequency').val());
	    // Premier test du mode de paiement au chargement de la page
	    test_payment();

	    // Lorsque le marchant change de mode de paiement, on test celui choisit
	    $('#id_payment_module').change(function() {
		test_payment();
	    });

	    // Au changement de One Shot
	    $('#one_shot_on').click(function() {

		var is_checked = $(this).is(':checked');

		if (is_checked == true) {
		    $('#engagement_duration, #advance_notice_duration').attr('disabled', 'disabled');
		    $('#frequency').attr('disabled', 'disabled');
		    $('#stock_decrementation_on, #stock_decrementation_off').removeAttr('disabled');
		    $("#duration option[value='']").remove();

		}
	    });

	    $('#one_shot_off').click(function() {

		var is_checked = $(this).is(':checked');

		if (is_checked == true) {
		    $('#engagement_duration, #advance_notice_duration').removeAttr('disabled');
		    $('#stock_decrementation_on').removeAttr('checked');
		    $('#stock_decrementation_off').attr('checked', 'checked');
		    $('#stock_decrementation_on, #stock_decrementation_off').attr('disabled', 'disabled');
		    $('#frequency').removeAttr('disabled');
		    
		    if ($("#duration option[value='']").length <= 0) {
			var o = new Option("{/literal}{l s='Unlimited' mod='subscriptionsmanager'}{literal}", "");
			$("#duration").append(o);
		    }
		}

	    });

	    // Au changement de Is renewable
	    $('#is_renewable_on').click(function() {
		var is_checked = $(this).is(':checked');

		if (is_checked == true)
		    $("#duration option[value='']").remove();
	    });

	    $('#is_renewable_off').click(function() {
		var is_checked = $(this).is(':checked');

		if (is_checked == true) {
		    if ($("#duration option[value='']").length <= 0) {
			var o = new Option("{/literal}{l s='Unlimited' mod='subscriptionsmanager'}{literal}", "");
			$("#duration").append(o);
		    }
		}
	    });

	    // Au changement du Stock decrementation
	    $('#stock_decrementation_on').click(function() {
		var is_checked = $(this).is(':checked');
		if (is_checked == true)
		    $('#frequency').removeAttr('disabled');
	    });

	    $('#stock_decrementation_off').click(function() {
		var is_checked = $(this).is(':checked');
		if (is_checked == true)
		    $('#frequency').attr('disabled', 'disabled');
	    });

	    // Au changement de Id group linked
	    $('#id_group_linked').change(function() {
		if ($(this).val() != '') {
		    $('#id_group_back').removeAttr('disabled');
		} else {
		    $('#id_group_back').val('');
		    $('#id_group_back').attr('disabled', 'disabled');
		}
	    });

	    // Au changement de Discount mode
	    $('#discount_mode').change(function() {

		if ($(this).val() != '') {
		    $('#discount_nb_months').removeAttr('disabled');
		} else {
		    $('#discount_nb_months').attr('disabled', 'disabled');
		}
		if ($(this).val() == 'reduction_offer') {
		    $('#discount_value, #discount_type').removeAttr('disabled');

		} else {
		    $('#discount_value, #discount_type').attr('disabled', 'disabled');
		}
	    });

	    // Au changement de Duration
	    $('#duration').change(function() {

		display_frequencies($(this).val()); // Appel des fréquences autorisées

		if ($(this).val() == '') {
		    $('#one_shot_on, #one_shot_off').removeAttr('checked');
		    $('#is_renewable_on, #is_renewable_off').removeAttr('checked');
		    $('#advance_notice_duration').attr('disabled', 'disabled');
		    $('#one_shot_on, #one_shot_off').attr('disabled', 'disabled');
		    $('#is_renewable_on, is_renewable_off').attr('disabled', 'disabled');
		}
		else if ($('#one_shot').is(':checked') === false) {
		    $('#advance_notice_duration').removeAttr('disabled');
		    $('#one_shot').removeAttr('disabled');
		    $('#is_renewable').removeAttr('disabled');
		}

	    });

	    // Au changement de Is renewable
	    $('#is_renewable_on').click(function() {
		var is_checked = $(this).is(':checked');
		if (is_checked == true)
		    $('#migrate_to, #migrate_type').removeAttr('disabled');

	    });

	    $('#is_renewable_off').click(function() {
		var is_checked = $(this).is(':checked');
		if (is_checked == true)
		    $('#migrate_to, #migrate_type').attr('disabled', 'disabled');
	    });

	});
    {/literal}
</script>

{if $errors}
    {$errors}
{/if}
<form action="{$form_action}" method="post" id="module_form" class="defaultForm  form-horizontal">
    <input type="hidden" name="id_schema" value="{$smarty.request.id}" />

    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-money"></i> 
	    {l s='Payment mode' mod='subscriptionsmanager'}
	</div>

	<div class="form-group ">
	    <label class="control-label col-lg-2">{l s='Choose a payment mode on the list' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="id_payment_module" id="id_payment_module">
		    <option value="">{l s='Choose a payment mode on the list' mod='subscriptionsmanager'}</option>
		    {foreach $paymentModules as $paymentModule}
			<option value="{$paymentModule->id}" {if isset($smarty.request.id_payment_module) && $smarty.request.id_payment_module == $paymentModule->id}selected="selected"{/if}>{$paymentModule->displayName}</option>
		    {/foreach}
		</select>
	    </div>
	    <p class="help-block">
		{l s='This payment will be used during the first order and the following' mod='subscriptionsmanager'}
	    </p>
	</div>
    </div>


    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-cogs"></i> 
	    {l s='Schema settings' mod='subscriptionsmanager'}
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2">{l s='Add the subscriber to the group' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="id_group_linked" id="id_group_linked">
		    <option value="">{l s='Do nothing' mod='subscriptionsmanager'}</option>
		    {foreach $clientGroups as $group}
			<option value="{$group.id_group}" {if isset($smarty.request.id_group_linked) && $smarty.request.id_group_linked == $group.id_group}selected="selected"{/if}>{$group.name}</option>
		    {/foreach}
		</select>
	    </div>
	</div>
	{* Groupe de retour *}
	<div class="form-group ">
	    <label class="control-label col-lg-2" >{l s='Group return after the subscription ended' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="id_group_back" {if isset($smarty.request.id_group_linked) && $smarty.request.id_group_linked == "" || !isset($smarty.request.id_group_linked)}disabled="disabled"{/if} id="id_group_back">
		    <option value="">{l s='Default group' mod='subscriptionsmanager'}</option>
		    {foreach $clientGroups as $group}
			<option value="{$group.id_group}" {if isset($smarty.request.id_group_back) && $smarty.request.id_group_back == $group.id_group}selected="selected"{/if}>{$group.name}</option>
		    {/foreach}
		</select>
	    </div>
	</div>


	{* TEST *}
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="one_shot">{l s='One time payment' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<span class="switch prestashop-switch fixed-width-lg">
		    <input id="one_shot_on" type="radio" {if isset($smarty.request.one_shot) && $smarty.request.one_shot == 1}checked="checked"{/if} value="1" name="one_shot">
		    <label for="one_shot_on"> Oui </label>
		    <input id="one_shot_off" type="radio" {if isset($smarty.request.one_shot) && $smarty.request.one_shot == 0 || !isset($smarty.request.one_shot)}checked="checked"{/if} value="0" name="one_shot">
		    <label for="one_shot_off"> Non </label>
		    <a class="slide-button btn"></a>
		</span>
	    </div>
	</div>





	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="is_renewable">{l s='Renewable subscription' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">

		<span class="switch prestashop-switch fixed-width-lg">
		    <input id="is_renewable_on" type="radio" {if isset($smarty.request.is_renewable) && $smarty.request.is_renewable == 1}checked="checked"{/if} value="1" name="is_renewable">
		    <label for="is_renewable_on"> Oui </label>
		    <input id="is_renewable_off" type="radio" {if isset($smarty.request.is_renewable) && $smarty.request.is_renewable == 0 || !isset($smarty.request.is_renewable)}checked="checked"{/if} value="0" name="is_renewable">
		    <label for="is_renewable_off"> Non </label>
		    <a class="slide-button btn"></a>
		</span>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="stock_decrementation">{l s='Decrementation of stock' mod='subscriptionsmanager'}</label>	
	    <div class="col-lg-10">

		<span class="switch prestashop-switch fixed-width-lg">
		    <input id="stock_decrementation_on" type="radio" {if (isset($smarty.request.one_shot) && $smarty.request.one_shot == 0) || !isset($smarty.request.one_shot)}disabled="disabled"{/if} {if isset($smarty.request.stock_decrementation) && $smarty.request.stock_decrementation == 1}checked="checked"{/if} value="1" name="stock_decrementation">
		    <label for="stock_decrementation_on"> Oui </label>
		    <input id="stock_decrementation_off" type="radio" {if (isset($smarty.request.one_shot) && $smarty.request.one_shot == 0) || !isset($smarty.request.one_shot)}disabled="disabled"{/if} {if isset($smarty.request.stock_decrementation) && $smarty.request.stock_decrementation == 0 || !isset($smarty.request.stock_decrementation)}checked="checked"{/if} value="0" name="stock_decrementation">
		    <label for="stock_decrementation_off"> Non </label>
		    <a class="slide-button btn"></a>
		</span>

		<p class="help-block">
		    {l s='This option (only used for one shot subscriptions) allows Prestashop decrement the quantity of the product during each passage of CRON' mod='subscriptionsmanager'}
		</p>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="duration">{l s='Subscription duration' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="duration" id="duration">
		    {foreach $periods as $val => $month}
			<option value="{$val}" {if isset($smarty.request.duration) && $smarty.request.duration == $val}selected="selected"{/if}>{$month}</option>
		    {/foreach}
		    {if isset($smarty.request.one_shot) && $smarty.request.one_shot == 0 && isset($smarty.request.is_renewable) && $smarty.request.is_renewable == 0 || (!isset($smarty.request.is_renewable) && !isset($smarty.request.one_shot))}
			<option value="" {if isset($smarty.request.duration) && $smarty.request.duration == 0}selected="selected"{/if}>{l s='Unlimited' mod='subscriptionsmanager'}</option>
		    {/if}
		</select>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="frequency">{l s='Frequency of subscription' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="frequency" id="frequency" {if isset($smarty.request.one_shot) && $smarty.request.one_shot == 1 && isset($smarty.request.stock_decrementation) && $smarty.request.stock_decrementation == 0}disabled="disabled"{/if}>
		    {foreach $periods as $val => $month}
			<option value="{$val}" {if isset($smarty.request.frequency) && $smarty.request.frequency == $val}selected="selected"{/if}>{$month}</option>
		    {/foreach}
		</select>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="engagement_duration">{l s='Engagement duration' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="engagement_duration" id="engagement_duration" {if isset($smarty.request.one_shot) && $smarty.request.one_shot == 1}disabled="disabled"{/if}>
		    <option value="">{l s='No engagement' mod='subscriptionsmanager'}</option>
		    {foreach $periods as $val => $month}
			<option value="{$val}" {if isset($smarty.request.engagement_duration) && $smarty.request.engagement_duration == $val}selected="selected"{/if}>{$month}</option>
		    {/foreach}
		</select>
		<p class="help-block">
		    {l s='Minimum period for which the subscriber may not cancel or renew a subscription' mod='subscriptionsmanager'}
		</p>

	    </div>
	</div>

	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="advance_notice_duration">{l s='Notice duration' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="advance_notice_duration" id="advance_notice_duration" {if isset($smarty.request.one_shot) && $smarty.request.one_shot == 1 || isset($smarty.request.duration) && $smarty.request.duration == 0}disabled="disabled"{/if}>
		    <option value="">{l s='No notice' mod='subscriptionsmanager'}</option>
		    {foreach $periods as $val => $month}
			<option value="{$val}" {if isset($smarty.request.advance_notice_duration) && $smarty.request.advance_notice_duration == $val}selected="selected"{/if}>{$month}</option>
		    {/foreach}
		</select>
		<p class="help-block">
		    {l s='Period before the end of the subscription for which the subscriber may not cancel or renew a subscription' mod='subscriptionsmanager'}
		</p>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2" >{l s='Product attribute associated with the schema' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="id_product_attribute" id="id_product_attribute">
		    {foreach $products as $product}
			<option {if isset($smarty.request.id_product_attribute) && $smarty.request.id_product_attribute == $product.id_product_attribute} selected="selected" {/if} {if isset($product.already_associated) && $product.already_associated == 1 && !isset($smarty.request.id_product_attribute) || (isset($product.already_associated) && $product.already_associated == 1 && isset($smarty.request.id_product_attribute) && $smarty.request.id_product_attribute != $product.id_product_attribute)} disabled="disabled" {/if} value="{if isset($product.already_associated) && $product.already_associated != 1 || isset($smarty.request.id_product_attribute) && $smarty.request.id_product_attribute == $product.id_product_attribute}{$product.id_product_attribute}{/if}">{$product.name} - {$product.price_with_vat} {$currency} {if isset($product.already_associated) && $product.already_associated == 1 && !isset($smarty.request.id_product_attribute) || (isset($product.already_associated) && $product.already_associated == 1 && isset($smarty.request.id_product_attribute) && $smarty.request.id_product_attribute != $product.id_product_attribute)}- {l s='Already used' mod='subscriptionsmanager'}{/if}</option>
		    {/foreach}
		</select>
		<p class="help-block">
		    <a href="{$admin_attributes_uri}" class="btn btn-default">{l s='Add new attributes' mod='subscriptionsmanager'} <i class="icon-external-link"></i></a>
		</p>
	    </div>
	</div>
    </div>

    {* Gestion des réductions *}
    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-wrench"></i>
	    {l s='Discount management' mod='subscriptionsmanager'} {l s='optional' mod='subscriptionsmanager'}
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2" >{l s='Type of reduction to be applied' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="discount_mode" id="discount_mode">
		    <option value="">{l s='Choose the type of reduction to be applied if necessary' mod='subscriptionsmanager'}</option>
		    <option value="month_offer" {if isset($smarty.request.discount_mode) && $smarty.request.discount_mode == 'month_offer'}selected="selected"{/if}>{l s='Free initial levy frequencies' mod='subscriptionsmanager'}</option>
		    <option value="reduction_offer" {if isset($smarty.request.discount_mode) && $smarty.request.discount_mode == 'reduction_offer'}selected="selected"{/if}>{l s='Cash reduction' mod='subscriptionsmanager'}</option>
		</select>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2" >{l s='Number of frequencies involved' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="discount_nb_months" id="discount_nb_months" {if isset($smarty.request.discount_mode) && $smarty.request.discount_mode == ''}disabled="disabled"{/if}>
		    {foreach $periods as $val => $month}
			<option value="{$val}" {if isset($smarty.request.discount_nb_months) && $smarty.request.discount_nb_months == $val}selected="selected"{/if}>{$val}</option>
		    {/foreach}
		</select>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2" >{l s='Reduction amount' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<input type="text" id="discount_value" name="discount_value" value="{if isset($smarty.request.discount_value)}{$smarty.request.discount_value}{/if}" {if isset($smarty.request.discount_mode) && $smarty.request.discount_mode == '' || isset($smarty.request.discount_mode) && $smarty.request.discount_mode == 'month_offer'}disabled="disabled"{/if} />
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="discount_type">{l s='Reduction type' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<select name="discount_type" id="discount_type" {if isset($smarty.request.discount_mode) && $smarty.request.discount_mode == '' || isset($smarty.request.discount_mode) && $smarty.request.discount_mode == 'month_offer'}disabled="disabled"{/if}>
		    <option value="0" {if isset($smarty.request.discount_type) && $smarty.request.discount_type == 0}selected="selected"{/if}>{l s='Reduction on price without VAT' mod='subscriptionsmanager'}</option>
		    <option value="1" {if isset($smarty.request.discount_type) && $smarty.request.discount_type == 1}selected="selected"{/if}>{l s='Reduction on price with VAT' mod='subscriptionsmanager'}</option>
		    <option value="2" {if isset($smarty.request.discount_type) && $smarty.request.discount_type == 2}selected="selected"{/if}>{l s='Reduction on price in percent' mod='subscriptionsmanager'}</option>
		</select>
	    </div>
	</div>





    </div>
    {* FIn Gestion des réductions *}


    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-envelope"></i>
	    {l s='Notification settings' mod='subscriptionsmanager'} {l s='optional' mod='subscriptionsmanager'}
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="notification_active">{l s='Notify the customer before the expiration of his subscription' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">

		<span class="switch prestashop-switch fixed-width-lg">
		    <input id="notification_active_on" type="radio" {if isset($smarty.request.notification_active) && $smarty.request.notification_active == '1'}checked="checked"{/if} value="1" name="notification_active">
		    <label for="notification_active_on"> Oui </label>
		    <input id="notification_active_off" type="radio" {if isset($smarty.request.notification_active) && $smarty.request.notification_active == '0' || !isset($smarty.request.notification_active)}checked="checked"{/if} value="0" name="notification_active">
		    <label for="notification_active_off"> Non </label>
		    <a class="slide-button btn"></a>
		</span>

	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2" >{l s='Number of days before the expiry' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<input type="text" name="notification_time" id="notification_time" value="{if isset($smarty.request.notification_time)}{$smarty.request.notification_time}{/if}" placeholder="{l s='For example : 5' mod='subscriptionsmanager'}">
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2" >{l s='Notification message' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">
		<textarea rows="6"style="width: 99%" name="notification_message" id="notification_message" placeholder="{l s='Your subscription will be ending soon ...' mod='subscriptionsmanager'}">{if isset($smarty.request.notification_message)}{$smarty.request.notification_message}{/if}</textarea>
	    </div>
	</div>
    </div>


    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-lock"></i>
	    {l s='Blocking settings' mod='subscriptionsmanager'} {l s='optional' mod='subscriptionsmanager'}
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-2"  for="locked">{l s='Lock this schema' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-10">

		<span class="switch prestashop-switch fixed-width-lg">
		    <input id="locked_on" type="radio" {if isset($smarty.request.locked) && $smarty.request.locked == '1'}checked="checked"{/if} value="1" name="locked">
		    <label for="locked_on"> Oui </label>
		    <input id="locked_off" type="radio" {if isset($smarty.request.locked) && $smarty.request.locked == '0' || !isset($smarty.request.locked)}checked="checked"{/if} value="0" name="locked">
		    <label for="locked_off"> Non </label>
		    <a class="slide-button btn"></a>
		</span>

		<p class="help-block">
		    {l s='If the schema is locked, the customer will no longer subscribe to it. Current subscriptions related to it will continue until expiration' mod='subscriptionsmanager'}
		</p>
	    </div>
	</div>
    </div>


    <div class="panel">
	<div class="panel-heading">
	    <i class="icon-mail-forward"></i>
	    {l s='Migration settings' mod='subscriptionsmanager'} {l s='optional' mod='subscriptionsmanager'}
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-4"  for="migrate_to" >{l s='Migrate subscriptions related to this schema to another once completed' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-8">
		<select name="migrate_to" id="migrate_to" {if isset($smarty.request.is_renewable) && $smarty.request.is_renewable != '1'}disabled="disabled"{/if}>
		    <option value="">{l s='No migration' mod='subscriptionsmanager'}</option>
		    {foreach $schemas as $id => $name}
			<option value="{$id}" {if isset($smarty.request.migrate_to) && $smarty.request.migrate_to == $id}selected="selected"{/if}>{$name}</option>
		    {/foreach}
		</select>
	    </div>
	</div>
	<div class="form-group ">
	    <label class="control-label col-lg-4"  for="migrate_type">{l s='Startup type of subscription once migrated' mod='subscriptionsmanager'}</label>
	    <div class="col-lg-8">
		<input type="radio" name="migrate_type" id="migrate_type" value="1" {if isset($smarty.request.migrate_type) && $smarty.request.migrate_type == '1'}checked="checked"{/if}/> {l s='Automatic' mod='subscriptionsmanager'}
		<input type="radio" name="migrate_type" id="migrate_type" value="0" {if isset($smarty.request.migrate_type) && $smarty.request.migrate_type == '0'}checked="checked"{/if}/> {l s='Manual' mod='subscriptionsmanager'}
	    </div></div>
    </div>


    <div class="panel">
	<div class="panel-footer">
	    <button class="btn btn-default pull-right" name="saveSchema" id="module_form_submit_btn" value="1" type="submit">
		<i class="process-icon-save"></i> {l s='Save' mod='subscriptionsmanager'}
	    </button>
	    <button class="btn btn-default" name="back" value="1" type="submit">
		<i class="process-icon-cancel"></i> {l s='Go back to the list' mod='subscriptionsmanager'}
	    </button>
	</div>
    </div>

</form>