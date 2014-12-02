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

				case 'about/editorialTeamBio.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialTeamBio.tpl';
					break;
					
				case 'about/contact.tpl':
					$template = $this->getTemplatePath() . 'templates/about/contact.tpl';
					break;
					
				case 'about/editorialTeamBoard.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialTeamBoard.tpl';
					break;
					
				case 'about/journalSponsorship.tpl':
					$template = $this->getTemplatePath() . 'templates/about/journalSponsorship.tpl';
					break;
					
				case 'about/siteMap.tpl':
					$template = $this->getTemplatePath() . 'templates/about/siteMap.tpl';
					break;
					
				case 'about/statistics.tpl':
					$template = $this->getTemplatePath() . 'templates/about/statistics.tpl';
					break;
					
				case 'about/history.tpl':
					$template = $this->getTemplatePath() . 'templates/about/history.tpl';
					break;
					
				case 'about/index.tpl':
					$template = $this->getTemplatePath() . 'templates/about/index.tpl';
					break;
					
				case 'about/site.tpl':
					$template = $this->getTemplatePath() . 'templates/about/site.tpl';
					break;
					
				case 'about/memberships.tpl':
					$template = $this->getTemplatePath() . 'templates/about/memberships.tpl';
					break;
					
				case 'about/displayMembership.tpl':
					$template = $this->getTemplatePath() . 'templates/about/displayMembership.tpl';
					break;
					
				case 'about/aboutThisPublishingSystem.tpl':
					$template = $this->getTemplatePath() . 'templates/about/aboutThisPublishingSystem.tpl';
					break;
					
				case 'about/subscriptions.tpl':
					$template = $this->getTemplatePath() . 'templates/about/subscriptions.tpl';
					break;
					
				case 'about/submissions.tpl':
					$template = $this->getTemplatePath() . 'templates/about/submissions.tpl';
					break;
					
				case 'about/editorialPolicies.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialPolicies.tpl';
					break;
					
				case 'about/editorialTeam.tpl':
					$template = $this->getTemplatePath() . 'templates/about/editorialTeam.tpl';
					break;
					
				case 'rt/header.tpl':
					$template = $this->getTemplatePath() . 'templates/rt/header.tpl';
					break;
					
				case 'rt/printerFriendly.tpl':
					$template = $this->getTemplatePath() . 'templates/rt/printerFriendly.tpl';
					break;
					
				case 'rt/email.tpl':
					$template = $this->getTemplatePath() . 'templates/rt/email.tpl';
					break;
					
				case 'common/breadcrumbs.tpl':
					$template = $this->getTemplatePath() . 'templates/common/breadcrumbs.tpl';
					break;
					
				case 'common/header.tpl':
					$template = $this->getTemplatePath() . 'templates/common/header.tpl';
					break;
					
				case 'common/navbar.tpl':
					$template = $this->getTemplatePath() . 'templates/common/navbar.tpl';
					break;
					
				case 'index/journal.tpl':
					$template = $this->getTemplatePath() . 'templates/index/journal.tpl';
					break;
					
				case 'index/site.tpl':
					$template = $this->getTemplatePath() . 'templates/index/site.tpl';
					break;
					
				case 'proofreader/index.tpl':
					$template = $this->getTemplatePath() . 'templates/proofreader/index.tpl';
					break;
					
				case 'proofreader/submission.tpl':
					$template = $this->getTemplatePath() . 'templates/proofreader/submission.tpl';
					break;
					
				case 'sectionEditor/submissionEditing.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionEditing.tpl';
					break;
					
				case 'sectionEditor/submissionHistory.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionHistory.tpl';
					break;
					
				case 'sectionEditor/submissionNotes.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionNotes.tpl';
					break;
					
				case 'sectionEditor/selectUser.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/selectUser.tpl';
					break;
					
				case 'sectionEditor/index.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/index.tpl';
					break;
					
				case 'sectionEditor/submissionRegrets.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionRegrets.tpl';
					break;
					
				case 'sectionEditor/setDueDate.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/setDueDate.tpl';
					break;
					
				case 'sectionEditor/searchUsers.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/searchUsers.tpl';
					break;
					
				case 'sectionEditor/submissionReview.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionReview.tpl';
					break;
					
				case 'sectionEditor/submissionEmailLogEntry.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionEmailLogEntry.tpl';
					break;
					
				case 'sectionEditor/submissionEventLogEntry.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionEventLogEntry.tpl';
					break;
					
				case 'sectionEditor/selectReviewForm.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/selectReviewForm.tpl';
					break;
					
				case 'sectionEditor/submission.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submission.tpl';
					break;
					
				case 'sectionEditor/previewReviewForm.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/previewReviewForm.tpl';
					break;
					
				case 'sectionEditor/createReviewerForm.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/createReviewerForm.tpl';
					break;
					
				case 'sectionEditor/reviewerRecommendation.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/reviewerRecommendation.tpl';
					break;
					
				case 'sectionEditor/selectReviewer.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/selectReviewer.tpl';
					break;
					
				case 'sectionEditor/submissionEventLog.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionEventLog.tpl';
					break;
					
				case 'sectionEditor/submissionCitations.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionCitations.tpl';
					break;
					
				case 'sectionEditor/submissionEmailLog.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/submissionEmailLog.tpl';
					break;
					
				case 'sectionEditor/userProfile.tpl':
					$template = $this->getTemplatePath() . 'templates/sectionEditor/userProfile.tpl';
					break;
					
				case 'gateway/lockss.tpl':
					$template = $this->getTemplatePath() . 'templates/gateway/lockss.tpl';
					break;
					
				case 'information/information.tpl':
					$template = $this->getTemplatePath() . 'templates/information/information.tpl';
					break;
					
				case 'manager/index.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/index.tpl';
					break;
					
				case 'manager/setup/settingsSaved.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/settingsSaved.tpl';
					break;
					
				case 'manager/setup/index.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/index.tpl';
					break;
					
				case 'manager/setup/setupHeader.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/setupHeader.tpl';
					break;
					
				case 'manager/setup/step4.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/step4.tpl';
					break;
					
				case 'manager/setup/step3.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/step3.tpl';
					break;
					
				case 'manager/setup/step1.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/step1.tpl';
					break;
					
				case 'manager/setup/step2.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/step2.tpl';
					break;
					
				case 'manager/setup/step5.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/setup/step5.tpl';
					break;
					
				case 'manager/files/index.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/files/index.tpl';
					break;
					
				case 'manager/groups/groups.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/groups/groups.tpl';
					break;
					
				case 'manager/groups/selectUser.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/groups/selectUser.tpl';
					break;
					
				case 'manager/groups/groupForm.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/groups/groupForm.tpl';
					break;
					
				case 'manager/groups/memberships.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/groups/memberships.tpl';
					break;
					
				case 'manager/reviewForms/reviewFormElementForm.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/reviewForms/reviewFormElementForm.tpl';
					break;
					
				case 'manager/reviewForms/reviewFormForm.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/reviewForms/reviewFormForm.tpl';
					break;
					
				case 'manager/reviewForms/reviewForms.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/reviewForms/reviewForms.tpl';
					break;
					
				case 'manager/reviewForms/reviewFormElements.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/reviewForms/reviewFormElements.tpl';
					break;
					
				case 'manager/reviewForms/previewReviewForm.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/reviewForms/previewReviewForm.tpl';
					break;
					
				case 'manager/languageSettings.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/languageSettings.tpl';
					break;
					
				case 'manager/people/searchUsers.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/people/searchUsers.tpl';
					break;
					
				case 'manager/people/enrollSync.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/people/enrollSync.tpl';
					break;
					
				case 'manager/people/userProfileForm.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/people/userProfileForm.tpl';
					break;
					
				case 'manager/people/enrollment.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/people/enrollment.tpl';
					break;
					
				case 'manager/people/email.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/people/email.tpl';
					break;
					
				case 'manager/people/selectMergeUser.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/people/selectMergeUser.tpl';
					break;
					
				case 'manager/people/userProfile.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/people/userProfile.tpl';
					break;
					
				case 'manager/statistics/reportGenerator.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/statistics/reportGenerator.tpl';
					break;
					
				case 'manager/statistics/index.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/statistics/index.tpl';
					break;
					
				case 'manager/emails/emailTemplateForm.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/emails/emailTemplateForm.tpl';
					break;
					
				case 'manager/emails/emails.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/emails/emails.tpl';
					break;
					
				case 'manager/sections/sections.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/sections/sections.tpl';
					break;
					
				case 'manager/sections/sectionForm.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/sections/sectionForm.tpl';
					break;
					
				case 'manager/importexport/plugins.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/importexport/plugins.tpl';
					break;
					
				case 'manager/plugins/managePlugins.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/plugins/managePlugins.tpl';
					break;
					
				case 'manager/plugins/plugins.tpl':
					$template = $this->getTemplatePath() . 'templates/manager/plugins/plugins.tpl';
					break;
					
				case 'author/submissionEditing.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submissionEditing.tpl';
					break;
					
				case 'author/completed.tpl':
					$template = $this->getTemplatePath() . 'templates/author/completed.tpl';
					break;
					
				case 'author/index.tpl':
					$template = $this->getTemplatePath() . 'templates/author/index.tpl';
					break;
					
				case 'author/submit/suppFile.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/suppFile.tpl';
					break;
					
				case 'author/submit/authorFees.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/authorFees.tpl';
					break;
					
				case 'author/submit/submitHeader.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/submitHeader.tpl';
					break;
					
				case 'author/submit/step4.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/step4.tpl';
					break;
					
				case 'author/submit/step3.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/step3.tpl';
					break;
					
				case 'author/submit/step1.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/step1.tpl';
					break;
					
				case 'author/submit/step2.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/step2.tpl';
					break;
					
				case 'author/submit/step5.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/step5.tpl';
					break;
					
				case 'author/submit/complete.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submit/complete.tpl';
					break;
					
				case 'author/submissionReview.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submissionReview.tpl';
					break;
					
				case 'author/submission.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission.tpl';
					break;
					
				case 'author/submission/status.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/status.tpl';
					break;
					
				case 'author/submission/management.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/management.tpl';
					break;
					
				case 'author/submission/proofread.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/proofread.tpl';
					break;
					
				case 'author/submission/summary.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/summary.tpl';
					break;
					
				case 'author/submission/authorFees.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/authorFees.tpl';
					break;
					
				case 'author/submission/editorDecision.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/editorDecision.tpl';
					break;
					
				case 'author/submission/peerReview.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/peerReview.tpl';
					break;
					
				case 'author/submission/copyedit.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/copyedit.tpl';
					break;
					
				case 'author/submission/layout.tpl':
					$template = $this->getTemplatePath() . 'templates/author/submission/layout.tpl';
					break;
					
				case 'author/active.tpl':
					$template = $this->getTemplatePath() . 'templates/author/active.tpl';
					break;
					
				case 'user/index.tpl':
					$template = $this->getTemplatePath() . 'templates/user/index.tpl';
					break;
					
				case 'user/register.tpl':
					$template = $this->getTemplatePath() . 'templates/user/register.tpl';
					break;
					
				case 'user/gifts.tpl':
					$template = $this->getTemplatePath() . 'templates/user/gifts.tpl';
					break;
					
				case 'user/login.tpl':
					$template = $this->getTemplatePath() . 'templates/user/login.tpl';
					break;
					
				case 'user/publicProfile.tpl':
					$template = $this->getTemplatePath() . 'templates/user/publicProfile.tpl';
					break;
					
				case 'user/registerSite.tpl':
					$template = $this->getTemplatePath() . 'templates/user/registerSite.tpl';
					break;
					
				case 'user/loginChangePassword.tpl':
					$template = $this->getTemplatePath() . 'templates/user/loginChangePassword.tpl';
					break;
					
				case 'user/subscriptions.tpl':
					$template = $this->getTemplatePath() . 'templates/user/subscriptions.tpl';
					break;
					
				case 'user/lostPassword.tpl':
					$template = $this->getTemplatePath() . 'templates/user/lostPassword.tpl';
					break;
					
				case 'user/changePassword.tpl':
					$template = $this->getTemplatePath() . 'templates/user/changePassword.tpl';
					break;
					
				case 'user/profile.tpl':
					$template = $this->getTemplatePath() . 'templates/user/profile.tpl';
					break;
					
				case 'rtadmin/journals.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/journals.tpl';
					break;
					
				case 'rtadmin/search.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/search.tpl';
					break;
					
				case 'rtadmin/version.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/version.tpl';
					break;
					
				case 'rtadmin/contexts.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/contexts.tpl';
					break;
					
				case 'rtadmin/versions.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/versions.tpl';
					break;
					
				case 'rtadmin/index.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/index.tpl';
					break;
					
				case 'rtadmin/searches.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/searches.tpl';
					break;
					
				case 'rtadmin/context.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/context.tpl';
					break;
					
				case 'rtadmin/validate.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/validate.tpl';
					break;
					
				case 'rtadmin/settings.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/settings.tpl';
					break;
					
				case 'rtadmin/addthis.tpl':
					$template = $this->getTemplatePath() . 'templates/rtadmin/addthis.tpl';
					break;
					
				case 'notification/maillistSubscribed.tpl':
					$template = $this->getTemplatePath() . 'templates/notification/maillistSubscribed.tpl';
					break;
					
				case 'notification/maillist.tpl':
					$template = $this->getTemplatePath() . 'templates/notification/maillist.tpl';
					break;
					
				case 'notification/settings.tpl':
					$template = $this->getTemplatePath() . 'templates/notification/settings.tpl';
					break;
					
				case 'search/authorDetails.tpl':
					$template = $this->getTemplatePath() . 'templates/search/authorDetails.tpl';
					break;
					
				case 'search/search.tpl':
					$template = $this->getTemplatePath() . 'templates/search/search.tpl';
					break;
					
				case 'search/authorIndex.tpl':
					$template = $this->getTemplatePath() . 'templates/search/authorIndex.tpl';
					break;
					
				case 'search/categories.tpl':
					$template = $this->getTemplatePath() . 'templates/search/categories.tpl';
					break;
					
				case 'search/titleIndex.tpl':
					$template = $this->getTemplatePath() . 'templates/search/titleIndex.tpl';
					break;
					
				case 'search/category.tpl':
					$template = $this->getTemplatePath() . 'templates/search/category.tpl';
					break;
					
				case 'payments/payMethodSettingsForm.tpl':
					$template = $this->getTemplatePath() . 'templates/payments/payMethodSettingsForm.tpl';
					break;
					
				case 'payments/paymentSettings.tpl':
					$template = $this->getTemplatePath() . 'templates/payments/paymentSettings.tpl';
					break;
					
				case 'payments/viewPayment.tpl':
					$template = $this->getTemplatePath() . 'templates/payments/viewPayment.tpl';
					break;
					
				case 'payments/viewPayments.tpl':
					$template = $this->getTemplatePath() . 'templates/payments/viewPayments.tpl';
					break;
					
				case 'submission/metadata/metadataView.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/metadata/metadataView.tpl';
					break;
					
				case 'submission/metadata/metadataEdit.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/metadata/metadataEdit.tpl';
					break;
					
				case 'submission/instructions.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/instructions.tpl';
					break;
					
				case 'submission/layout/proofGalleyTop.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/layout/proofGalleyTop.tpl';
					break;
					
				case 'submission/layout/proofGalleyHTML.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/layout/proofGalleyHTML.tpl';
					break;
					
				case 'submission/layout/proofGalley.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/layout/proofGalley.tpl';
					break;
					
				case 'submission/layout/galleyView.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/layout/galleyView.tpl';
					break;
					
				case 'submission/layout/galleyForm.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/layout/galleyForm.tpl';
					break;
					
				case 'submission/comment/comment.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/comment/comment.tpl';
					break;
					
				case 'submission/comment/editComment.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/comment/editComment.tpl';
					break;
					
				case 'submission/comment/header.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/comment/header.tpl';
					break;
					
				case 'submission/comment/editorDecisionComment.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/comment/editorDecisionComment.tpl';
					break;
					
				case 'submission/comment/editorDecisionEmail.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/comment/editorDecisionEmail.tpl';
					break;
					
				case 'submission/comment/peerReviewComment.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/comment/peerReviewComment.tpl';
					break;
					
				case 'submission/suppFile/suppFile.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/suppFile/suppFile.tpl';
					break;
					
				case 'submission/suppFile/suppFileView.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/suppFile/suppFileView.tpl';
					break;
					
				case 'submission/reviewForm/reviewFormResponse.tpl':
					$template = $this->getTemplatePath() . 'templates/submission/reviewForm/reviewFormResponse.tpl';
					break;
					
				case 'copyeditor/index.tpl':
					$template = $this->getTemplatePath() . 'templates/copyeditor/index.tpl';
					break;
					
				case 'copyeditor/submission.tpl':
					$template = $this->getTemplatePath() . 'templates/copyeditor/submission.tpl';
					break;
					
				case 'copyeditor/submissionCitations.tpl':
					$template = $this->getTemplatePath() . 'templates/copyeditor/submissionCitations.tpl';
					break;
					
				case 'layoutEditor/futureIssues.tpl':
					$template = $this->getTemplatePath() . 'templates/layoutEditor/futureIssues.tpl';
					break;
					
				case 'layoutEditor/index.tpl':
					$template = $this->getTemplatePath() . 'templates/layoutEditor/index.tpl';
					break;
					
				case 'layoutEditor/submission.tpl':
					$template = $this->getTemplatePath() . 'templates/layoutEditor/submission.tpl';
					break;
					
				case 'layoutEditor/backIssues.tpl':
					$template = $this->getTemplatePath() . 'templates/layoutEditor/backIssues.tpl';
					break;
					
				case 'layoutEditor/submissions.tpl':
					$template = $this->getTemplatePath() . 'templates/layoutEditor/submissions.tpl';
					break;
					
				case 'comment/comment.tpl':
					$template = $this->getTemplatePath() . 'templates/comment/comment.tpl';
					break;
					
				case 'comment/comments.tpl':
					$template = $this->getTemplatePath() . 'templates/comment/comments.tpl';
					break;
					
				case 'help/view.tpl':
					$template = $this->getTemplatePath() . 'templates/help/view.tpl';
					break;
					
				case 'help/header.tpl':
					$template = $this->getTemplatePath() . 'templates/help/header.tpl';
					break;
					
				case 'help/searchResults.tpl':
					$template = $this->getTemplatePath() . 'templates/help/searchResults.tpl';
					break;
					
				case 'help/helpToc.tpl':
					$template = $this->getTemplatePath() . 'templates/help/helpToc.tpl';
					break;
					
				case 'reviewer/index.tpl':
					$template = $this->getTemplatePath() . 'templates/reviewer/index.tpl';
					break;
					
				case 'reviewer/submission.tpl':
					$template = $this->getTemplatePath() . 'templates/reviewer/submission.tpl';
					break;
					
				case 'admin/journals.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/journals.tpl';
					break;
					
				case 'admin/categories/categoryForm.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/categories/categoryForm.tpl';
					break;
					
				case 'admin/categories/categories.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/categories/categories.tpl';
					break;
					
				case 'admin/languages.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/languages.tpl';
					break;
					
				case 'admin/auth/sourceSettings.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/auth/sourceSettings.tpl';
					break;
					
				case 'admin/auth/sources.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/auth/sources.tpl';
					break;
					
				case 'admin/index.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/index.tpl';
					break;
					
				case 'admin/journalSettings.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/journalSettings.tpl';
					break;
					
				case 'admin/systemInfo.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/systemInfo.tpl';
					break;
					
				case 'admin/languageDownloadErrors.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/languageDownloadErrors.tpl';
					break;
					
				case 'admin/settings.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/settings.tpl';
					break;
					
				case 'admin/selectMergeUser.tpl':
					$template = $this->getTemplatePath() . 'templates/admin/selectMergeUser.tpl';
					break;
					
				case 'issue/header.tpl':
					$template = $this->getTemplatePath() . 'templates/issue/header.tpl';
					break;
					
				case 'issue/interstitial.tpl':
					$template = $this->getTemplatePath() . 'templates/issue/interstitial.tpl';
					break;
					
				case 'issue/viewPage.tpl':
					$template = $this->getTemplatePath() . 'templates/issue/viewPage.tpl';
					break;
					
				case 'issue/archive.tpl':
					$template = $this->getTemplatePath() . 'templates/issue/archive.tpl';
					break;
					
				case 'issue/issueGalley.tpl':
					$template = $this->getTemplatePath() . 'templates/issue/issueGalley.tpl';
					break;
					
				case 'article/interstitial.tpl':
					$template = $this->getTemplatePath() . 'templates/article/interstitial.tpl';
					break;
					
				case 'article/footer.tpl':
					$template = $this->getTemplatePath() . 'templates/article/footer.tpl';
					break;
					
				case 'article/comments.tpl':
					$template = $this->getTemplatePath() . 'templates/article/comments.tpl';
					break;
					
				case 'editor/issues/futureIssues.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/futureIssues.tpl';
					break;
					
				case 'editor/issues/proofIssueGalleyTop.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/proofIssueGalleyTop.tpl';
					break;
					
				case 'editor/issues/issueGalleys.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/issueGalleys.tpl';
					break;
					
				case 'editor/issues/issueToc.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/issueToc.tpl';
					break;
					
				case 'editor/issues/createIssue.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/createIssue.tpl';
					break;
					
				case 'editor/issues/issueGalleyForm.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/issueGalleyForm.tpl';
					break;
					
				case 'editor/issues/backIssues.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/backIssues.tpl';
					break;
					
				case 'editor/issues/issueData.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/issueData.tpl';
					break;
					
				case 'editor/issues/proofIssueGalley.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/issues/proofIssueGalley.tpl';
					break;
					
				case 'editor/index.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/index.tpl';
					break;
					
				case 'editor/selectSectionEditor.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/selectSectionEditor.tpl';
					break;
					
				case 'editor/submissions.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/submissions.tpl';
					break;
					
				case 'editor/notifyUsers.tpl':
					$template = $this->getTemplatePath() . 'templates/editor/notifyUsers.tpl';
					break;
					
				case 'subscription/openAccessNotifyEmail.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/openAccessNotifyEmail.tpl';
					break;
					
				case 'subscription/subscriptionTypeForm.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/subscriptionTypeForm.tpl';
					break;
					
				case 'subscription/individualSubscriptionForm.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/individualSubscriptionForm.tpl';
					break;
					
				case 'subscription/institutionalSubscriptions.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/institutionalSubscriptions.tpl';
					break;
					
				case 'subscription/subscriptionTypes.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/subscriptionTypes.tpl';
					break;
					
				case 'subscription/giftIndividualSubscriptionForm.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/giftIndividualSubscriptionForm.tpl';
					break;
					
				case 'subscription/institutionalSubscriptionForm.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/institutionalSubscriptionForm.tpl';
					break;
					
				case 'subscription/individualSubscriptions.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/individualSubscriptions.tpl';
					break;
					
				case 'subscription/userIndividualSubscriptionForm.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/userIndividualSubscriptionForm.tpl';
					break;
					
				case 'subscription/subscriptionsSummary.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/subscriptionsSummary.tpl';
					break;
					
				case 'subscription/subscriptionPolicyForm.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/subscriptionPolicyForm.tpl';
					break;
					
				case 'subscription/userInstitutionalSubscriptionForm.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/userInstitutionalSubscriptionForm.tpl';
					break;
					
				case 'subscription/users.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/users.tpl';
					break;
					
				case 'subscription/userProfile.tpl':
					$template = $this->getTemplatePath() . 'templates/subscription/userProfile.tpl';
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