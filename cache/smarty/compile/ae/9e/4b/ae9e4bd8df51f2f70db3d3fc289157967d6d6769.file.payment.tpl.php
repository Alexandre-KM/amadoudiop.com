<?php /* Smarty version Smarty-3.1.19, created on 2017-02-23 12:20:18
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/modules/bankwire/views/templates/hook/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:98158902558aec5722bb424-50126052%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ae9e4bd8df51f2f70db3d3fc289157967d6d6769' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/modules/bankwire/views/templates/hook/payment.tpl',
      1 => 1487591172,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '98158902558aec5722bb424-50126052',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'link' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aec5722fc960_86401136',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aec5722fc960_86401136')) {function content_58aec5722fc960_86401136($_smarty_tpl) {?>
<div class="row">
	<div class="col-xs-12">
		<p class="payment_module">
			<a class="bankwire" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('bankwire','payment'), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwire'),$_smarty_tpl);?>
">
				<?php echo smartyTranslate(array('s'=>'Pay by bank wire','mod'=>'bankwire'),$_smarty_tpl);?>
 <span><?php echo smartyTranslate(array('s'=>'(order processing will be longer)','mod'=>'bankwire'),$_smarty_tpl);?>
</span>
			</a>
		</p>
	</div>
</div>
<?php }} ?>
