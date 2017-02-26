<?php /* Smarty version Smarty-3.1.19, created on 2017-02-24 15:05:03
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/modules/paypal/views/templates/hook/column.tpl" */ ?>
<?php /*%%SmartyHeaderCode:173878732358b03d8fe208c6-63075677%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '39793b566be396bd91874cca2df372f17bb28993' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/modules/paypal/views/templates/hook/column.tpl',
      1 => 1487925939,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '173878732358b03d8fe208c6-63075677',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'base_dir_ssl' => 0,
    'logo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b03d8fe57897_93162538',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b03d8fe57897_93162538')) {function content_58b03d8fe57897_93162538($_smarty_tpl) {?>

<div id="paypal-column-block">
	<p><a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['base_dir_ssl']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
modules/paypal/about.php" rel="nofollow"><img src="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['logo']->value, ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" alt="PayPal" title="<?php echo smartyTranslate(array('s'=>'Pay with PayPal','mod'=>'paypal'),$_smarty_tpl);?>
" style="max-width: 100%" /></a></p>
</div>
<?php }} ?>
