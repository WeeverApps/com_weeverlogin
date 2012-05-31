<?php
/*	
*	Weever Apps Login Component for Joomla
*	(c) 2012 Weever Apps Inc. <http://www.weeverapps.com/>
*
*	Author: 	Robert Gerald Porter <rob@weeverapps.com>
*	Version: 	0.2
*   License: 	GPL v3.0
*
*   This extension is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   This extension is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details <http://www.gnu.org/licenses/>.
*
*/

defined('_JEXEC') or die;

jimport('joomla.installer.installer');

$lang 	= &JFactory::getLanguage();

$lang->load("com_weever");

/* Detect plg_weever_cors */

$db 	= & JFactory::getDBO();
$query 	= "SELECT `id` FROM `#__plugins` WHERE element=".$db->Quote('weever_cors')." AND folder=".$db->Quote('system');

$db->setQuery($query);
$check 	= $db->loadResultArray();

if(count($check) > 0)
{	

	$pluginInstallText 		= JText::_("WEEVER_UPDATING_PLUGIN");
	$templateInstallText 	= JText::_("WEEVER_UPDATING_TEMPLATE");
	
}
else 
{

	$pluginInstallText 		= JText::_("WEEVER_INSTALLING_PLUGIN");
	$templateInstallText 	= JText::_("WEEVER_INSTALLING_TEMPLATE");

}

/* Install plg_weever_cors plugin */

$installer 			= new JInstaller;
$src 				= $this->parent->getPath('source');
$path 				= $src.DS.'plugins'.DS.'system'.DS.'weever_cors';
$result 			= $installer->install($path);

if($result)
	$message 	= "<span style='color:green'>" . JText::_("WEEVER_SUCCESS") . "</span>";
else
	$message 	= "<span style='color:red'>" . JText::_("WEEVER_FAILED") . "</span>";
	
echo "<p>" . $pluginInstallText . "system/weever_cors: <b>" . $message . "</b></p>";

$query 			= "UPDATE #__plugins SET published='1' WHERE element='weever_cors' AND folder='system'";

$db->setQuery($query);
$db->query();

echo "<p><i>".JText::_("WEEVER_ENABLED_PLUGIN")."Weever Cross Origin Resource Sharing (CORS) Connector</i></p>";




/* Detect plg_weever_authenticate */

$db 	= & JFactory::getDBO();
$query 	= "SELECT `id` FROM `#__plugins` WHERE element=".$db->Quote('weever_authenticate')." AND folder=".$db->Quote('user');

$db->setQuery($query);
$check 	= $db->loadResultArray();

if(count($check) > 0)
{	

	$pluginInstallText 		= JText::_("WEEVER_UPDATING_PLUGIN");
	$templateInstallText 	= JText::_("WEEVER_UPDATING_TEMPLATE");
	
}
else 
{

	$pluginInstallText 		= JText::_("WEEVER_INSTALLING_PLUGIN");
	$templateInstallText 	= JText::_("WEEVER_INSTALLING_TEMPLATE");

}

/* Install plg_weever_cors plugin */

$installer 			= new JInstaller;
$src 				= $this->parent->getPath('source');
$path 				= $src.DS.'plugins'.DS.'user'.DS.'weever_authenticate';
$result 			= $installer->install($path);

if($result)
	$message 	= "<span style='color:green'>" . JText::_("WEEVER_SUCCESS") . "</span>";
else
	$message 	= "<span style='color:red'>" . JText::_("WEEVER_FAILED") . "</span>";
	
echo "<p>" . $pluginInstallText . "user/weever_authenticate: <b>" . $message . "</b></p>";

$query 			= "UPDATE #__plugins SET published='1' WHERE element='weever_authenticate' AND folder='user'";

$db->setQuery($query);
$db->query();

echo "<p><i>".JText::_("WEEVER_ENABLED_PLUGIN")."Weever AJAX Authenticator for Joomla</i></p>";

