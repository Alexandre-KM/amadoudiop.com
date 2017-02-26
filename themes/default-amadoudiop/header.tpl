{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<!DOCTYPE HTML>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8 ie7"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9 ie8"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<!--[if gt IE 8]> <html class="no-js ie9"{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}><![endif]-->
<html{if isset($language_code) && $language_code} lang="{$language_code|escape:'html':'UTF-8'}"{/if}>
	<head>
		<meta charset="utf-8" />
		<title>{$meta_title|escape:'html':'UTF-8'}</title>
		{if isset($meta_description) AND $meta_description}
			<meta name="description" content="{$meta_description|escape:'html':'UTF-8'}" />
		{/if}
		{if isset($meta_keywords) AND $meta_keywords}
			<meta name="keywords" content="{$meta_keywords|escape:'html':'UTF-8'}" />
		{/if}
		<meta name="generator" content="PrestaShop" />
		<meta name="robots" content="{if isset($nobots)}no{/if}index,{if isset($nofollow) && $nofollow}no{/if}follow" />
		<meta name="viewport" content="width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="icon" type="image/vnd.microsoft.icon" href="{$favicon_url}?{$img_update_time}" />
		<link rel="shortcut icon" type="image/x-icon" href="{$favicon_url}?{$img_update_time}" />
		{if $page_name !='index'}
			{if isset($css_files)}
				{foreach from=$css_files key=css_uri item=media}
					{if $css_uri == 'lteIE9'}
						<!--[if lte IE 9]>
						{foreach from=$css_files[$css_uri] key=css_uriie9 item=mediaie9}
						<link rel="stylesheet" href="{$css_uriie9|escape:'html':'UTF-8'}" type="text/css" media="{$mediaie9|escape:'html':'UTF-8'}" />
						{/foreach}
						<![endif]-->
					{else}
						<link rel="stylesheet" href="{$css_uri|escape:'html':'UTF-8'}" type="text/css" media="{$media|escape:'html':'UTF-8'}" />
					{/if}
				{/foreach}
			{/if}
		{/if}
		<link rel="stylesheet" type="text/css" href="themes/default-amadoudiop/css/global.css" />
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

		{if isset($js_defer) && !$js_defer && isset($js_files) && isset($js_def)}
			{$js_def}
			{if $page_name !='index'}
				{foreach from=$js_files item=js_uri}

				<script type="text/javascript" src="{$js_uri|escape:'html':'UTF-8'}"></script>
				{/foreach}
			{else}
				<script type="text/javascript" src="js/jquery/jquery-1.11.0.min.js"></script>
				<script type="text/javascript" src="js/jquery/jquery-migrate-1.2.1.min.js"></script>
			{/if}

			<script src="themes/default-amadoudiop/js/autoload/10-bootstrap.min.js"></script>
			<script src="js/modernizr.custom.js"></script>
			<script src="js/modernizr-2.6.2.min.js"></script>
			{if $page_name == 'order'}
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
			{/if}
			{literal}
			<script>
				(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
				(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
				m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
				})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

				ga('create', 'UA-91777585-1', 'auto');
				ga('send', 'pageview');
			</script>
			{/literal}
		{/if}
		{$HOOK_HEADER}
		<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,600&amp;subset=latin,latin-ext" type="text/css" media="all" />
		<!--[if IE 8]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
	</head>
	<body{if isset($page_name)} id="{$page_name|escape:'html':'UTF-8'}"{/if} class="creemson-background {if isset($page_name)}{$page_name|escape:'html':'UTF-8'}{/if}{if isset($body_classes) && $body_classes|@count} {implode value=$body_classes separator=' '}{/if}{if $hide_left_column} hide-left-column{else} show-left-column{/if}{if $hide_right_column} hide-right-column{else} show-right-column{/if}{if isset($content_only) && $content_only} content_only{/if} lang_{$lang_iso}">

			<div class="container">
			<!-- Top Navigation -->
			<div class="codrops-top clearfix">
				<span class="right"><a class="" href="images/cv.pdf"><span>Get my CV in pdf</span></a></span>
				{if !$logged}
					<span class="right"><a class="" href="connexion"><span>Login</span></a></span>
					<span class="right"><a class="" href="#" data-toggle="modal" data-target="#myModalRegister"><span>Devenir client</span></a></span>
				{else}
					<span class="right"><a class="" href="{$base_dir}index.php?mylogout"><span>Logout</span></a></span>
					<span class="right"><a class="" href="mon-compte"><span>Mon Compte</span></a></span>
				{/if}
				<span class="right"><a class="" href="		commande"><span>Cart</span></a></span>

				<span class="right"><a class="" href="services-12.htm"><span>Services</span></a></span>


			</div>
			{if $page_name =='index'}
			<header>
				<h1>Amadou Diop <span>{$page_name}</span></h1>
				{if $page_name =='index'}
				<nav class="codrops-demos">
					<a class="current-demo" href="#portfolio">My portfolio</a>
					<a href="#contact">Contact me</a>
				</nav>
				{/if}
			</header>
			{/if}


			<div class="container">
			{if $page_name !='index'}<div class="col-md-12 text-color-black">{/if}
