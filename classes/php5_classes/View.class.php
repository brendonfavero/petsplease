<?php
//View.class.php
/**
 * Holds the geoView class, which is responsible for (part of) the process of
 * rendering the page.
 * 
 * @package System
 * @since Version 4.0.0
 */
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## 
##    7.2.0-2-g2198a76
## 
##################################

/**
 * As the name implies, this is used for creating the page view.  It does take
 * a little setup (like any class), the setup is normally done in the
 * display_page method in geoSite, or display_page in geoAdmin if displaying a
 * page in the admin.
 * 
 * @package System
 * @since Version 4.0.0
 */
class geoView implements Iterator
{
	/**
	 * Instance of geoView
	 * @var geoView
	 * @internal
	 */
	protected static $_instance;
	
	/**
	 * Used internally
	 * @internal
	 */
	protected $_viewVars = array(), $_modules, $_page, $_language, $_category, 
		$_template, $_script_files, $_css_files, $_forceTemplateAttachment = false,
		$_isRendered = false, $_onlyNewVars = false, $_alwaysShowTemplateError = false;
	
	/**
	 * This is an array of info about different JS libraries
	 * @var array
	 */
	protected $_jsLibraries = array (
		'lib_prototype' => array (
			'local' => '/js/prototype.js',
			'googleAPI' => '//ajax.googleapis.com/ajax/libs/prototype/1.7.1.0/prototype.js',
			'version' => '1.7.1.0',
		),
		'lib_scriptaculous' => array (
			'local' => '/js/scriptaculous/scriptaculous.js',
			'googleAPI' => '//ajax.googleapis.com/ajax/libs/scriptaculous/1.9.0/scriptaculous.js',
			'version' => '1.9.0',
		),
		'lib_jquery' => array(
			'local' => '/js/jquery.min.js',
			'googleAPI' => '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',
			'version' => '1.9.1',
		),
		'lib_jquery_ui' => array(
			//NOTE: bundled version is not the full version provided in google 
			//api, either option will work though
			'local' => '/js/jquery-ui.min.js',
			'googleAPI' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js',
			'version' => '1.10.0',
		),
	);
	/**
	 * This is an array of information about different CSS libraries.  Currently
	 * only the CSS library used for jQuery-UI is configured.
	 * @var array
	 */
	protected $_cssLibraries = array (
		'lib_jquery_ui_css' => array (
			'local' => '/jquery-ui/jquery-ui.min.css',
			'googleAPI' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.0/themes/ui-lightness/jquery-ui.css',
			'version' => '1.10.0',
			),
		);
	
	const JS_LIB_PROTOTYPE = 'lib_prototype';
	
	const JS_LIB_SCRIPTACULOUS = 'lib_scriptaculous';
	
	const JS_LIB_JQUERY = 'lib_jquery';
	
	const JS_LIB_JQUERY_UI = 'lib_jquery_ui';
	
	const CSS_LIB_JQUERY_UI = 'lib_jquery_ui_css';
	
	/**
	 * Template
	 *
	 * @var geoTemplate
	 */
	protected $_tpl;
	
	/**
	 * Get an instance of the geoView object.  Uses singleton method.
	 *
	 * @return geoView
	 */
	public static function getInstance ()
	{
		if (!isset(self::$_instance) || !is_object(self::$_instance)) {
			$c = __CLASS__;
			self::$_instance = new $c; 
		}
		return self::$_instance;
	}
	
	/**
	 * Privately declared to prevent creating geoView class directly, instead use
	 * singleton geoView::getInstance() method to get view class.
	 * 
	 * @since Version 7.2.0
	 */
	private function __construct()
	{
		//doesn't do anything, just here to stop attempts to create new object
		//in it's tracks
	}
	
	/**
	 * Lock the ability to set view variables to only allow setting variables that are not already
	 * set.  Usefull to allow addons to set a var and then block the main software from over-writting
	 * that setting later on.
	 *
	 */
	public function lockSetVarNewOnly()
	{
		$this->_onlyNewVars = true;
		return $this;
	}
	
	/**
	 * Un-lock the lock done by {@link geoView::lockSetNewOnly()}
	 *
	 */
	public function unLockSetVarNewOnly()
	{
		$this->_onlyNewVars = false;
		return $this;
	}
	
	/**
	 * Clears all header html, css and js files to be added to header
	 */
	public function clearHeaderHtml ()
	{
		$this->_header_html = '';
		$this->_css_files = array();
		$this->_script_files = array();
	}
	
	/**
	 * Add stuff to the header of the page
	 *
	 * @param string $html
	 * @return geoView
	 */
	public function addTop($html)
	{
		if($html) {
			if (!isset($this->_header_html)) {
				$this->_header_html = $html;
			} else {
				$this->_header_html = $this->_header_html . $html;
			}
		}
		//echo 'adding to head: <pre>'.geoString::specialChars($html).'</pre>';
		return $this;
	}
	
	/**
	 * Add stuff to the boyd of the page
	 *
	 * @param string $html
	 * @return geoView
	 */
	public function addBody($html)
	{
		if($html) $this->body_html .= $html;
		return $this;
	}
	
	/**
	 * Add to a module's body HTML
	 * 
	 * It is recommended to instead use a template.
	 *
	 * @param string $module
	 * @param string $html
	 * @return geoView
	 */
	public function addModuleHtml ($module, $html)
	{
		$vars = $this->modules;
		$vars[$module]['body'] = $html;
		$this->modules = $vars;
		return $this;
	}
	
