<?php /* Smarty version Smarty-3.1.19, created on 2017-02-25 11:33:03
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/admin074jjgeu4/themes/default/template/helpers/list/list_action_view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:18859299758b15d5fd0c8f3-08337805%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd5a744290555e25dae90c0eb421a92ec4fa27700' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/admin074jjgeu4/themes/default/template/helpers/list/list_action_view.tpl',
      1 => 1482157020,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '18859299758b15d5fd0c8f3-08337805',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b15d5fd15b64_98080796',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b15d5fd15b64_98080796')) {function content_58b15d5fd15b64_98080796($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" >
	<i class="icon-search-plus"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>

</a><?php }} ?>
