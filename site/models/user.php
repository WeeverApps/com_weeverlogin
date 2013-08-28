<?php
/*	
*	Weever Apps Login Component for Joomla
*	(c) 2012 Weever Apps Inc. <http://www.weeverapps.com/>
*
*	Author: 	Robert Gerald Porter 	<rob@weeverapps.com>
*				Aaron Song 				<aaron@weeverapps.com>
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

class WeeverLoginModelUser extends JModel
{

	public $json		= null;
	
	public function __construct()
	{
        
        parent::__construct();
        
	}
	
	public function getUserId($username)
	{
		
		$db = JFactory::getDBO();
		
		$query = 	
			"SELECT * FROM #__users ".
			"WHERE `username` = '".$username."'";
		
		$db->setQuery($query);
		
		$result = $db->loadObject();
		
		if( $result->id )
			return $result->id;
		else
			return true;
	
	}
	
	public function checkUser($username)
	{
		
		$db = JFactory::getDBO();
		
		$query = 	
			"SELECT * FROM #__users ".
			"WHERE `username` = '".$username."'";
		
		$db->setQuery($query);
		
		$result = $db->loadObject();
		
		if( $result )
			return false;
		else
			return true;
	
	}
	
	public function checkEmail($email)
	{
		
		$db = JFactory::getDBO();
		
		$query = 	
			"SELECT * FROM #__users ".
			"WHERE `email` = '".$email."'";
		
		$db->setQuery($query);
		
		$result = $db->loadObject();
		
		if( $result )
			return false;
		else
			return true;
	
	}

}