	/**
	 * Add to an addon's body HTML
	 * 
	 * It is recommended to instead use a template.
	 * 
	 * @param string $author The auth_tag
	 * @param string $addon
	 * @param string $tag
	 * @param string $html
	 * @return geoView
	 */
	public function addAddonHtml ($author,$addon,$tag, $html)
	{
		$vars = $this->addons;
		$vars[$author][$addon][$tag]['body'] = $html;
		$this->addons = $vars;
		return $this;
	}
	
	/**
	 * Set the page for the view
	 * 
	 * @param mixed $page
	 */
	public function setPage($page)
	{
		$this->_page = $page;
	}
	
	/**
	 * Set the language ID for the page
	 * 
	 * @param int $language_id
	 */
	public function setLanguage($language_id)
	{
		$this->_language = intval($language_id);
		if (!$this->_language) {
			$this->_language = 1;
		}
	}
	
	/**
	 * Set the category ID for the current page.
	 * 
	 * @param int $category_id
	 */
	public function setCategory($category_id)
	{
		$this->_category = (int)$category_id;
	}
	
	/**
	 * Gets the category as previously set using $view->setCategory().
	 * 
	 * @return int
	 * @since Version 6.0.0
	 */
	public function getCategory ()
	{
		return (int)$this->_category;
	}
	
	/**
	 * Gets the language as previously set using $view->setLanguage().  This is
	 * for completeness only as it will not be set until fairly late in the page
	 * load, normally you would use getLanguage method in session
	 * or DataAccess class.
	 * 
	 * @return int
	 * @since Version 6.0.0
	 */
	public function getLanguage ()
	{
		return (int)$this->_language;
	}
	
	/**
	 * Gets the page set by $view->setPage(), which will be either null if not
	 * set yet, or a class that extends geoSite class or the geoSite class itself.
	 * 
	 * @return geoSite
	 * @since Version 6.0.0
	 */
	public function getPage ()
	{
		return $this->_page;
	}
	
	/**
	 * Renders the page.
	 * 
	 * @param int|string $page_id
	 * @param bool $return If true, will return the rendered template instead
	 *  of outputing it to the page.
	 *
	 */
	public function render ($page_id = null, $return = false)
	{
		$this->setRendered(true);
		
		if (!isset($this->_tpl)) {
			if ($this->_category) {
				geoTemplate::setCategory($this->_category);
			}
			if ($this->_language) {
				geoTemplate::setLanguage($this->_language);
			}
			$this->_tpl = new geoTemplate;
		} else {
			//be sure it has all the latest messages
			$this->_tpl->assign('messages', DataAccess::getInstance()->get_text(1));
		}
		if ($this->preview_mode === "preview_only") {
			$tpl = new geoTemplate('system','preview_window');
			$this->addTop($tpl->fetch('index'));
			$this->_tpl->loadFilter('output','strip_forms');
			if (defined('IN_ADMIN')) {
				//add base href to top in admin
				$this->_tpl->loadFilter('output','listing_preview_admin');
			}
		}
		
		if ($page_id == 'admin') {
			$this->_tpl->setAdmin();
			$this->_template = 'index';
		} else if ($page_id !== null) {
			$this->_tpl->setMainPage($page_id);
		}
		
		//pre-process any auto add head stuff
		$this->_preProcessAddons($this->_tpl->createTemplate($this->_template));
		if ($this->geo_inc_files['body_html'] && !$this->geo_inc_files['body_html_addon'] && !$this->geo_inc_files['body_html_system']) {
			//preprocess main_page
			$this->_preProcessAddons($this->_tpl->createTemplate($this->geo_inc_files['body_html']));
		}
		//let addons know about what modules are pre-loaded
		if (count($this->_modules)) {
			geoAddon::triggerUpdate('notify_modules_preload', $this->_modules);
		}
		
		$this->_header_html = $this->getJsHtml() . $this->getCssHtml() . $this->_header_html;
		//echo 'header html: <pre>'.geoString::specialChars($this->_header_html).'</pre>';
		$this->_tpl->assign($this->_viewVars);
		try {
			if ($return) {
				return $this->_tpl->fetch($this->_template);
			}
			$this->_tpl->display($this->_template);
		} catch (Exception $e) {
			$message = $this->_errorCaught($e);
			
			if ($return) {
				return $message;
			}
			echo $message;
		}
		return true;
	}
	
