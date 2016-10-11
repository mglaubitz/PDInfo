<?php

/* Copyright (c) 2011 Leifos GmbH */

include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");
 
/**
 * PDInfo configuration user interface class
 *
 * @author Alex Killing <killing@leifos.de>
 *
 * @version $Id$
 */
class ilPDInfoConfigGUI extends ilPluginConfigGUI
{
	/**
	 * Handles all commmands, default is "configure"
	 */
	function performCommand($cmd)
	{
		switch ($cmd)
		{
			default:
				$this->$cmd();
				break;
		}
	}

	/**
	 * Configure screen
	 */
	function configure()
	{
		global $tpl;

		$form = $this->initConfigurationForm();
		$tpl->setContent($form->getHTML());
	}
	
	//
	// From here on, this is just an example implementation using
	// a standard form (without saving anything)
	//
	
	/**
	 * Init configuration form.
	 *
	 * @return object form object
	 */
	public function initConfigurationForm()
	{
		global $ilCtrl;
		
		$pl = $this->getPluginObject();

		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();
	
		// show block?
		$cb = new ilCheckboxInputGUI($pl->txt("show_block"), "show_block");
		$cb->setValue(1);
		$cb->setChecked($this->getConfigValue('show_block', false));
		$form->addItem($cb);
		
		// Info message 
		$pdinfo_message = new ilTextInputGUI($pl->txt("pdinfo_message"),
			"pdinfo_message");
		$pdinfo_message->setRequired(true);
		$pdinfo_message->setMaxLength(3000);
		$pdinfo_message->setSize(60);
		$pdinfo_message->setValue($this->getConfigValue('pdinfo_message'));
		$form->addItem($pdinfo_message);
/*		
		// PDInfo login path
		$pdinfo_login_path = new ilTextInputGUI($pl->txt("pdinfo_login_path"),
			"pdinfo_login_path");
		$pdinfo_login_path->setRequired(true);
		$pdinfo_login_path->setMaxLength(255);
		$pdinfo_login_path->setSize(60);
		$pdinfo_login_path->setValue(
			$this->getConfigValue('pdinfo_login_path'));
		$form->addItem($pdinfo_login_path);
		
		// PDInfo SOAP user
		$pdinfo_soap_user = new ilTextInputGUI($pl->txt("pdinfo_soap_user"),
			"pdinfo_soap_user");
		$pdinfo_soap_user->setRequired(true);
		$pdinfo_soap_user->setMaxLength(255);
		$pdinfo_soap_user->setSize(60);
		$pdinfo_soap_user->setValue($this->getConfigValue('pdinfo_soap_user'));
		$form->addItem($pdinfo_soap_user);
		
		// PDInfo SOAP password
		$pdinfo_soap_password = new ilTextInputGUI(
			$pl->txt("pdinfo_soap_password"), "pdinfo_soap_password");
		$pdinfo_soap_password->setRequired(true);
		$pdinfo_soap_password->setInputType('password');
		$pdinfo_soap_password->setMaxLength(255);
		$pdinfo_soap_password->setSize(60);
		$pdinfo_soap_password->setValue(
			$this->getConfigValue('pdinfo_soap_password'));
		$form->addItem($pdinfo_soap_password);
		
		// Connection timeout in seconds
		$pdinfo_connection_timeout = new ilTextInputGUI(
			$pl->txt("pdinfo_connection_timeout"), "pdinfo_connection_timeout");
		$pdinfo_connection_timeout->setRequired(true);
		$pdinfo_connection_timeout->setMaxLength(255);
		$pdinfo_connection_timeout->setSize(60);
		$pdinfo_connection_timeout->setValue(
			$this->getConfigValue('pdinfo_connection_timeout'));
		$form->addItem($pdinfo_connection_timeout);
		
		// Logging
		$pdinfo_logging = new ilCheckboxInputGUI(
			$pl->txt("pdinfo_logging"), "pdinfo_logging");
		$pdinfo_logging->setValue(1);
		$pdinfo_logging->setChecked($this->getConfigValue('pdinfo_logging', false));
		$form->addItem($pdinfo_logging);
		
		// Log file (absolute path to the current log file)
		$pdinfo_logfile_path = new ilTextInputGUI(
			$pl->txt("pdinfo_logfile_path"), "pdinfo_logfile_path");
		$pdinfo_logfile_path->setRequired(true);
		$pdinfo_logfile_path->setMaxLength(255);
		$pdinfo_logfile_path->setSize(60);
		$pdinfo_logfile_path->setValue(
			$this->getConfigValue('pdinfo_logfile_path'));
		$form->addItem($pdinfo_logfile_path);
*/	
		$form->addCommandButton("save", $pl->txt("save"));
	                
		$form->setTitle($pl->txt("pdinfo_configuration"));
		$form->setFormAction($ilCtrl->getFormAction($this));
		
		return $form;
	}
	
	/**
	 * Save form input (currently does not save anything to db)
	 *
	 */
	public function save()
	{
		global $tpl, $ilCtrl;
	
		$pl = $this->getPluginObject();
		
		$form = $this->initConfigurationForm();
		if ($form->checkInput())
		{
			$pdinfo_message = $form->getInput("pdinfo_message");
/*			$pdinfo_login_path = $form->getInput("pdinfo_login_path");
			$pdinfo_soap_user = $form->getInput("pdinfo_soap_user");
			$pdinfo_soap_password = $form->getInput("pdinfo_soap_password");
			$pdinfo_connection_timeout = $form->getInput("pdinfo_connection_timeout");
			$pdinfo_logging = $form->getInput("pdinfo_logging");
			$pdinfo_logfile_path = $form->getInput("pdinfo_logfile_path");*/
			$sb = $form->getInput("show_block");
			
			$this->storeConfigValue('pdinfo_message', $pdinfo_message);
/*			$this->storeConfigValue('pdinfo_login_path', $pdinfo_login_path);
			$this->storeConfigValue('pdinfo_soap_user', $pdinfo_soap_user);
			$this->storeConfigValue('pdinfo_soap_password', $pdinfo_soap_password);
			$this->storeConfigValue('pdinfo_connection_timeout', $pdinfo_connection_timeout);
			$this->storeConfigValue('pdinfo_logging', $pdinfo_logging);
			$this->storeConfigValue('pdinfo_logfile_path', $pdinfo_logfile_path);*/
			$this->storeConfigValue('show_block', $sb);
			
			ilUtil::sendSuccess($pl->txt("configuration_saved"), true);
			$ilCtrl->redirect($this, "configure");
		}
		else
		{
			$form->setValuesByPost();
			$tpl->setContent($form->getHtml());
		}
	}
	
	protected function storeConfigValue($name, $value)
	{
		global $ilDB;
		
		if($this->getConfigValue($name, false) === false)
			$sql = "INSERT INTO `ui_uihk_pdinfo_config` (`name`,`value`)
					VALUES (
						{$ilDB->quote($name, "text")},
						{$ilDB->quote($value, "text")})";
		else
			$sql = "UPDATE `ui_uihk_pdinfo_config`
					SET `value` = {$ilDB->quote($value, "text")}
					WHERE `name` = {$ilDB->quote($name, "text")}";
		
		return $ilDB->manipulate($sql);
	}
	
	protected function getConfigValue($name, $default = '')
	{
		global $ilDB;
		
		$sql = "SELECT `value` 
				FROM `ui_uihk_pdinfo_config`
				WHERE `name` = {$ilDB->quote($name, "text")}";
		
		$result = $ilDB->query($sql);
		$row = $ilDB->fetchObject($result);
		
		if(!$row)
			return $default;
		else
			return $row->value;
	}
}
?>
