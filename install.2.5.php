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

jimport("joomla.installer.installer");

class com_WeeverLoginInstallerScript
{

	public		$release 	= "0.2";
	public		$src;
	public		$installer;

	public function install($parent)
	{
	
		$manifest 			= $parent->get("manifest");
		$parent 			= $parent->getParent();	
		$this->src 			= $parent->getPath("source");
		$this->installer 	= new JInstaller();
		$lang 				= &JFactory::getLanguage();
		
		$lang->load("com_weever");
		
		$document = &JFactory::getDocument();
		
		$this->installPackagedExtensions($manifest);		
		
	}
	
	
	protected function installPackagedExtensions($manifest)
	{
		
		$output = "
				<div style='clear:both'>
				
						<img src='components/com_weeverlogin/assets/icons/icon-48-weever_toolbar_title.png' style='float:left;padding-right:2em' />
						<h1 style='padding-top:0.625em;padding-bottom:1em;'>Weever Apps Login Component version ". $manifest->version ." (Beta)</h1>
						
				</div>
				";
	
		if( isset($manifest->plugins) )
			$output .= $this->installPlugins($manifest);
			
		if( isset($manifest->templates) )
			$output .= $this->installTemplates($manifest);
			
		if( isset($manifest->components) )
			$output .= $this->installComponents($manifest);
			
		
		$output	.= "<h2><a href='index.php?option=com_weeverlogin'>Go to Login Settings »»</a></h2>";
			
		echo $output;
			
	}
	
	
	protected function installPlugins($manifest)
	{
	
		$output = "";
	
		foreach( $manifest->plugins->plugin as $plugin ) 
		{
			
			$attributes 	= $plugin->attributes();
			$plg 			= $this->src.DS.$attributes['folder'].DS.$attributes['plugin'];
			
			$result = $this->installer->install($plg);
			
			if($result)
				$message = "<span style='color:green'>".JText::_("WEEVER_SUCCESS")."</span>";
			else
				$message = "<span style='color:red'>".JText::_("WEEVER_FAILED")."</span>";
			
			$output .= "<p>".JText::_("WEEVER_INSTALLING_PLUGIN").$attributes['folder'].DS.
							$attributes['plugin'].": <b>".$message."</b></p>";

			$result = $this->enablePlugin($attributes['plugin']);
			
			if($result)			
				$output .= "<p><i>".JText::_("WEEVER_ENABLED_PLUGIN").$attributes['name']."</i></p>";
			else 
				$output .= "<p style='color:red'><i>".JText::_("WEEVER_ENABLE_PLUGIN_ERROR").$attributes['plugin']."</i></p>";
				
		}
		
		return $output;

	}
	
		
	protected function installComponents($manifest)
	{
	
		$output = "";
	
		foreach( $manifest->components->component as $component ) 
		{
			
			$attributes 	= $component->attributes();
			$com 			= $this->src.DS.$attributes['folder'].DS.$attributes['component'];
			
			$result = $this->installer->install($com);
			
			if($result)
				$message = "<span style='color:green'>".JText::_("WEEVER_SUCCESS")."</span>";
			else
				$message = "<span style='color:red'>".JText::_("WEEVER_FAILED")."</span>";
			
			$output .= "<p>".JText::_("WEEVER_INSTALLING_COMPONENT").
							$attributes['component'].": <b>".$message."</b></p>";
				
		}
		
		return $output;

	}
	
	
	protected function installTemplates($manifest)
	{
	
		$output = "";
	
		foreach( $manifest->templates->template as $template ) 
		{
			
			$attributes 	= $template->attributes();
			$tmpl 			= $this->src.DS.'templates'.DS.$attributes['template'];
			
			// @ for 2.5.0 - 2.5.1 template install bug
			$result = @$this->installer->install($tmpl);
			
			if($result)
				$message = "<span style='color:green'>".JText::_("WEEVER_SUCCESS")."</span>";
			else
				$message = "<span style='color:red'>".JText::_("WEEVER_FAILED")."</span>";
			
			$output .= "<p>".JText::_("WEEVER_INSTALLING_TEMPLATE").'templates'.DS.
							$attributes['template'].": <b>".$message."</b></p>";
				
		}
		
		return $output;

	}
		
	
	protected function enablePlugin($ext, $type = 'plugin')
	{
	   
	   	$db = &JFactory::getDBO();
	   	
	   	$query = "UPDATE #__extensions ".
	   			"SET 	".$db->nameQuote('enabled')	." = 1 ".
	   			"WHERE	".$db->nameQuote('element')	." = ".$db->quote($ext)." ".
	   			"AND	".$db->nameQuote('type')	." = ".$db->quote($type)." ";
	   	
	   	$db->setQuery($query);
	   	$result = $db->query();
	   	
	   	return $result;
	
	}
	
	
	protected function getExtensionId($type, $name, $group='')
	{
	
	   	$db = &JFactory::getDBO();
	
		if($type=='plugin')
		{
			
			$query = "SELECT extension_id ".
					"FROM 	#__extensions ".
					"WHERE 	".$db->quoteName('type')."		= ".$db->quote($type)	." ".
					"AND 	".$db->quoteName('folder')."	= ".$db->quote($group)	." ".
					"AND 	".$db->quoteName('element')."	= ".$db->quote($name)	." ";
					
			$db->setQuery($query);
			$db->query();
			
		}
		else
		{
			
			$query = "SELECT extension_id ".
					"FROM #__extensions ".
					"WHERE 	".$db->quoteName('type')."		= ".$db->quote($type)." ".
					"AND 	".$db->quoteName('element')."	= ".$db->quote($name)." ";
					
			$db->setQuery($query);
			$db->query();
		
		}
			
		$result = $db->loadResult();
		
		return $result;
	
	}
   
	
	public function uninstall($parent) 
	{
	
	
		$manifest = $parent->get("manifest");
		$parent = $parent->getParent();
		$source = $parent->getPath("source");
		
		$lang = &JFactory::getLanguage();
		$lang->load("com_weever");
		
		$uninstaller = new JInstaller();
		
		if( isset( $manifest->plugins ) ) 
		{
		
			foreach($manifest->plugins->plugin as $plugin) 
			{
				$attributes 	= $plugin->attributes();
				// 'group' required for uninstall. 
				$id 			= $this->getExtensionId('plugin', $attributes['plugin'], $attributes['group']);
				
				$uninstaller->uninstall('plugin',$id,0);   			
			}
			
		}
		
		if( isset( $manifest->templates ) ) 
		{
		
			foreach($manifest->templates->template as $template) 
			{
				$attributes = $template->attributes();
				$id = $this->getExtensionId('template', $attributes['template']);
				$uninstaller->uninstall('template',$id);
			}
			
		}
		
		if( isset( $manifest->components ) ) 
		{
		
			foreach($manifest->components->component as $component) 
			{
				$attributes = $component->attributes();
				$id = $this->getExtensionId('component', $attributes['component']);
				$uninstaller->uninstall('component',$id);
			}
			
		}
			
	
	}
	
	
	public function update($parent) 
	{
	
		$manifest 			= $parent->get("manifest");
		$parent 			= $parent->getParent();	
		$this->src 			= $parent->getPath("source");
		$this->installer 	= new JInstaller();
		$lang 				= &JFactory::getLanguage();
		
		$lang->load("com_weever");
	
		$this->installPackagedExtensions($manifest);
			
	}
	
	
   public function preflight($type, $parent) 
   {

		//echo '<p>' . JText::_('COM_WEEVER_PREFLIGHT_' . $type . '_TEXT') . '</p>';
   }


   public function postflight($type, $parent) 
   {
		//echo '<p>' . JText::_('COM_WEEVER_POSTFLIGHT_' . $type . '_TEXT') . '</p>';

   }

}