<?php /* Smarty version Smarty-3.1.19, created on 2017-02-23 12:16:18
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/admin074jjgeu4/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:140001558aec4822a8ed9-28604442%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd081f59d768bf7cc344f3aaf0c9a16e52f48ce70' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/admin074jjgeu4/themes/default/template/content.tpl',
      1 => 1482157020,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '140001558aec4822a8ed9-28604442',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aec4822aeb30_34748703',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aec4822aeb30_34748703')) {function content_58aec4822aeb30_34748703($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div><?php }} ?>
