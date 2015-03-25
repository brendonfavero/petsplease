<?php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.4
Copyright (c) 2001-2014 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.4.4-55-g2f2d33e
## 
##################################

//make sure errors are not shown.
error_reporting(0);

define ('MIN_MYSQL', '4.1.0');
define ('MIN_PHP', '5.2.3');

//un-comment to see errors..
ini_set('display_errors','stdout');

//Fix for stupid sites that have magic_quotes_runtime turned on...  Must turn it off!
if (function_exists('set_magic_quotes_runtime') && get_magic_quotes_runtime()) {
	//must check for function first, since function will be removed from PHP in
	//future, along with ability to turn this stupid setting on.  Hooray!
	set_magic_quotes_runtime(false);
}

//make sure we have enough memory to load the upgrade script, since it takes
//more than normal.
require_once('../ini_tools.php');
//make sure it is at least 128 megs (more needed on 64bit servers)
geoRaiseMemoryLimit('128M');

require_once('../config.default.php');
if (!defined('PHP5_DIR')) { define('PHP5_DIR','php5_classes/'); }

require_once CLASSES_DIR . PHP5_DIR . 'smarty/Smarty.class.php';

##  Do a few checks:
if (!is_writable(GEO_BASE_DIR . 'templates_c/')) {
	die ('Upgrade error: you need to make the directory <strong>'.GEO_BASE_DIR.'templates_c/</strong> writable (CHMOD 777).');
}


/**
 * Checks the min requirements, needs to work with PHP4 and not be encoded,
 * so that it can properly display requirement check page even when requirements
 * are not met.
 * 
 * @package Update
 */
class geoReq
{
	//If this is set to true, will use templates to show what it looks like when
	//PHP checks fail...  See upgrade/templates/requirement_check_php_fail.tpl.php
	var $regen_php_failed = false;
	
	//set to true to test the "PHP Failed" script
	var $view_php_failed_tpl = false;
	
