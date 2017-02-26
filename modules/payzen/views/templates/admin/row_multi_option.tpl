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

<tr id="payzen_multi_option_{$key|escape:'html':'UTF-8'}">
  <td>
    {include file="./input_text_lang.tpl"
      languages=$prestashop_languages
      current_lang=$prestashop_id_lang
      input_name="PAYZEN_MULTI_OPTIONS[{$key|escape:'html':'UTF-8'}][label]"
      field_id="PAYZEN_MULTI_OPTIONS_{$key|escape:'html':'UTF-8'}_label"
      input_value=$option.label
      style="width: 140px;"
    }
  </td>
  <td>
    <input id="PAYZEN_MULTI_OPTIONS_{$key|escape:'html':'UTF-8'}_amount_min"
        name="PAYZEN_MULTI_OPTIONS[{$key|escape:'html':'UTF-8'}][amount_min]"
        value="{$option.amount_min|escape:'html':'UTF-8'}"
        style="width: 75px;"
        type="text">
  </td>
  <td>
    <input id="PAYZEN_MULTI_OPTIONS_{$key|escape:'html':'UTF-8'}_amount_max"
        name="PAYZEN_MULTI_OPTIONS[{$key|escape:'html':'UTF-8'}][amount_max]"
        value="{$option.amount_max|escape:'html':'UTF-8'}"
        style="width: 75px;"
        type="text">
  </td>
  <td>
    <input id="PAYZEN_MULTI_OPTIONS_{$key|escape:'html':'UTF-8'}_contract"
        name="PAYZEN_MULTI_OPTIONS[{$key|escape:'html':'UTF-8'}][contract]"
        value="{$option.contract|escape:'html':'UTF-8'}"
        style="width: 65px;"
        type="text">
  </td>
  <td>
    <input id="PAYZEN_MULTI_OPTIONS_{$key|escape:'html':'UTF-8'}_count"
        name="PAYZEN_MULTI_OPTIONS[{$key|escape:'html':'UTF-8'}][count]"
        value="{$option.count|escape:'html':'UTF-8'}"
        style="width: 55px;"
        type="text">
  </td>
  <td>
    <input id="PAYZEN_MULTI_OPTIONS_{$key|escape:'html':'UTF-8'}_period"
        name="PAYZEN_MULTI_OPTIONS[{$key|escape:'html':'UTF-8'}][period]"
        value="{$option.period|escape:'html':'UTF-8'}"
        style="width: 55px;"
        type="text">
  </td>
  <td>
    <input id="PAYZEN_MULTI_OPTIONS_{$key|escape:'html':'UTF-8'}_first"
        name="PAYZEN_MULTI_OPTIONS[{$key|escape:'html':'UTF-8'}][first]"
        value="{$option.first|escape:'html':'UTF-8'}"
        style="width: 70px;"
        type="text">
  </td>
  <td>
    <button type="button" style="width: 75px;" onclick="javascript: payzenDeleteOption({$key|escape:'html':'UTF-8'});">{l s='Delete' mod='payzen'}</button>
  </td>
</tr>
