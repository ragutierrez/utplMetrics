{**
 * templates/author/submit/step5.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Step 5 of author article submission.
 *
 *}
{assign var="pageTitle" value="author.submit.step5"}
{include file="`$importPath`templates/author/submit/submitHeader.tpl"}

<p>{translate key="author.submit.confirmationDescription" journalTitle=$journal->getLocalizedTitle()}</p>

<form method="post" action="{url op="saveSubmit" path=$submitStep}">
<input type="hidden" name="articleId" value="{$articleId|escape}" />
{include file="common/formErrors.tpl"}

<h3>{translate key="author.submit.filesSummary"}</h3>
<table class="table table-striped" width="100%">
	<tr class="heading" valign="bottom">
		<th width="10%">{translate key="common.id"}</th>
		<th width="35%">{translate key="common.originalFileName"}</th>
		<th width="25%">{translate key="common.type"}</th>
		<th width="20%" class="nowrap">{translate key="common.fileSize"}</th>
		<th width="10%" class="nowrap">{translate key="common.dateUploaded"}</th>
	</tr>
	{foreach from=$files item=file}
	<tr valign="top">
		<td>{$file->getFileId()}</td>
		<td><a class="file" href="{url op="download" path=$articleId|to_array:$file->getFileId()}">{$file->getOriginalFileName()|escape}</a></td>
		<td>{if ($file->getFileStage() == ARTICLE_FILE_SUPP)}{translate key="article.suppFile"}{else}{translate key="author.submit.submissionFile"}{/if}</td>
		<td>{$file->getNiceFileSize()}</td>
		<td>{$file->getDateUploaded()|date_format:$dateFormatTrunc}</td>
	</tr>
	{foreachelse}
	<tr valign="top">
		<td colspan="5" class="nodata">{translate key="author.submit.noFiles"}</td>
	</tr>
	{/foreach}
</table>

<br>

{if $authorFees}
	{include file="author/submit/authorFees.tpl" showPayLinks=1}
	{if $currentJournal->getLocalizedSetting('waiverPolicy') != ''}
		{if $manualPayment}
			<h3>{translate key="payment.alreadyPaid"}</h3>
			<table class="table" width="100%">
				<tr valign="top">
				<td width="5%" align="left"><input type="checkbox" name="paymentSent" value="1" {if $paymentSent}checked="checked"{/if} /></td>
				<td width="95%">{translate key="payment.paymentSent"}</td>
				</tr>
				<tr>
				<td />
				<td>{translate key="payment.alreadyPaidMessage"}</td>
				<tr>
			</table>
		{/if}
		<h3>{translate key="author.submit.requestWaiver"}</h3>
		<table class="data" width="100%">
			<tr valign="top">
				<td width="5%" align="left"><input type="checkbox" name="qualifyForWaiver" value="1" {if $qualifyForWaiver}checked="checked"{/if}/></td>
				<td width="95%">{translate key="author.submit.qualifyForWaiver"}</td>
			</tr>
			<tr>
				<td />
				<td>
					<label for="commentsToEditor">{translate key="author.submit.addReasonsForWaiver"}</label><br />
					<textarea name="commentsToEditor" id="commentsToEditor" rows="3" cols="40" class="textArea">{$commentsToEditor|escape}</textarea>
				</td>
			</tr>
		</table> 
	{/if}

	<br>
{/if}

{call_hook name="Templates::Author::Submit::Step5::AdditionalItems"}

<p>
	<input type="submit" value="{translate key="author.submit.finishSubmission"}" class="btn btn-primary"/>
	<input type="button" value="{translate key="common.cancel"}" class="btn btn-default" onclick="confirmAction('{url page="author"}', '{translate|escape:"jsparam" key="author.submit.cancelSubmission"}')"/>
</p>

</form>

{include file="common/footer.tpl"}

