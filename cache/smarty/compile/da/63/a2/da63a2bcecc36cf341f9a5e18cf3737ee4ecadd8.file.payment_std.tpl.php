<?php /* Smarty version Smarty-3.1.19, created on 2017-02-23 12:20:18
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/modules/payzen/views/templates/hook/payment_std.tpl" */ ?>
<?php /*%%SmartyHeaderCode:209935744458aec5723af572-38394976%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'da63a2bcecc36cf341f9a5e18cf3737ee4ecadd8' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/modules/payzen/views/templates/hook/payment_std.tpl',
      1 => 1483697716,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '209935744458aec5723af572-38394976',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'payzen_std_card_data_mode' => 0,
    'payzen_logo' => 0,
    'payzen_title' => 0,
    'link' => 0,
    'payzen_avail_cards' => 0,
    'key' => 0,
    'first' => 0,
    'img_file' => 0,
    'base_dir_ssl' => 0,
    'label' => 0,
    'year' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aec572472ee2_66547035',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aec572472ee2_66547035')) {function content_58aec572472ee2_66547035($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sites/amadoudiop.com/prestashop-prod/tools/smarty/plugins/modifier.date_format.php';
?>

<?php if (version_compare(@constant('_PS_VERSION_'),'1.6','>=')) {?>
<div class="row"><div class="col-xs-12<?php if (version_compare(@constant('_PS_VERSION_'),'1.6.0.11','<')) {?> col-md-6<?php }?>">
<?php }?>
<div class="payment_module payzen">
  <?php if ($_smarty_tpl->tpl_vars['payzen_std_card_data_mode']->value==1) {?>
    <a onclick="javascript: $('#payzen_standard').submit();" title="<?php echo smartyTranslate(array('s'=>'Click here to pay by bank card','mod'=>'payzen'),$_smarty_tpl);?>
" href="javascript: void(0);">
  <?php } else { ?> 
    <a class="unclickable" title="<?php echo smartyTranslate(array('s'=>'Enter payment information and click «Pay now» button','mod'=>'payzen'),$_smarty_tpl);?>
">
  <?php }?>
    <img class="logo" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['payzen_logo']->value, ENT_QUOTES, 'UTF-8', true);?>
" alt="PayZen"/><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['payzen_title']->value, ENT_QUOTES, 'UTF-8', true);?>


    <form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getModuleLink('payzen','redirect',array(),true), ENT_QUOTES, 'UTF-8', true);?>
"
          method="post"
          id="payzen_standard"
          <?php if ($_smarty_tpl->tpl_vars['payzen_std_card_data_mode']->value==3) {?>onsubmit="javascript: return payzenCheckFields();"<?php }?>>

      <input type="hidden" name="payzen_payment_type" value="standard" />

      <?php if (($_smarty_tpl->tpl_vars['payzen_std_card_data_mode']->value==2)||($_smarty_tpl->tpl_vars['payzen_std_card_data_mode']->value==3)) {?>
        <br />

        <?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(true, null, 0);?>
        <?php  $_smarty_tpl->tpl_vars["label"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["label"]->_loop = false;
 $_smarty_tpl->tpl_vars["key"] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['payzen_avail_cards']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["label"]->key => $_smarty_tpl->tpl_vars["label"]->value) {
$_smarty_tpl->tpl_vars["label"]->_loop = true;
 $_smarty_tpl->tpl_vars["key"]->value = $_smarty_tpl->tpl_vars["label"]->key;
?>
          <div style="display: inline-block;">
            <?php if (count($_smarty_tpl->tpl_vars['payzen_avail_cards']->value)==1) {?>
              <input type="hidden" id="payzen_card_type_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="payzen_card_type" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
" >
            <?php } else { ?>
              <input type="radio" id="payzen_card_type_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
" name="payzen_card_type" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
" style="vertical-align: middle;"<?php if ($_smarty_tpl->tpl_vars['first']->value==true) {?> checked="checked"<?php }?> >
            <?php }?>

            <label for="payzen_card_type_<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['key']->value, ENT_QUOTES, 'UTF-8', true);?>
">
              <?php ob_start();?><?php echo htmlspecialchars(mb_strtolower($_smarty_tpl->tpl_vars['key']->value, 'UTF-8'), ENT_QUOTES, 'UTF-8', true);?>
<?php $_tmp1=ob_get_clean();?><?php $_smarty_tpl->tpl_vars['img_file'] = new Smarty_variable((@constant('_PS_MODULE_DIR_')).('payzen/views/img/').($_tmp1).('.png'), null, 0);?>

              <?php if (file_exists($_smarty_tpl->tpl_vars['img_file']->value)) {?>
                <img src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['base_dir_ssl']->value, ENT_QUOTES, 'UTF-8', true);?>
modules/payzen/views/img/<?php echo mb_strtolower($_smarty_tpl->tpl_vars['key']->value, 'UTF-8');?>
.png" alt="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['label']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['label']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="card" >
              <?php } else { ?>
                <span class="card"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['label']->value, ENT_QUOTES, 'UTF-8', true);?>
</span>
              <?php }?> 
            </label>

            <?php $_smarty_tpl->tpl_vars['first'] = new Smarty_variable(false, null, 0);?>
          </div>
        <?php } ?>
        <br />
        <div style="margin-bottom: 12px;"></div>

        <?php if ($_smarty_tpl->tpl_vars['payzen_std_card_data_mode']->value==3) {?>
          <label for="payzen_card_number"> <?php echo smartyTranslate(array('s'=>'Card number','mod'=>'payzen'),$_smarty_tpl);?>
</label><br />
          <input type="text" name="payzen_card_number" value="" autocomplete="off" maxlength="19" id="payzen_card_number" style="max-width: 220px;" class="data" >
          <br />

          <label for="payzen_cvv"> <?php echo smartyTranslate(array('s'=>'CVV','mod'=>'payzen'),$_smarty_tpl);?>
</label><br />
          <input type="text" name="payzen_cvv" value="" autocomplete="off" maxlength="4" id="payzen_cvv" style="max-width: 55px;" class="data" >
          <br />

          <label for="payzen_expiry_month"><?php echo smartyTranslate(array('s'=>'Expiration date','mod'=>'payzen'),$_smarty_tpl);?>
</label><br />
          <select name="payzen_expiry_month" id="payzen_expiry_month" style="width: 90px;" class="data">
            <option value=""><?php echo smartyTranslate(array('s'=>'Month','mod'=>'payzen'),$_smarty_tpl);?>
</option>
            <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['name'] = 'expiry';
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] = (int) 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] = is_array($_loop=13) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] = ((int) 1) == 0 ? 1 : (int) 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total']);
?>
            <option value="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['expiry']['index']);?>
"><?php echo str_pad($_smarty_tpl->getVariable('smarty')->value['section']['expiry']['index'],2,"0",@constant('STR_PAD_LEFT'));?>
</option>
            <?php endfor; endif; ?>
          </select>

          <select name="payzen_expiry_year" id="payzen_expiry_year" style="width: 90px;" class="data">
            <option value=""><?php echo smartyTranslate(array('s'=>'Year','mod'=>'payzen'),$_smarty_tpl);?>
</option>
            <?php $_smarty_tpl->tpl_vars['year'] = new Smarty_variable(smarty_modifier_date_format(time(),"%Y"), null, 0);?>
            <?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['name'] = 'expiry';
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] = (int) $_smarty_tpl->tpl_vars['year']->value;
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['year']->value+9) ? count($_loop) : max(0, (int) $_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] = ((int) 1) == 0 ? 1 : (int) 1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['expiry']['total']);
?>
            <option value="<?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['expiry']['index']);?>
"><?php echo intval($_smarty_tpl->getVariable('smarty')->value['section']['expiry']['index']);?>
</option>
            <?php endfor; endif; ?>
          </select>
          <br />
        <?php }?>

        <?php if (version_compare(@constant('_PS_VERSION_'),'1.6','<')) {?>
          <input type="submit" name="submit" value="<?php echo smartyTranslate(array('s'=>'Pay now','mod'=>'payzen'),$_smarty_tpl);?>
" class="button" />
        <?php } else { ?>
          <button type="submit" name="submit" class="button btn btn-default standard-checkout button-medium" >
            <span><?php echo smartyTranslate(array('s'=>'Pay now','mod'=>'payzen'),$_smarty_tpl);?>
</span>
          </button>
        <?php }?>
      <?php }?>
    </form>
  </a>
</div>
<?php if (version_compare(@constant('_PS_VERSION_'),'1.6','>=')) {?>
</div></div>
<?php }?>
<?php }} ?>
