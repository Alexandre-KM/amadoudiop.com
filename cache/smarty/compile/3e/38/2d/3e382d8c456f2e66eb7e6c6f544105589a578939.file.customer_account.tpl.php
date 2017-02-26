<?php /* Smarty version Smarty-3.1.19, created on 2017-02-24 15:04:51
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/modules/subscriptionsmanager//views/templates/front/customer_account.tpl" */ ?>
<?php /*%%SmartyHeaderCode:53626648658b03d83077135-13538935%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3e382d8c456f2e66eb7e6c6f544105589a578939' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/modules/subscriptionsmanager//views/templates/front/customer_account.tpl',
      1 => 1487598754,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '53626648658b03d83077135-13538935',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b03d830a6bb3_79042389',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b03d830a6bb3_79042389')) {function content_58b03d830a6bb3_79042389($_smarty_tpl) {?><li>    
    <a href="<?php echo mb_convert_encoding(htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('subscriptionsmanager','subscriptions'), ENT_QUOTES, 'UTF-8', true), "HTML-ENTITIES", 'UTF-8');?>
" title="<?php echo smartyTranslate(array('s'=>'My subscriptions','mod'=>'subscriptionsmanager'),$_smarty_tpl);?>
">
	<i class="icon-heart"></i>
	<span><?php echo smartyTranslate(array('s'=>'My subscriptions','mod'=>'subscriptionsmanager'),$_smarty_tpl);?>
</span>
    </a>
</li><?php }} ?>
