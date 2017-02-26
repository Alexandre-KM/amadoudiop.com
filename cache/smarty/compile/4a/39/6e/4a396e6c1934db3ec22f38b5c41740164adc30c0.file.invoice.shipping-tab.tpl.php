<?php /* Smarty version Smarty-3.1.19, created on 2017-02-24 15:05:46
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/pdf/invoice.shipping-tab.tpl" */ ?>
<?php /*%%SmartyHeaderCode:87487622258b03dbaf2f296-53802301%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4a396e6c1934db3ec22f38b5c41740164adc30c0' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/pdf/invoice.shipping-tab.tpl',
      1 => 1482157024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '87487622258b03dbaf2f296-53802301',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'carrier' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b03dbaf32f74_86824822',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b03dbaf32f74_86824822')) {function content_58b03dbaf32f74_86824822($_smarty_tpl) {?>
<table id="shipping-tab" width="100%">
	<tr>
		<td class="shipping center small grey bold" width="44%"><?php echo smartyTranslate(array('s'=>'Carrier','pdf'=>'true'),$_smarty_tpl);?>
</td>
		<td class="shipping center small white" width="56%"><?php echo $_smarty_tpl->tpl_vars['carrier']->value->name;?>
</td>
	</tr>
</table>
<?php }} ?>
