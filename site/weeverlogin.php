<?php
/*	
*	Weever Apps Login Component for Joomla
*	(c) 2010-2011 Weever Apps Inc. <http://www.weeverapps.com/>
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

jimport('joomla.application.component.controller');

class WeeverLoginController extends JController
{

	
	public function display()
	{
	
		$view 		= JRequest::getVar('view');
		$userid 	= &JFactory::getUser()->get('id');
		
		if($userid)
		{
		
			echo "Already logged in";
		
		}
		
		if(!$view)
		{
		
			JRequest::setVar('view','login');
			
		}
		
		parent::display();
	
	}
	

}


$controller = new WeeverLoginController();
$controller->execute(JRequest::getWord('task'));
$controller->redirect();