	/**
	 * Pass caught template errors to this to have them handled.
	 * 
	 * @param Exception $e
	 * @return string A string to display to the site
	 */
	private function _errorCaught ($e)
	{
		$db = DataAccess::getInstance();
		
		$email = $db->get_site_setting('site_email');
		
		$message = $e->getMessage();
		$parts = explode('"',$message);
			
		$path = '';
		if (count($parts)>4 && strpos($parts[1],'geo_tset')!==false) {
			//convert to something that can be read!
			$fparts = explode(':',$parts[1]);
		
			$path = "<strong>Template Set:</strong> {$fparts[1]}<br />
				<strong>Template Type:</strong> {$fparts[2]}<br />
				<strong>File:</strong> {$fparts[3]}".(($fparts[4])? '/'.$fparts[4]:'')."<br />
				<strong>Line:</strong> ".preg_replace('/[^0-9]*/','',$parts[2])."<br />
				<strong>Template Code:</strong> <br /><div style='border: 1px solid gray; padding: 5px; white-space: pre; height: 50px; overflow: auto; font-size: 12px;'>{$parts[3]}</div>";
		}
			
		$box = "<div style='padding: 10px; border: 3px solid red;'>";
			
		$url = $this->selfUrl();
		
		$emailMessage = "You are receiving this because a template error was generated on your site:<br />
					<strong>".$db->get_site_setting('classifieds_url')."</strong>
					<br /><br />See below for template error details:<br /><br />
					$box
						<strong>Full URL:</strong> <a href=\"{$url}\">{$url}</a><br /><br />
						{$path}
						<br /><br /><strong>Error Message:</strong> $message
					</div>
					<br /><br />
					<strong>DEBUG INFO:</strong>  See below for full environment information to help
					troubleshoot.
					<br /><br /><strong>Full Error & Backtrace:</strong><br /><pre>{$e}</pre><br /><br />
					<strong>Env Vars:</strong><br />
<pre>\$_SERVER = ".var_export($_SERVER,true).";
\$_GET = ".var_export($_GET,true)./* //DO NOT INCLUDE post vars, may contain sensitive data like CC info
"\$_POST = ".var_export($_POST,true).*/"
\$_COOKIE = ".var_export($_COOKIE,true)."</pre>";
		
		if (!defined('IN_ADMIN')&&!geoPC::is_trial()) {
			//only bother sending e-mail if not in admin... if in admin we're just
			//going to display the error.
			geoEmail::sendMail('brendon@ardex.com.au', 'Automated Admin Notice: TEMPLATE ERROR!', $emailMessage,
				0, 0, 0, 'text/html');
		}
					
		$message = "
		$box
			<strong>Oops! Template Error!</strong>  Please pardon our dust as we work to update the site.  We could not display the page due to a template error, sorry about that!<br /><br />
			Full template error details have just been e-mailed to the site admin.
		</div>";
		if (defined('IN_ADMIN') || defined('IAMDEVELOPER') || $this->_alwaysShowTemplateError
			|| isset($_COOKIE['debug']) || geoPC::is_trial()
		) {
			$message .= "<br /><strong>Technical Details:</strong><br />".$emailMessage;
		}
		return $message;
	}
	
	/**
	 * Gets the current full URL including query string for the currently viewed
	 * page.
	 * 
	 * @return string
	 */
	public function selfUrl ()
	{
		$url = geoFilter::getBaseHref().basename($_SERVER['SCRIPT_NAME']);
		
		//add GET parameters
		if (count($_GET)) {
			$url .= '?'.http_build_query($_GET);
		}
		
		return $url;
	}
	
	/**
	 * Pre-load template, used internally
	 * @param geoTemplate $template
	 * @internal
	 */
	private function _preload ($template)
	{
		//Borrowed from section of fetch()
		if (!$template->compiled->exists || ($template->smarty->force_compile && !$template->compiled->isCompiled)) {
			$template->compileTemplateSource();
		}
			
		if (!$template->compiled->loaded) {
			$_smarty_tpl = $template;
			include($template->compiled->filepath);
			if ($template->mustCompile) {
				// recompile and load again
				$template->compileTemplateSource();
				include($template->compiled->filepath);
			}
			$template->compiled->loaded = true;
		}
	}
	/**
	 * Pre-process addon tags
	 * @param geoTemplate $_tpl
	 * @internal
	 */
	private function _preProcessAddons ($_tpl)
	{
		if (!isset($this->_modules)) {
			$this->_modules = array();
		}
		try {
			$this->_preload($_tpl);
		} catch (Exception $e) {
			//don't do anything, error will be output by part that displays.
			return;
		}
		$tags = $_tpl->used_tags;
		
		if(!$tags) {
			//no tags found -- nothing to do here
			return;
		}
		
		foreach ($tags['module'] as $module) {
			$this->_modules[$module] = $module;
		}
		
		//pre-process addons (main reason for this)
		foreach ($tags['addon'] as $vars) {
			$info = geoAddon::getInfoClass($vars['addon']);
			if (!$info) {
				continue;
			}
			if (isset($vars['tag']) && in_array($vars['tag'], $info->tags)) {
				$tag = geoAddon::getInstance()->getTags($vars['addon']);
				$call = $vars['tag'].'_auto_add_head';
				if ($tag && is_callable(array($tag, $call))) {
					$tag->$call();
				}
			}
		}
		
		//make sure included templates are processed as well
		foreach ($tags['include'] as $vars) {
			$_incTpl = $_tpl->createTemplate($vars['file'], $_tpl);
			
			if ($vars['g_type']) {
				$_incTpl->gType($vars['g_type']);
			}
			if ($vars['g_resource']) {
				$_incTpl->gResource($vars['g_resource']);
			}
			$this->_preProcessAddons($_incTpl);
			unset($incTpl);
		}
	}
	
	/**
	 * Whether or not the given filename has already been added.
	 * 
	 * @param string $filename
	 * @return bool
	 */
	public function isCssFile($filename)
	{
		return (in_array($filename,$this->_css_files));
	}
	
	/**
	 * Whether or not the given filename has already been added.
	 * 
	 * @param string $filename
	 * @return bool
	 */
	public function isJScriptFile($filename)
	{
		return (in_array($filename,$this->_script_files));
	}
	
