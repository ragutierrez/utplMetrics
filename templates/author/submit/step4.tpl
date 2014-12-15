{**
 * templates/author/submit/step4.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Step 4 of author article submission.
 *
 *}
{assign var="pageTitle" value="author.submit.step4"}
{include file="`$importPath`templates/author/submit/submitHeader.tpl"}

<script type="text/javascript">
{literal}
<!--
function confirmForgottenUpload() {
	var fieldValue = document.getElementById('submitForm').uploadSuppFile.value;
	if (fieldValue) {
		return confirm("{/literal}{translate key="author.submit.forgottenSubmitSuppFile"}{literal}");
	}
	return true;
}
// -->
{/literal}
</script>

<form id="submitForm" method="post" action="{url op="saveSubmit" path=$submitStep}" enctype="multipart/form-data">
<input type="hidden" name="articleId" value="{$articleId|escape}" />
{include file="common/formErrors.tpl"}

<p>{translate key="author.submit.supplementaryFilesInstructions"}</p>
<br>
<table class="table table-striped" width="100%">
	<tr valign="bottom">
		<th width="5%">{translate key="common.id"}</th>
		<th width="40%">{translate key="common.title"}</th>
		<th width="25%">{translate key="common.originalFileName"}</th>
		<th width="15%" class="nowrap">{translate key="common.dateUploaded"}</th>
		<th width="15%" align="right">{translate key="common.action"}</th>
	</tr>
	{foreach from=$suppFiles item=file}
	<tr valign="top">
		<td>{$file->getSuppFileId()}</td>
		<td>{$file->getSuppFileTitle()|escape}</td>
		<td>{$file->getOriginalFileName()|escape}</td>
		<td>{$file->getDateSubmitted()|date_format:$dateFormatTrunc}</td>
		<td align="right"><a href="{url op="submitSuppFile" path=$file->getSuppFileId() articleId=$articleId}" class="action">{translate key="common.edit"}</a>&nbsp;|&nbsp;<a href="{url op="deleteSubmitSuppFile" path=$file->getSuppFileId() articleId=$articleId}" onclick="return confirm('{translate|escape:"jsparam" key="author.submit.confirmDeleteSuppFile"}')" class="action">{translate key="common.delete"}</a></td>
	</tr>
	{foreachelse}
	<tr valign="top">
		<td colspan="6" class="nodata">{translate key="author.submit.noSupplementaryFiles"}</td>
	</tr>
	{/foreach}
</table>

<br>

<table class="data" width="100%">
<tr>
	<td width="30%">{fieldLabel name="uploadSuppFile" key="author.submit.uploadSuppFile"}</td>
	<td width="70%" class="value">
		<input type="file" name="uploadSuppFile" id="uploadSuppFile"  class="uploadField" />
		<input name="submitUploadSuppFile" type="submit" class="button" value="{translate key="common.upload"}" />
		{if $currentJournal->getSetting('showEnsuringLink')}<a class="action" href="javascript:openHelp('{get_help_id key="editorial.sectionEditorsRole.review.blindPeerReview" url="true"}')">{translate key="reviewer.article.ensuringBlindReview"}</a>{/if}
	</td>
</tr>
</table>

<br>

<p>
	<input type="submit" onclick="return confirmForgottenUpload()" value="{translate key="common.saveAndContinue"}" class="btn btn-primary" />
	<input type="button" value="{translate key="common.cancel"}" class="btn btn-default" onclick="confirmAction('{url page="author"}', '{translate|escape:"jsparam" key="author.submit.cancelSubmission"}')" /></p>

</form>

{include file="common/footer.tpl"}

