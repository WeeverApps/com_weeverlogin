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

$settings = WeeverLoginHelper::getWeeverSettingsDB();

$appdress = "http://" . WeeverLoginHelper::getCustomAppDomain($settings);

if( $appdress == "http://" )
	$appdress = 'http://weeverapp.com/app/'.WeeverLoginHelper::getPrimaryDomain($settings);

?><!DOCTYPE html>
<html>

	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	

		<link rel='stylesheet' href='<?php echo JURI::base(true)."/components/com_weeverlogin/assets/css/wxl.css"; ?>' type='text/css' />
		
		<script type='text/javascript' src='<?php echo JURI::base(true)."/components/com_weeverlogin/assets/js/touch.js"; ?>'></script>
		<script type='text/javascript' src='<?php echo JURI::base(true)."/components/com_weeverlogin/assets/js/app-all.js"; ?>'></script>
		
		<script type="text/javascript">
		
			var wxl = wxl || {};
			
			wxl.appdress 	= '<?php echo JRequest::getVar('appUrl', $appdress); ?>';
			
			<?php if( strstr( JRequest::getVar("appUrl"), "guest=1" ) ) : ?> 
				wxl.guestParam	= '';
			<?php else: ?>
				wxl.guestParam 	= '?guest=1';
			<?php endif; ?>
			
			// ST2 override
			// necessary because J1.5 onLoginFailure is broken
			// so we're going to assume all bad responses are login failures.
			
			Ext.decode = function() {
		
				isNative = function() {
				
					var useNative = null;
			
					return function() {
					
						if (useNative === null) {
							useNative = Ext.USE_NATIVE_JSON && window.JSON && JSON.toString() == '[object JSON]';
						}
			
						return useNative;
						
					};
					
				}(),
				doDecode = function(json) {
				
					return eval("(" + json + ')');
						
				};
		
				var dc;
				
				return function(json, safe) {
					if (!dc) {
					
						// setup decoding function on first access
						dc = isNative() ? JSON.parse : doDecode;
						
					}
					try {
					
						return dc(json);
						
					} catch (e) {
					
						if (safe === true) {
							return null;
						}
						
						Ext.Msg.alert( "Your login or password was invalid.", Ext.emptyFn() );
					}
					
				};
			
			}();
		
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
					exception: function (panel, result, options) {
					
						alert("Failed");
					
					},
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
						
							xtype:		'fieldset',
							id:			'wxl-login-form-container',
							cls:		'wxl-login-form',
							items:		[
							
								{
								
									xtype: 			'textfield', 
									name: 			'username',
									label: 			'Username',
									placeHolder: 	'Username',
									cls:            'wxl-login-field',
									id:             'wxl-login-field-username',
									required: 		true,
									clearIcon: 		true
								
								},
								{
								
									xtype: 			'passwordfield',
									name: 			'passwd',
									required: 		true,
									label: 			'Password',
									placeHolder: 	'Password',
									cls:            'wxl-login-field',
									id:             'wxl-login-field-password',
									clearIcon: 		true
									
								},
								{
								
									xtype:			'checkboxfield',
									name:			'remember',
									value:			1,
									label:			'Remember Me',
									labelWidth:		'75%',
									cls:            'wxl-login-checkbox',
									id:             'wxl-login-field-rememberme',
									clearIcon:		true
								
								}
							
							]
						
						},
						{
												
							xtype:		'button',
							cls:        'wxl-login-btn',
							id:         'wxl-login-btn-primary',
							text: 		'Sign In',
							ui: 		'confirm',
							handler: 	function() {
							
								this.up('formpanel').submit({
							
									url:		'index.php',
									method:		'POST',
									success:	function() {
									
										window.location = wxl.appdress;
									
									},
									failure:	function() {
									
										alert("Failed");
									
									}				    				
									
								});
							
							}
							
						},
						{
						
							xtype:		'button',
							text:		'Proceed as Guest',
                                                        cls:            'wxl-login-btn',
                                                        id:             'wxl-login-btn-guest',
							handler:	function() {
							
								window.location = wxl.appdress + wxl.guestParam;
							
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

<?php 

jexit();