	/**
	 * Replaces the template body with the requirement check.
	 * Uses repuirement_check.php and requirement_check.html.
	 */
	function reqCheck(){
		$this->step_text = 'Requirement Check';
		
		$failed = '<span class="failed"><img src="images/no.gif" alt="no" title="no"></span>';
		$passed = '<span class="passed"><img src="images/yes.gif" alt="yes" title="yes"></span>';
		$not_needed = '---';
		if ($this->regen_php_failed) {
			$not_tested_debug = 'Not Tested, since PHP version check failed above.';
		}
		$overall_fail = '';
		$overall_pass = '<p class="passed">All minimum requirements met.</p>';
		//license agreement
		if (file_exists('../docs/license.html')) {
			$checkbox = '<p><label><input type="checkbox" name="license" id="license" /> Yes, I have read and agree to the <a href="../docs/license.html" class="login_link">License Agreement</a></label></p>';
		} else {
			$checkbox = '<p><label><input type="checkbox" name="license" id="license" /> Yes, I have read and agree to the Software License Agreement, which is available in the client area of the Geodesic cart system.</label></p>';
		}
		if (!$this->regen_php_failed && defined('IAMDEVELOPER')){
			$overall_fail .= $checkbox;
		}
		$overall_pass .= $checkbox;
		//back up agreement
		$overall_pass .= '<p><label><input type="checkbox" name="backup_agree" id="backup_agree" /> Yes, I have <strong>backed up</strong> the entire database and all files.</label></p>';
		if (!$this->regen_php_failed && defined('IAMDEVELOPER')){
			$overall_fail .= '<p><label><input type="checkbox" name="backup_agree" id="backup_agree" /> Yes, I have <strong>backed up</strong> the entire database and all files.</label></p>';
		}
		
		$package = 'both';
		if (file_exists('package.php')) {
			$package = include 'package.php';
		}
		if ($package=='both') {
			$overall_fail .= '<p class="body_txt1"><div style="text-align: left; background-color: #FFF; padding: 5px; border: 1px solid #EA1D25;"><span class="failed">IMPORTANT: As shown above, one or more of your server\'s minimum requirements have not been met.  These requirements must be met in order to continue with this installation.
	<br><br>NOTE: Zend Optimizer and IonCube are FREELY available for your host to download and install on your server. There is NO COST to your host, since the version that needs to be installed is the
	"decryption" version.
	<br><br>Hosting Trouble? Find our recommended hosting solutions by <a href="http://geodesicsolutions.com/resources.html" class="login_link" target="_blank">CLICKING HERE</a>.</span></div></p>
	<p>Please refer to the <a href="http://geodesicsolutions.com/support/wiki/update/start" class="login_link" target="_blank">Geodesic Solutions User Manual</a>.</p>';
		} else {
			//version with no reference to zend optimizer
			$overall_fail .= '<p class="body_txt1"><div style="text-align: left; background-color: #FFF; padding: 5px; border: 1px solid #EA1D25;"><span class="failed">IMPORTANT: As shown above, one or more of your server\'s minimum requirements have not been met.  These requirements must be met in order to continue with this installation.
	<br><br>NOTE: IonCube loaders are FREELY available for your host to download and install on your server. There is NO COST to your host, since the version that needs to be installed is the
	"decryption" version.
	<br><br>Hosting Trouble? Find our recommended hosting solutions by <a href="http://geodesicsolutions.com/resources.html" class="login_link" target="_blank">CLICKING HERE</a>.</span></div></p>
	<p>Please refer to the <a href="http://geodesicsolutions.com/support/wiki/update/start" class="login_link" target="_blank">Geodesic Solutions User Manual</a>.</p>';
		}
		
		$continue_pass = '<input type="submit" name="continue" value="Continue >>" />';
		$continue_fail = '';
		if (!$this->regen_php_failed && defined('IAMDEVELOPER')){
			//allow to keep going even if req fail, if developer..
			$continue_fail = $continue_pass;
		}
		//req text
		$php_version_req = 'PHP Version '.MIN_PHP.'+';
		$mysql_req = 'MySQL Version '.MIN_MYSQL.'+';
		$ioncube_ini_req = 'ionCube Loader';
		$zend_req = 'Zend Optimizer';
		
		//start out with passed message, then replace if one of the requirements fail.
		$overall = $overall_pass;
		//start out with the continue as pass, then replace if one of the requirements fail.
		$continue = $continue_pass;
		
		
		
		$this->tplVars['package'] = $package;
		
		////PHP VERSION CHECK
		$version_num = phpversion();
		if ($this->regen_php_failed) {
			$version_num = '<?php echo phpversion(); ?>';
		}
		$php = ($this->regen_php_failed)? false : version_compare($version_num, MIN_PHP, '>=');
		$php_text = 'PHP '.$version_num;
		
		if (!$php) {
			$overall = $overall_fail;
			$continue = $continue_fail;
		}
		
		//replace php version text
		$this->tplVars['php_version_text'] = $php_text;
		//replace php version check result
		$this->tplVars['php_version_result'] = ($php)? $passed: $failed;
		//replace php version req text
		$this->tplVars['php_version_req'] = $php_version_req;
		
		//safe mode text
		$safe_mode = $this->safeModeCheck();
		$this->tplVars['safe_mode_text'] = ($safe_mode)? 'safe_mode is OFF' : 'safe_mode is ON!';
		//replace safe mode check result
		$this->tplVars['safe_mode_result'] = ($safe_mode)? $passed : $failed;
		//replace safe mode req text
		$this->tplVars['safe_mode_req'] = 'PHP safe_mode OFF';
		
		if (!$safe_mode) {
			$overall = $overall_fail;
			$continue = $continue_fail;
		}
		
		
		
		$this->tplVars['body_tpl'] = 'requirement_check.tpl';
		
		////MYSQL CHECK
		$mysql = $this->mysqlCheck($text, $php);
		//replace mysql text
		$this->tplVars['mysql_text'] = $text;
		//replace mysql check result
		$this->tplVars['mysql_result'] = ($mysql)? $passed : $failed;
		//replace php version req text
		$this->tplVars['mysql_req'] = $mysql_req;
		if (!$mysql) {
			$overall = $overall_fail;
			$continue = $continue_fail;
		}
		////IONCUBE INI CHECK
		$ioncube_ini = $this->ioncubeCheck($ioncube_ini_text, $package);
		//replace ioncube ini req text
		$this->tplVars['ioncube_ini_req'] = $ioncube_ini_req;
		
		if ($package=='both') {
			////ZEND CHECK
			$zend = $this->zendCheck($zend_text);
			//replace zend req text
			$this->tplVars['zend_req'] = $zend_req;
		}
		////See which loader will be used
		if ($ioncube_ini) {
			//ioncube ini will be used.
			if ($package=='both') {
				$reason = 'since ionCube Loader is installed.';
				
				if ($zend){
					$zend_text = 'Installed, but not used '.$reason;
				} else {
					$zend_text = 'Not Installed, but not needed '.$reason;
				}
				//change failed message to the not needed message, since one of the requirements was met.
				$failed = $not_needed;
			}
		} else if ($package=='both' && $zend){
			//zend will be used.
			$reason = 'since Zend Optimizer is installed';
			
			$ioncube_ini_text .= 'Not Loaded, but not needed '.$reason;
			
			//change failed message to the not needed message, since one of the requirements was met.
			$failed = $not_needed;
		} else {
			//use all the default returned text values.
			//keep the failed message as failed, since all 3 requirements failed.
			$overall = $overall_fail;
			$continue = $continue_fail;
		}
		
		
		//replace runtime result
		$this->tplVars['ioncube_runtime_result'] = ($ioncube_runtime)? $passed : $failed;
		//replace ini result
		$this->tplVars['ioncube_ini_result'] = ($ioncube_ini)? $passed : $failed;
		//replace zend result
		$this->tplVars['zend_result'] = ($zend)? $passed : $failed;
		
		//replace runtime text
		$this->tplVars['ioncube_runtime_text'] = $ioncube_runtime_text;
		//replace runtime text
		$this->tplVars['ioncube_ini_text'] = $ioncube_ini_text;
		//replace runtime text
		$this->tplVars['zend_text'] = $zend_text;
		
		if ($this->regen_php_failed) {
			//we are pretending PHP is failing, in order to re-generate the
			//requirement check php file..
			
			//safe mode
			$this->tplVars['safe_mode_text'] = $not_tested_debug;
			$this->tplVars['safe_mode_result'] = $not_needed;
			
			//mysql
			$this->tplVars['mysql_text'] = $not_tested_debug;
			$this->tplVars['mysql_result'] = $not_needed;
			
			//ioncube
			$this->tplVars['ioncube_ini_text'] = $not_tested_debug;
			$this->tplVars['ioncube_ini_result'] = $not_needed;
			
			//zend
			$this->tplVars['zend_text'] = $not_tested_debug;
			$this->tplVars['zend_result'] = $not_needed;
		}
		
		//replace overall text
		$this->tplVars['overall_result'] = $overall;
		//replace continue button yo.
		$this->tplVars['continue'] = $continue;
		
		//developer force version form
		if(defined('IAMDEVELOPER') && !$this->regen_php_failed) {
			$developer = '<p>DEVELOPER FEATURE: Force upgrade to version: <input type="text" name="force_version" value="7.4.4" /><br /><input type="submit" value="Force Version >>" /></p>';
		} else {
			$developer = '';
		}
		$this->tplVars['developer_force_version'] = $developer;
	}
	/**
	 * Takes the template, does tag substitution, and echos it.
	 */
	function display_page() {
		//replace the upgrade step with the text.
		$this->tplVars['upgrade_step'] = $this->step_text;
		//replace the header text
		$this->tplVars['head'] = $this->head_text;
		
		if ($this->view_php_failed_tpl || version_compare(phpversion(), MIN_PHP, '<')) {
			//does not meet PHP 5.2 requrements, use dummy requirements page
			require 'templates/requirement_check_php_fail.tpl.php';
			return;
		}
		
		$tpl = new Smarty();
		$tpl->compile_dir = GEO_BASE_DIR.'templates_c';
		$tpl->template_dir = GEO_BASE_DIR . 'upgrade/templates';
		
		//clear templates_c for sites that have weird timestamps, so it uses latest
		//update templates freshly compiled
		$tpl->clearCompiledTemplate();
		
		$tpl->assign($this->tplVars);
		
		$tpl->display('index.tpl');
	}
	function mysqlCheck (& $text, $php_check){
		if (!function_exists('mysql_connect')){
			//mysql not even installed.
			$text .= 'MySQL not installed, or not configured to work properly with PHP.';
			return false;
		}
		@include('../config.default.php');
		if ($php_check && isset($db_host) && $db_host != 'your_database_hostname' && strlen($db_host)){
			//if config.php is already set up, attempt to get server version.
			//adodb should be included by now.
			include_once(CLASSES_DIR.'adodb/adodb.inc.php');
			@$db =& ADONewConnection($db_type);
			
			@$db->Connect($db_host, $db_username, $db_password, $database);
			$info = $db->ServerInfo();
			if (is_array($info)){
				$mysql_version = $info['version'];
				if (strlen($mysql_version)){
					$version_comp = version_compare($mysql_version, MIN_MYSQL);
					
					$text .= 'MySQL '.$mysql_version;
					
					if ($version_comp == -1){
						//mysql is a less version.
						return false;
					} else {
						return true;
					}
				}
			}
		}
		$reason = '(database connection settings not configured in config.php) - Version will be checked at the db connection step.';
		if (!$php_check) {
			//not checked, since if before PHP 5, the mysql check will cause a fatal
			//syntax error.
			$reason = '(Not checked since PHP requirement failed)';
		}
		$text = 'MySQL - Version not known '.$reason;
		//version not known, but mysql is at least installed, so proceed.
		return true;
	}
	
