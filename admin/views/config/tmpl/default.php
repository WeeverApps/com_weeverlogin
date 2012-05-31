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

if(WeeverLoginHelper::joomlaVersion() != '1.5')  // ### non-1.5 only
	$jsJoomla = "Joomla.";
	
else 
	$jsJoomla = "";
	
JHTML::_('behavior.modal', 'a.popup');
JHTML::_('behavior.mootools');
JHTML::_('behavior.tooltip');

jimport('joomla.html.pane');

$pane 	= &JPane::getInstance('tabs');

?>

<form action='index.php' enctype='multipart/form-data' method='post' name='adminForm' id='adminForm'>


<?php echo $pane->startPane('config'); ?>
<?php echo $pane->startPanel(JText::_("WEEVERLOGIN_BASIC_SETTINGS"), 'basic-settings'); ?>

	<div class="wx-submitcontainer">
	
		<a href="#" onclick="javascript:<?php echo $jsJoomla; ?>submitbutton('apply')"><button class="wxui-btn orange medium radius3">&#x2713; &nbsp;<?php echo JText::_('WEEVERLOGIN_SAVE_BUTTON'); ?></button></a>
		
	</div>   

	<fieldset class='adminForm'>
	
		<legend><?php echo JText::_('WEEVERLOGIN_MAIN_SETTINGS'); ?></legend>
			
		<table class="admintable">
			
			<tr>
			
				<td class="key hasTip" title="<?php echo JText::_('WEEVERLOGIN_TIP_ENABLE_LOGIN_APP'); ?>">
					<?php echo JText::_('WEEVERLOGIN_ENABLE_LOGIN_APP'); ?>
				</td>
				
				<td>
				
					<select name="EnableLogin">
					
						<option value="0"><?php echo JText::_('NO'); ?></option>
						<option value="1" <?php echo ($this->login->enabled != 0) ? "selected='selected'" : ""; ?>><?php echo JText::_('YES'); ?></option>
						
					</select>
				
				</td>
				
			</tr>
			
			<tr>
			
				<td class="key hasTip" title="<?php echo JText::_('WEEVERLOGIN_TIP_ALLOW_GUEST_ACCESS'); ?>">
					<?php echo JText::_('WEEVERLOGIN_ALLOW_GUEST_ACCESS'); ?>
				</td>
				
				<td>
				
					<select name="AllowGuest">
					
						<option value="1"><?php echo JText::_('YES'); ?></option>
						<option value="0" <?php echo ($this->login->enabled == 2) ? "selected='selected'" : ""; ?>><?php echo JText::_('NO'); ?></option>
						
					</select>
				
				</td>
				
			</tr>
		
		</table>
	
	</fieldset>
	
	
	<fieldset class='adminForm'>
	
		<legend><?php echo JText::_('WEEVERLOGIN_USER_SYSTEM'); ?></legend>
			
		<table class="admintable">
			
			<tr>
			
				<td class="key hasTip" title="<?php echo JText::_('WEEVERLOGIN_TIP_SELECT_USER_PROFILE_SYSTEM'); ?>">
					<?php echo JText::_('WEEVERLOGIN_SELECT_USER_PROFILE_SYSTEM'); ?>
				</td>
				
				<td>
				
					<select name="UserSystem">
					
						<option value="joomla"><?php echo JText::_('WEEVERLOGIN_PROFILE_JOOMLA_NATIVE'); ?></option>
						<option value="k2" <?php echo ($this->login->system == "k2") ? "selected='selected'" : ''; ?>><?php echo JText::_('WEEVERLOGIN_PROFILE_K2'); ?></option>
						
					</select>
				
				</td>
				
			</tr>
		
		</table>
	
	</fieldset>


<?php echo $pane->endPanel(); ?>
<?php echo $pane->startPanel(JText::_("WEEVERLOGIN_ADVANCED_CSS_OVERRIDES"), 'advanced-css-overrides'); ?>

	<div class="wx-submitcontainer">
	
		<a href="#" onclick="javascript:<?php echo $jsJoomla; ?>submitbutton('apply')"><button class="wxui-btn orange medium radius3">&#x2713; &nbsp;<?php echo JText::_('WEEVERLOGIN_SAVE_BUTTON'); ?></button></a>
		
	</div> 


	<fieldset class='adminForm'>
	
		<legend><?php echo JText::_('WEEVERLOGIN_CSS_OVERRIDES'); ?></legend>
			
		<table class="admintable">
			
			<tr>
			
				<td class="key hasTip" title="<?php echo JText::_('WEEVERLOGIN_TIP_CSS_OVERRIDE_URL'); ?>">
					<?php echo JText::_('WEEVERLOGIN_CSS_OVERRIDE_URL'); ?>
				</td>
				
				<td>
				
					<input type="text" name="CssUrl" value="<?php echo WeeverLoginHelper::getLoginCssUrl(); ?>" placeholder="<?php echo JText::_('WEEVERLOGIN_CSS_OVERRIDE_URL_PLACEHOLDER'); ?>" />
				
				</td>
				
			</tr>
		
		</table>
	
	</fieldset>

<?php echo $pane->endPanel(); ?>
<?php echo $pane->endPane(); ?>

	<input type="hidden" name="option" value="com_weeverlogin" />
	<input type="hidden" name="site_key" id="wx-site-key" value="<?php echo WeeverLoginHelper::getKey(); ?>" />
	<input type="hidden" name="view" value="config" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_('form.token'); ?>

</form>