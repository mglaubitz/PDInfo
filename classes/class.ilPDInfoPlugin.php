<?php

/* Copyright (c) 2011 Leifos GmbH */

include_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");
 
/**
 * PDInfo plugin. This class only returns the internal plugin name.
 * It must correspond to the directory the plugin is located.
 *
 * @author Alex Killing <killing@leifos.de>
 *
 * @version $Id$
 */
class ilPDInfoPlugin extends ilUserInterfaceHookPlugin
{
	function getPluginName()
	{
		return "PDInfo";
	}
	protected function uninstallCustom()
	{
		// TODO: Implement uninstallCustom() method.
	}
	

}

?>
