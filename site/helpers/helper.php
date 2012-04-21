<?php
/*	
*	Weever Apps Login Component for Joomla
*	(c) 2012 Weever Apps Inc. <http://www.weeverapps.com/>
*
*	Author: 	Robert Gerald Porter <rob@weeverapps.com>
*	Version: 	0.1
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


class WeeverLoginHelper {

	static function getWeeverSettingsDB()
	{
	
		$db = &JFactory::getDBO();
			
		$query = 	"	SELECT	* ".
					"	FROM	#__weever_config ";
				
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		return $result;
	
	}
	
	static function getPrimaryDomain($result)
	{
	
		foreach((array)$result as $k=>$v)
		{
			if($v->option == "primary_domain")
				return $v->setting;
		}
		
		return null;
	
	}
	
	static function getDevices($result)
	{
	
		foreach((array)$result as $k=>$v)
		{
			if($v->option == "devices")
				return $v->setting;
		}
		
		return null;
	
	}
	
	static function getAppEnabled($result)
	{
	
		foreach((array)$result as $k=>$v)
		{
			if($v->option == "app_enabled")
				return $v->setting;
		}
	
		return null;
	}
	
	static function getCustomAppDomain($result)
	{
	
		foreach((array)$result as $k=>$v)
		{
			if($v->option == "domain")
				return $v->setting;
		}
	
		return null;
	}

}