	function ioncubeCheck (& $text, $package){
		$loaded = extension_loaded('ionCube Loader');
		if ($loaded){
			if ($package=='both') {
				$text .= 'Loaded.  This will be the used loader method';
			} else {
				$text .= 'Installed';
			}
			if(version_compare(phpversion(), '5.5.0', '>=')) {
				$text .= '<p><strong>IMPORTANT:</strong> PHP 5.5+ may require specially-encoded files, which may be obtained from <a href="https://geodesicsolutions.com/client-area/task,helpdesk/">Geodesic Support</a></p>';
			}
			return true;
		} 
		$url = "http://www.ioncube.com/loader_installation.php";
		if (file_exists('../ioncube/loader-wizard.php')){
			$url = "../ioncube/loader-wizard.php";
		}
		if ($package=='both') {
			$text .= '<span style="font-weight: bold; color:#EA1D25;">Not Loaded.</span>  If you wish to use this loader method, <a href="'.$url.'" class="login_link">you can find instructions here.</a>';
		} else {
			$text .= '<span style="font-weight: bold; color:#EA1D25;">Not Installed.</span>  You can find instructions <a href="'.$url.'" class="login_link">here</a>.';
		}
		
		return false;
	}
	function zendCheck (& $text){
		$version_num = phpversion();
		
		if(!version_compare($version_num, '5.3.0', '<')) {
			$text .= '<strong>Not Available</strong> because PHP version is 5.3.0 or higher';
			return false;
		}
		
		if (function_exists('zend_loader_enabled') && zend_loader_enabled()){
			$text .= 'Installed.  This will be the used loader method';
			return true;
		} 
		$text .= '<span style="font-weight: bold; color:#EA1D25;">Not Installed.</span>  If you wish to use this loader method, you must have your host or system administrator
	install Zend Optimizer.  More info can be found at the <a href="http://www.zend.com/products/zend_optimizer" class="login_link">Official Zend Website.</a>';
	
		return false;
	}
	function safeModeCheck ()
	{
		$safe_mode_value = ini_get('safe_mode');
		if ($safe_mode_value=='off') {
			//true is "passed"
			return true;
		} else if ($safe_mode_value=='on') {
			//false is "failed"
			return false;
		}
		//safe mode not 'on' or 'off' so must be 1 or 0...
		return !$safe_mode_value;
	}
}
if (isset($_GET['resetProgress']) && $_GET['resetProgress']) {
	//reset the update progress, can be used when a previous update was not "finished"
	//resulting in error.
	require_once 'updateFactory.php';
	
	$upgrade = new geoUpdateFactory();
	$upgrade->removeUpgradeTables();
	//clear templates_c, but don't bother with main cache
	$upgrade->clearCache();
	//send them back to main page
	header('Location: index.php');
	exit;
}

