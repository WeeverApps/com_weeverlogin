<?php
/*	
*	Weever Apps Login Component for Joomla
*	(c) 2012 Weever Apps Inc. <http://www.weeverapps.com/>
*
*	Author: 	Aaron Song <aaron@weeverapps.com>
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

$guest 		= WeeverLoginHelper::getLoginSetting();
$appdress 	= "http://" . WeeverLoginHelper::getCustomAppDomain();
$css_url	= WeeverLoginHelper::getLoginCssUrl();

if( $appdress == "http://" && WeeverLoginHelper::getStageStatus() == false )
	$appdress = 'http://weeverapp.com/app/'.WeeverLoginHelper::getPrimaryDomain();
else if ($appdress == "http://" && WeeverLoginHelper::getStageStatus() == true )
	$appdress = 'http://stage.weeverapp.com/app/'.WeeverLoginHelper::getPrimaryDomain();


?><!DOCTYPE html>
<html>

	<head>
	
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	

		<link rel='stylesheet' href='<?php echo JURI::base(true)."/components/com_weeverlogin/assets/css/wxl.css"; ?>' type='text/css' />
		
	<?php if( $css_url ) : ?>
	
		<link rel='stylesheet' href='<?php echo $css_url; ?>' type='text/css' />
		
	<?php endif; ?>
		
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
				
					fullscreen:			true,
					id:					'wxregisterformpanel',
        			styleHtmlContent:   true,
        			styleHtmlCls:       'wxl-html',					
					xtype:				'formpanel',
					usernameCheck:		null,
					emailCheck:			null,
					mailAddressVerify:	'',
					
					exception: function (panel, result, options) {
					
						alert("Failed");
					
					},
					items: 			[
					/* {
							xtype:      'panel',
							html:       '<img class="wx-login-logo" src="http://yoursite/yourlogo.png" />'
						},
					*/
						{
						
							xtype:		'hiddenfield',
							name:		'wxCorsRequest',
							value:		1
						
						},
						{
						
							xtype:		'hiddenfield',
							name:		'<?php echo JUtility::getToken(); ?>',
							value:		1
						
						},
						<?php if( WeeverLoginHelper::joomlaVersion() != "1.5" ) : ?>
						
						{
						
							xtype:		'hiddenfield',
							name:		'return',
							value:		'aW5kZXgucGhwP29wdGlvbj1jb21fdXNlcnMmd3hDb25maXJtTG9naW49MQ==' // this is base64 of wxConfirmLogin=1 in URL
						
						},
						
						<?php endif; ?>
						
						{
						
							xtype:		'hiddenfield',
							name:		'option',
							value:		'com_users'
						
						},{
						
							xtype:		'hiddenfield',
							name:		'task',
							value:		'registration.register'
						
						}						
					] 	
					
				},
				checkUsername: function(username) {
					
					var me = this;
					
					Ext.data.JsonP.request({
							
					    url: 			'<?php echo JURI::root(); ?>index.php?option=com_weeverlogin',
					    callbackKey: 	'callback',
					    params: 		{
					    
					        task: 		'checkUser',
					        username:	username
					        
					    },
					    success: function(result, request) {
							
							//console.log('123');
							//console.log(request);
							//console.log(result);
					        Ext.getCmp('wxl-register-field-usernameCheckNotice').setHtml(result.message);
					        me.config.usernameCheck = result;
					        
					    },
					    failure : function(response) {
					    
					        me.config.usernameCheck = response;
					        
					    }
					    
					});
					
				},
				checkEmail: function(email) {
					
					var me = this;
					
					Ext.data.JsonP.request({
							
					    url: 			'<?php echo JURI::root(); ?>index.php?option=com_weeverlogin',
					    callbackKey: 	'callback',
					    params: 		{
					    
					        task: 	'checkEmail',
					        email:	email
					        
					    },
					    success: function(result, request) {
							
							Ext.getCmp('wxl-register-field-emailCheckNotice').setHtml(result.message);
							
					        me.config.emailCheck = result;
					        
					    },
					    failure : function(response) {
					   	
					        me.config.emailCheck = response;
					        
					    }
					    
					});
					
				},
				verifyEmailAddress: function(email) {
					
					var me			= this,
						email1		= Ext.getCmp('wxl-register-field-email1').getValue(),
						atpos		= email1.indexOf('@'),
						dotpos		= email1.lastIndexOf('.');
					
					if (atpos < 1 || dotpos < atpos+2 || dotpos+2 >= email1.length) {
						
						Ext.getCmp('wxl-register-field-emailCheckNotice').setHtml('Email address appears to be invalid.\n');
						me.config.emailAddressVerify = 'Email address appears to be invalid.\n';
						
						return false;
					
					}else {
						
						me.config.emailAddressVerify = '';
						return true;
					}
					
				},
				initialize: function() {
				
					this.callParent(arguments);
					
					var me = this;
					
					me.add([
					
					
					{
					
						xtype:		'fieldset',
						id:			'wxl-login-form-container',
						cls:		'wxl-login-form',
						items:		[
						
							{
							
								xtype: 			'textfield', 
								name: 			'jform[name]',
								label: 			'Name',
								placeHolder: 	'Name',
								cls:            'wxl-login-field',
								id:             'wxl-register-field-name',
								required: 		true,
								clearIcon: 		true
							
							},
							{
							
								xtype:		'fieldset',	            
								items:		[
									
									{
									
										xtype:			'textfield',
										cls:			'wxl-login-field',
										name:			'jform[username]',
										id:				'wxl-register-field-username',
										label:			'Username',
										required:		true,
										clearIcon: 		true,
										placeHolder:	'Username',
										listeners: 	{
											
											'keyup': 	{
											
												fn: function(view, e, eOpts) {
													
													if ( view.getValue().length >= 3 )
														me.checkUsername(view.getValue());
												
												}
												
											}
											
										}
									
									},{
										
										xtype:			'label',
										id:				'wxl-register-field-usernameCheckNotice',
										html:			''
									
									}
								]
							
							},
							{
							
								xtype:			'passwordfield',
								cls:			'wxl-login-field',
								name:			'jform[password1]',
								placeHolder: 	'Password',
								id:				'wxl-register-field-password1',
								label:			'Password',
								required:		true,
								clearIcon: 		true
							
							},{
							
								xtype:			'passwordfield',
								cls:			'wxl-login-field',
								name:			'jform[password2]',
								id:				'wxl-register-field-password2',
								label:			'Confirm Password',
								required:		true,
								clearIcon: 		true
							
							},
							
							
							{
							
								xtype:		'fieldset',           
								items:		[
									
									{
									
										xtype:			'textfield',
										cls:			'wxl-login-field',
										name:			'jform[email1]',
										id:				'wxl-register-field-email1',
										label:			'Email',
										required:		true,
										clearIcon: 		true,
										placeHolder:	'Email Address',
										listeners: 	{
											
											'change': 	{
											
												fn: function(view, newValue, oldValue, eOpts) {
													
													if ( me.verifyEmailAddress(newValue) )
														me.checkEmail(newValue);
													
												}
												
											}
											
										}
									
									},{
										
										xtype:			'label',
										id:				'wxl-register-field-emailCheckNotice',
										html:			''
									
									}
								]
							
							},{
							
								xtype:			'textfield',
								cls:			'wxl-login-field',
								name:			'jform[email2]',
								id:				'wxl-register-field-email2',
								label:			'Confirm Email',
								required:		true,
								clearIcon: 		true	
							}
						
						]
					
					},
					{
											
						xtype:		'button',
						cls:        'wxl-login-btn',
						id:         'wxl-register-btn-register',
						text: 		'Register',
						ui: 		'confirm',
						handler:	function() {
							
							var //me			= Ext.getCmp('wxregisterformpanel'),
								name		= Ext.getCmp('wxl-register-field-name').getValue(),
								username	= Ext.getCmp('wxl-register-field-username').getValue(),
								password1 	= Ext.getCmp('wxl-register-field-password1').getValue(),
								password2 	= Ext.getCmp('wxl-register-field-password2').getValue(),
								email1		= Ext.getCmp('wxl-register-field-email1').getValue(),
								email2	 	= Ext.getCmp('wxl-register-field-email2').getValue(),
								errorMsg	= '';
								
							if ( '' == name )
								errorMsg += '<div class="wx-register-validiation-msg">Please enter your name.</div>' + "\n";
							
							if ( '' == username )
								errorMsg += '<div class="wx-register-validiation-msg">Please enter a user name.</div>' + "\n";
							
							if ( '' == password1 )
								errorMsg += '<div class="wx-register-validiation-msg">Please enter a password.</div>' + "\n";
							
							if ( '' == password2 )
								errorMsg += '<div class="wx-register-validiation-msg">Please confirm your password.</div>' + "\n";
								
							if ( '' == email1 )
								errorMsg += '<div class="wx-register-validiation-msg">Please enter an email address.</div>' + "\n";
								
							if ( '' == email2 )
								errorMsg += '<div class="wx-register-validiation-msg">Please confirm your email address.</div>' + "\n";
								
							
							if ( '' != name && '' != username ) {
								
								if ( me.config.usernameCheck && false == me.config.usernameCheck.success ) {
									
									errorMsg += me.config.usernameCheck.message + "\n";
									
									Ext.getCmp('wxl-register-field-username').reset();
									
								}
								
							}
							
							if ( '' != email1 && '' != email2 ) {
								
								if ( '' != me.config.emailAddressVerify ) {
									
									errorMsg += me.config.emailAddressVerify;
									
									Ext.getCmp('wxl-register-field-email1').reset();
									Ext.getCmp('wxl-register-field-email2').reset();
									
								} else {
									
									if ( me.config.emailCheck && false == me.config.emailCheck.success ) {
										
										errorMsg += me.config.emailCheck.message + "\n";
										
										Ext.getCmp('wxl-register-field-email1').reset();
										Ext.getCmp('wxl-register-field-email2').reset();
										
									}
									
									if ( email1 != email2 ) {
										
										errorMsg += "The email addresses you entered do not match.\n";
										
										Ext.getCmp('wxl-register-field-email1').reset();
										Ext.getCmp('wxl-register-field-email2').reset();
										
									}
									
								}
								
							}
							
							if ( '' != password1 && '' != password2 ) {
								
								if ( password1 != password2 ) {
									
									errorMsg += "The passwords you entered do not match.\n";
									
									Ext.getCmp('wxl-register-field-password1').reset();
									Ext.getCmp('wxl-register-field-password2').reset();
									
								}
								
							}
							
							if ( '' != errorMsg ) {
							
								Ext.Msg.alert('Error!', errorMsg, Ext.emptyFn);
								return;
								
							}
							
							
							Ext.Ajax.request({
							    method: 'POST',
							    withCredentials: true,
							    params: me.getValues(),
							    url: '<?php echo JURI::root(); ?>index.php?option=com_users&task=registration.register',
							    useDefaultXhrHeader: false,
							    success: function(response){
							        
							        Ext.Msg.alert('Success!', '<div class="wx-register-validiation-msg">Your account has been created and an activation link has been sent to the email address you entered. Note that you must activate the account by clicking on the activation link when you get the email before you can login.</div>', Ext.emptyFn);
							        
							    }
							});
								
						}
						
					},
					{
					
						xtype: 		'button',
						cls: 		'wxl-login-btn white',
						text: 		'&laquo; Back',
						handler: 	function() {
						
							history.back();
							
						}
						
					},
					{
					
						xtype: 		'button',
						cls: 		'wxl-login-btn white',
						text: 		'Reset',
						handler: 	function() {
						
							me.reset();
							
						}
						
					}
					
					]);
					
				}

			});
			
		</script>
		
	</head>
	
	<body></body>
	
</html>

<?php 

jexit();

