{**
 * breadcrumbs.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2000-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Breadcrumbs
 *
 *}
<div id="breadcrumb">
	<ol class="breadcrumb">
	<li>
		<a href="{url context=$homeContext page="index"}">{translate key="navigation.home"}</a>
	</li>
	{foreach from=$pageHierarchy item=hierarchyLink}
		<li>
			<a href="{$hierarchyLink[0]|escape}" class="hierarchyLink">{if not $hierarchyLink[2]}{translate key=$hierarchyLink[1]}{else}{$hierarchyLink[1]|escape}{/if}</a>
		</li>
	{/foreach}
	<li class="active">
	{$pageCrumbTitleTranslated}
	</li>
	</ol>
</div>

