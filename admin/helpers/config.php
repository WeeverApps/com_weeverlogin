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

jimport('joomla.application.component.helper');
jimport('joomla.plugin.helper');


final class WeeverLoginConst
{

	const VERSION			= "0.2";
	const RELEASE_TYPE		= "beta";
	const RELEASE_NAME		= "<a href='http://en.wikipedia.org/wiki/Antonin_Artaud' target='_blank'>Artaud</a>";
	const NAME				= "Weever Apps Login Component for Joomla!";
	const COPYRIGHT_YEAR	= "(c) 2010-2012";
	const COPYRIGHT			= "Weever Apps Inc.";
	const COPYRIGHT_URL		= "http://www.weeverapps.com/";
	const LICENSE			= "GPL v3.0";
	const LICENSE_URL		= "http://www.gnu.org/licenses/gpl-3.0.html";
	const RELEASE_DATE		= "May 31, 2012";
	const SUPPORT_WEB		= "http://support.weeverapps.com/";
	const LIVE_SERVER		= "http://weeverapp.com/";
	const LIVE_STAGE		= "http://stage.weeverapp.com/";
	const API_VERSION		= "api/v1/";
	const SUPPORTED_TYPES	= "-blog--calendar--component--contact--form--listingcomponent--page--photo--social--video--panel--aboutapp--map--directory--proximity-";

}


class WeeverLoginHelperJS
{

	public static function loadConfJS($staging = null)
	{
	
		
		$document = &JFactory::getDocument();
		
		if($staging)
			$server = WeeverLoginConst::LIVE_STAGE;
		else 
			$server = WeeverLoginConst::LIVE_SERVER;
		
		$document->addCustomTag (
			'<script type="text/javascript">
			
			if (typeof(Joomla) === "undefined") {
				var Joomla = {};
			}
			
			Joomla.WeeverLoginConst = {
				VERSION: "'.WeeverLoginConst::VERSION.'",
				RELEASE_TYPE: "'.WeeverLoginConst::RELEASE_TYPE.'",
				RELEASE_NAME: "'.WeeverLoginConst::RELEASE_NAME.'",
				NAME: "'.WeeverLoginConst::NAME.'",
				COPYRIGHT_YEAR: "'.WeeverLoginConst::COPYRIGHT_YEAR.'",
				COPYRIGHT: "'.WeeverLoginConst::COPYRIGHT.'",
				COPYRIGHT_URL: "'.WeeverLoginConst::COPYRIGHT_URL.'",
				LICENSE: "'.WeeverLoginConst::LICENSE.'",
				LICENSE_URL: "'.WeeverLoginConst::LICENSE_URL.'",
				RELEASE_DATE: "'.WeeverLoginConst::RELEASE_DATE.'",
				SUPPORT_WEB: "'.WeeverLoginConst::SUPPORT_WEB.'",
				LIVE_SERVER: "'.WeeverLoginConst::LIVE_SERVER.'",
				LIVE_STAGE: "'.WeeverLoginConst::LIVE_STAGE.'",
				server: "'.$server.'"
			};
			
			</script>');
		
	
	}

}