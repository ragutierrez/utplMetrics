{**
 * templates/author/submissionEditing.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Author's submission editing.
 *
 *}
{strip}
{translate|assign:"pageTitleTranslated" key="submission.page.editing" id=$submission->getId()}
{assign var="pageCrumbTitle" value="submission.editing"}
{include file="`$importPath`templates/common/header.tpl"}
{/strip}

<ul class="menu">
	<li><a href="{url op="submission" path=$submission->getId()}">{translate key="submission.summary"}</a></li>
	<li><a href="{url op="submissionReview" path=$submission->getId()}">{translate key="submission.review"}</a></li>
	<li class="current"><a href="{url op="submissionEditing" path=$submission->getId()}">{translate key="submission.editing"}</a></li>
</ul>

{include file="author/submission/summary.tpl"}

<br>

{include file="author/submission/copyedit.tpl"}

<br>

{include file="author/submission/layout.tpl"}

<br>

{include file="author/submission/proofread.tpl"}

{include file="common/footer.tpl"}

