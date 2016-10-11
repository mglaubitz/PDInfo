<?php

/* Copyright (c) 2011 Leifos GmbH  */

// error_reporting(E_ALL);

include_once("./Services/UIComponent/classes/class.ilUIHookPluginGUI.php");

/**
 * User interface hook class.
 *
 * This class hooks into the user interface of ILIAS. The main goal is
 * to display a new block on top of the left column on the personal desktop.
 *
 * @author Alex Killing <killing@leifos.de>
 *
 * @version $Id$
 */
class ilPDInfoUIHookGUI extends ilUIHookPluginGUI
{
	private $oPDInfoLogger = NULL;
	/**
	 * Get html for a user interface area
	 *
	 * @param
	 * @return
	 */
	 
	function getHTML($a_comp, $a_part, $a_par = array())
	{
		// if we are on the personal desktop and the left column is rendered
		if ($a_comp == "Services/PersonalDesktop" && $a_part == "right_column")
		{
			// prepend the HTML of the PDInfo block
			return array("mode" => ilUIHookPluginGUI::PREPEND,
				"html" => $this->getBlockHTML());
		}

		// in all other cases, keep everything as it is
		return array("mode" => ilUIHookPluginGUI::KEEP, "html" => "");
	}
	
	/**
	 * Get PDInfo block html
	 *
	 * @return string HTML of pdinfo block
	 */
	function getBlockHTML()
	{
		$ilPDInfoPlugin = new ilPDInfoPlugin();
//		$ilPDInfoPlugin->includeClass("class.ilPDInfoLogger.php");
//		$ilPDInfoPlugin->includeClass("class.ilPDInfoSOAPConnector.php");
		
		global $ilUser;
		$pl = $this->getPluginObject();
		
		if (!$this->getConfigValue('show_block'))
		{
			return;
		}
/*		define('PDINFO_DEBUG_MODE', $this->getConfigValue('pdinfo_logging'));
		
		// Initialize PDInfo logger
		if (PDINFO_DEBUG_MODE)
		{
			$this->oPDInfoLogger = new ilPDInfoLogger($this->getConfigValue('pdinfo_logfile_path'));
		}
		else 
		{
			$this->oPDInfoLogger = new ilPDInfoLogger(NULL);
		}
		
		// Retrieve PDInfo Surveys
		if(empty($_SESSION['pdinfo_survey_links']) || PDINFO_DEBUG_MODE)
		{	
			$sEmailAddress = $ilUser->getEmail();
			
			if (!empty($sEmailAddress))
			{
				$this->oPDInfoLogger->logMsg(
					"Retrieving survey links for '". $sEmailAddress . "'");
				$_SESSION['pdinfo_survey_links'] = 
					$this->getSurveysForEmailAddress($sEmailAddress, $pl);
				// $this->oPDInfoLogger->logMsg(print_r($_SESSION['pdinfo_survey_links'], true));
			}
			else
			{
				$this->oPDInfoLogger->logMsg($pl->txt("pdinfo_NO_ILIAS_EMAIL_FOUND"));
				$_SESSION['pdinfo_survey_links'] = $pl->txt("pdinfo_NOT_CONNECTED");
			}
		}
*/	
		$btpl = $pl->getTemplate("tpl.pdinfo_block.html");
		
		// output title
		$btpl->setVariable("TITLE", $pl->txt("pdinfo_title"));

		// custom variable
		// 23.01.2014, martin.ullrich@rz.uni-freiburg.de
		$btpl->setVariable("PDINFO_MESSAGE", $this->getConfigValue('pdinfo_message'));
		
/*		// output user data
		$btpl->setVariable("USER_LAST", $ilUser->getLastname());
		$btpl->setVariable("USER_FIRST", $ilUser->getFirstname());
		
		$btpl->setVariable("USER_ID", $ilUser->getId());
		$btpl->setVariable("USER_LOGIN", $ilUser->getLogin());
		
		$btpl->setVariable("USER_EMAIL", $ilUser->getEmail());
		$btpl->setVariable("USER_PDINFO_SURVEYS", $_SESSION['pdinfo_survey_links']);
		
		// TODO: REMOVE THIS
	//	$this->oPDInfoLogger->logMsg(print_r(get_class_methods($ilUser), true));
	//	$this->oPDInfoLogger->logMsg(print_r($ilUser), true));
*/
		
		return $btpl->get();
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
