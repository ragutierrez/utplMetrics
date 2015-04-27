{**
 * templates/article/newArticleView.tpl
 *
 * Copyright (c) 2013-2014 Simon Fraser University Library
 * Copyright (c) 2003-2014 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Article View.
 *}
{if $galley}
	{assign var=pubObject value=$galley}
{else}
	{assign var=pubObject value=$article}
{/if}

{include file="`$importPath`templates/article/newArticleHeader.tpl"}

{if $galley}
	{if $galley->isHTMLGalley()}
		{$galley->getHTMLContents()}
	{elseif $galley->isPdfGalley()}
		{include file="article/pdfViewer.tpl"}
	{/if}
{else}
	
	{if is_a($article, 'PublishedArticle')}
		{assign var=galleys value=$article->getGalleys()}
	{/if}
	{if $galleys && $subscriptionRequired && $showGalleyLinks}
	<div id="topBar">
		<div id="accessKey">
			<img src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
			{translate key="reader.openAccess"}&nbsp;
			<img src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
			{if $purchaseArticleEnabled}
				{translate key="reader.subscriptionOrFeeAccess"}
			{else}
				{translate key="reader.subscriptionAccess"}
			{/if}
		</div>
	</div>
	{/if}
	

	{if $coverPagePath}
		<div id="articleCoverImage"><img src="{$coverPagePath|escape}{$coverPageFileName|escape}"{if $coverPageAltText != ''} alt="{$coverPageAltText|escape}"{else} alt="{translate key="article.coverPage.altText"}"{/if}{if $width} width="{$width|escape}"{/if}{if $height} height="{$height|escape}"{/if}/>
		</div>
	{/if}
	{call_hook name="Templates::Article::Article::ArticleCoverImage"}
	<div id="articleTitle"><h2>{$article->getLocalizedTitle()|strip_unsafe_html}</h2></div>
	<div id="authorString" class="text-info"><h4>{translate key="plugins.generic.utplMetrics.authors.label"}: {$article->getAuthorString()|escape}</h4></div>

	<div class="container-fluid">
		{foreach from=$pubIdPlugins item=pubIdPlugin}
			{if $issue->getPublished()}
				{assign var=pubId value=$pubIdPlugin->getPubId($pubObject)}
			{else}
				{assign var=pubId value=$pubIdPlugin->getPubId($pubObject, true)}{* Preview rather than assign a pubId *}
			{/if}
			{if $pubId}
				{$pubIdPlugin->getPubIdDisplayType()|escape}: {if $pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}<a id="pub-id::{$pubIdPlugin->getPubIdType()|escape}" href="{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}">{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}</a>{else}{$pubId|escape}{/if}
			{/if}
		{/foreach}

		{if $article->getLocalizedSubject()}
			<h4>{translate key="article.subject"}</h4>
			<div id="articleSubject">
				<br />
				<div>{$article->getLocalizedSubject()|escape}</div>
				<br />
			</div>
		{/if}
		<div class="pull-left" style="padding:0 2% 2% 0">
			<div id="citeArticle" class="btn btn-primary" onclick='window.open("{url page="rt" op="captureCite" path=$article->getBestArticleId($currentJournal)}", "popupWindow", "width=700,height=600,scrollbars=yes");'>
				{translate key="plugins.generic.utplMetrics.tabCiteButtonText"}
			</div>
			<div class="btn-group" style="margin-right:15px">
				<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">{translate key="plugins.generic.utplMetrics.tabShare"} <span class="caret"></span>
  				</button>
  				<ul class="dropdown-menu" role="menu">
				    <li><a target="_blank" style="padding-left: 10px;" href="http://www.reddit.com/submit?url={if $pubIdPlugin->getPubIdType()=='doi'&& $pubId}{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.reddit.16.png" alt="Reddit" height="16" width="16">Reddit</a></li>
				    <li><a target="_blank" style="padding-left: 10px;" href="https://plus.google.com/share?url={if $pubIdPlugin->getPubIdType()=='doi'&& $pubId}{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.gplus.16.png" alt="Reddit" height="16" width="16">Google+</a></li>
				    <li><a target="_blank" style="padding-left: 10px;" href="http://www.stumbleupon.com/submit?url={if $pubIdPlugin->getPubIdType()=='doi'&& $pubId}{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.stumble.16.png" alt="Reddit" height="16" width="16">StumbleUpon</a></li>
				    <li><a target="_blank" style="padding-left: 10px;" href="http://www.facebook.com/share.php?u={if $pubIdPlugin->getPubIdType()=='doi'&& $pubId}{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}&t={$article->getLocalizedTitle()|strip_unsafe_html}">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.fb.16.png" alt="Reddit" height="16" width="16">Facebook</a></li>
				    <li><a target="_blank" style="padding-left: 10px;" href="http://www.linkedin.com/shareArticle?url={if $pubIdPlugin->getPubIdType()=='doi'&& $pubId}{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}&title={$article->getLocalizedTitle()|strip_unsafe_html}&summary=Checkout%20this%20article%20I%20found">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.linkedin.16.png" alt="Reddit" height="16" width="16">LinkedIn</a></li>
				    <li><a target="_blank" style="padding-left: 10px;" href="http://www.citeulike.org/posturl?url={if $pubIdPlugin->getPubIdType()=='doi'&& $pubId}{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}&title={$article->getLocalizedTitle()|strip_unsafe_html}">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.cul.16.png" alt="Reddit" height="16" width="16">CiteULike</a></li>
				    <li><a target="_blank" style="padding-left: 10px;" href="http://www.mendeley.com/import/?url={if $pubIdPlugin->getPubIdType()=='doi'&& $pubId}{$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else}{url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.mendeley.16.png" alt="Reddit" height="16" width="16">Mendeley</a></li>
				    <li><a target="_blank" style="padding-left: 10px;" href="http://twitter.com/intent/tweet?text={$article->getLocalizedTitle()|strip_unsafe_html}{if $pubIdPlugin->getPubIdType()=='doi'&& $pubId} {$pubIdPlugin->getResolvingURL($currentJournal->getId(), $pubId)|escape}{else} {url page="article" op="view" path=$article->getBestArticleId($currentJournal)}{/if}">
				    	<img style="margin-right: 5px;" src="{$baseImportPath}img/icon.twtr.16.png" alt="Reddit" height="16" width="16">Twitter</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container-fluid">
	<!-- Tabs Description -->
	<ul class="nav nav-tabs" role="tablist" id="articleTabs">
		{if $article->getLocalizedAbstract()}
			<li role="presentation" class="active">
				<a href="#articleAbstract" aria-controls="articleAbstract" role="tab" data-toggle="tab">
					<h4>{translate key="article.abstract"}</h4>
				</a>
			</li>
		{else}
			<li role="presentation" class="active">
				<a href="#articleAbstract" aria-controls="articleAbstract" role="tab" data-toggle="tab">
					<h4>{translate key="article.abstract"}</h4>
				</a>
			</li>
		{/if}
		
	  	{if $citationFactory->getCount()}
	  		<li role="presentation">
	  			<a href="#articleCitations" aria-controls="articleCitations" role="tab" data-toggle="tab">
	  				<h4>{translate key="submission.citations"}</h4>
	  			</a>
	  		</li>
		{/if}
		{if $pubId}
		<li role="presentation" id="chart">
			<a href="#Metrics" aria-controls="Metrics" role="tab" data-toggle="tab">
				<h4>{translate key="plugins.generic.utplMetrics.metrics.tabTitle"}</h4>
			</a>
		</li>
		{/if}
		<li>
			<a href="#aboutAuthors" aria-controls="aboutAuthors" role="tab" data-toggle="tab">
				<h4>{translate key="plugins.generic.utplMetrics.aboutAuthors.tabTitle"}</h4>
			</a>
		</li>
		<li>
			<a href="#comments" aria-controls="comments" role="tab" data-toggle="tab">
				<h4>{translate key="plugins.generic.utplMetrics.comments.tabTitle"}</h4>
			</a>
		</li>
	</ul>
	<!-- END Tabs Description -->
	</div>

	<!-- Tabs Content -->
	<div class="tab-content panel-body">
		<div role="tabpanel" class="tab-pane active" id="articleAbstract">
			{if $article->getLocalizedAbstract()}
				<p>{$article->getLocalizedAbstract()|strip_unsafe_html|nl2br}</p>
			{/if}
			{if $galleys}
				{if (!$subscriptionRequired || $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || $subscribedUser || $subscribedDomain)}
					{assign var=hasAccess value=1}
				{else}
					{assign var=hasAccess value=0}
				{/if}
				<div id="articleFullText">
					<h4>{translate key="reader.fullText"}</h4>
					{if $hasAccess || ($subscriptionRequired && $showGalleyLinks)}
						{foreach from=$article->getGalleys() item=galley name=galleyList}
							<a href="{url page="article" op="view" path=$article->getBestArticleId($currentJournal)|to_array:$galley->getBestGalleyId($currentJournal)}" class="file" {if $galley->getRemoteURL()}target="_blank"{else}target="_parent"{/if}>
								{$galley->getGalleyLabel()|escape}
							</a>
							{if $subscriptionRequired && $showGalleyLinks && $restrictOnlyPdf}
								{if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN || !$galley->isPdfGalley()}
									<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
								{else}
									<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
								{/if}
							{/if}
						{/foreach}
						{if $subscriptionRequired && $showGalleyLinks && !$restrictOnlyPdf}
							{if $article->getAccessStatus() == $smarty.const.ARTICLE_ACCESS_OPEN}
								<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_open_medium.gif" alt="{translate key="article.accessLogoOpen.altText"}" />
							{else}
								<img class="accessLogo" src="{$baseUrl}/lib/pkp/templates/images/icons/fulltext_restricted_medium.gif" alt="{translate key="article.accessLogoRestricted.altText"}" />
							{/if}
						{/if}
					{else}
						&nbsp;<a href="{url page="about" op="subscriptions"}" target="_parent">{translate key="reader.subscribersOnly"}</a>
					{/if}
				</div>
			{/if}
		</div>
		{if $citationFactory->getCount()}
			<div role="tabpanel" class="tab-pane" id="articleCitations">
				{iterate from=citationFactory item=citation}
					<p>{$citation->getRawCitation()|strip_unsafe_html}</p>
				{/iterate}
			</div>
		{/if}
		{if $pubId}
		<div role="tabpanel" class="tab-pane" id="Metrics">
			<div>
				<h2>{translate key="plugins.generic.utplMetrics.metrics.ViewedSubtitle"}</h2>
				<div id="div_for_table" style="height:125px">
					<div id="div_near_table" style="background-color: #ccc;padding:2%;width:25%;text-align:center;height:100%" class="pull-left">
						<div style="font-size: 11px;">{translate key="plugins.generic.utplMetrics.table.all_total_views"}</div>
						<div style="font-weight:bold;" class="all_total_views"></div>
						<div id="pub_date"></div>
					</div>
					<div id="div_where_table" style="text-align:center;height:100%;">
						<table id="table_stats" style="height:100%;">
							<tr style="background-color: rgb(204, 204, 204);">
								<td style='width:33.3333333333333%'>{translate key="plugins.generic.utplMetrics.table.html_views"}</td>
								<td style='width:33.3333333333333%'>{translate key="plugins.generic.utplMetrics.table.pdf_downloads"}</td>
								<td style='width:33.3333333333333%'>{translate key="plugins.generic.utplMetrics.table.total"}</td>
							</tr>
							<tr>
								<td id="total_html">0</td>
								<td id="total_pdf">0</td>
								<td class="all_total_views">0</td>
							</tr>
							<tr>
								<td colspan="3"><b id="percent">0</b>% {translate key="plugins.generic.utplMetrics.table.percent_text"}</td>
							</tr>
						</table>
					</div>
				</div>
				<div style="padding-top:10px;padding-bottom:10px;"></div>
				<div id="chart">
					<div id="canvas-holder">
						<div id="loading">
						</div>
						<div id="legend" class="pull-left"></div>
						<canvas id="chart-area" height="300px"/>
					</div>
				</div>
			</div>
			<hr>
			<div>
				<h2>{translate key="plugins.generic.utplMetrics.metrics.CitedSubtitle"}</h2>
				<div id="CitedSubtitle"></div>
			</div>
			<hr>
			<div>
				<h2>{translate key="plugins.generic.utplMetrics.metrics.DiscussedSubtitle"}</h2>
				<div id="DiscussedSubtitle"></div>
			</div>
			<hr>
			<div>
				<h2>{translate key="plugins.generic.utplMetrics.metrics.SavedSubtitle"}</h2>
				<div id="SavedSubtitle"></div>
			</div>
			<hr>
			<div>
				<span><sub>Metrics powered by <a href="http://www.altmetric.com/">ALTMETRIC</a> and <a href="http://www.elsevier.es/">ELSEVIER</a><sub></span>
			</div>
		</div>
		{/if}
		<div role="tabpanel" class="tab-pane" id="aboutAuthors">
			{foreach from=$article->getAuthors() item=author}
				<div id="author">
					{assign var=lastName value=$author->getLastName()}
					{assign var=firstName value=$author->getFirstName()}
					{assign var=middleName value=$author->getMiddleName()}
					<h3><strong>{$lastName|escape}, {$firstName|escape}{if $middleName} {$author->getMiddleName()|escape}{/if}</strong></h3>
					{if $author->getCountryLocalized()}
						<div id="authorCountry">
							<h4>Country: {$author->getCountryLocalized()|escape}</h4>
						</div>
					{/if}
					{if $author->getBiography($currentLocale)}
						<div id="authorBiography">
							<h4>Biografía:</h4>
								<p>{$author->getBiography($currentLocale)|escape}</p>
						</div>
					{else}
						<div id="authorBiography">
							<h4>Biografía:</h4>
								<p>{$author->getBiography("en_US")|escape}</p>
						</div>
					{/if}
					{if $author->getEmail()}
						<div id="Contact">
							<h4>Contact:</h4>
								<a href="mailto:$author->getEmail()">{$author->getEmail()|escape}</a>
						</div>
					{/if}
				</div>
			{/foreach}
		</div>
		<div role="tabpanel" class="tab-pane" id="comments">
			<div id="commentsOnArticle">
				<h4>{translate key="comments.commentsOnArticle"}</h4>
				<ul>
					{foreach from=$comments item=comment}
					{assign var=poster value=$comment->getUser()}
					<li>
						<a href="{url page="comment" op="view" path=$article->getId()|to_array:$galleyId:$comment->getId()}" target="_parent">{$comment->getTitle()|escape|default:"&nbsp;"}</a>
						{if $comment->getChildCommentCount()==1}
							{translate key="comments.oneReply"}
						{elseif $comment->getChildCommentCount()>0}
							{translate key="comments.nReplies" num=$comment->getChildCommentCount()}
						{/if}

						<br/>

						{if $poster}
							{url|assign:"publicProfileUrl" page="user" op="viewPublicProfile" path=$poster->getId()}
							{translate key="comments.authenticated" userName=$poster->getFullName()|escape publicProfileUrl=$publicProfileUrl}
						{elseif $comment->getPosterName()}
							{translate key="comments.anonymousNamed" userName=$comment->getPosterName()|escape}
						{else}
							{translate key="comments.anonymous"}
						{/if}
						({$comment->getDatePosted()|date_format:$dateFormatShort})
					</li>
					{/foreach}
				</ul>
				<a href="{url page="comment" op="view" path=$article->getId()|to_array:$galleyId}" class="action" target="_parent">{translate key="comments.viewAllComments"}</a>
				{assign var=needsSeparator value=1}
			</div>

			{if $postingAllowed}
				{if $needsSeparator}
					&nbsp;|&nbsp;
				{else}
					<br/><br/>
				{/if}
				<a class="action" href="{url page="comment" op="add" path=$article->getId()|to_array:$galleyId}" target="_parent">{translate key="rt.addComment"}</a>
			{/if}
		</div>
	</div> <!-- END Tabs Content -->
{/if}

