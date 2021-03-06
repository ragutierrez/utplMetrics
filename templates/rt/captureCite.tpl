{**
 * templates/rt/captureCite.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article reading tools -- Capture Citation
 *
 *}
{strip}
{assign var=pageTitle value="rt.captureCite"}
{include file="`$importPath`templates/rt/header.tpl"}
{/strip}

{if $galleyId}
	{url|assign:"articleUrl" page="article" op="view" path=$articleId|to_array:$galleyId}
{else}
	{url|assign:"articleUrl" page="article" op="view" path=$articleId}
{/if}
<div id="captureCite">
<script type="text/javascript">
	$(document).ready(function() {ldelim}
		$('#citation').click(function(){ldelim}
			console.log("click"+$(this)[0].innerHTML);
			window.prompt("Copy to clipboard: Ctrl+C, Enter", $.trim($(this)[0].innerHTML));
		{rdelim});
	{rdelim});
</script>
<h3>{$article->getLocalizedTitle()|strip_unsafe_html}</h3>

<form action="#">
<label for="citeType">{translate key="rt.captureCite.format"}</label>&nbsp;&nbsp;<select onchange="document.location='{url|escape:"javascript" path=$articleId|to_array:$galleyId:"REPLACE"}'.replace('REPLACE', this.options[this.selectedIndex].value)">
	{foreach from=$citationPlugins item=thisCitationPlugin}
		<option {if $citationPlugin && $citationPlugin->getName() == $thisCitationPlugin->getName()}selected="selected" {/if}value="{$thisCitationPlugin->getName()|escape}">{$thisCitationPlugin->getCitationFormatName()|escape}</option>
	{/foreach}
</select>
	
</form>
<div class="panel panel-primary" style="margin-top:20px;padding:10px">
{call_hook name="Template::RT::CaptureCite"}
</div>
<h4>{translate key="plugins.generic.utplMetrics.captureCite.help"}</h4>
</div>

{include file="rt/footer.tpl"}

