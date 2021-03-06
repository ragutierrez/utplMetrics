{**
 * templates/author/submit/complete.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * The submission process has been completed; notify the author.
 *
 *}
{strip}
{assign var="pageTitle" value="author.track"}
{include file="`$importPath`templates/common/header.tpl"}
{/strip}

<div id="submissionComplete">
<p>{translate key="author.submit.submissionComplete" journalTitle=$journal->getLocalizedTitle()}</p>

{if $canExpedite}
	{url|assign:"expediteUrl" op="expediteSubmission" articleId=$articleId}
	{translate key="author.submit.expedite" expediteUrl=$expediteUrl}
{/if}
{if $paymentButtonsTemplate }
	{include file=$paymentButtonsTemplate orientation="vertical"}
{/if}

<p><a href="{url op="index"}">{translate key="author.track"}</a></p>
</div>

{include file="common/footer.tpl"}

