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

jimport('joomla.application.component.controller');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

require_once (JPATH_COMPONENT.DS.'helpers'.DS.'helper'.'.php');
require_once (JPATH_COMPONENT.DS.'helpers'.DS.'config'.'.php');
require_once (JPATH_COMPONENT.DS.'controller.php');

JToolBarHelper::title( 'Login Settings', 'weever_toolbar_title');

$cssFile 	= JURI::base(true).'/components/com_weeverlogin/assets/css/weeverlogin.css?v='.WeeverLoginConst::VERSION;

$document 	=& JFactory::getDocument();
$document->addStyleSheet($cssFile, 'text/css', null, array());

$controller = new WeeverLoginController();

$controller->registerTask('apply', 'save');
$controller->execute( JRequest::getWord('task') );
$controller->redirect();

include("views/modules/footer.php");