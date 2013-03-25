<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>Swift PHP Framework</title>
		<style type="text/css">
			body {
				color: #000305;
				font-size: 87.5%;
				font-family: Arial, sans-serif;
				line-height: 1.429;
				margin: 0;
				padding: 0;
				text-align: left;
				width:800px;
				background-color: #CCC;
				clear: both;
				margin: 0 auto;
			}
			#body-wrapper {
				height: 100%;
				width: 100%;
				background-color: #CCC;
			}
			#banner {
				margin: 0 auto;
				padding: 2em 0 0 0;
			}
			#banner h1 {
				font-size: 2.0em;
				line-height: .6;
				font-weight: bold;
				margin: 0 0 .6em 0;
				text-align: center;
			}
			#content {
				text-align:center;
				background: white;
				margin-bottom: 1em;
				overflow: hidden;
				padding: 20px 20px;
				width: 760px;
				border-radius: 10px;
				-moz-border-radius: 10px;
				-webkit-border-radius: 10px;
				border: 1px solid #999;
				border-image: initial;
			}
			#contentinfo {
				padding-bottom: 2em;
				text-align: right;
			}
			#contentinfo p {
				text-align: center;
			}
		</style>
	</head>
	<body id="index" class="home">
	<div id="body-wrapper">
		<header id="banner" class="body">
		    <?php
				global $swift;
				$logo_url = $swift->config('app_view_url') . '/images/logo.png';
			?>
			<h1><img src="<?php echo $logo_url; ?>" title="Swift PHP Framework" alt="Swift PHP Framework" /></h1>
		</header><!-- /#banner -->
		<section id="content" class="body">