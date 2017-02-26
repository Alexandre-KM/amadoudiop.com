/**
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
 */

/**
 * Misc JavaScript functions.
 */

function payzenAddOption(first) {
    if(first) {
        $('#payzen_multi_options_btn').hide();
        $('#payzen_multi_options_table').show();
    }

    var timestamp = new Date().getTime();

    var rowTpl = $('#payzen_row_multi_option').html();
    rowTpl = rowTpl.replace(/PAYZEN_KEY/g, '' + timestamp);

    $(rowTpl).insertBefore('#payzen_multi_option_add');
}


function payzenDeleteOption(key) {
    $('#payzen_multi_option_' + key).remove();

    if($('#payzen_multi_options_table tbody tr').length == 1) {
        $('#payzen_multi_options_btn').show();
        $('#payzen_multi_options_table').hide();
    }
}

function payzenAdditionalOptionsToggle(legend) {
    var fieldset = $(legend).parent();

    $(legend).children('span').toggleClass('ui-icon-triangle-1-e ui-icon-triangle-1-s');
    fieldset.find('section').slideToggle();
}

function payzenCategoryTableVisibility() {
    var category = $('select#PAYZEN_COMMON_CATEGORY option:selected').val();

    if(category == 'CUSTOM_MAPPING') {
        $('.payzen_category_mapping').show();
        $('.payzen_category_mapping select').removeAttr('disabled');
    } else {
        $('.payzen_category_mapping').hide();
        $('.payzen_category_mapping select').attr('disabled', 'disabled');
    }
}

function payzenDeliveryTypeChanged(key) {
    var type =  $('#PAYZEN_ONEY_SHIP_OPTIONS_' + key + '_type').val();

    if(type == 'RECLAIM_IN_SHOP') {
        $('#PAYZEN_ONEY_SHIP_OPTIONS_' + key + '_address').show();
    } else {
        $('#PAYZEN_ONEY_SHIP_OPTIONS_' + key + '_address').val('');
        $('#PAYZEN_ONEY_SHIP_OPTIONS_' + key + '_address').hide();
    }
}

function payzenRedirectChanged() {
	 var redirect = $('select#PAYZEN_REDIRECT_ENABLED option:selected').val();

	if(redirect == 'True') {
	    $('#payzen_redirect_settings').show();
	    $('#payzen_redirect_settings select, #payzen_redirect_settings input').removeAttr('disabled');
	} else {
	    $('#payzen_redirect_settings').hide();
	    $('#payzen_redirect_settings select, #payzen_redirect_settings input').attr('disabled', 'disabled');
	}
}

function payzenHideOtherLanguage(id, name) {
    $('.translatable-field').hide();
    $('.lang-' + id).css('display', 'inline');

    $('.translation-btn button span').text(name);

    var id_old_language = id_language;
    id_language = id;

    if (id_old_language != id) {
        changeEmployeeLanguage();
    }
}
