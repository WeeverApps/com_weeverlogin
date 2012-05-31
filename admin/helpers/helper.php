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


class WeeverLoginHelper {


	public static function joomlaVersion() 
	{
	
		$version 	= new JVersion;
		$joomla 	= $version->getShortVersion();
		$joomla 	= substr($joomla,0,3);
		
		return $joomla;
	
	}
	

	public static function getSetting($id)
	{
	
		$row =& JTable::getInstance('WeeverConfig', 'Table');
		$row->load($id);
		
		return $row->setting;
	
	}
	
	
	public static function setSetting($id, $arg)
	{
	
		$row =& JTable::getInstance('WeeverConfig', 'Table');
		$row->load($id);
		
		$row->setting = $arg;
		
		$row->store();
		
		return true;
	
	}


	public static function getSiteDomain()
	{
	
		$siteDomain = JURI::base();
		$siteDomain = str_replace("http://","",$siteDomain);
		$siteDomain = str_replace("administrator/","",$siteDomain);
		$siteDomain = rtrim($siteDomain, "/");
		
		return $siteDomain;
	
	}
	
	
	public static function getKey() 			{ return self::getSetting(3); }	
	public static function getDeviceSettings() 	{ return self::getSetting(5); }
	public static function getAppStatus() 		{ return self::getSetting(6); }
	public static function getStageStatus()		{ return self::getSetting(7); }
	public static function getLoginSetting()	{ return self::getSetting(13); }
	public static function getPrimaryDomain()	{ return self::getSetting(4); }
	public static function getCustomAppDomain()	{ return self::getSetting(10); }
	public static function getLoginCssUrl()		{ return self::getSetting(14); }
	
	public static function setLoginCssUrl($arg)			{ return self::setSetting(14, $arg); }
	
	
	public static function buildAjaxQuery($query)
	{
	
		$postdata = self::buildWeeverHttpQuery($query, true);
		
		return WeeverLoginHelper::sendToWeeverServer($postdata);
	
	}
	
	
	public static function buildWeeverHttpQuery($array, $ajax = false)
	{
	
		$array['version'] 	= WeeverLoginConst::VERSION;
		$array['generator'] = WeeverLoginConst::NAME;
		$array['cms'] 		= 'joomla';
		
		if($ajax == true)
		{
		
			$array['app']		= 'ajax';
			$array['site_key'] 	= self::getKey();
		
		}
		
		return http_build_query($array);	
	
	}
	
	
	public static function sendToWeeverServerCurl($context)
	{

		if(self::getStageStatus())
			$weeverServer = WeeverLoginConst::LIVE_STAGE;
		else
			$weeverServer = WeeverLoginConst::LIVE_SERVER;
			
		$url = $weeverServer.WeeverLoginConst::API_VERSION;
		
		$ch = curl_init($url);
		
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$context);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

		$response = curl_exec($ch);
		$error = curl_error($ch);

		curl_close($ch);
        
        if ($error != "")
        {
            $result = $error;
            return $result;
        }
       
        $result = $response;
		
		return $result;

	}
	
	
	public static function sendToWeeverServer($postdata)
	{

		
		if(in_array('curl', get_loaded_extensions()))
		{
		
			$context 	= $postdata;
			$response 	= WeeverLoginHelper::sendToWeeverServerCurl($context);
			
		}
		elseif(ini_get('allow_url_fopen') == 1)
		{
		
			$context 	= WeeverLoginHelper::buildPostDataContext($postdata);
			$response 	= WeeverLoginHelper::sendToWeeverServerFOpen($context);
			
		}
		
		else 
			$response = JText::_('WEEVERLOGIN_ERROR_NO_CURL_OR_FOPEN');

		return $response;
	
	}
	
	
	public static function sendToWeeverServerFOpen($context)
	{
		
		if(self::getStageStatus())
			$weeverServer = WeeverLoginConst::LIVE_STAGE;
			
		else
			$weeverServer = WeeverLoginConst::LIVE_SERVER;
			
		$url 		= $weeverServer.WeeverLoginConst::API_VERSION;
		$response 	= file_get_contents($url, false, $context);
		
		return $response;
	
	}
	
	
	public static function buildPostDataContext($postdata)
	{
	
		$opts = array(
		
					'http'	=> array(
					
							'method'	=>"POST",
							'header'	=>"User-Agent: ".WeeverLoginConst::NAME." version: ". 
										WeeverLoginConst::VERSION."\r\n"."Content-length: " .
										strlen($postdata)."\r\n".
							         	"Content-type: application/x-www-form-urlencoded\r\n",
							'content' 	=> $postdata
						
							)
					);
	
		return stream_context_create($opts);
	
	}
	

}