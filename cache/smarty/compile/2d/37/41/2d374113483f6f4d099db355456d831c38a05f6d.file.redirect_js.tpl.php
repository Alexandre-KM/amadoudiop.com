<?php /* Smarty version Smarty-3.1.19, created on 2017-02-23 19:30:55
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/modules/payzen/views/templates/front/redirect_js.tpl" */ ?>
<?php /*%%SmartyHeaderCode:168442501858af2a5fc7c6f1-11171833%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2d374113483f6f4d099db355456d831c38a05f6d' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/modules/payzen/views/templates/front/redirect_js.tpl',
      1 => 1483697714,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '168442501858af2a5fc7c6f1-11171833',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58af2a5fc7e6d3_50353084',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58af2a5fc7e6d3_50353084')) {function content_58af2a5fc7e6d3_50353084($_smarty_tpl) {?>

<script type="text/javascript">
  
    $(document).ready(function() {
      $('#payzen_form').submit(function() {
        // disable submit button
        $('button[type=submit], input[type=submit]').prop('disabled', true);
        return true;
      });

      var redirectUrl = window.location.href;
      var sep = (redirectUrl.indexOf('?') >= 0) ? '&' : '?';
      $.ajax({
        type: 'POST',
        headers: {'Cache-Control': 'no-cache'},
        url: redirectUrl + sep + 'rand=' + new Date().getTime(),
        async: true,
        cache: false,
        dataType: 'json',
        data: {
          checkCart: true,
          ajax: true
        },
        success: function(jsonData) {
          if (!jsonData.success) {
            window.location.href = jsonData.redirect;
          } else {
            $('#payzen_content').show();
            $('#payzen_form').submit();
          }
        }
      });
    });
  
</script>
<?php }} ?>
