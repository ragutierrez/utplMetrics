{**
 * templates/submission/layout/proofGalleyHTML.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Frame displaying an HTML galley.
 *
 *}
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$currentLocale|replace:"_":"-"}" xml:lang="{$currentLocale|replace:"_":"-"}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$defaultCharset|escape}" />
	<title>{translate key=$pageTitle}</title>
<!-- 
	<link rel="stylesheet" href="{$baseUrl}/lib/pkp/styles/common.css" type="text/css" />
	<link rel="stylesheet" href="{$baseUrl}/styles/common.css" type="text/css" />
	 -->
	<link rel="stylesheet" href="{$baseUrl}/styles/compiled.css" type="text/css" />
	{foreach from=$stylesheets item=cssUrl}
		<link rel="stylesheet" href="{$cssUrl}" type="text/css" />
	{/foreach}

	<script type="text/javascript" src="{$baseImportPath}/js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="{$baseImportPath}/bootstrap3/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="{$baseImportPath}/bootstrap3/css/bootstrap.min.css" type="text/css" />

	<!-- Compiled scripts -->
	{if $useMinifiedJavaScript}
		<script type="text/javascript" src="{$baseUrl}/js/pkp.min.js"></script>
	{else}
		{include file="common/minifiedScripts.tpl"}
	{/if}
	
	{$additionalHeadData}

	<script type="text/javascript">
		$(document).ready(function() {ldelim}
			$('.block').each(function(b){ldelim}
				$(this)[0].className="panel panel-default";
			{rdelim});
			$('#sidebar .panel.panel-default').each(function(b){ldelim}
				$(this)[0].style.padding="10px";
			{rdelim});
			$('#sizer').each(function(b){ldelim}
				$(this)[0].style.height="25px";
			{rdelim});
		{rdelim});
	</script>

</head>
<body>
	{$galley->getHTMLContents()}
</body>
</html>