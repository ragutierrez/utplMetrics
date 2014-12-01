<?php
/*
*
* archivo: utplPlugin.inc.php
* se ha tomado como referencia: ALM Plugin y Google Analytics Plugin
*
*/

import('lib.pkp.classes.plugins.GenericPlugin');
import('lib.pkp.classes.core.JSONManager');

DEFINE('ALTMETRIC_URL', 'http://api.altmetric.com/v1/doi/');
DEFINE('ELSEVIER_URL', 'http://api.elsevier.com/content/search/scopus?apiKey=');

class utplPlugin extends GenericPlugin {

	/** @var $altmetricKey string */
	var $_altmetricKey;
	/** @var $scopusKey string */
	var $_scopusKey;

	/**
	*
	* Function to print data in browser console
	* @param $data object
	*
	*/
	function dtc( $data ) {

		if ( is_array( $data ) )
			$output = "<script>console.log( '" . implode( ',', $data) . "' );</script>";
		else
			$output = "<script>console.log( '" . $data . "' );</script>";

		echo $output;
	}


	/**
	 * @see LazyLoadPlugin::register()
	 */
	function register($category, $path) {
		$success = parent::register($category, $path);
		if (!Config::getVar('general', 'installed')) return false;

		$application =& Application::getApplication();
		$request =& $application->getRequest();
		$router =& $request->getRouter();
		$context = $router->getContext($request);

		if ($success && $context) {
			$scopusKey = $this->getSetting($context->getId(), 'scopusKey');
			$altmetricKey = $this->getSetting($context->getId(), 'altmetricKey');
			if ($scopusKey || $altmetricKey) {
				$this->_scopusKey = $scopusKey;
				$this->_altmetricKey = $altmetricKey;
				HookRegistry::register('TemplateManager::display', array(&$this, '_displayCallback'));
			}
		}
		return $success;
	}

	/**
	 * @see LazyLoadPlugin::getName()
	 */
	function getName() {
		return 'utplplugin';
	}