	/**
	 * Gets the HTML to insert into the page for all CSS files added.  Meant to
	 * be used by display page directly, inserted as part of {header_html}.
	 * 
	 * @return string
	 */
	public function getCssHtml()
	{
		$return = '';
		$append = $this->_getResourceAppend();
		if (isset($this->_css_files) && is_array($this->_css_files)) {
			foreach ($this->_css_files as $filename) {
				if(substr($filename,-4) === ".css") {
					//only append to CSS files that don't already have query strings
					$filename = $filename."?_=$append";
				}
				$return .= "<link rel='stylesheet' type='text/css' href='$filename' />\n";
			}
		}
		return $return;
	}
	
	/**
	 * Gets the HTML to insert into the page for all JS files added.  Meant to
	 * be used by display page directly, inserted as part of {header_html}.
	 * 
	 * @return string
	 */
	public function getJsHtml()
	{
		$return = '';
		if ($this->_useGoogleLibs) {
			//add the thingy to the top needed for loading google API libraries.
			$return .= "<script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>\n";
		}

		$append = $this->_getResourceAppend();
		foreach ($this->_script_files as $filename) {
			if(substr($filename,-3) === ".js") {
				//only append to JS files that don't already have query strings (mostly for compatibility with Google Maps)
				$filename = $filename."?_=$append";
			}
			$return .= "<script type=\"text/javascript\" src=\"$filename\"></script>\n";
		}
		return $return;
	}
	
	/**
	 * Gets a string that is suitable for appending to the query string of js/css resources to prevent stale-caching across updates.
	 * For security, hashes in the site URL (that way, each site has a unique string, so there's no way to google for all sites running a specific version)
	 * @return String
	 * @since 7.1.0
	 */
	private function _getResourceAppend()
	{
		return substr(sha1(DataAccess::getInstance()->get_site_setting('classifieds_url').geoPC::getVersion()),0,5);
	}
	
