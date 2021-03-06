<?php

import('lib.pkp.classes.form.Form');

class SettingsForm extends Form {

	/** @var $journalId int */
	var $journalId;

	/** @var $plugin object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 * @param $journalId int
	 */
	function SettingsForm(&$plugin, $journalId) {
		$this->journalId = $journalId;
		$this->plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$journalId = $this->journalId;
		$plugin =& $this->plugin;

		$scopusKey = $plugin->getSetting($journalId, 'scopusKey');
		$altmetricKey = $plugin->getSetting($journalId, 'altmetricKey');

		if(is_null($scopusKey)){
			$scopusKey = '';
		}
		if(is_null($scopusKey)){
			$altmetricKey ='' ;
		}



		$this->setData('scopusKey', $scopusKey);
		$this->setData('altmetricKey', $altmetricKey);

	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('scopusKey', 'altmetricKey'));
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$plugin =& $this->plugin;
		$journalId = $this->journalId;

		$plugin->updateSetting($journalId, 'scopusKey', $this->getData('scopusKey'));
		$plugin->updateSetting($journalId, 'altmetricKey', $this->getData('altmetricKey'));

	}
}

?>
