<?php
/*	
*	Weever Apps Login Administrator Component for Joomla
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

class WeeverLoginController extends JController
{

	public function display()
	{
	
		$view = JRequest::getVar('view');
		
		if(!$view)
		{
			JRequest::setVar('view','config');
		}
		
		parent::display();
	
	}
	
	
	public function save()
	{
	
		$login			= JRequest::getVar("EnableLogin");
		$guest			= JRequest::getVar("AllowGuest");
		$system			= JRequest::getVar("UserSystem");
		$css_url		= JRequest::getVar("CssUrl");
		$login_setting 	= 0; 
		
		if( !$login )
			$login_setting 	= 0;
			
		else 
		{
		
			if( !$guest )
				$login_setting 	= 2;
				
			else 
				$login_setting 	= 1;		
		
		}
		
		WeeverLoginHelper::setLoginCssUrl( $css_url );
		
		$query 		= array(
		
			'system' 			=> $system,
			'enabled'			=> $login_setting,
			'm' 				=> 'edit_login'
					
		);
				
		JRequest::setVar( 'weever_server_response', WeeverLoginHelper::buildAjaxQuery($query), 'post' );
		
		if(JRequest::getVar('weever_server_response'))
		{
				
			if($this->getTask() == 'apply')
				$this->setRedirect(
					
					'index.php?option=com_weeverlogin&view=config',
				 	JText::_('WEEVERLOGIN_SERVER_RESPONSE').JRequest::getVar('weever_server_response')
				 	
				 );
					
			else		
				$this->setRedirect(
				
					'index.php?option=com_weeverlogin&view=config',
					JText::_('WEEVERLOGIN_SERVER_RESPONSE').JRequest::getVar('weever_server_response')
					
				);
			
			return;
			
		}
		else
		{
		
			$this->setRedirect(
			
				'index.php?option=com_weeverlogin&view=config',
				JText::_('WEEVERLOGIN_ERROR_COULD_NOT_CONNECT_TO_SERVER'), 
				'error'
				
			);
			
			return;
			
		}			
		
	}
	
}