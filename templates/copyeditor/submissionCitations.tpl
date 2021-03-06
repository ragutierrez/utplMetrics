{**
 * templates/copyeditor/submissionCitations.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Copyeditor's citation editing view.
 *}
{strip}
{translate|assign:"pageTitleTranslated" key="submission.page.citations" id=$submission->getId()}
{assign var="pageCrumbTitle" value="submission.citations"}
{include file="`$importPath`templates/common/header.tpl"}
{/strip}

{include file="citation/citationEditor.tpl}

{include file="common/footer.tpl"}

