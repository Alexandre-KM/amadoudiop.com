<?php /* Smarty version Smarty-3.1.19, created on 2017-02-24 10:49:55
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/modules/cheque/views/templates/hook/payment.tpl" */ ?>
<?php /*%%SmartyHeaderCode:105951126758aff5a2f25964-05940966%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '17be4b45d2fff3662c8afee0325e9964f42208a5' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/modules/cheque/views/templates/hook/payment.tpl',
      1 => 1487927087,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '105951126758aff5a2f25964-05940966',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aff5a2f3bb93_13123780',
  'variables' => 
  array (
    'total_price' => 0,
    'link' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aff5a2f3bb93_13123780')) {function content_58aff5a2f3bb93_13123780($_smarty_tpl) {?>
<?php echo $_smarty_tpl->tpl_vars['total_price']->value;?>

<?php if ($_smarty_tpl->tpl_vars['total_price']->value>=1000) {?>
<div class="row">
	<div class="col-xs-12">
        <p class="payment_module">
            <a class="cheque" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('cheque','payment',array(),true), ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo smartyTranslate(array('s'=>'Pay by check.','mod'=>'cheque'),$_smarty_tpl);?>
">
                <?php echo smartyTranslate(array('s'=>'Pay by check','mod'=>'cheque'),$_smarty_tpl);?>
 <span><?php echo smartyTranslate(array('s'=>'(order processing will be longer)','mod'=>'cheque'),$_smarty_tpl);?>
</span>
            </a>
        </p>
    </div>
</div>
<?php }?>
<?php }} ?>
