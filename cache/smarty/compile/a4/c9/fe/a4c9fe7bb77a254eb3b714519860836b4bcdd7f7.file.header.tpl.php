<?php /* Smarty version Smarty-3.1.19, created on 2017-02-24 15:05:46
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/pdf/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:43120245658b03dbac8a941-83166049%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a4c9fe7bb77a254eb3b714519860836b4bcdd7f7' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/pdf/header.tpl',
      1 => 1482157024,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '43120245658b03dbac8a941-83166049',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'logo_path' => 0,
    'width_logo' => 0,
    'height_logo' => 0,
    'header' => 0,
    'date' => 0,
    'title' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b03dbacd0503_78177940',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b03dbacd0503_78177940')) {function content_58b03dbacd0503_78177940($_smarty_tpl) {?>


<table style="width: 100%">
<tr>
	<td style="width: 50%">
		<?php if ($_smarty_tpl->tpl_vars['logo_path']->value) {?>
			<img src="<?php echo $_smarty_tpl->tpl_vars['logo_path']->value;?>
" style="width:<?php echo $_smarty_tpl->tpl_vars['width_logo']->value;?>
px; height:<?php echo $_smarty_tpl->tpl_vars['height_logo']->value;?>
px;" />
		<?php }?>
	</td>
	<td style="width: 50%; text-align: right;">
		<table style="width: 100%">
			<tr>
				<td style="font-weight: bold; font-size: 14pt; color: #444; width: 100%;"><?php if (isset($_smarty_tpl->tpl_vars['header']->value)) {?><?php echo mb_strtoupper(htmlspecialchars($_smarty_tpl->tpl_vars['header']->value, ENT_QUOTES, 'UTF-8', true), 'UTF-8');?>
<?php }?></td>
			</tr>
			<tr>
				<td style="font-size: 14pt; color: #9E9F9E"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['date']->value, ENT_QUOTES, 'UTF-8', true);?>
</td>
			</tr>
			<tr>
				<td style="font-size: 14pt; color: #9E9F9E"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['title']->value, ENT_QUOTES, 'UTF-8', true);?>
</td>
			</tr>
		</table>
	</td>
</tr>
</table>

<?php }} ?>
