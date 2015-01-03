{**
 * templates/layoutEditor/submission.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Layout editor's view of submission details.
 *
 *}
{strip}
{translate|assign:"pageTitleTranslated" key="submission.page.editing" id=$submission->getId()}
{assign var="pageCrumbTitle" value="submission.editing"}
{include file="`$importPath`templates/common/header.tpl"}
{/strip}

{include file="layoutEditor/submission/summary.tpl"}

<br>

{include file="layoutEditor/submission/layout.tpl"}

<br>

{include file="layoutEditor/submission/proofread.tpl"}

<br>

{include file="layoutEditor/submission/scheduling.tpl"}

{include file="common/footer.tpl"}

