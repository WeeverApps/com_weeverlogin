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
		<script type='text/javascript' src='http://api.recaptcha.net/js/recaptcha_ajax.js'></script>
		
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
				xtype:		'wxCustomLapbandRegistrationForm',
				config: 	{
				
					fullscreen:			true,
					id:					'wxregisterformpanel',
        			wxConfig:            {},
        			layout:             'vbox',
        			scrollable:         'vertical',
        			cls:                'wx-scrollable-panel wx-form wx-custom-lapband-registration-form',
        			styleHtmlContent:   true,
        			styleHtmlCls:       'wx-html',
					listeners:		{
					
						painted:		{
						
							fn:			'onPainted',
							single:		true
							
						}
					
					},
					exception: function (panel, result, options) {
					
						alert("Failed");
					
					},
					items: 			[
					    /*
					    {
							xtype:      'panel',
							html:       '<img class="wx-login-logo" src="http://mvs013-011.directrouter.com/~sales/images/mobile_assets/lapbandconnect_landinglogo.png" />'
						},
						*/
						{
	                        xtype : 'container',
	                        styleHtmlContent: true,
	                       	cls   : 'wx-form-infobox wx-lapband-infobox',
	                        html  : '<img class="wx-login-logo" src="images/mobile_assets/lapbandconnect_landinglogo.png" /><p>Please include the registration code from your clinic to unlock all the features of this app.</p><p style="margin-bottom: 0;">You can still create a Preview account with limited features without a code.</p>'                
	
	                    },
						/*
						{
						
							xtype:		'hiddenfield',
							name:		'wxCorsRequest',
							value:		1
						
						},
						*/
						/*
						{
						
							xtype:		'hiddenfield',
							name:		'recaptcha_challenge_field',
							value:		'03AHJ_VuvedmeD8QuTA5fNzb6aOex6s34M1iH14e5hdXIUfmIjvIBbZOYl52QJXXIaMkiclSK6P2mKPtjdocN6OLDTZ5RAofmE1eWYP41bXSpUTf8Am3ux94665HWu1viB-3wCarR07x2FVXVSGldIKlysUZLNExM_UxliubazCV27FAxnbI6xOy8'
						
						},
						{
						
							xtype:		'hiddenfield',
							name:		'recaptcha_response_field',
							value:		'misclo 214'
						
						},
						*/
						{
						
							xtype:		'hiddenfield',
							name:		'<?php echo JUtility::getToken(); ?>',
							value:		1
						
						},
						
						/*
						<?php if( WeeverLoginHelper::joomlaVersion() != "1.5" ) : ?>
						
						{
						
							xtype:		'hiddenfield',
							name:		'return',
							value:		'aW5kZXgucGhwP29wdGlvbj1jb21fdXNlcnMmd3hDb25maXJtTG9naW49MQ==' // this is base64 of wxConfirmLogin=1 in URL
						
						},
						
						<?php endif; ?>
						*/
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
				onPainted:		function(view) {
					
					var me	= this;
					
					//Recaptcha.create("6LcVa-USAAAAAKq_1nGke_pfad5lzyLiG4zSZnJi", "dynamic_recaptcha_1", {theme: "white",lang : 'en',tabindex: 0});
					
					/*
					Recaptcha.create("your_public_key", element, {
						theme: "red",
						callback: Recaptcha.focus_response_field}
					);
					*/
					
				},
				loginApp: function(username, password) {
				
					var loginData = {},
						loginToken = '<?php echo JUtility::getToken(); ?>';
					
					loginData.username 		= username;
					loginData.password 		= password;
					loginData[loginToken] 	= 1;
					loginData.return		= 'aW5kZXgucGhwP29wdGlvbj1jb21fdXNlcnMmd3hDb25maXJtTG9naW49MQ=='; // this is base64 of wxConfirmLogin=1 in URL
					loginData.Submit		= 'Log in';
					loginData.remember		= 'yes';
					loginData.option		= 'com_users';
					loginData.task			= 'user.login';
					
					//console.log('999');
					//console.log(loginData);
					//console.log(wxl.appdress);
					
					Ext.Ajax.request({
					    method: 'POST',
					    withCredentials: true,
					    params: loginData,
					    url: '<?php echo JURI::root(); ?>index.php?option=com_users&task=registration.register',
					    useDefaultXhrHeader: false,
					    success: function(response){
					    
					    	//console.log('yiha123');
					    	//console.log(response);
					        window.location = wxl.appdress;
					        
					    }
					});
				
				},
				sendClinicCode: function(username, clinicCode, password) {
					
					var me = this;
					//console.log('sendClinic.......');
					//console.log(username);
					Ext.data.JsonP.request({
							
					    url: 			'<?php echo JURI::root(); ?>index.php?option=com_weeverlogin',
					    callbackKey: 	'callback',
					    params: 		{
					    
					        task: 		'getUserId',
					        username:	username
					        
					    },
					    success: function(result, request) {
							
							//console.log('123');
							//console.log(request);
							//console.log(result);
					       
					        //return result.userId;
					        var postData = {};
					        postData.securecode = clinicCode;
					        postData.userId = result.userId;
					        
					        Ext.Ajax.request({
					            method: 'POST',
					            withCredentials: true,
					            params: postData,
					            url: '<?php echo JURI::root(); ?>index.php?option=com_lapbandcode&task=upgrade',
					            useDefaultXhrHeader: false,
					            success: function(response){
					            
					            	//console.log('***');
					            	//console.log(response);
					                
					                Ext.Msg.alert('Success!', '<div class="wx-register-validiation-msg">Your account was created and your clinic code also has been sent and need to be approved by administrator!</div>', function() {me.loginApp(username, password);});
					            	   
					            }
					        });
					        
					    }
					    
					});
					
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
						defaults:   {
	                        labelWidth: '',
	                        labelAlign: 'top'
	                    },					
						items:		[
						
							{
							
								xtype:			'textfield',
								cls:			'wx-input',
								name:			'ClinicCode',
								id:				'wxl-register-field-clinic-code',
								label:			'Clinic Code',
								placeHolder:	'Enter Code',
								//required:		true,
								useClearIcon: 	true,
							},
							{
							
								xtype: 			'textfield', 
								name: 			'jform[name]',
								label: 			'Name',
								placeHolder: 	'Enter Name',
								cls:            'wxl-login-field',
								id:             'wxl-register-field-name',
								required: 		true,
								clearIcon: 		true,
							
							},
							{
							
								xtype:		'fieldset',
								defaults:   {
								    labelWidth: '',
								    labelAlign: 'top'
								},	            
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
																			
										xtype:		'container',
										styleHtmlContent: true,
										id:			'wxl-register-field-usernameCheckNotice',
										cls: 		'wx-form-infobox',
										html:		'Checking Username:'
									
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
								clearIcon: 		true,
							
							},{
							
								xtype:			'passwordfield',
								cls:			'wxl-login-field',
								name:			'jform[password2]',
								id:				'wxl-register-field-password2',
								label:			'Re-enter',
								placeHolder:	'Password',
								required:		true,
								clearIcon: 		true,
							
							},
							
							
							{
							
								xtype:		'fieldset',
								defaults:   {
								    labelWidth: '',
								    labelAlign: 'top'
								},         
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
																			
										xtype:		'container',
										styleHtmlContent: true,
										id:			'wxl-register-field-emailCheckNotice',
										cls: 		'wx-form-infobox',
										html:		'Checking Email:'
									
									}
								]
							
							},{
							
								xtype:			'textfield',
								cls:			'wxl-login-field',
								name:			'jform[email2]',
								id:				'wxl-register-field-email2',
								label:			'Re-enter',
								placeHolder:	'Email Address',
								required:		true,
								clearIcon: 		true
							}/*,
							{
							
								xtype:			'container',
								cls:			'wxl-captcha-field wx-non-responsive-table',
								name:			'captcha',
								id:				'dynamic_recaptcha_1'
								//label:			'Captcha',
								//required:		true,
								//clearIcon: 		true
							}*/
						
						]
					
					},
					{
											
						xtype:		'button',
						cls:        'wx-btn green radiuspoint25',
						id:         'wxl-register-btn-register',
						text: 		'Register',
						ui: 		'confirm',
						handler:	function() {
							
							var me			= Ext.getCmp('wxregisterformpanel');
								//challenge 	= Recaptcha.get_challenge(),
								//response 	= Recaptcha.get_response();
							
							//console.log(challenge);
							//console.log(response);
							/*
							me.add([					
								{
									xtype:		'hiddenfield',
									name:		'recaptcha_challenge_field',
									value:		challenge
								},
								{
									xtype:		'hiddenfield',
									name:		'recaptcha_response_field',
									value:		response
								}
							]);
							*/
							var clinicCode	= Ext.getCmp('wxl-register-field-clinic-code').getValue(),
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
							
							//console.log('testing');
							//console.log(me.getValues());
							
							Ext.Ajax.request({
							    method: 'POST',
							    withCredentials: true,
							    params: me.getValues(),
							    url: '<?php echo JURI::root(); ?>index.php?option=com_users&task=registration.register&mobile=1',
							    useDefaultXhrHeader: false,
							    success: function(response){
							    
							    	//console.log('yiha123');
							    	//console.log(response);
							        
							        //Ext.Msg.alert('Success!', '<div class="wx-register-validiation-msg">Your account has been created.</div>', Ext.emptyFn);
							        if ( '' != clinicCode ) {
							        	
							        	me.sendClinicCode(username, clinicCode, password1);
							        	
							        } else {
							        	
							        	/*
							        	if ( response.responseText.search('The CAPTCHA solution was incorrect') ) {
							        	
							        		Ext.Msg.alert(
							        			
							        			'Failure!',
							        			'<div class="wx-register-validiation-msg">The CAPTCHA solution was incorrect.</div>', Ext.emptyFn
							        		
							        		);
							        		
							        	} else {
							        		
							        		Ext.Msg.alert(
							        			
							        			'Success!',
							        			'<div class="wx-register-validiation-msg">Your account has been created.</div>',
							        			function() {
							        				me.loginApp(username, password1);	
							        			}
							        		
							        		);
							        		
							        	}*/
							        	Ext.Msg.alert(
							        		
							        		'Success!',
							        		'<div class="wx-register-validiation-msg">Your account has been created.</div>',
							        		function() {
							        			me.loginApp(username, password1);	
							        		}
							        	
							        	);
							        	
							        }
							        
							    }
							});
							
						}
						
					},
					{
					
						xtype: 		'button',
						cls: 		'wx-btn white radiuspoint25',
						text: 		'&laquo; Back',
						handler: 	function() {
						
							history.back();
							
						}
						
					},
					{
					
						xtype: 		'button',
						cls: 		'wx-btn white radiuspoint25',
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
