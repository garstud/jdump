<?php
defined('_JEXEC') or die('Restricted access');
 
class com_dumpInstallerScript
{
	private $extensionTitle = "JDump";
	private $extensionName = "com_dump";
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		JFactory::getApplication()->enqueueMessage("install <b>".$this->extensionTitle."</b> succeed", 'success');
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option='.$this->extensionName);
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		// $parent is the class calling this method
		echo "<p>The component <b>".$this->extensionTitle."</b> has been unstalled!</p>";
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
		echo "<h2>Updating ".$this->extensionTitle."</h2>";
		echo "<pre>".$this->extensionTitle." has been updated to version ".$parent->get('manifest')->version."</pre>";
        if(function_exists("dump")) dump($parent->__toString(), "Parent toString");
        if(function_exists("dump")) dump($parent->getParent(), "Parent Object");
/*
http://docs.joomla.org/Manifest_files#Script_file
http://docs.joomla.org/Making_single_installation_packages_for_Joomla!_1.5,_1.6_and_1.7#Updating_doesn.27t_work_as_you.27d_expect

		$db = JFactory::getDBO();
		// Obviously you may have to change the path and name if your installation SQL file ;)
		if(method_exists($parent, 'extension_root')) {
				$sqlfile = $parent->getPath('extension_root').DS.'install.sql';
		} else {
				$sqlfile = $parent->getParent()->getPath('extension_root').DS.'install.sql';
		}
		// Don't modify below this line
		$buffer = file_get_contents($sqlfile);
		if ($buffer !== false) {
				jimport('joomla.installer.helper');
				$queries = JInstallerHelper::splitSql($buffer);
				if (count($queries) != 0) {
						foreach ($queries as $query)
						{
								$query = trim($query);
								if ($query != '' && $query{0} != '#') {
										$db->setQuery($query);
										if (!$db->query()) {
												JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
												return false;
										}
								}
						}
				}
		}
*/		
		
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		//JFactory::getApplication()->enqueueMessage("Installer preflight ".$this->extensionTitle." lors de <b>".$type."</b>", 'notice');                                                                         
		echo "<p>Install preparing task for ".$this->extensionTitle." : <b>".$type."</b></p>";
	}
 
	/**
	 * method to run after an install/update method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		JFactory::getApplication()->enqueueMessage("Install ending task ".$this->extensionTitle." : <b>".$type."</b>", 'notice');
		
		// Get a database connector object
		$db = JFactory::getDbo();

		$labelMsg = "Publishing plugin System: ".$this->extensionTitle."</p>";
		try
		{
			// Enable plugin by default
			$q = $db->getQuery(true);
			$q->update('#__extensions');
			$q->set(array('enabled=1', 'ordering = 1'));
			$q->where("element='dump'");
			$q->where("type='plugin'", 'AND');
			$q->where("folder='system'", 'AND');
			$db->setQuery($q);

			method_exists($db, 'execute') ? $db->execute() : $db->query();
			JFactory::getApplication()->enqueueMessage("<span class='icon-publish'></span> ".$labelMsg, 'notice');
		}
		catch (Exception $e)
		{
			JFactory::getApplication()->enqueueMessage("<span class='icon-unpublish'></span> ".$labelMsg, 'notice');
			throw $e;
		}
	}
}
