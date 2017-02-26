<?php /* Smarty version Smarty-3.1.19, created on 2017-02-26 00:58:02
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/order-address.tpl" */ ?>
<?php /*%%SmartyHeaderCode:154399492658aec63613d8e2-73227804%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b3ca83400df0073cb78973dc5269224ff35ece2c' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/order-address.tpl',
      1 => 1488067079,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '154399492658aec63613d8e2-73227804',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aec636252363_92918798',
  'variables' => 
  array (
    'back_order_page' => 0,
    'link' => 0,
    'oldMessage' => 0,
    'back' => 0,
    'addresses' => 0,
    'address' => 0,
    'opc' => 0,
    'formatedAddressFieldsValuesList' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aec636252363_92918798')) {function content_58aec636252363_92918798($_smarty_tpl) {?>
	<?php $_smarty_tpl->tpl_vars['current_step'] = new Smarty_variable('address', null, 0);?>
	<?php $_smarty_tpl->_capture_stack[0][] = array('path', null, null); ob_start(); ?><?php echo smartyTranslate(array('s'=>'Addresses'),$_smarty_tpl);?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?>
	<?php $_smarty_tpl->tpl_vars["back_order_page"] = new Smarty_variable("order.php", null, 0);?>
	<h1 class="page-heading"><?php echo smartyTranslate(array('s'=>'Informations'),$_smarty_tpl);?>
</h1>
	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./order-steps.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

	<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

		<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink($_smarty_tpl->tpl_vars['back_order_page']->value,true), ENT_QUOTES, 'UTF-8', true);?>
" method="post">

<div class="addresses clearfix">
	<div class="row">
		<div id="ordermsg" class="col-md-12">
			<h2>Récapitulatif de votre demande</h2>
			<label><?php echo smartyTranslate(array('s'=>'Indiquer ici de façon claire vos différentes taches.'),$_smarty_tpl);?>
</label>
			<textarea class="form-control" cols="60" rows="6" name="message"><?php if (isset($_smarty_tpl->tpl_vars['oldMessage']->value)) {?><?php echo $_smarty_tpl->tpl_vars['oldMessage']->value;?>
<?php }?></textarea>
		</div>
	</div> <!-- end row -->
	<div class="row">
		<div class="col-md-12">
			<h2>L'échange et le stockage de ces données sont cryptés.</h2>
		</div>
	<div class="col-md-6">
		<h2>Accès au backoffice de Prestashop</h2>
		<div class="required form-group">
			<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Url du backoffice'),$_smarty_tpl);?>
 <sup>*</sup></label>
			<small>http://www.mondomaine.com/phpmyadmin</small>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'E-mail'),$_smarty_tpl);?>
 <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Mot de passe'),$_smarty_tpl);?>
 <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
	</div>	<div class="col-md-6">
			<h2>Administration du nom de domaine</h2>
			<div class="required form-group">
				<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Entreprise hébergeant le domaine'),$_smarty_tpl);?>
 <sup>*</sup></label>
				<small>http://www.mondomaine.com/phpmyadmin</small>
				<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
			</div>
			<div class="required form-group">
				<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Compte Utilisateur'),$_smarty_tpl);?>
 <sup>*</sup></label>
				<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
			</div>
			<div class="required form-group">
				<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Mot de passe'),$_smarty_tpl);?>
 <sup>*</sup></label>
				<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
			</div>
		</div>
	</div> <!-- end row -->
	<div class="row">
	  <div class="col-md-6">
			<h2>Serveur FTP</h2>
	    <div class="required form-group">
	      <label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Hôte'),$_smarty_tpl);?>
 <sup>*</sup></label>
				<small>ex: ftp.monnondedomaine.com ou IP: 126.76.9.2.65</small>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_host" name="message_host" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Port'),$_smarty_tpl);?>
 <sup>*</sup></label>
				<small>ex: 21 - 22 - autre port...</small>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Protocole'),$_smarty_tpl);?>
 <sup>*</sup></label>
				<small>ex: FTP ou SFTP</small>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Nom Utilisateur'),$_smarty_tpl);?>
 <sup>*</sup></label>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
			<div class="required form-group">
	      <label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Mot de passe'),$_smarty_tpl);?>
 <sup>*</sup></label>
	      <input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
	    </div>
		</div>
	<div class="col-md-6">
		<h2>Accès à la base de données</h2>
		<div class="required form-group">
			<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Url de phpmyadmin'),$_smarty_tpl);?>
 <sup>*</sup></label>
			<small>http://www.mondomaine.com/phpmyadmin</small>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Nom Utilisateur'),$_smarty_tpl);?>
 <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
		<div class="required form-group">
			<label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Mot de passe'),$_smarty_tpl);?>
 <sup>*</sup></label>
			<input type="text" class="is_required validate form-control" data-validate="isName" id="message_protocole" name="message_protocole" value="" />
		</div>
	</div>

</div><!-- row -->


</div> <!-- end addresses -->
			<p class="cart_navigation clearfix">
				<input type="hidden" class="hidden" name="step" value="2" />
				<input type="hidden" name="back" value="<?php echo $_smarty_tpl->tpl_vars['back']->value;?>
" />
				<?php  $_smarty_tpl->tpl_vars['address'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['address']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['addresses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['address']->key => $_smarty_tpl->tpl_vars['address']->value) {
$_smarty_tpl->tpl_vars['address']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['address']->key;
?>
				<input type="hidden" value="<?php echo intval($_smarty_tpl->tpl_vars['address']->value['id_address']);?>
" name="id_address_delivery" id="id_address_delivery" />
				<?php } ?>

				<button type="submit" name="processAddress" class="button btn btn-default button-medium">
					<span><?php echo smartyTranslate(array('s'=>'Proceed to checkout'),$_smarty_tpl);?>
<i class="icon-chevron-right right"></i></span>
				</button>
			</p>
		</form>

<?php if (!$_smarty_tpl->tpl_vars['opc']->value) {?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('orderProcess'=>'order'),$_smarty_tpl);?>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'txtProduct')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'txtProduct'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'product','js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'txtProduct'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'txtProducts')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'txtProducts'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'products','js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'txtProducts'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'CloseTxt')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'CloseTxt'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo smartyTranslate(array('s'=>'Submit','js'=>1),$_smarty_tpl);?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'CloseTxt'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }?><?php $_smarty_tpl->_capture_stack[0][] = array('default', null, null); ob_start(); ?><?php if ($_smarty_tpl->tpl_vars['back']->value) {?>&mod=<?php echo urlencode($_smarty_tpl->tpl_vars['back']->value);?>
<?php }?><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php $_smarty_tpl->_capture_stack[0][] = array('addressUrl', null, null); ob_start(); ?><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['link']->value->getPageLink('address',true,null,((('back=').($_smarty_tpl->tpl_vars['back_order_page']->value)).('?step=1')).(Smarty::$_smarty_vars['capture']['default'])));?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('addressUrl'=>Smarty::$_smarty_vars['capture']['addressUrl']),$_smarty_tpl);?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', null, null); ob_start(); ?><?php echo urlencode('&multi-shipping=1');?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('addressMultishippingUrl'=>(Smarty::$_smarty_vars['capture']['addressUrl']).(Smarty::$_smarty_vars['capture']['default'])),$_smarty_tpl);?>
<?php $_smarty_tpl->_capture_stack[0][] = array('addressUrlAdd', null, null); ob_start(); ?><?php echo (Smarty::$_smarty_vars['capture']['addressUrl']).('&id_address=');?>
<?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('addressUrlAdd'=>Smarty::$_smarty_vars['capture']['addressUrlAdd']),$_smarty_tpl);?>
<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('formatedAddressFieldsValuesList'=>$_smarty_tpl->tpl_vars['formatedAddressFieldsValuesList']->value),$_smarty_tpl);?>
<?php echo $_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_FUNCTION]['addJsDef'][0][0]->addJsDef(array('opc'=>$_smarty_tpl->smarty->registered_plugins[Smarty::PLUGIN_MODIFIER]['boolval'][0][0]->boolval($_smarty_tpl->tpl_vars['opc']->value)),$_smarty_tpl);?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', null, null); ob_start(); ?><h3 class="page-subheading"><?php echo smartyTranslate(array('s'=>'Your billing address','js'=>1),$_smarty_tpl);?>
</h3><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'titleInvoice')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'titleInvoice'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo addcslashes(Smarty::$_smarty_vars['capture']['default'],'\'');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'titleInvoice'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', null, null); ob_start(); ?><h3 class="page-subheading"><?php echo smartyTranslate(array('s'=>'Your delivery address','js'=>1),$_smarty_tpl);?>
</h3><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'titleDelivery')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'titleDelivery'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo addcslashes(Smarty::$_smarty_vars['capture']['default'],'\'');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'titleDelivery'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php $_smarty_tpl->_capture_stack[0][] = array('default', null, null); ob_start(); ?><a class="button button-small btn btn-default" href="<?php echo Smarty::$_smarty_vars['capture']['addressUrlAdd'];?>
" title="<?php echo smartyTranslate(array('s'=>'Update','js'=>1),$_smarty_tpl);?>
"><span><?php echo smartyTranslate(array('s'=>'Update','js'=>1),$_smarty_tpl);?>
<i class="icon-chevron-right right"></i></span></a><?php list($_capture_buffer, $_capture_assign, $_capture_append) = array_pop($_smarty_tpl->_capture_stack[0]);
if (!empty($_capture_buffer)) {
 if (isset($_capture_assign)) $_smarty_tpl->assign($_capture_assign, ob_get_contents());
 if (isset( $_capture_append)) $_smarty_tpl->append( $_capture_append, ob_get_contents());
 Smarty::$_smarty_vars['capture'][$_capture_buffer]=ob_get_clean();
} else $_smarty_tpl->capture_error();?><?php $_smarty_tpl->smarty->_tag_stack[] = array('addJsDefL', array('name'=>'liUpdate')); $_block_repeat=true; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'liUpdate'), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>
<?php echo addcslashes(Smarty::$_smarty_vars['capture']['default'],'\'');?>
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo $_smarty_tpl->smarty->registered_plugins['block']['addJsDefL'][0][0]->addJsDefL(array('name'=>'liUpdate'), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }} ?>
