{**
 * templates/author/completed.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Show the details of completed submissions.
 *
 *}
<div id="submissions">
<table class="table table-striped">
	<tr valign="bottom" class="heading">
		<th width="5%">{sort_heading key="common.id" sort="id"}</th>
		<th width="5%"><span class="disabled">{translate key="submission.date.mmdd"}</span><br />{sort_heading key="submissions.submit" sort="submitDate"}</th>
		<th width="5%">{sort_heading key="submissions.sec" sort="section"}</th>
		<th width="23%">{sort_heading key="article.authors" sort="authors"}</th>
		<th width="32%">{sort_heading key="article.title" sort="title"}</th>
		{if $statViews}<th width="5%">{sort_heading key="submission.views" sort="views"}</th>{/if}
		<th width="25%" align="right">{sort_heading key="common.status" sort="status"}</th>
	</tr>
	<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
{iterate from=submissions item=submission}
	{assign var="articleId" value=$submission->getId()}
	<tr valign="top">
		<td>{$articleId|escape}</td>
		<td>{$submission->getDateSubmitted()|date_format:$dateFormatTrunc}</td>
		<td>{$submission->getSectionAbbrev()|escape}</td>
		<td>{$submission->getAuthorString(true)|truncate:40:"..."|escape}</td>
		<td><a href="{url op="submission" path=$articleId}" class="action">{$submission->getLocalizedTitle()|strip_tags|truncate:60:"..."}</a></td>
		{assign var="status" value=$submission->getSubmissionStatus()}
		{if $statViews}
			<td>
				{if $status==STATUS_PUBLISHED}
					{assign var=viewCount value=0}
					{foreach from=$submission->getGalleys() item=galley}
						{assign var=thisCount value=$galley->getViews()}
						{assign var=viewCount value=$viewCount+$thisCount}
					{/foreach}
					{$viewCount|escape}
				{else}
					&mdash;
				{/if}
			</td>
		{/if}
		<td align="right">
			{if $status==STATUS_ARCHIVED}{translate key="submissions.archived"}
			{elseif $status==STATUS_PUBLISHED}{print_issue_id articleId="$articleId"}
			{elseif $status==STATUS_DECLINED}{translate key="submissions.declined"}
			{/if}
		</td>
	</tr>
	<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
{/iterate}
{if $submissions->wasEmpty()}
	<tr>
		<td colspan="{if $statViews}7{else}6{/if}" class="nodata">{translate key="submissions.noSubmissions"}</td>
	</tr>
	<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
{else}
	<tr>
		<td colspan="{if $statViews}5{else}4{/if}" align="left">{page_info iterator=$submissions}</td>
		<td colspan="2" align="right">{page_links anchor="submissions" name="submissions" iterator=$submissions sort=$sort sortDirection=$sortDirection}</td>
	</tr>
{/if}
</table>
</div>