	/**
	 * @see PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.utplMetrics.displayName');
	}

	/**
	 * @see PKPPlugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.generic.utplMetrics.description');
	}

	/**
	* @see GenericPlugin::getManagementVerbs()
	*/
	function getManagementVerbs() {
		$verbs = array();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('plugins.generic.utplMetrics.settings'));
		}
		return parent::getManagementVerbs($verbs);
	}

	/**
	 * @see GenericPlugin::manage()
	 */
	function manage($verb, $args, &$message, &$messageParams) {
		if (!parent::manage($verb, $args, $message, $messageParams)) return false;
		switch ($verb) {
			case 'settings':
				$journal =& Request::getJournal();

				$templateMngr =& TemplateManager::getManager();
				$templateMngr->register_function('plugin_url', array(&$this, 'smartyPluginUrl'));

				$this->import('SettingsForm');
				$form = new SettingsForm($this, $journal->getId());

				if (Request::getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						$message = NOTIFICATION_TYPE_SUCCESS;
						$messageParams = array('contents' => __('plugins.generic.utplMetrics.settings.saved'));
						return false;
					} else {
						$form->display();
					}
				} else {
					$form->initData();
					$form->display();
				}
				return true;
			default:
				// Unknown management verb
				assert(false);
			return false;
		}
	}

	function _displayCallback($hookName, $params) {
		if ($this->getEnabled()) {
			$templateMgr =& $params[0];
			$template =& $params[1];

			var_dump($template);
			$importPath = ".." . DIRECTORY_SEPARATOR . $this->getPluginPath() . DIRECTORY_SEPARATOR;
			$templateMgr->assign('importPath', $importPath);

			switch ($template) {

				case 'about/aboutThisPublishingSystem.tpl':
					$template = $this->getTemplatePath() . 'templates/about/aboutThisPublishingSystem.tpl';
					break;
					
				case 'about/contact.tpl':
					$template = $this->getTemplatePath() . 'templates/about/contact.tpl';
					break;
					
				case 'about/editorialPolicies.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialPolicies.tpl';
					break;
					
				case 'about/editorialTeam.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialTeam.tpl';
					break;
					
				case 'about/editorialTeamBoard.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialTeamBoard.tpl';
					break;
					
				case 'about/editorialTeamBio.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialTeamBio.tpl';
					break;
					
				case 'about/history.tpl':
					$template = $this->getTemplatePath() . 'templates/about/history.tpl';
					break;
					
				case 'about/index.tpl':
					$template = $this->getTemplatePath() . 'templates/about/index.tpl';
					break;

				case 'about/journalSponsorship.tpl':
					$template = $this->getTemplatePath() . 'templates/about/journalSponsorship.tpl';
					break;

				case 'about/site.tpl':
					$template = $this->getTemplatePath() . 'templates/about/site.tpl';
					break;
					
				case 'about/siteMap.tpl':
					$template = $this->getTemplatePath() . 'templates/about/siteMap.tpl';
					break;
					
				case 'about/statistics.tpl':
					$template = $this->getTemplatePath() . 'templates/about/statistics.tpl';
					break;
					
				case 'about/submissions.tpl':
					$template = $this->getTemplatePath() . 'templates/about/submissions.tpl';
					break;
					
				case 'about/subscriptions.tpl':
					$template = $this->getTemplatePath() . 'templates/about/subscriptions.tpl';
					break;
					
				case 'admin/auth/sourceSettings.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/auth/sourceSettings.tpl';
					break;
					
				case 'admin/auth/sources.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/auth/sources.tpl';
					break;
					
				case 'admin/categories/categories.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/categories/categories.tpl';
					break;
					
				case 'admin/categories/categoryForm.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/categories/categoryForm.tpl';
					break;
					
				case 'admin/index.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/index.tpl';
					break;
					
				case 'admin/journalSettings.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/journalSettings.tpl';
					break;
					
				case 'admin/journals.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/journals.tpl';
					break;
					
				case 'admin/languageDownloadErrors.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/languageDownloadErrors.tpl';
					break;
					
				case 'admin/languages.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/languages.tpl';
					break;
					
				case 'admin/selectMergeUser.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/selectMergeUser.tpl';
					break;
					
				case 'admin/settings.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/settings.tpl';
					break;
					
				case 'admin/systemInfo.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/systemInfo.tpl';
					break;
					
				case 'author/index.tpl':
					$template = $this->getTemplatePath() . 'templates/author/index.tpl';
					break;
					
				case 'author/submission.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission.tpl';
					break;
					
				case 'author/submissionEditing.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submissionEditing.tpl';
					break;
					
				case 'author/submissionReview.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submissionReview.tpl';
					break;
					
				case 'author/submit/complete.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/complete.tpl';
					break;
					
				case 'author/submit/submitHeader.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/submitHeader.tpl';
					break;
					
				case 'index/journal.tpl':

					$template = $this->getTemplatePath() . 'templates/index/journal.tpl';
					break;

				case 'index/site.tpl':

					$template = $this->getTemplatePath() . 'templates/index/site.tpl';
					break;

				case '':
					$template = $this->getTemplatePath() . 'templates/';
					break;
					
				case 'search/authorDetails.tpl':
					$template = $this->getTemplatePath() . 'templates/search/authorDetails.tpl';
					break;
					
				case 'search/authorIndex.tpl':
					$template = $this->getTemplatePath() . 'templates/search/authorIndex.tpl';
					break;
					
				case 'search/categories.tpl':
					$template = $this->getTemplatePath() . 'templates/search/categories.tpl';
					break;
					
				case 'search/category.tpl':
					$template = $this->getTemplatePath() . 'templates/search/category.tpl';
					break;
					
				case 'search/search.tpl':
					$template = $this->getTemplatePath() . 'templates/search/search.tpl';
					break;
					
				case 'search/titleIndex.tpl':
					$template = $this->getTemplatePath() . 'templates/search/titleIndex.tpl';
					break;
					
				case 'user/register.tpl':
					$template = $this->getTemplatePath() . 'templates/user/register.tpl';
					break;
					
				case 'user/changePassword.tpl':
					$template = $this->getTemplatePath() . 'templates/user/changePassword.tpl';
					break;
					
				case 'user/gifts.tpl':
					$template = $this->getTemplatePath() . 'templates/user/gifts.tpl';
					break;
					
				case 'user/index.tpl':
					$template = $this->getTemplatePath() . 'templates/user/index.tpl';
					break;
					
				case 'user/login.tpl':
					$template = $this->getTemplatePath() . 'templates/user/login.tpl';
					break;
					
				case 'user/loginChangePassword.tpl':
					$template = $this->getTemplatePath() . 'templates/user/loginChangePassword.tpl';
					break;
					
				case 'user/lostPassword.tpl':
					$template = $this->getTemplatePath() . 'templates/user/lostPassword.tpl';
					break;
					
				case 'user/profile.tpl':
					$template = $this->getTemplatePath() . 'templates/user/profile.tpl';
					break;
					
				case 'user/publicProfile.tpl':
					$template = $this->getTemplatePath() . 'templates/user/publicProfile.tpl';
					break;
					
				case 'user/register.tpl':
					$template = $this->getTemplatePath() . 'templates/user/register.tpl';
					break;
					
				case 'user/registerSite.tpl':
					$template = $this->getTemplatePath() . 'templates/user/registerSite.tpl';
					break;
					
				case 'user/subscriptions.tpl':
					$template = $this->getTemplatePath() . 'templates/user/subscriptions.tpl';
					break;
					
				case 'article/article.tpl':

					$additionalHeadData = $templateMgr->get_template_vars('additionalHeadData');
					$baseImportPath = Request::getBaseUrl() . DIRECTORY_SEPARATOR . $this->getPluginPath() . DIRECTORY_SEPARATOR;


					$template = $this->getTemplatePath() . 'templates/article/newArticleView.tpl';

					$article =& $templateMgr->get_template_vars('article');
					assert(is_a($article, 'PublishedArticle'));
					$articleId = $article->getId();

					$downloadStats = $this->_getDownloadStats($request, $articleId);
					
					// Se usa un método de ayuda para agregar estadísticas en vez de recuperar 
					// directamente desde "metrics DAO" porque necesitamos nuestro propio formato de array.
					list($totalHtml, $totalPdf, $byMonth, $byYear) = $this->_aggregateDownloadStats($downloadStats);
					$downloadJson = $this->_buildDownloadStatsJson($totalHtml, $totalPdf, $byMonth, $byYear);

					$utplMetricsStatsJson = $this->_getUtplMetricsStats($article);

					if ($downloadJson || $utplMetricsStatsJson) {
						if ($utplMetricsStatsJson) $templateMgr->assign('utplMetricsStatsJson', $utplMetricsStatsJson);
						if ($downloadJson) $templateMgr->assign('additionalStatsJson', $downloadJson);

						$jqueryImportPath = $baseImportPath . 'js/jquery-1.11.1.min.js';
						$tooltipImportPath = $baseImportPath . 'js/alm.js';
						$chartjsImportPath = $baseImportPath . 'js/Chart.js/Chart.js';

						$templateMgr->assign('jqueryImportPath', $jqueryImportPath);
						$templateMgr->assign('tooltipImportPath', $tooltipImportPath);
						$templateMgr->assign('chartjsImportPath', $chartjsImportPath);

					}
					break;
			}
			return false;
		}
	}

	/**
	 * @see PKPPlugin::getInstallSitePluginSettingsFile()
	 */
	function getInstallSitePluginSettingsFile() {
		return $this->getPluginPath() . '/settings.xml';
	}

	/**
	* @see AcronPlugin::parseCronTab()
	*/
	function callbackParseCronTab($hookName, $args) {
		$taskFilesPath =& $args[0];
		$taskFilesPath[] = $this->getPluginPath() . DIRECTORY_SEPARATOR . 'scheduledTasks.xml';

		return false;
	}


	/**
	* Obtiene las citas y "shares" del artículo en tiempo real.
	* @param $doi article DOI
	* @return array
	*/
	function _getCitas($doi)
	{

		$citas=array();

		$scopus_url = ELSEVIER_URL.$this->_scopusKey."&query=DOI(\"".$doi."\")";
		$scopus_response = file_get_contents($scopus_url);
		$scopus_json = json_decode($scopus_response);
		if ($scopus_json->{'search-results'}->{'opensearch:totalResults'}==0){
			$citas+=array("scopus"=>0);
		} else{
			$citas+=array("scopus"=>intval($scopus_json->{'search-results'}->{'entry'}[0]->{'citedby-count'}));
		}
		
		$altmetric_url=ALTMETRIC_URL.$doi."?key=".$this->_altmetricKey;
		$altmetric_response = file_get_contents($altmetric_url);
		$altmetric_json = json_decode($altmetric_response);

		if ($altmetric_json!=NULL){
			$citas+=array('google_plus'=>(isset($altmetric_json->{'cited_by_gplus_count'}) ? (($altmetric_json->{'cited_by_gplus_count'}!="") ? $altmetric_json->{'cited_by_gplus_count'}:0):0));
			$citas+=array('facebook'=>(isset($altmetric_json->{'cited_by_fbwalls_count'}) ? (($altmetric_json->{'cited_by_fbwalls_count'}!="") ? $altmetric_json->{'cited_by_fbwalls_count'}:0):0));
			$citas+=array('tweets'=>(isset($altmetric_json->{'cited_by_tweeters_count'}) ? (($altmetric_json->{'cited_by_tweeters_count'}!="") ? $altmetric_json->{'cited_by_tweeters_count'}:0):0));
			$citas+=array('reddit'=>(isset($altmetric_json->{'cited_by_rdts_count'}) ? (($altmetric_json->{'cited_by_rdts_count'}!="") ? $altmetric_json->{'cited_by_rdts_count'}:0):0));
			$citas+=array('citeULike'=>intval(($altmetric_json->{'readers'}->{'citeulike'}!="") ? $altmetric_json->{'readers'}->{'citeulike'}:0));
			$citas+=array('mendeley'=>intval(($altmetric_json->{'readers'}->{'mendeley'}!="") ? $altmetric_json->{'readers'}->{'mendeley'}:0));
		}else{
			$citas+=array('google_plus'=>0);
			$citas+=array('facebook'=>0);
			$citas+=array('tweets'=>0);
			$citas+=array('reddit'=>0);
			$citas+=array('citeULike'=>0);
			$citas+=array('mendeley'=>0);
		}
		return array($citas);

	}

	/**
	 * 
	 * @param $article Article
	 * @return string JSON message
	 */
	function _getUtplMetricsStats($article) {

		if ($article->getDatePublished()) {
			$datePublished = $article->getDatePublished();
		} else {
			// A veces no existe valor getDatePublished para el artículo,
			// entonces buscamos en la publicacion (issue)
			$issueDao =& DAORegistry::getDAO('IssueDAO');  /* @var $issueDao IssueDAO */
			$issue =& $issueDao->getIssueByArticleId($article->getId(), $article->getJournalId());
			$datePublished = $issue->getDatePublished();
		}
		$doi = $article->getPubId('doi');
		$response = array(
			array(
				'publication_date' => date('c', strtotime($datePublished)),
				'doi' => $doi,
				'title' => $article->getLocalizedTitle(),
				'sources' => $this->_getCitas($doi)
		));

		$jsonManager = new JSONManager();
		return $jsonManager->encode($response);
	}

	/**
	 * Get download stats for the passed article id.
	 * @param $request PKPRequest
	 * @param $articleId int
	 * @return array MetricsDAO::getMetrics() result.
	 */
	function _getDownloadStats(&$request, $articleId) {
		// Pull in download stats for each article galley.
		$request =& Application::getRequest();
		$router =& $request->getRouter();
		$context =& $router->getContext($request); /* @var $context Journal */
		
		$metricsDao =& DAORegistry::getDAO('MetricsDAO'); /* @var $metricsDao MetricsDAO */

		// Load the metric type constant.
		PluginRegistry::loadCategory('reports');

		// Always merge the old timed views stats with default metrics.
		$metricTypes = array(OJS_METRIC_TYPE_TIMED_VIEWS, $context->getDefaultMetricType());
		$columns = array(STATISTICS_DIMENSION_MONTH, STATISTICS_DIMENSION_FILE_TYPE);
		$filter = array(STATISTICS_DIMENSION_ASSOC_TYPE => ASSOC_TYPE_GALLEY, STATISTICS_DIMENSION_SUBMISSION_ID => $articleId);
		$orderBy = array(STATISTICS_DIMENSION_MONTH => STATISTICS_ORDER_ASC);

		return $metricsDao->getMetrics($metricTypes, $columns, $filter, $orderBy);
	}

	/**
	 * Aggregate stats and return data in a format
	 * that can be used to build the statistics JSON response
	 * for the article page.
	 * @param $stats array A _getDownloadStats return value.
	 * @return array
	 */
	function _aggregateDownloadStats($stats) {
		$totalHtml = 0;
		$totalPdf = 0;
		$byMonth = array();
		$byYear = array();

		if (!is_array($stats)) $stats = array();

		foreach ($stats as $record) {
			$views = $record[STATISTICS_METRIC];
			$fileType = $record[STATISTICS_DIMENSION_FILE_TYPE];
			switch($fileType) {
				case STATISTICS_FILE_TYPE_HTML:
					$totalHtml += $views;
					break;
				case STATISTICS_FILE_TYPE_PDF:
					$totalPdf += $views;
					break;
				default:
					// switch is considered a loop for purposes of continue
					continue 2;
			}
			$year = date('Y', strtotime($record[STATISTICS_DIMENSION_MONTH]. '01'));
			$month = date('n', strtotime($record[STATISTICS_DIMENSION_MONTH] . '01'));
			$yearMonth = date('Ym', strtotime($record[STATISTICS_DIMENSION_MONTH] . '01'));

			if (!isset($byYear[$year])) $byYear[$year] = array();
			if (!isset($byYear[$year][$fileType])) $byYear[$year][$fileType] = 0;
			$byYear[$year][$fileType] += $views;

			if (!isset($byMonth[$yearMonth])) $byMonth[$yearMonth] = array();
			if (!isset($byMonth[$yearMonth][$fileType])) $byMonth[$yearMonth][$fileType] = 0;
			$byMonth[$yearMonth][$fileType] += $views;
		}

		return array($totalHtml, $totalPdf, $byMonth, $byYear);
	}

	/**
	 * Get total statistics for JSON response.
	 * @param $totalPdf int
	 * @param $totalHtml int
	 * @return array
	 */
	function _getStatsTotal($totalHtml, $totalPdf) {
		$metrics = array('pdf' => $totalPdf, 'html' => $totalHtml);
		return array_merge($metrics, $this->_getUtplMetricsTemplate());
	}

	/**
	 * Get statistics by time dimension (month or year)
	 * for JSON response.
	 * @param array the download statistics in an array by dimension
	 * @param string month | year
	 */
	function _getStatsByTime($data, $dimension) {
		switch ($dimension) {
			case 'month':
				$isMonthDimension = true;
				break;
			case 'year':
				$isMonthDimension = false;
				break;
			default:
				return null;
		}

		if (count($data)) {
			$byTime = array();
			foreach ($data as $date => $fileTypes) {
				// strtotime sometimes fails on just a year (YYYY) (it treats it as a time (HH:mm))
				// and sometimes on YYYYMM
				// So make sure $date has all 3 parts
				$date = str_pad($date, 8, "01");
				$year = date('Y', strtotime($date));
				if ($isMonthDimension) {
					$month = date('n', strtotime($date));
				}
				$pdfViews = isset($fileTypes[STATISTICS_FILE_TYPE_PDF])? $fileTypes[STATISTICS_FILE_TYPE_PDF] : 0;
				$htmlViews = isset($fileTypes[STATISTICS_FILE_TYPE_HTML])? $fileTypes[STATISTICS_FILE_TYPE_HTML] : 0;

				$partialStats = array(
					'year' => $year,
					'pdf' => $pdfViews,
					'html' => $htmlViews,
					'total' => $pdfViews + $htmlViews
				);

				if ($isMonthDimension) {
					$partialStats['month'] = $month;
				}

				$byTime[] = array_merge($partialStats, $this->_getUtplMetricsTemplate());
			}
		} else {
			$byTime = null;
		}

		return $byTime;
	}

	/**
	 * Get template for altmetric metrics JSON response.
	 * @return array
	 */
	function _getUtplMetricsTemplate() {
		return array(
			'shares' => null,
			'groups' => null,
			'comments' => null,
			'citations' => 0
		);
	}

	/**
	 * Build article stats JSON response based
	 * on parameters returned from _aggregateStats().
	 * @param $totalHtml array
	 * @param $totalPdf array
	 * @param $byMonth array
	 * @param $byYear array
	 * @return string JSON response
	 */
	function _buildDownloadStatsJson($totalHtml, $totalPdf, $byMonth, $byYear) {
		$response = array(
			'name' => 'ojsViews',
			'display_name' => __('plugins.generic.utplMetrics.thisJournal'),
			'events_url' => null,
			'metrics' => $this->_getStatsTotal($totalHtml, $totalPdf),
			'by_day' => null,
			'by_month' => $this->_getStatsByTime($byMonth, 'month'),
			'by_year' => $this->_getStatsByTime($byYear, 'year')
		);

		// Encode the object.
		$jsonManager = new JSONManager();
		return $jsonManager->encode($response);
	}

}