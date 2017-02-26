<?php /* Smarty version Smarty-3.1.19, created on 2017-02-23 12:51:43
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/modules/subscriptionsmanager//views/templates/admin/hook_product_actions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:15557743258aecccf867d90-37838007%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a14bccfcf99550d59809bf3b166ff7b6d1499c9d' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/modules/subscriptionsmanager//views/templates/admin/hook_product_actions.tpl',
      1 => 1487598754,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '15557743258aecccf867d90-37838007',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lockedSchemas' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aecccf8a6423_34906800',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aecccf8a6423_34906800')) {function content_58aecccf8a6423_34906800($_smarty_tpl) {?><script type='text/javascript'>
	    
<?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['lockedSchemas']->value;?>
<?php $_tmp1=ob_get_clean();?><?php if (!empty($_tmp1)) {?>
    function check_locking(){

	var lockedSchemas = <?php echo $_smarty_tpl->tpl_vars['lockedSchemas']->value;?>
;

	if(jQuery.inArray($('#idCombination').val(), lockedSchemas) !== -1){
	    $('#add_to_cart').after( '<p id="locked" style="background-color: #9B0000; color: #FFFFFF; display: inline-block; font-size: 10px; font-weight: bold; text-align: center; padding: 0 10px; text-shadow: none; text-transform: uppercase;"><?php echo smartyTranslate(array('s'=>'This product is locked. You cant add it to your cart','mod'=>'subscriptionsmanager'),$_smarty_tpl);?>
</p>' );
	    $('#add_to_cart').css('display', 'none'); 
	    $('#add_to_cart').attr('id', 'add_to_cart_hidden'); 
	}
	else{
	    $('#add_to_cart_hidden').css('display', 'block'); 
	    $('#add_to_cart_hidden').attr('id', 'add_to_cart'); 
	    $('#locked').remove();			
	}
    }
<?php }?>

	$(document).ready(function() {

	    <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['lockedSchemas']->value;?>
<?php $_tmp2=ob_get_clean();?><?php if (!empty($_tmp2)) {?>check_locking();<?php }?>

	    $('#add_to_cart input').click(function() {
		setTimeout(function(){
			ajaxCart.refresh();
		}, 1000);
		setTimeout(function(){
			ajaxCart.refresh();
		}, 3000);
		setTimeout(function(){
			ajaxCart.refresh();
		}, 5000);
		setTimeout(function(){
			ajaxCart.refresh();
		}, 10000);
	    });

	    <?php ob_start();?><?php echo $_smarty_tpl->tpl_vars['lockedSchemas']->value;?>
<?php $_tmp3=ob_get_clean();?><?php if (!empty($_tmp3)) {?>$('.attribute_list select').change(function(){			
		check_locking();			
	    });
	    $('.attribute_list input').change(function(){			
		check_locking();			
	    });<?php }?>

	});
    </script><?php }} ?>
