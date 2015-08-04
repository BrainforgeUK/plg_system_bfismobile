<?php
/**
* @package   Plugin for detecting when site is visited by a mobile.
* @version   0.0.1
* @author    http://www.brainforge.co.uk
* @copyright Copyright (C) 2013 Jonathan Brain. All rights reserved.
* @license	 GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/
 
// no direct access
defined('_JEXEC') or die;

jimport( 'joomla.plugin.plugin');
jimport( 'joomla.environment.browser' );

class plgSystemBFismobile extends JPlugin {
  /**
   *
   */
  function onAfterInitialise() {
    if (defined('IS_MOBILE')) {
      return true;
    }

    if (!get_cfg_var('is_mobile')) {
      // Catch mobiles defined in Joomla
   		$browser = JBrowser::getInstance();
      if (!$browser->ismobile()) {
    		if (!isset($_SERVER['HTTP_USER_AGENT'])) {
          return true;
  		  }
      	$additional = $this->params->get('additional-mobiles');
     	  if (empty($additional)) {
     	    return true;
   	    }
 	      $mobiles = explode(',', strtolower($additional));
   			$agent = strtolower(trim($_SERVER['HTTP_USER_AGENT']));
        $found = false;
        foreach($mobiles as $mobile) {
          $mobile = trim($mobile);
          if (empty($mobile)) {
            continue;
          }
          if (stripos($agent, $mobile) !== false) {
            $found = true;
            break;
          }
        }
        if (!$found) {
     	    return true;
        }
      }
    }

    define('IS_MOBILE', true);
 		if (isset($_SERVER['HTTP_HOST'])) {
    	$mobilesiteuri = $this->params->get('mobile_site_uri');
      if (!empty($mobilesiteuri)) {
        if ($mobilesiteuri != $_SERVER["HTTP_HOST"]) {
          JFactory::getApplication()->redirect('http://' . $mobilesiteuri . @$_SERVER["REQUEST_URI"]);
        }
      }
    }
    return true;
  }
}
?>