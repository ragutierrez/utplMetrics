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
		<li role="presentation" id="chart">
			<a href="#Metrics" aria-controls="Metrics" role="tab" data-toggle="tab">
				<h4>{translate key="plugins.generic.utplMetrics.title"}</h4>
			</a>
		</li>
		<li>
			<a href="#aboutAuthors" aria-controls="aboutAuthors" role="tab" data-toggle="tab">
				<h4>{translate key="plugins.generic.utplMetrics.aboutAuthors.tabTitle"}</h4>
			</a>
		</li>
	</ul> <!-- END Tabs Description -->

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
		<div role="tabpanel" class="tab-pane text-center" id="Metrics">
			<div id="canvas-holder" class="center-block">
				<div id="loading">{translate key="plugins.generic.utplMetrics.loading"}</div>
				<div id="legend" class="pull-left"></div>
				<canvas class="center" id="chart-area" height="400px"/>
			</div>
			<div>
				<span><sub>Metrics powered by <a href="http://www.altmetric.com/">ALTMETRIC</a> and <a href="http://www.elsevier.es/">ELSEVIER</a><sub></span>
			</div>
		</div>
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
		
	</div> <!-- END Tabs Content -->
{/if}

<br />
<br />
{call_hook name="Templates::Article::MoreInfo"}

<script type="text/javascript">
	options = {ldelim}
		utplMetricsStatsJson: $.parseJSON('{$utplMetricsStatsJson|escape:"javascript"}'),
		additionalStatsJson: $.parseJSON('{$additionalStatsJson|escape:"javascript"}'),
		altmetricUrl: '{$smarty.const.ALTMETRIC_URL}',
		elsevierUrl: '{$smarty.const.ELSEVIER_URL}',
		downloads:'{$articleGalleysViewsAndDowloads}',
		minItemsToShowGraph: {ldelim}
			minEventsForYearly: 0,
			minEventsForMonthly: 0,
			minEventsForDaily: 0,
			minYearsForYearly: 0,
			minMonthsForMonthly: 0,
			minDaysForDaily: 0
		{rdelim},
	{rdelim}

	$.getScript('{$jqueryImportPath}', function() {ldelim}
		$.getScript('{$chartjsImportPath}', function() {ldelim}
			$( "#chart" ).click(function() {ldelim}
				var colorDictionary = {ldelim}
					facebook : "#3b5998",
					google_plus : "#d62d20",
					reddit : "#FF5700",
					tweets : "#326ada",
					citeULike : "#446600",
					mendeley : "#B61F2F",
					scopus : "#006666",
					scholar: "#2D2D2D"
				{rdelim}

				var chartData = 
				{ldelim}
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

				sources=options.utplMetricsStatsJson[0].sources[0];

				for (key in sources){ldelim}
					chartData.labels.push(key);
					chartData.datasets[0].fillColor.push(colorDictionary[key]);
					chartData.datasets[0].strokeColor.push(colorDictionary[key]);
					chartData.datasets[0].highlightFill.push(colorDictionary[key]);
					chartData.datasets[0].highlightStroke.push(colorDictionary[key]);
					chartData.datasets[0].data.push(sources[key]);
				{rdelim}


				$(document).ready(function() {ldelim}

					$('div canvas')[0].width=$(window).width()*0.30;
					var ctx = document.getElementById("chart-area").getContext("2d");
					window.myChart = new Chart(ctx).Bar(chartData);

					var le = $('#legend')[0];
					
					while(le.hasChildNodes()) {ldelim}
			        	le.removeChild(le.lastChild);
			    	{rdelim}

					var ul = $('<ul/>');

					for (key in sources){ldelim}

						var li = $('<li />');
						var li_style = li[0].style;

						var span = $('<span />').addClass('badge');
						span[0].textContent=sources[key];

						li[0].textContent=key.toLocaleUpperCase();
						li.addClass('list-group-item');
						li_style.borderColor=colorDictionary[key];
						li_style.borderLeftWidth="10px";
						li_style.padding="2%"
						li_style.minWidth="150px";
						li_style.fontWeight='bold';
						
						span.appendTo(li);
						span[0].style.backgroundColor=colorDictionary[key];
						li.appendTo(ul);

			    	{rdelim}
					ul.appendTo(le);
					$('#loading').attr('style','display:none;');
				{rdelim});
				$(window).resize(function() {ldelim}
					$('div canvas')[0].width=$(window).width()*0.30;
					var ctx = document.getElementById("chart-area").getContext("2d");
					window.myChart = new Chart(ctx).Bar(chartData);
				{rdelim});
			{rdelim});
		{rdelim});
	{rdelim});

</script>

{include file="`$importPath`templates/article/comments.tpl"}

{include file="`$importPath`templates/article/footer.tpl"}