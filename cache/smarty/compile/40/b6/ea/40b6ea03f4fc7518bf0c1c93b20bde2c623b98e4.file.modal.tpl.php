<?php /* Smarty version Smarty-3.1.19, created on 2017-02-26 16:09:57
         compiled from "/Users/Alex/Desktop/sites-git/amadoudiop.com/admin074jjgeu4/themes/default/template/helpers/modules_list/modal.tpl" */ ?>
<?php /*%%SmartyHeaderCode:124522152458b2efc53cd9d1-94797219%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '40b6ea03f4fc7518bf0c1c93b20bde2c623b98e4' => 
    array (
      0 => '/Users/Alex/Desktop/sites-git/amadoudiop.com/admin074jjgeu4/themes/default/template/helpers/modules_list/modal.tpl',
      1 => 1488114804,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '124522152458b2efc53cd9d1-94797219',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b2efc5429ce8_89457587',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b2efc5429ce8_89457587')) {function content_58b2efc5429ce8_89457587($_smarty_tpl) {?><div class="modal fade" id="modules_list_container">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title"><?php echo smartyTranslate(array('s'=>'Recommended Modules and Services'),$_smarty_tpl);?>
</h3>
			</div>
			<div class="modal-body">
				<div id="modules_list_container_tab_modal" style="display:none;"></div>
				<div id="modules_list_loader"><i class="icon-refresh icon-spin"></i></div>
			</div>
		</div>
	</div>
</div>
<?php }} ?>
