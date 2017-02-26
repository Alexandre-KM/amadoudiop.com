<?php /* Smarty version Smarty-3.1.19, created on 2017-02-25 13:17:43
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:183069997058aec4879e9c68-37242954%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33153afaf29e81cd96cc96108920ef80c03a3674' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/footer.tpl',
      1 => 1488024873,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '183069997058aec4879e9c68-37242954',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aec4879f0d58_71547461',
  'variables' => 
  array (
    'page_name' => 0,
    'link' => 0,
    'back' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aec4879f0d58_71547461')) {function content_58aec4879f0d58_71547461($_smarty_tpl) {?>

<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index') {?></div><?php }?><!-- col-md-12 -->
<footer>
	<div class="container">
		<h3>Network</h3>
		<ul class="network">
			<li>Github</li>
			<li>LinkedIn</li>
			<li>Viadeo</li>
		</ul>
		<h3>Colaborators</h3>
		<ul class="network">
			<li><a href="https://www.alexandrecarette.com/">Alexandre Carrette</a></li>
			<li><a href="http://guillaume-fradeira.com/">Guillaume Fradeira</a></li>
			<li><a href="#">Nitish Peroo</a></li>
		</ul>
		<span>Copyright © 2017</span>
	</div>
</footer>
<div class="component">
	<!-- Start Nav Structure -->
	<button class="cn-button" id="cn-button">+</button>
	<div class="cn-wrapper" id="cn-wrapper">
			<ul>
				<li><a href="#about"><span class="icon-headphones"></span></a></li>
				<li><a href="#portfolio"><span class="icon-picture"></span></a></li>
				<li><a href="#"><span class="icon-home"></span></a></li>
				<li><a href="#"><span class="icon-facetime-video"></span></a></li>
				<li><a href="#contact"><span class="icon-envelope-alt"></span></a></li>
			 </ul>
	</div>
	<div id="cn-overlay" class="cn-overlay"></div>
</div>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./global.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

</div><!-- container -->
<?php if ($_smarty_tpl->tpl_vars['page_name']->value=='index') {?>
<script src="js/polyfills.js"></script>
<script src="js/circular-menu.js"></script>
<script src="js/creemson.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="js/grid.js"></script>
<!-- slick -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
<script>
	$(function() {
		Grid.init();
	});
</script>
<?php }?>
<!-- Modal register-->
<div class="modal fade" id="myModalRegister" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span class="modal-title" id="myModalLabel"><?php echo smartyTranslate(array('s'=>'REGISTRATION'),$_smarty_tpl);?>
</span>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
	<form action="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['link']->value->getPageLink('authentication',true), ENT_QUOTES, 'UTF-8', true);?>
" method="post" id="account-creation_form" class="std box">
<div class="row">
  <div class="col-md-6">
    <div class="required form-group">
      <label for="customer_firstname"><?php echo smartyTranslate(array('s'=>'Nom'),$_smarty_tpl);?>
 <sup>*</sup></label>
      <input onkeyup="$('#firstname').val(this.value);" type="text" class="is_required validate form-control" data-validate="isName" id="customer_firstname" name="customer_firstname" value="<?php if (isset($_POST['customer_firstname'])) {?><?php echo $_POST['customer_firstname'];?>
<?php }?>" />
    </div>
    <div class="required form-group">
      <label for="customer_lastname"><?php echo smartyTranslate(array('s'=>'Prénom'),$_smarty_tpl);?>
 <sup>*</sup></label>
      <input onkeyup="$('#lastname').val(this.value);" type="text" class="is_required validate form-control" data-validate="isName" id="customer_lastname" name="customer_lastname" value="<?php if (isset($_POST['customer_lastname'])) {?><?php echo $_POST['customer_lastname'];?>
<?php }?>" />
    </div>
    <div class="presta required form-group">
      <label for="customer_fonction"><?php echo smartyTranslate(array('s'=>'Fonction'),$_smarty_tpl);?>
 <sup>*</sup></label>
      <input type="text" class="form-control" id="customer_fonction" name="customer_fonction" value="" />
    </div>
    <div class="required form-group">
      <label for="customer_phone"><?php echo smartyTranslate(array('s'=>'Téléphone'),$_smarty_tpl);?>
 <sup>*</sup></label>
      <input type="text" class="is_required validate form-control" id="customer_phone" name="customer_phone" value="" />
    </div>
    <div class="required form-group">
      <label for="email"><?php echo smartyTranslate(array('s'=>'E-mail du contact'),$_smarty_tpl);?>
 <sup>*</sup></label>
      <input type="email" class="is_required validate form-control" data-validate="isEmail" id="email" name="email" value="<?php if (isset($_POST['email'])) {?><?php echo $_POST['email'];?>
<?php }?>" />
    </div>
    <div class="required password form-group">
      <label for="passwd"><?php echo smartyTranslate(array('s'=>'Password'),$_smarty_tpl);?>
 <sup>*</sup></label>
      <input type="password" class="is_required validate form-control" data-validate="isPasswd" name="passwd" id="passwd" />
      <span class="form_info"><?php echo smartyTranslate(array('s'=>'5 characters minimum'),$_smarty_tpl);?>
</span>
    </div>
</div>
<div class="col-md-6">
		<div class="account_creation">
      <div class="required form-group">
  			<label for="customer_denomination"><?php echo smartyTranslate(array('s'=>'Dénomination entreprise'),$_smarty_tpl);?>
 <sup>*</sup></label>
  			<input type="text" class="is_required validate form-control" id="customer_denomination" name="customer_denomination" value="" />
  		</div>
  		<div class="required form-group">
  			<label for="customer_siret"><?php echo smartyTranslate(array('s'=>'SIRET'),$_smarty_tpl);?>
 <sup>*</sup></label>
  			<input type="text" class="is_required validate form-control" id="customer_siret" name="customer_siret" value="" />
  		</div>
  		<div class="required form-group">
  			<label for="customer_website"><?php echo smartyTranslate(array('s'=>'Site internet'),$_smarty_tpl);?>
 <sup>*</sup></label>
  			<input type="text" class="is_required validate form-control" id="customer_website" name="customer_website" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_address"><?php echo smartyTranslate(array('s'=>'Adresse de facturation'),$_smarty_tpl);?>
 <sup>*</sup></label>
  			<input type="text" class="form-control" id="customer_address" name="customer_address" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_address2"><?php echo smartyTranslate(array('s'=>'Adresse complément'),$_smarty_tpl);?>
 </label>
  			<input type="text" class="form-control" id="customer_address2" name="customer_address2" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_postcode"><?php echo smartyTranslate(array('s'=>'Code postal'),$_smarty_tpl);?>
 <sup>*</sup></label>
  			<input type="text" class="form-control" id="customer_postcode" name="customer_postcode" value="" />
  		</div>
  		<div class="presta required form-group">
  			<label for="customer_city"><?php echo smartyTranslate(array('s'=>'Ville'),$_smarty_tpl);?>
 <sup>*</sup></label>
  			<input type="text" class="form-control" id="customer_city" name="customer_city" value="" />
  		</div>
			<div class="required select form-group">
				<label for="id_country">Pays <sup>*</sup></label>
				<select name="id_country" id="id_country" class="form-control">

						<option value="1">France</option>

				</select>
			</div>
</div>
</div>
		</div>
		<div class="submit clearfix">
			<input type="hidden" name="email_create" value="1" />
			<input type="hidden" name="is_new_customer" value="1" />
			<?php if (isset($_smarty_tpl->tpl_vars['back']->value)) {?><input type="hidden" class="hidden" name="back" value="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['back']->value, ENT_QUOTES, 'UTF-8', true);?>
" /><?php }?>
			<button type="submit" name="submitAccountModal" id="submitAccountModal" class="btn btn-default">
				<span><?php echo smartyTranslate(array('s'=>'Register'),$_smarty_tpl);?>
</span>
			</button>
			<p class="pull-right required"><span><sup>*</sup><?php echo smartyTranslate(array('s'=>'Required'),$_smarty_tpl);?>
</span></p>
		</div>
	</form>
 </div>

    </div>
  </div>
</div>
<!-- Modal register-->
	</body>
</html>
<?php }} ?>
