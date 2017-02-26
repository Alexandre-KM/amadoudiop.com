<?php /* Smarty version Smarty-3.1.19, created on 2017-02-26 16:11:18
         compiled from "/Users/Alex/Desktop/sites-git/amadoudiop.com/admin074jjgeu4/themes/default/template/helpers/list/list_action_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:113223958258b2f0166ce434-01464492%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2036a825c6989634639b71ce0c042a3462a9eb41' => 
    array (
      0 => '/Users/Alex/Desktop/sites-git/amadoudiop.com/admin074jjgeu4/themes/default/template/helpers/list/list_action_edit.tpl',
      1 => 1488114806,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '113223958258b2f0166ce434-01464492',
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
  'unifunc' => 'content_58b2f01672fc34_70025200',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b2f01672fc34_70025200')) {function content_58b2f01672fc34_70025200($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="edit">
	<i class="icon-pencil"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>

</a><?php }} ?>