	/**
	 * Sets a variable that will be local in scope to the body_html template.
	 * This works similar to smarty->assign() function.
	 *
	 * @param string|mixed $var1
	 * @param mixed $var2
	 * @return geoView
	 */
	public function setBodyVar ($var1, $var2 = null)
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$body_vars = $this->body_vars;
		if (isset($var2)) {
			$body_vars [$var1] = $var2;
		} else if (is_array($var1)) {
			$body_vars = (is_array($body_vars))? $body_vars: array();
			$body_vars = array_merge($body_vars, $var1);
		}
		$this->body_vars = $body_vars;
		return $this;
	}
	
	/**
	 * Sets a variable that will be local in scope to the header_html template.
	 * This works similar to smarty->assign() function.
	 *
	 * @param string|mixed $var1
	 * @param mixed $var2
	 * @return geoView
	 */
	public function setHeaderVar ($var1, $var2 = null)
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$vars = $this->header_vars;
		if ($var2 !== null) {
			$vars [$var1] = $var2;
		} else if (is_array($var1)) {
			$vars = (is_array($vars))? $vars: array();
			$vars = array_merge($vars, $var1);
		}
		$this->header_vars = $vars;
		return $this;
	}
	
	/**
	 * Sets a variable that will be local in scope to a module template.
	 * This works similar to smarty->assign() function.
	 *
	 * @param string $module_name module's tag name.
	 * @param string|mixed $var1
	 * @param mixed $var2
	 * @return geoView
	 */
	public function setModuleVar ($module_name, $var1, $var2 = null)
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$modules = $this->module_vars;
		
		if (isset($var2)) {
			$modules [$module_name][$var1] = $var2;
		} else if (is_array($var1)) {
			$existing = (isset($modules[$module_name]))? $modules[$module_name]: array();
			$modules[$module_name] = array_merge($existing, $var1);
		}
		$this->module_vars = $modules;
		return $this;
	}
	
	/**
	 * Sets a variable that will be local in scope to a addon template.
	 * This works similar to smarty->assign() function.
	 *
	 * @param string $author_tag
	 * @param string $addon_name
	 * @param string $tag
	 * @param string|mixed $var1
	 * @param mixed $var2
	 * @return geoView
	 */
	public function setAddonVar ($author_tag, $addon_name, $tag, $var1, $var2 = null)
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$addons = $this->addon_vars;
		if (isset($var2)) {
			$addons [$author_tag][$addon_name][$tag][$var1] = $var2;
		} else if (is_array($var1)) {
			$existing = (isset($addons [$author_tag][$addon_name][$tag]))? $addons [$author_tag][$addon_name][$tag]: array();
			$addons [$author_tag][$addon_name][$tag] = array_merge($existing, $var1);
		}
		$this->addon_vars = $addons;
		return $this;
	}
	
	/**
	 * Sets the template file that will be included for the body_html in the
	 * template.
	 *
	 * @param string $tpl_file
	 * @param string $addon_name If specified, will let system know to find the
	 *  template in the addon's templates, not under system.
	 * @param string $system_resource If specified, will use system template and
	 *  resource specified
	 * @return geoView for easy chaining
	 */
	public function setBodyTpl($tpl_file, $addon_name = '', $system_resource = '')
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$geo_inc_files = (isset($this->_viewVars['geo_inc_files']))? $this->_viewVars['geo_inc_files']: array();
		if (!is_array($geo_inc_files)) {
			$geo_inc_files = array();
		}
		$geo_inc_files['body_html'] = $tpl_file;
		if ($addon_name) {
			$geo_inc_files['body_html_addon'] = $addon_name;
			//in case system was previously set, un-set it
			unset($geo_inc_files['body_html_system']);
		} else if ($system_resource) {
			$geo_inc_files['body_html_system'] = $system_resource;
			//in case addon was previously set, un-set it
			unset($geo_inc_files['body_html_addon']);
		} else {
			//must be setting admin...  unset both addon and system resources in
			//case those were previously set (specifically, to allow cart in admin panel)
			unset($geo_inc_files['body_html_system'], $geo_inc_files['body_html_addon']);
		}
		$this->_viewVars['geo_inc_files'] = $geo_inc_files;
		return $this;
	}

	/**
	 * Sets the template file that will be included for the header_html in the
	 * template.
	 *
	 * @param string $tpl_file
	 * @param string $addon_name If specified, will let system know to find the
	 *  template in the addon's templates, not under system.
	 * @param string $system_resource If specified, will let system know to find
	 *   the template in the system folder location
	 * @return geoView for easy chaining
	 */
	public function setHeaderTpl ($tpl_file, $addon_name = '', $system_resource = '')
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$geo_inc_files = $this->_viewVars['geo_inc_files'];
		if (!is_array($geo_inc_files)) {
			$geo_inc_files = array();
		}
		$geo_inc_files['header_html'] = $tpl_file;
		if ($addon_name) {
			$geo_inc_files['header_html_addon'] = $addon_name;
		} else if ($system_resource) {
			$geo_inc_files['header_html_system'] = $system_resource;
		}
		$this->_viewVars['geo_inc_files'] = $geo_inc_files;
		return $this;
	}
	
	/**
	 * Sets the template file that will be included for a specific
	 * module.
	 *
	 * @param string $module_tag The tag, in the tpl it will be {module tag=$module_tag}
	 * @param string $tpl_file
	 * @return geoView for easy chaining
	 */
	public function setModuleTpl($module_tag, $tpl_file)
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$geo_inc_files = (isset($this->_viewVars['geo_inc_files']))? $this->_viewVars['geo_inc_files']: array();
		if (!is_array($geo_inc_files)) {
			$geo_inc_files = array();
		}
		
		$geo_inc_files['modules'][$module_tag] = $tpl_file;
		$this->_viewVars['geo_inc_files'] = $geo_inc_files;
		return $this;
	}
	
	/**
	 * Sets the template file that will be included for a specific
	 * addon.
	 *
	 * @param string $auth_tag
	 * @param string $addon_name
	 * @param string $tag
	 * @param string $tpl_file
	 * @return geoView for easy chaining
	 */
	public function setAddonTpl($auth_tag, $addon_name, $tag, $tpl_file)
	{
		//don't use this->_viewVars directly to avoid possible 
		//problems with the iteration.
		$geo_inc_files = $this->_viewVars['geo_inc_files'];
		if (!is_array($geo_inc_files)) {
			$geo_inc_files = array();
		}
		$geo_inc_files['addons'][$auth_tag][$addon_name][$tag] = $tpl_file;
		$this->_viewVars['geo_inc_files'] = $geo_inc_files;
		return $this;
	}
	
	/**
	 * Force the overall page to use a specific template file instead of determining which
	 * template to use by looking at the templates to page file.
	 * 
	 * This is handy to use in situations where what template to use is determined by other
	 * factors than the current language, category, and page.
	 *
	 * @param string $template_file The template file to use, relative to the main_page directory
	 *  in the templates.
	 * @return geoView For easy method chaining.
	 */
	public function forceTemplateAttachment($template_file)
	{
		$this->_template = $template_file;
		$this->_forceTemplateAttachment = true;
		return $this;
	}
	
	/**
	 * Gets the template attached to the specified "page id", which can actually
	 * be an integer or a string, but typically it's just an integer that goes with
	 * a specific page.  You would use a string when there are multiple templates other
	 * than the main one, for instance a classified details template.
	 * 
	 * Note that this function relies on setCategory and setLanguage to be called prior, in
	 * order to get the attachment specific for the current language and category.  If none
	 * specific to the language or category are found, the default for lang 1 cat 0 is returned.
	 *
	 * @param int|string $page_id Typically the page id, or a string like: PAGE_ID_sub_page_name
	 * @param int|null $languageId Optional, if not specified, will use the current
	 *   language set in the view object.
	 * @param int|null $categoryId Optional, if not specified, will use the current
	 *   category set in the view object.
	 * @param bool $strict
	 * @return string The filename for the attached template to use, specific to the language and
	 *  category.
	 */
	public function getTemplateAttachment($page_id, $languageId=null, $categoryId=null, $strict = true)
	{
		if (!isset($this->_tpl)) {
			//set cat and language in template before creating new template
			//so that those vars will be available to the template.
			$cat = (int)($categoryId===null)? $this->_category : $categoryId;
			$lang = (int)($languageId===null)? $this->_language : $languageId;
			if ($cat) {
				geoTemplate::setCategory($cat);
			}
			if ($lang) {
				geoTemplate::setLanguage($lang);
			}
			
			$this->_tpl = new geoTemplate;
		}
		
		if ($languageId !== null || $categoryId !== null || !$this->_forceTemplateAttachment) {
			if ($languageId === null) {
				$languageId = $this->_language;
			}
			if ($categoryId === null) {
				$categoryId = $this->_category;
			}
			$languageId = (int)$languageId;
			$categoryId = (geoPC::is_ent())? (int)$categoryId : 0;
			$templates = $this->getTemplateAttachments($page_id, $strict);
			if (!$strict && !$templates) {
				//not strict, and template not found, return empty string.
				return '';
			}
			//figure out which language/category to use
			$cat_id = 0;
			$lang_id = 1;
			if ($this->isAffiliatePage && $this->affiliate_group_id) {
				//this takes precedence, check to see if there is an affiliate
				//group-specific setting
				$gId = (int)$this->affiliate_group_id;
				
				if ($gId && isset($templates['affiliate_group'][$languageId][$gId])) {
					$lang_id = $languageId;
				}
				
				if ($gId && isset($templates['affiliate_group'][$lang_id][$gId])) {
					//we found the attachment!
					return $templates['affiliate_group'][$lang_id][$gId];
				}
				//group specific template not found, despite being affiliate page
			}
			if (isset($templates[$languageId][0]) || isset($templates[$languageId][$categoryId])) {
				//template set for specific language and main category, or specific language and specific category,
				//so use the template assignment for this specific language
				$lang_id = $languageId;
			}
			if (isset($templates[$lang_id][$categoryId])) {
				//template is set for specific category, so use that template assignment
				$cat_id = $categoryId;
			}
			
			if (!isset($templates[$lang_id][$cat_id])) {
				//Error: the template was not specified, this would only happen if
				//there is no template assignment for even the default of lang 1 category 0.
				throw new Exception ("No template found for page $page_id language $lang_id and category $cat_id (local: $this->_language $this->_category");
			}
			return $templates[$lang_id][$cat_id];
		} else {
			return $this->_template;
		}
	}
	
	/**
	 * Get all the template attachments for the specified page ID.
	 * 
	 * @param string|int $page_id
	 * @param bool $strict If false, will simply return false or empty array when
	 *   template assignment could not be determined.  Default is to display
	 *   error message saying the problem, and end the script.
	 * @return array The array of templates to page attachments.
	 * @since Version 5.0.0
	 */
	public function getTemplateAttachments ($page_id, $strict = true)
	{
		$file = geoTemplate::getFilePath('main_page','attachments',"templates_to_page/{$page_id}.php", $strict);
		if ($strict) {
			$templates = require $file;
		} else {
			$templates = include $file;
		}
		return $templates;
	}
	
	/**
	 * Gets all of the variables set so far.
	 *
	 * @return array
	 */
	public function getAllAssignedVars ()
	{
		return $this->_viewVars;
	}
	
	/**
	 * Gets all the assigned body vars set so far:: vars that are assigned using
	 * {@link geoView::setBodyVar()}
	 * 
	 * @return array The array of vars set so far, or an empty array if no body
	 *   vars have been set yet.
	 * @since Version 5.0.0
	 */
	public function getAssignedBodyVars ()
	{
		return isset($this->_viewVars['body_vars'])? $this->_viewVars['body_vars'] : array();
	}
	
	/**
	 * Gets all the assigned addon vars set so far:: vars that are assigned using
	 * {@link geoView::setAddonVar()}
	 * 
	 * @param string $author_tag
	 * @param string $addon_name
	 * @param string $tag
	 * @return array The array of vars set so far, or an empty array if no addon
	 *   vars have been set yet for the specified tag.
	 * @since Version 5.0.0
	 */
	public function getAssignedAddonVars ($author_tag, $addon_name, $tag)
	{
		return isset($this->_viewVars['addon_vars'][$author_tag][$addon_name][$tag])? $this->_viewVars['addon_vars'][$author_tag][$addon_name][$tag] : array();
	}
	
	/**
	 * Gets all the assigned header vars set so far:: vars that are assigned using
	 * {@link geoView::setHeaderVar()}
	 * 
	 * @return array The array of vars set so far, or an empty array if no header
	 *   vars have been set yet.
	 * @since Version 5.0.0
	 */
	public function getAssignedHeaderVars ()
	{
		return isset($this->_viewVars['header_vars'])? $this->_viewVars['header_vars'] : array();
	}
	
	/**
	 * Gets all the assigned module vars set so far for the specified module::
	 * vars that are assigned using {@link geoView::setModuleVar()}
	 * 
	 * @param string $module_name
	 * @return array The array of vars set so far, or an empty array if no module
	 *   vars have been set yet for the specified module.
	 * @since Version 5.0.0
	 */
	public function getAssignedModuleVars ($module_name)
	{
		return isset($this->_viewVars['module_vars'][$module_name])? $this->_viewVars['module_vars'][$module_name] : array();
	}
	
	/**
	 * Loads the template attachment, then loads all the modules attached to that
	 * template, all according to the page id specified, the language set using
	 * setLanguage, the geoSite extended class set using setPage, and the
	 * current category set using setCategory.  If any of those (especially using
	 * setPage() to set instance of geoSite class) is not done, it can
	 * potentially cause errors.
	 *
	 * @param int|string $page_id
	 * @param bool $load_extra_mainbody If true, will also load modules attached to extra pages main body,
	 *  requires that the page be for an extra page.
	 */
	public function loadModules ($page_id, $load_extra_mainbody = false)
	{
		//Get the template attached
		if ($load_extra_mainbody) {
			
			$settings_file = geoTemplate::getFilePath('main_page','attachments',"templates_to_page/{$page_id}.php");
			$settings = require ($settings_file);
			//load attachment for either the current language, or if that is
			//not set, language 1
			$extra_template = (isset($settings['extra_page_main_body'][$this->_language][0]))? $settings['extra_page_main_body'][$this->_language][0] : $settings['extra_page_main_body'][1][0];
			if (strlen($extra_template) > 0) {
				$this->setBodyTpl($extra_template);
			}
		}
		$template = $this->_template = $this->getTemplateAttachment($page_id);
	}
	/**
	 * Used internally
	 * @internal
	 */
	private $_useGoogleLibs = null;
	
	/**
	 * Loads the js library
	 * 
	 * @param string $lib
	 * @param bool $ignoreAdmin
	 * @return string
	 */
	private function _loadJsLibrary ($lib, $ignoreAdmin = false)
	{
		if (!isset($this->_jsLibraries[$lib])) {
			//not a known lib
			return $lib;
		}
		
		if (defined('IN_ADMIN') && !$ignoreAdmin) {
			return '../'.$this->_jsLibraries[$lib]['local'];
		}
		if ($this->_useGoogleLibs === null) {
			//figure out if we should use google libs or not
			$db = DataAccess::getInstance();
			
			$this->_useGoogleLibs = $db->get_site_setting('useGoogleLibApi');
		}
		
		if ($this->_useGoogleLibs && $this->_jsLibraries[$lib]['googleAPI']!==null) {
			return $this->_jsLibraries[$lib]['googleAPI'];
		}
		//use the local location
		return $this->_jsLibraries[$lib]['local'];
	}
	
	/**
	 * Loads the CSS library
	 * 
	 * @param string $lib
	 * @param bool $ignoreAdmin
	 * @return string
	 * @since Version 7.2.0
	 */
	private function _loadCssLibrary ($lib, $ignoreAdmin = false)
	{
		if (!isset($this->_cssLibraries[$lib])) {
			//not a known lib
			return $lib;
		}
		
		if (defined('IN_ADMIN') && !$ignoreAdmin) {
			return '../'.geoTemplate::getUrl('',$this->_cssLibraries[$lib]['local'], true, true);
		}
		if ($this->_useGoogleLibs === null) {
			//figure out if we should use google libs or not
			$db = DataAccess::getInstance();
				
			$this->_useGoogleLibs = $db->get_site_setting('useGoogleLibApi');
		}
		
		if ($this->_useGoogleLibs && $this->_cssLibraries[$lib]['googleAPI']!==null) {
			return $this->_cssLibraries[$lib]['googleAPI'];
		}
		//use the local location
		return geoTemplate::getUrl('',$this->_cssLibraries[$lib]['local']);
	}
	
	/**
	 * Adds a javascript file to be added to the page once it is rendered.
	 *
	 * @param string|array $script_urls Either 1 url (string), or multiple url's (array),
	 *   can use geoView::JS_LIB_* constants in place of URL to use the given library.
	 * @param string $order Either append or prepend
	 * @param bool $ignoreAdmin Set to true, to ignore the fact that it might be
	 *   loaded from admin panel, and still load as if on front side.  Param added in version 6.0.0
	 * @return geoView
	 */
	public function addJScript ($script_urls, $order='append', $ignoreAdmin = false)
	{
		if (!is_array($script_urls)) $script_urls = array($script_urls);
		
		//trim/clean all of them
		$cleaned = array();
		foreach ($script_urls as $url) {
			$url = trim($url);
			
			if ($url && isset($this->_jsLibraries[$url])) {
				$url = $this->_loadJsLibrary($url, $ignoreAdmin);
			}
			if (strlen($url)) {
				$cleaned[] = ''.$url;
			}
		}
		$script_urls = $cleaned;
		if (!is_array($this->_script_files)) {
			$this->_script_files = array();
		}
		if (count($script_urls)) {
			switch ($order) {
				case 'prepend':
					//start from the scripts given, and add the
					//ones that we already have
					$existing = $this->_script_files;
					foreach ($existing as $url) {
						if (!in_array($url, $script_urls)) {
							$script_urls[] = $url;
						}
					}
					$this->_script_files = $script_urls;
					break;
					
				case 'append':
					//break ommited on purpose
				default:
					//start from pre-existing script, and add
					//the new ones
					foreach ($script_urls as $url) {
						if (!in_array($url, $this->_script_files)) {
							$this->_script_files[] = $url;
						}
					}
					break;
			}
		}
		//allow chaining
		return $this;
	}
	
	/**
	 * Adds a css file or files to be added to the page once it is rendered.
	 *
	 * @param string|array $css_urls can either be a string of one css file, or
	 *  an array of css files
	 * @param string $order Either append or prepend
	 * @param bool $ignoreAdmin Set to true, to ignore the fact that it might be
	 *   loaded from admin panel, and still load as if on front side.  {@since Version 7.2.0}
	 * @return geoView
	 */
	public function addCssFile ($css_urls, $order='append', $ignoreAdmin = false)
	{
		if (!is_array($css_urls)) $css_urls = array($css_urls);
		
		//trim/clean all of them
		$cleaned = array();
		foreach ($css_urls as $url) {
			$url = trim($url);
			if ($url && isset($this->_cssLibraries[$url])) {
				$url = $this->_loadCssLibrary($url, $ignoreAdmin);
			}
			if (strlen($url)) {
				$cleaned[] = ''.$url;
			}
		}
		$css_urls = $cleaned;
		if (!is_array($this->_css_files)) {
			$this->_css_files = array();
		}
		if (count($css_urls)) {
			switch ($order) {
				case 'prepend':
					//start from the csss given, and add the
					//ones that we already have
					$existing = $this->_css_files;
					foreach ($existing as $url) {
						if (!in_array($url, $css_urls)) {
							$css_urls[] = $url;
						}
					}
					$this->_css_files = $css_urls;
					break;
					
				case 'append':
					//break ommited on purpose
				default:
					//start from pre-existing css, and add
					//the new ones
					foreach ($css_urls as $url) {
						if (!in_array($url, $this->_css_files)) {
							$this->_css_files[] = $url;
						}
					}
					break;
			}
		}
		//allow chaining
		return $this;
	}
	
	/**
	 * Gets an instance of the template object that is going to be used to
	 * render the page.  Make sure the category and language are set in
	 * the view class prior to calling this, or those vars will not be
	 * available to the template when displaying the page.
	 *
	 * @return geoTemplate
	 */
	public function getTemplateObject()
	{
		if (!isset($this->_tpl)) {
			//set cat and language in template before creating new template
			//so that those vars will be available to the template.
			if ($this->_category) {
				geoTemplate::setCategory($this->_category);
			}
			if ($this->_language) {
				geoTemplate::setLanguage($this->_language);
			}
			
			$this->_tpl = new geoTemplate;
		}
		return $this->_tpl;
	}
	
	/**
	 * Whether or not the page has been rendered or not.
	 *
	 * @return bool
	 */
	public function isRendered()
	{
		return $this->_isRendered;
	}
	
	/**
	 * Sets whether the page has been rendered.  If set to true, when it gets time
	 * to auto render the page (on pages that are auto rendered), it won't auto 
	 * render.  This is handy if you need to display the page in a non-standard 
	 * way, like if you just want to echo something out..
	 *
	 * @param bool $is_rendered
	 * @return geoView
	 */
	public function setRendered($is_rendered)
	{
		$this->_isRendered = ($is_rendered)? true: false;
		return $this;
	}

	/**
	 * Prevent cloning of the object
	 *
	 */
	public function __clone ()
	{
		throw new Exception ('Error: Cloning of geoView object not permitted.');
	}
	
	/**
	 * Magic method, get view template var
	 * 
	 * @param string $name
	 * @return mixed
	 */
	public function __get ($name)
	{
		if (isset($this->_viewVars[$name])) {
			return $this->_viewVars[$name];
		}
		return false;
	}
	
	/**
	 * Magic method, set view template var
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set ($name, $value)
	{
		if (!$this->_onlyNewVars || !isset($this->_viewVars[$name])) {
			//only set if not locked or if locked but var not set yet, or if value is an array
			$this->_viewVars[$name] = $value;
		} else if (is_array($value) && is_array($value)) {
			//TODO: it is locked, do some fancy checking...
			$this->_viewVars[$name] = $value;
		}
	}
	/**
	 * Magic method, used to see if var is set
	 *
	 * @param string $name
	 * @return bool
	 */
	public function __isset ($name)
	{
		return isset($this->_viewVars[$name]);
	}
	
	/**
	 * Magic method to unset given view variable
	 * @param string $name
	 */
	public function __unset ($name)
	{
		unset($this->_viewVars[$name]);
	}
	/**
	 * (non-PHPdoc)
	 * @see Iterator::rewind()
	 */
	public function rewind ()
	{
		reset($this->_viewVars);
	}
	/**
	 * (non-PHPdoc)
	 * @see Iterator::current()
	 */
	public function current ()
	{
		return current($this->_viewVars);
	}
	/**
	 * (non-PHPdoc)
	 * @see Iterator::key()
	 */
	public function key ()
	{
		return key($this->_viewVars);
	}
	/**
	 * (non-PHPdoc)
	 * @see Iterator::next()
	 */
	public function next()
	{
		return next($this->_viewVars);
	}
	/**
	 * (non-PHPdoc)
	 * @see Iterator::valid()
	 */
	public function valid()
	{
		return !is_null($this->key());
	}
	/**
	 * Basically this will render the page.
	 * 
	 * @return string
	 */
	public function toString()
	{
		if ($this->bypass_display_page) {
			//special case...  bypass displaying anything
			return '';
		}
		$this->setRendered(true);
		//add the js and css files to the head html
		$this->_header_html = $this->getJsHtml() . $this->getCssHtml() . $this->_header_html;
		if (!isset($this->_tpl)) {
			//set cat and language in template before creating new template
			//so that those vars will be available to the template.
			if ($this->_category) {
				geoTemplate::setCategory($this->_category);
			}
			if ($this->_language) {
				geoTemplate::setLanguage($this->_language);
			}
			
			$this->_tpl = new geoTemplate;
		}
		if (!isset($this->_template)) {
			$this->_template = 'index';
			if (!isset($this->_viewVars['charset'])) {
				$this->_viewVars['charset'] = DataAccess::getInstance()->get_site_setting('charset');
			}
		}
		$this->_tpl->assign($this->_viewVars);
		return $this->_tpl->fetch($this->_template);
	}
	/**
	 * Magic method, basically if you echo out the view object, it will render
	 * the page.  Magical isn't it?  This actually calls {@see geoView::toString()}
	 * 
	 * @return string
	 */
	public function __toString ()
	{
		return $this->toString();
	}
}
