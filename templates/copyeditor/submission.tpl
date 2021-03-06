{**
 * templates/copyeditor/submission.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Copyeditor's submission view.
 *
 *}
{strip}
{translate|assign:"pageTitleTranslated" key="submission.page.editing" id=$submission->getId()}
{assign var="pageCrumbTitle" value="submission.editing"}
{include file="`$importPath`templates/common/header.tpl"}
{/strip}

{include file="copyeditor/submission/summary.tpl"}

<br>

{include file="copyeditor/submission/copyedit.tpl"}

<br>

{include file="copyeditor/submission/layout.tpl"}

{include file="common/footer.tpl"}

