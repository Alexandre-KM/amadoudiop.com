<?php /* Smarty version Smarty-3.1.19, created on 2017-02-25 19:33:06
         compiled from "/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:106932824758aec487964e66-41723161%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '61302137b6e0900db1fe3b50733617a8799cc03d' => 
    array (
      0 => '/home/sites/amadoudiop.com/prestashop-prod/themes/default-amadoudiop/header.tpl',
      1 => 1488047580,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '106932824758aec487964e66-41723161',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_58aec4879e78f5_27238322',
  'variables' => 
  array (
    'language_code' => 0,
    'meta_title' => 0,
    'meta_description' => 0,
    'meta_keywords' => 0,
    'nobots' => 0,
    'nofollow' => 0,
    'favicon_url' => 0,
    'img_update_time' => 0,
    'page_name' => 0,
    'css_files' => 0,
    'css_uri' => 0,
    'css_uriie9' => 0,
    'mediaie9' => 0,
    'media' => 0,
    'js_defer' => 0,
    'js_files' => 0,
    'js_def' => 0,
    'js_uri' => 0,
    'HOOK_HEADER' => 0,
    'body_classes' => 0,
    'hide_left_column' => 0,
    'hide_right_column' => 0,
    'content_only' => 0,
    'lang_iso' => 0,
    'logged' => 0,
    'base_dir' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58aec4879e78f5_27238322')) {function content_58aec4879e78f5_27238322($_smarty_tpl) {?><?php if (!is_callable('smarty_function_implode')) include '/home/sites/amadoudiop.com/prestashop-prod/tools/smarty/plugins/function.implode.php';
?>
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"<?php if (isset($_smarty_tpl->tpl_vars['language_code']->value)&&$_smarty_tpl->tpl_vars['language_code']->value) {?> lang="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language_code']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7"<?php if (isset($_smarty_tpl->tpl_vars['language_code']->value)&&$_smarty_tpl->tpl_vars['language_code']->value) {?> lang="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language_code']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8"<?php if (isset($_smarty_tpl->tpl_vars['language_code']->value)&&$_smarty_tpl->tpl_vars['language_code']->value) {?> lang="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language_code']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9"<?php if (isset($_smarty_tpl->tpl_vars['language_code']->value)&&$_smarty_tpl->tpl_vars['language_code']->value) {?> lang="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language_code']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>><![endif]-->
<html<?php if (isset($_smarty_tpl->tpl_vars['language_code']->value)&&$_smarty_tpl->tpl_vars['language_code']->value) {?> lang="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['language_code']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php }?>>
	<head>
		<meta charset="utf-8" />
		<title><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['meta_title']->value, ENT_QUOTES, 'UTF-8', true);?>
</title>
		<?php if (isset($_smarty_tpl->tpl_vars['meta_description']->value)&&$_smarty_tpl->tpl_vars['meta_description']->value) {?>
			<meta name="description" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['meta_description']->value, ENT_QUOTES, 'UTF-8', true);?>
" />
		<?php }?>
		<?php if (isset($_smarty_tpl->tpl_vars['meta_keywords']->value)&&$_smarty_tpl->tpl_vars['meta_keywords']->value) {?>
			<meta name="keywords" content="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['meta_keywords']->value, ENT_QUOTES, 'UTF-8', true);?>
" />
		<?php }?>
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="<?php if (isset($_smarty_tpl->tpl_vars['nobots']->value)) {?>no<?php }?>index,<?php if (isset($_smarty_tpl->tpl_vars['nofollow']->value)&&$_smarty_tpl->tpl_vars['nofollow']->value) {?>no<?php }?>follow" />
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon_url']->value;?>
?<?php echo $_smarty_tpl->tpl_vars['img_update_time']->value;?>
" />
		<link rel="shortcut icon" type="image/x-icon" href="<?php echo $_smarty_tpl->tpl_vars['favicon_url']->value;?>
?<?php echo $_smarty_tpl->tpl_vars['img_update_time']->value;?>
" />
		<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index') {?>
			<?php if (isset($_smarty_tpl->tpl_vars['css_files']->value)) {?>
				<?php  $_smarty_tpl->tpl_vars['media'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['media']->_loop = false;
 $_smarty_tpl->tpl_vars['css_uri'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['css_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['media']->key => $_smarty_tpl->tpl_vars['media']->value) {
$_smarty_tpl->tpl_vars['media']->_loop = true;
 $_smarty_tpl->tpl_vars['css_uri']->value = $_smarty_tpl->tpl_vars['media']->key;
?>
					<?php if ($_smarty_tpl->tpl_vars['css_uri']->value=='lteIE9') {?>
						<!--[if lte IE 9]>
						<?php  $_smarty_tpl->tpl_vars['mediaie9'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['mediaie9']->_loop = false;
 $_smarty_tpl->tpl_vars['css_uriie9'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['css_files']->value[$_smarty_tpl->tpl_vars['css_uri']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['mediaie9']->key => $_smarty_tpl->tpl_vars['mediaie9']->value) {
$_smarty_tpl->tpl_vars['mediaie9']->_loop = true;
 $_smarty_tpl->tpl_vars['css_uriie9']->value = $_smarty_tpl->tpl_vars['mediaie9']->key;
?>
						<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['css_uriie9']->value, ENT_QUOTES, 'UTF-8', true);?>
" type="text/css" media="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['mediaie9']->value, ENT_QUOTES, 'UTF-8', true);?>
" />
						<?php } ?>
						<![endif]-->
					<?php } else { ?>
						<link rel="stylesheet" href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['css_uri']->value, ENT_QUOTES, 'UTF-8', true);?>
" type="text/css" media="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['media']->value, ENT_QUOTES, 'UTF-8', true);?>
" />
					<?php }?>
				<?php } ?>
			<?php }?>
		<?php }?>
		<link rel="stylesheet" type="text/css" href="/themes/default-amadoudiop/css/global.css" />
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/portfolio.css" />
		<link rel="stylesheet" type="text/css" href="css/circular-menu.css" />
		<link rel="stylesheet" type="text/css" href="css/dope-menu.css" />
		<link rel="stylesheet" type="text/css" href="css/creemson.css" />
		<link rel="stylesheet" type="text/css" href="css/creemson-background.css" />
		<link rel="stylesheet" type="text/css" href="css/desktop-border.css" />
		<!-- slick -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.css"/>
		<!-- Add the slick-theme.css if you want default styling -->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.css"/>

		<?php if (isset($_smarty_tpl->tpl_vars['js_defer']->value)&&!$_smarty_tpl->tpl_vars['js_defer']->value&&isset($_smarty_tpl->tpl_vars['js_files']->value)&&isset($_smarty_tpl->tpl_vars['js_def']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['js_def']->value;?>

			<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index') {?>
				<?php  $_smarty_tpl->tpl_vars['js_uri'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['js_uri']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['js_files']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['js_uri']->key => $_smarty_tpl->tpl_vars['js_uri']->value) {
$_smarty_tpl->tpl_vars['js_uri']->_loop = true;
?>

				<script type="text/javascript" src="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['js_uri']->value, ENT_QUOTES, 'UTF-8', true);?>
"></script>
				<?php } ?>
			<?php } else { ?>
				<script type="text/javascript" src="/js/jquery/jquery-1.11.0.min.js"></script>
				<script type="text/javascript" src="/js/jquery/jquery-migrate-1.2.1.min.js"></script>
			<?php }?>

			<script src="/themes/default-amadoudiop/js/autoload/10-bootstrap.min.js"></script>
			<script src="js/modernizr.custom.js"></script>
			<script src="js/modernizr-2.6.2.min.js"></script>
			<?php if ($_smarty_tpl->tpl_vars['page_name']->value=='order') {?>
			<script>
/*  @preserve
jQuery pub/sub plugin by Peter Higgins (dante@dojotoolkit.org)
Loosely based on Dojo publish/subscribe API, limited in scope. Rewritten blindly.
Original is (c) Dojo Foundation 2004-2010. Released under either AFL or new BSD, see:
http://dojofoundation.org/license for more information.
*/
(function($) {
	var topics = {};
	$.publish = function(topic, args) {
	    if (topics[topic]) {
	        var currentTopic = topics[topic],
	        args = args || {};

	        for (var i = 0, j = currentTopic.length; i < j; i++) {
	            currentTopic[i].call($, args);
	        }
	    }
	};
	$.subscribe = function(topic, callback) {
	    if (!topics[topic]) {
	        topics[topic] = [];
	    }
	    topics[topic].push(callback);
	    return {
	        "topic": topic,
	        "callback": callback
	    };
	};
	$.unsubscribe = function(handle) {
	    var topic = handle.topic;
	    if (topics[topic]) {
	        var currentTopic = topics[topic];

	        for (var i = 0, j = currentTopic.length; i < j; i++) {
	            if (currentTopic[i] === handle.callback) {
	                currentTopic.splice(i, 1);
	            }
	        }
	    }
	};
})(jQuery);