<br />
<br />
{call_hook name="Templates::Article::MoreInfo"}

<script type="text/javascript">
	options = {ldelim}
		utplMetricsStatsJson: $.parseJSON('{$utplMetricsStatsJson|escape:"javascript"}'),
		ojsStats:$.parseJSON('{$ojsStatsJson|escape:"javascript"}'),
		article_date:'{$issue->getDatePublished()|escape:"javascript"}',
	{rdelim}

	$.getScript('{$jqueryImportPath}', function() {ldelim}
		$.getScript('{$chartjsImportPath}', function() {ldelim}
			$("#chart").click(function() {ldelim}
				var colorDictionary = {ldelim}
					ojs_Views : "#3b5998",
					ojs_Downloads : "#d62d20",
				{rdelim}

				var chartData = {ldelim}
				    labels: [],
				    datasets: [
				        {ldelim}
				            label: "Bar DataSet",
				            fillColor: [],
				            strokeColor: [],
				            highlightFill: [],
				            highlightStroke: [],
				            data: []
				        {rdelim}
				    ]
				{rdelim};

				sources=options.ojsStats;

				for (key in sources){ldelim}
						if (sources[key]>0){ldelim}
							chartData.labels.push(key);
							chartData.datasets[0].fillColor.push(colorDictionary[key]);
							chartData.datasets[0].strokeColor.push(colorDictionary[key]);
							chartData.datasets[0].highlightFill.push(colorDictionary[key]);
							chartData.datasets[0].highlightStroke.push(colorDictionary[key]);
							chartData.datasets[0].data.push(sources[key]);
					{rdelim}
				{rdelim}

				$(document).ready(function() {ldelim}
					$('#CitedSubtitle')[0].innerHTML='';
					$('#SavedSubtitle')[0].innerHTML='';
					$('#DiscussedSubtitle')[0].innerHTML='';

					var ojs_Views = parseInt(options.ojsStats.ojs_Views);
					var ojs_Downloads = parseInt(options.ojsStats.ojs_Downloads);

					$('#table_stats td').each(function(){ldelim}
						$(this)[0].style.border = '1px solid #ccc';
					{rdelim});

					$('.all_total_views').each(function(){ldelim}
						$(this)[0].textContent = ojs_Views + ojs_Downloads;
						{rdelim});
					$('#total_html')[0].textContent = ojs_Views;
					$('#total_pdf')[0].textContent = ojs_Downloads;
					$('#percent')[0].textContent = ((ojs_Downloads*100)/(ojs_Views+ojs_Downloads)).toFixed(2);
					//articleDate
					var ad = new Date(options.article_date.split(" ")[0]);

					var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun","Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
					var d = new Date();
					$("div#pub_date")[0].innerHTML= monthNames[ad.getMonth()]+' '+ad.getDate()+', '+ad.getFullYear() +'<br/>'+ monthNames[d.getMonth()]+' '+d.getDate()+', '+d.getFullYear();
					$("div#pub_date")[0].style.fontSize='10px';

					var ctx = document.getElementById("chart-area").getContext("2d");
					window.myChart = new Chart(ctx).Bar(chartData);

					var le = $('#legend')[0];
					
					while(le.hasChildNodes()) {ldelim}
			        	le.removeChild(le.lastChild);
			    	{rdelim}

					var ul = $('<ul/>');
					ul[0].style.padding="0";

					for (key in sources){ldelim}
						if (sources[key]>0){ldelim}
							var li = $('<li />');
							var li_style = li[0].style;

							var span = $('<span />').addClass('badge');
							span[0].textContent=sources[key];

							li[0].textContent=key.toLocaleUpperCase();
							li.addClass('list-group-item');
							li_style.borderColor=colorDictionary[key];
							li_style.borderLeftWidth="10px";
							li_style.padding="2%"
							li_style.minWidth="180px";
							li_style.fontWeight='bold';
							
							span.appendTo(li);
							span[0].style.backgroundColor=colorDictionary[key];
							li.appendTo(ul);
			    		{rdelim}
			    	{rdelim}
					ul.appendTo(le);
					$('#loading').attr('style','display:none;');
				{rdelim});
				$(window).resize(function() {ldelim}
					var ctx = document.getElementById("chart-area").getContext("2d");
					window.myChart = new Chart(ctx).Bar(chartData);
					if($(window).width()>=650) {ldelim}
						$('#div_near_table')[0].style.border='';
						$('#div_where_table')[0].style.border='';
						$('#div_near_table')[0].style='';
						$('#div_near_table')[0].class="pull-left";
						$('#div_near_table')[0].style.width='25%';
						$('#div_where_table table')[0].style.width='';
						$('#div_for_table')[0].style.height='124px';
						$('#div_near_table')[0].style.height='100%';
						$('#div_where_table')[0].style.height='100%';
						$('#div_where_table table')[0].style.height='100%';
						$('#div_where_table')[0].style.width='';
						$('#div_near_table')[0].style.backgroundColor='#ccc';
						$('#div_near_table')[0].style.padding='2%';
						$('#div_near_table')[0].style.textAlign='center'
						$('#div_near_table')[0].style.height='100%';
					{rdelim} else if($(window).width()<=450) {ldelim}
						$('#div_for_table')[0].style.height='';
						$('#div_where_table table')[0].style.width='';
						$('#div_where_table table')[0].style.height='';
						$('#div_where_table')[0].style.height='';
						$('#div_near_table')[0].style.width='180px';
						$('#div_where_table')[0].style.width='180px';
					{rdelim} else if($(window).width()<650 && $(window).width()>450) {ldelim}
						$('#div_for_table')[0].style.height='201px';
						$('#div_where_table')[0].style.height='';
						$('#div_where_table table')[0].style.height='';
						$('#div_where_table')[0].style.width='100%';
						$('#div_near_table')[0].style.height='';
						$('#div_near_table')[0].style.width='100%';
						$('#div_near_table')[0].style.border='1px solid #000';
						$('#div_where_table')[0].style.border='1px solid #000';
						$('#div_where_table table')[0].style.width='100%';
					{rdelim}
				{rdelim});

				$(document).ready(function() {ldelim}
					if($(window).width()>=650) {ldelim}
						$('#div_near_table')[0].style.border='';
						$('#div_where_table')[0].style.border='';
						$('#div_near_table')[0].style='';
						$('#div_near_table')[0].class="pull-left";
						$('#div_near_table')[0].style.width='25%';
						$('#div_where_table table')[0].style.width='';
						$('#div_for_table')[0].style.height='124px';
						$('#div_near_table')[0].style.height='100%';
						$('#div_where_table')[0].style.height='100%';
						$('#div_where_table table')[0].style.height='100%';
						$('#div_where_table')[0].style.width='';
						$('#div_near_table')[0].style.backgroundColor='#ccc';
						$('#div_near_table')[0].style.padding='2%';
						$('#div_near_table')[0].style.textAlign='center'
						$('#div_near_table')[0].style.height='100%';
					{rdelim} else if($(window).width()<=390) {ldelim}
						$('#div_for_table')[0].style.height='';
						$('#div_where_table table')[0].style.width='';
						$('#div_where_table table')[0].style.height='';
						$('#div_where_table')[0].style.height='';
						$('#div_near_table')[0].style.width='180px';
						$('#div_where_table')[0].style.width='180px';
					{rdelim} else if($(window).width()<650 && $(window).width()>390) {ldelim}
						$('#div_for_table')[0].style.height='201px';
						$('#div_where_table')[0].style.height='';
						$('#div_where_table table')[0].style.height='';
						$('#div_where_table')[0].style.width='100%';
						$('#div_near_table')[0].style.height='';
						$('#div_near_table')[0].style.width='100%';
						$('#div_near_table')[0].style.border='1px solid #000';
						$('#div_where_table')[0].style.border='1px solid #000';
						$('#div_where_table table')[0].style.width='100%';
					{rdelim}

					var altmetricStats = options.utplMetricsStatsJson[0].sources;
					var divClass = '';

					for (key in altmetricStats){ldelim}
						if(key=='google_plus'||key=='facebook'||key=='tweets'){ldelim}
							divClass='#DiscussedSubtitle';
						{rdelim}else if(key=='mendeley'||key=='citeULike'){ldelim}
							divClass='#SavedSubtitle';
						{rdelim}else if(key=='scopus'){ldelim}
							divClass='#CitedSubtitle';
						{rdelim}
						$('<img/>',{ldelim}'src':'{$baseImportPath}img/'+key+'.png',{rdelim}).css({ldelim}'border-bottom':'1px solid #ccc'{rdelim})
					.appendTo($('<div/>', {ldelim}'id':'sub_'+key,{rdelim})
						.css({ldelim}'width':'102px','height':'102px','border':'1px solid #ccc','margin':'0 8px 8px 0','display':'inline-block'{rdelim}).appendTo(divClass));
						var div_id = '#sub_'+key;
						$(div_id).append($('<div/>',{ldelim}'text':altmetricStats[key]{rdelim}).css({ldelim}'background-color':'#ccc','color':'#069','font-size':'1.3em','text-decoration':'none','text-align':'center','font-weight':'bold','width':'100px','height':'32px'{rdelim}))
					{rdelim}

				{rdelim});
			{rdelim});
		{rdelim});
	{rdelim});

</script>

{include file="`$importPath`templates/article/footer.tpl"}