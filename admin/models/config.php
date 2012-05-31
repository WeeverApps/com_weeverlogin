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

jimport('joomla.application.component.model');

class WeeverLoginModelConfig extends JModel
{

	public $json		= null;
	
	public function __construct()
	{
        
        parent::__construct();

		$this->getJsonConfigSync();
        
	}
	
	public function getConfigData()
	{
		
		return $this->json;
	
	}

	public function getJsonConfigSync()
	{
	
		if( WeeverLoginHelper::getStageStatus() )
		{
		
			$weeverServer 	= WeeverLoginConst::LIVE_STAGE;
			$stageUrl 		= WeeverLoginHelper::getSiteDomain();
			
		}
		else
		{
		
			$weeverServer 	= WeeverLoginConst::LIVE_SERVER;
			$stageUrl 		= '';
			
		}
		
		$postdata = WeeverLoginHelper::buildWeeverHttpQuery(
		
			array( 	
			
				'stage' 	=> $stageUrl,
				'app' 		=> 'json',
				'site_key' 	=> WeeverLoginHelper::getKey(),
				'm' 		=> "config_sync"	
							
			)
				
		);
			
		$json 	= WeeverLoginHelper::sendToWeeverServer($postdata);

		if( $json == "Site key missing or invalid." )
		{
		
			 JError::raiseNotice(100, JText::_('WEEVERLOGIN_NOTICE_NO_SITEKEY'));
			 return false;
			 
		}
		
		$this->json = json_decode($json);
	
	}


}