</script>
			<script src="js/jSignature.js"></script>
			<script src="js/plugins/jSignature.CompressorBase30.js"></script>
			<script src="js/plugins/jSignature.CompressorSVG.js"></script>
			<script src="js/plugins/jSignature.UndoButton.js"></script>
			<script src="js/plugins/signhere/jSignature.SignHere.js"></script>
			<?php }?>
			
			<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

				ga('create', 'UA-91777585-1', 'auto');
				ga('send', 'pageview');
			</script>
			
		<?php }?>
		<?php echo $_smarty_tpl->tpl_vars['HOOK_HEADER']->value;?>

		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext" type="text/css" media="all" />
		<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
	<body<?php if (isset($_smarty_tpl->tpl_vars['page_name']->value)) {?> id="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page_name']->value, ENT_QUOTES, 'UTF-8', true);?>
"<?php }?> class="creemson-background <?php if (isset($_smarty_tpl->tpl_vars['page_name']->value)) {?><?php echo htmlspecialchars($_smarty_tpl->tpl_vars['page_name']->value, ENT_QUOTES, 'UTF-8', true);?>
<?php }?><?php if (isset($_smarty_tpl->tpl_vars['body_classes']->value)&&count($_smarty_tpl->tpl_vars['body_classes']->value)) {?> <?php echo smarty_function_implode(array('value'=>$_smarty_tpl->tpl_vars['body_classes']->value,'separator'=>' '),$_smarty_tpl);?>
<?php }?><?php if ($_smarty_tpl->tpl_vars['hide_left_column']->value) {?> hide-left-column<?php } else { ?> show-left-column<?php }?><?php if ($_smarty_tpl->tpl_vars['hide_right_column']->value) {?> hide-right-column<?php } else { ?> show-right-column<?php }?><?php if (isset($_smarty_tpl->tpl_vars['content_only']->value)&&$_smarty_tpl->tpl_vars['content_only']->value) {?> content_only<?php }?> lang_<?php echo $_smarty_tpl->tpl_vars['lang_iso']->value;?>
">

			<div class="container">
			<!-- Top Navigation -->
			<div class="codrops-top clearfix">
				<span class="right"><a class="" href="images/cv.pdf"><span>Get my CV in pdf</span></a></span>
				<?php if (!$_smarty_tpl->tpl_vars['logged']->value) {?>
					<span class="right"><a class="" href="/connexion"><span>Login</span></a></span>
					<span class="right"><a class="" href="#" data-toggle="modal" data-target="#myModalRegister"><span>Devenir client</span></a></span>
				<?php } else { ?>
					<span class="right"><a class="" href="<?php echo $_smarty_tpl->tpl_vars['base_dir']->value;?>
index.php?mylogout"><span>Logout</span></a></span>
					<span class="right"><a class="" href="/mon-compte"><span>Mon Compte</span></a></span>
				<?php }?>
				<span class="right"><a class="" href="		/commande"><span>Cart</span></a></span>

				<span class="right"><a class="" href="/services-12.htm"><span>Services</span></a></span>


			</div>
			<?php if ($_smarty_tpl->tpl_vars['page_name']->value=='index') {?>
			<header>
				<h1>Amadou Diop <span><?php echo $_smarty_tpl->tpl_vars['page_name']->value;?>
</span></h1>
				<?php if ($_smarty_tpl->tpl_vars['page_name']->value=='index') {?>
				<nav class="codrops-demos">
					<a class="current-demo" href="#portfolio">My portfolio</a>
					<a href="#contact">Contact me</a>
				</nav>
				<?php }?>
			</header>
			<?php }?>


			<div class="container">
			<?php if ($_smarty_tpl->tpl_vars['page_name']->value!='index') {?><div class="col-md-12 text-color-black"><?php }?>
<?php }} ?>
