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

?><!DOCTYPE html>
<html>

	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	

		<link rel='stylesheet' href='<?php echo JURI::base(true)."/components/com_weeverlogin/assets/css/wxl.css"; ?>' type='text/css' />
		
		<script type='text/javascript' src='<?php echo JURI::base(true)."/components/com_weeverlogin/assets/js/touch.js"; ?>'></script>
		<script type='text/javascript' src='<?php echo JURI::base(true)."/components/com_weeverlogin/assets/js/app-all.js"; ?>'></script>
		
		<script type="text/javascript">
		
			Ext.application({
			
			    name: 			'WxLogin',
			    icon: 			'resources/images/logo.png',
			    views: 			['Login'],
			    launch: 		function() {
			    
			        Ext.create('WxLogin.view.Login');
			        
			    }
			    
			});
			
			Ext.define('WxLogin.view.Login', {
			
			    extend: 	'Ext.form.Panel',
			    config: 	{
			    
			    	fullscreen:		true,
			    	xtype:			'formpanel',
			    	items: 			[
			    		
			    		{
			    			
			    			xtype: 		'hiddenfield',
			    			name: 		'option',
			    			value: 		'com_user'
			    		
			    		},
			    		{
			    		
			    			xtype: 		'hiddenfield',
			    			name: 		'task',
			    			value: 		'login'
			    			
			    		},
			    		{
			    		
			    			xtype:		'hiddenfield',
			    			name:		'jCorsRequest',
			    			value:		1
			    		
			    		},
			    		{
			    		
			    			xtype:		'hiddenfield',
			    			name:		'<?php echo JUtility::getToken(); ?>',
			    			value:		1
			    		
			    		},
			    		{
			    		
			    			xtype: 			'textfield', 
			    			name: 			'username',
			    			label: 			'Username',
			    			placeHolder: 	'Your Username',
			    			required: 		'true',
			    			clearIcon: 		'true'
			    		
			    		},
			    		{
			    		
			    			xtype: 			'passwordfield',
			    			name: 			'passwd',
			    			required: 		'true',
			    			label: 			'Password',
			    			placeHolder: 	'Password',
			    			clearIcon: 		'true'
			    			
			    		},
			    		{
			    								
			    			xtype:		'button',
			    			text: 		'Sign In',
			    			ui: 		'confirm',
			    			handler: 	function() {
			    			
			    				this.up('formpanel').submit({
			    			
				    				url:		'index.php',
				    				method:		'POST',
				    				success:	function() {
				    				
				    					alert('POSTED! Forwarding....');
				    				
				    				}
				    				
				    			});
			    			
			    			}
			    			
			    		}
			    		
			    	] 	
			        
			    },
			    initialize: function() {
			    
			        this.callParent(arguments);
			        
			    }
			
			});
			
		</script>
		
	</head>
	
	<body></body>
	
</html>

<?php jexit(); ?>

