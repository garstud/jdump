<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
 
jimport('joomla.form.formfield');
 
// The class name must always be the same as the filename (in camel case)
class JFormFieldCurrentIP extends JFormField {
 
	//The field class must know its own type through the variable $type.
	protected $type = 'currentip';
 
/*	public function getLabel() {
		// code that returns HTML that will be shown as the label
	}
*/
	public function getInput() {
		// code that returns HTML that will be shown as the form field
		$userIP = $this->_getClientIP();
		return $userIP;
	}
    
	
	// Function to get the client IP address
	private function _getClientIP() {
        // priority ordored server IP variables names
        $tSrvIpVars = array('REMOTE_ADDR', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED');
		$ipAddress = 'undefined';
        foreach($tSrvIpVars as $ipVar) {
            if (isset($_SERVER[$ipVar])) {
                $ipAddress = $_SERVER[$ipVar];
                if(function_exists("dump")) dump($ipAddress, "getting IP with ".$ipVar);
                break;
            }
        }
		return $ipAddress;
	}  
    
}
