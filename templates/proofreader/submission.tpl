{**
 * templates/proofreader/submission.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Show the details of a submission.
 *
 *
 *}
{strip}
{translate|assign:"pageTitleTranslated" key="submission.page.editing" id=$submission->getId()}
{assign var="pageCrumbTitle" value="submission.editing"}
{include file="`$importPath`templates/common/header.tpl"}
{/strip}

{include file="proofreader/submission/summary.tpl"}

<br>

{include file="proofreader/submission/layout.tpl"}

<br>

{include file="proofreader/submission/proofread.tpl"}

<br>

{include file="proofreader/submission/scheduling.tpl"}

{include file="common/footer.tpl"}

