<?php /* Smarty version Smarty-3.1.19, created on 2017-02-26 16:11:18
         compiled from "/Users/Alex/Desktop/sites-git/amadoudiop.com/admin074jjgeu4/themes/default/template/helpers/list/list_action_delete.tpl" */ ?>
<?php /*%%SmartyHeaderCode:129322609358b2f016739282-23569722%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '235001cd89dacbd7ccace3690a21c68a4e35ed4b' => 
    array (
      0 => '/Users/Alex/Desktop/sites-git/amadoudiop.com/admin074jjgeu4/themes/default/template/helpers/list/list_action_delete.tpl',
      1 => 1488114809,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '129322609358b2f016739282-23569722',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'confirm' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b2f0167961c3_73393825',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b2f0167961c3_73393825')) {function content_58b2f0167961c3_73393825($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php if (isset($_smarty_tpl->tpl_vars['confirm']->value)) {?> onclick="if (confirm('<?php echo $_smarty_tpl->tpl_vars['confirm']->value;?>
')){return true;}else{event.stopPropagation(); event.preventDefault();};"<?php }?> title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="delete">
	<i class="icon-trash"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>

</a><?php }} ?>
