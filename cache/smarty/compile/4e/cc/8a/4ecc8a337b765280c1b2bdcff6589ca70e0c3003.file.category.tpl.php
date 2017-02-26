<?php /* Smarty version Smarty-3.1.19, created on 2017-02-26 16:11:38
         compiled from "/Users/Alex/Desktop/sites-git/amadoudiop.com/themes/default-amadoudiop/category.tpl" */ ?>
<?php /*%%SmartyHeaderCode:199494732358b2f02a4eaa36-15007385%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ecc8a337b765280c1b2bdcff6589ca70e0c3003' => 
    array (
      0 => '/Users/Alex/Desktop/sites-git/amadoudiop.com/themes/default-amadoudiop/category.tpl',
      1 => 1488116900,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '199494732358b2f02a4eaa36-15007385',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'category' => 0,
    'subcategories' => 0,
    'products' => 0,
    'categoryNameComplement' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b2f02a6344a7_57254754',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b2f02a6344a7_57254754')) {function content_58b2f02a6344a7_57254754($_smarty_tpl) {?>
<?php echo $_smarty_tpl->getSubTemplate (((string)$_smarty_tpl->tpl_vars['tpl_dir']->value)."./errors.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php if (isset($_smarty_tpl->tpl_vars['category']->value)) {?>
	<?php if ($_smarty_tpl->tpl_vars['category']->value->id&&$_smarty_tpl->tpl_vars['category']->value->active) {?>

		<h1 class="page-heading<?php if ((isset($_smarty_tpl->tpl_vars['subcategories']->value)&&!$_smarty_tpl->tpl_vars['products']->value)||(isset($_smarty_tpl->tpl_vars['subcategories']->value)&&$_smarty_tpl->tpl_vars['products']->value)||!isset($_smarty_tpl->tpl_vars['subcategories']->value)&&$_smarty_tpl->tpl_vars['products']->value) {?> product-listing<?php }?>"><span class="cat-name"><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['category']->value->name, ENT_QUOTES, 'UTF-8', true);?>
<?php if (isset($_smarty_tpl->tpl_vars['categoryNameComplement']->value)) {?>&nbsp;<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['categoryNameComplement']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?></span></h1>

		<?php if ($_smarty_tpl->tpl_vars['products']->value) {?>

			<?php echo $_smarty_tpl->getSubTemplate ("./product-list.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('products'=>$_smarty_tpl->tpl_vars['products']->value), 0);?>

			<div class="content_sortPagiBar">
				<div class="bottom-pagination-content clearfix">

                    <?php echo $_smarty_tpl->getSubTemplate ("./pagination.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('paginationId'=>'bottom'), 0);?>

				</div>
			</div>
		<?php }?>
	<?php } elseif ($_smarty_tpl->tpl_vars['category']->value->id) {?>
		<p class="alert alert-warning"><?php echo smartyTranslate(array('s'=>'This category is currently unavailable.'),$_smarty_tpl);?>
</p>
	<?php }?>
<?php }?>
<?php }} ?>
