	{**
 *
 * utplMetrics plugin settings
 *
 *}
{strip}
{assign var="pageTitle" value="plugins.generic.utplMetrics.displayName"}
{include file="common/header.tpl"}
{/strip}
<!-- UTPL Metrics Form -->
<div id="utplMetrics">
<div id="description">{translate key="plugins.generic.utplMetrics.description"}</div>

<div class="separator">&nbsp;</div>
<br/>

<form method="post" action="{plugin_url path="settings"}">
{include file="common/formErrors.tpl"}

<table width="100%" class="data">
	<tr valign="top">
		<td width="100%" colspan=2>
			<h3>{translate key="plugins.generic.utplMetrics.settings.altmetricAPIKey"}</h3>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%" colspan=2>
			{translate key="plugins.generic.utplMetrics.settings.altmetricAPIKey.description"}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr valign="top">
		<td width="40%" class="label">{fieldLabel required="true" key="plugins.generic.utplMetrics.settings.altmetricAPIKey.label"}</td>
		<td width="60%" class="value"><input type="text" name="altmetricKey" value="{$altmetricKey|escape}" id="altmetricAPIKey" size="40" maxlength="40" class="textField" /></td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr valign="top">
		<td width="100%" colspan=2>
			<h3>{translate key="plugins.generic.utplMetrics.settings.elsevierAPIKey"}</h3>
		</td>
	</tr>
	<tr valign="top">
		<td width="100%" colspan=2>
			{translate key="plugins.generic.utplMetrics.settings.elsevierAPIKey.description"}
		</td>
	</tr>
	<tr>
		<td colspan="2">
			&nbsp;
		</td>
	</tr>
	<tr valign="top">
		<td width="40%" class="label">{fieldLabel required="true" key="plugins.generic.utplMetrics.settings.elsevierAPIKey.label"}</td>
		<td width="60%" class="value"><input type="text" name="scopusKey" value="{$scopusKey|escape}" id="elsevierAPIKey" size="40" maxlength="120" class="textField" /></td>
	</tr>
</table>

<br/>
<br/>
<input type="submit" name="save" class="button defaultButton" value="{translate key="common.save"}"/>
<input type="button" class="button" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>

<!-- <p><span class="formRequired">{translate key="common.requiredField"}</span></p> -->
</div>
{include file="common/footer.tpl"}
