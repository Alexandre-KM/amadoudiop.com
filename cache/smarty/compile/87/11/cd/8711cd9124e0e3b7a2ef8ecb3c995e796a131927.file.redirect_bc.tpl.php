<?php /* Smarty version Smarty-3.1.19, created on 2017-02-23 19:30:55
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/modules/payzen/views/templates/front/redirect_bc.tpl" */ ?>
<?php /*%%SmartyHeaderCode:134117185258af2a5fc095d2-27688340%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8711cd9124e0e3b7a2ef8ecb3c995e796a131927' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/modules/payzen/views/templates/front/redirect_bc.tpl',
      1 => 1483697714,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '134117185258af2a5fc095d2-27688340',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'payzen_params' => 0,
    'payzen_title' => 0,
    'payzen_url' => 0,
    'key' => 0,
    'value' => 0,
    'payzen_logo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58af2a5fc768b1_77116365',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58af2a5fc768b1_77116365')) {function content_58af2a5fc768b1_77116365($_smarty_tpl) {?>

<?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?>PayZen<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
<?php if (version_compare(@constant('_PS_VERSION_'),'1.6','<')) {?>
  <?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./breadcrumb.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }?>

<?php if (isset($_smarty_tpl->tpl_vars['payzen_params']->value)&&$_smarty_tpl->tpl_vars['payzen_params']->value['vads_action_mode']=='SILENT') {?>
  <h1><?php echo smartyTranslate(array('s'=>'Payment processing','mod'=>'payzen'),$_smarty_tpl);?>
</h1>
<?php } else { ?>
  <h1><?php echo smartyTranslate(array('s'=>'Redirection to payment gateway','mod'=>'payzen'),$_smarty_tpl);?>
</h1>
<?php }?>

<?php $_smarty_tpl->tpl_vars['current_step'] = new Smarty_variable('payment', null, 0);?>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./order-steps.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<div id="payzen_content" style="display: none;">
  <h3><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['payzen_title']->value, ENT_QUOTES, 'UTF-8', true);?>
</h3>

  <form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['payzen_url']->value, ENT_QUOTES, 'UTF-8', true);?>
" method="post" id="payzen_form" name="payzen_form">
    <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['payzen_params']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
      <input type="hidden" name="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['value']->value, ENT_QUOTES, 'UTF-8', true);?>
" />
    <?php } ?>

    <p>
      <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['payzen_logo']->value, ENT_QUOTES, 'UTF-8', true);?>
" alt="PayZen" style="margin-bottom: 5px" />
      <br />

      <?php if ($_smarty_tpl->tpl_vars['payzen_params']->value['vads_action_mode']=='SILENT') {?>
        <?php echo smartyTranslate(array('s'=>'Please wait a moment. Your order payment is now processing.','mod'=>'payzen'),$_smarty_tpl);?>

      <?php } else { ?>
        <?php echo smartyTranslate(array('s'=>'Please wait, you will be redirected to the payment platform.','mod'=>'payzen'),$_smarty_tpl);?>

      <?php }?>

      <br /> <br />
      <?php echo smartyTranslate(array('s'=>'If nothing happens in 10 seconds, please click the button below.','mod'=>'payzen'),$_smarty_tpl);?>

      <br /><br />
    </p>

  <?php if (version_compare(@constant('_PS_VERSION_'),'1.6','<')) {?>
    <p class="cart_navigation">
      <input type="submit" name="submitPayment" value="<?php echo smartyTranslate(array('s'=>'Pay','mod'=>'payzen'),$_smarty_tpl);?>
" class="exclusive" />
    </p>
  <?php } else { ?>
    <p class="cart_navigation clearfix">
      <button type="submit" name="submitPayment" class="button btn btn-default standard-checkout button-medium" >
        <span><?php echo smartyTranslate(array('s'=>'Pay','mod'=>'payzen'),$_smarty_tpl);?>
</span>
      </button>
    </p>
  <?php }?>
  </form>
</div>

<?php echo $_smarty_tpl->getSubTemplate ("./redirect_js.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