if($_GET['run'] == 'show_upgrades' && !isset($_POST['license'])){
	die('You must agree to the License Agreement to proceed with the upgrade. Please <a href="index.php">go back</a>, read the License Agreement, and click the appropriate checkbox before continuing.');
} elseif ($_GET['run'] == 'show_upgrades' && !isset($_POST['backup_agree'])){
	die('You must create a site backup to proceed with the upgrade. Once you have created a backup, please <a href="index.php">go back</a>, and click the appropriate checkbox before continuing.');
} else if (isset($_GET['force_version']) && defined('IAMDEVELOPER'))
{
	require_once 'updateFactory.php';

	$upgrade = new geoUpdateFactory();
	if($upgrade->updateCurrentVersion($_GET['force_version']))
		echo "updated version to ".$_GET['force_version']."<br><a href=\"index.php\">Back to Upgrade Page</a>";
} elseif(!isset($_GET['run'])) { 
	// Do prereq check - note that this class works in PHP4 and without Ioncube or Zend...
	
	$checks = new geoReq();
	$checks->reqCheck();
	$checks->display_page();
} elseif ($_GET['run'] == 'show_log'){
	//output the log, don't do any processing to avoid
	//the log database being changed...
	require_once 'updateFactory.php';

	$upgrade = new geoUpdateFactory();
	
	$upgrade->unSerialize();
	$upgrade->showLog();
	$upgrade->display_page();
} else {
	// Run the upgrade(s)
	require_once 'updateFactory.php';

	$upgrade = new geoUpdateFactory();
	
	$result = $upgrade->factory();
	//var_dump($result);
	
	//show the results page
	$upgrade->show_results();
	$upgrade->display_page();
}