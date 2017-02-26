<?php /* Smarty version Smarty-3.1.19, created on 2017-02-26 16:11:43
         compiled from "/Users/Alex/Desktop/sites-git/amadoudiop.com/modules/subscriptionsmanager//views/templates/admin/hook_product_actions.tpl" */ ?>
<?php /*%%SmartyHeaderCode:60347740458b2f02f86eb67-04489449%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '726364baf0709c096f348dcc4caf844d69d5954d' => 
    array (
      0 => '/Users/Alex/Desktop/sites-git/amadoudiop.com/modules/subscriptionsmanager//views/templates/admin/hook_product_actions.tpl',
      1 => 1488116029,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '60347740458b2f02f86eb67-04489449',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'lockedSchemas' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58b2f02f92a625_42602752',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58b2f02f92a625_42602752')) {function content_58b2f02f92a625_42602752($_smarty_tpl) {?><script type='text/javascript'>
	    
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
