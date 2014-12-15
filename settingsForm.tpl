	{**
 *
 * utplMetrics plugin settings
 *
 *}
{strip}
{assign var="pageTitle" value="plugins.generic.utplMetrics.displayName"}
{include file="`$importPath`templates/common/header.tpl"}
{/strip}
<!-- UTPL Metrics Form -->
<div id="utplMetrics">
<div id="description">{translate key="plugins.generic.utplMetrics.description"}</div>

<div class="separator">&nbsp;</div>
<br/>

<form method="post" action="{plugin_url path="settings"}">
{include file="common/formErrors.tpl"}

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="text-center">
				{translate key="plugins.generic.utplMetrics.settings.altmetricAPIKey"}
			</h3>
		</div>
		<div class="panel-body">
			{translate key="plugins.generic.utplMetrics.settings.altmetricAPIKey.description"}
			<div>
				{fieldLabel required="true" key="plugins.generic.utplMetrics.settings.altmetricAPIKey.label"}
				<input type="text" name="altmetricKey" value="{$altmetricKey|escape}" id="altmetricAPIKey" size="40" maxlength="40" class="textField"/>
			</div>
		</div>
	</div>
	
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="text-center">{translate key="plugins.generic.utplMetrics.settings.elsevierAPIKey"}</h3>
		</div>
		<div class="panel-body">
			{translate key="plugins.generic.utplMetrics.settings.elsevierAPIKey.description"}
			<div>
				{fieldLabel required="true" key="plugins.generic.utplMetrics.settings.elsevierAPIKey.label"}
				<input type="text" name="scopusKey" value="{$scopusKey|escape}" id="elsevierAPIKey" size="40" maxlength="120" class="textField"/>
			</div>
		</div>
	</div>

<br/>
<br/>
<input type="submit" name="save" class="btn btn-primary" value="{translate key="common.save"}"/>
<input type="button" class="btn btn-default" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>

<!-- <p><span class="formRequired">{translate key="common.requiredField"}</span></p> -->
</div>
{include file="common/footer.tpl"}
