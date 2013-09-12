<?php
/**
 * Swift.php
 *
 * This file contains the Swift class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.2
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2010 - 2013 Media Vim LLC (http://mediavim.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining 
 * a copy of this software and associated documentation files (the "Software"), 
 * to deal in the Software without restriction, including without limitation 
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, 
 * and/or sell copies of the Software, and to permit persons to whom the 
 * Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS 
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE 
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
 * SOFTWARE.
 */

// Turn off PHP error reporting for notcies and warnings
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

// Define constants for Swift PHP framework
define('FW_NAME', 'Swift PHP');
define('FW_VERSION', '1.2');
define('FW_CORE_DIR', dirname(__FILE__));
define('FW_BASE_DIR', dirname(FW_CORE_DIR));
define('FW_CLASSES_DIR', FW_CORE_DIR . '/' . 'classes');
define('FW_INCLUDES_DIR', FW_CORE_DIR . '/' . 'includes');
define('FW_CACHE_DIR', FW_CORE_DIR . '/' . 'cache');

// Include our PHP Class autoloader
require_once("autoloader.php");

/**
 * The Swift class contains all functions and code required
 * to load and run our php web app.
 * @package Swift
 */
class Swift {
	
	// static variable to hold instance of our Swift object
	private static $m_instance;
	
	// variables to hold our config and router objects
	private $m_config;
	private $m_router;
	private $m_view_data;
	private $m_cache;
	
	/**
	 * Creates a new Swift object
	 * @return Swift A new Swift object
	 */
	public function __construct() {
		// Initialize our config, router, and view data objects.
		$this->m_config = new SwiftRegistry();
		$this->m_router = new SwiftRouter();
		$this->m_view_data = new SwiftRegistry();
		// Add default settings to the config
		$this->m_config->set('app_name', 'Swift PHP App');
		$this->m_config->set('app_url', 'http://' . $_SERVER['SERVER_NAME']);
		$this->m_config->set('app_view_dir', 'view');
		$this->m_config->set('app_404', null);
		$this->m_config->set('app_cache_dir', FW_CACHE_DIR);
		// Initialize our cache object using the default cache directory
		$this->m_cache = new SwiftCache($this->m_config->get('app_cache_dir'));
	}
	
	/**
	 * Gets an instance of the Swift class and returns it.
	 * @return Swift A Swift object
	 */
	public static function getInstance() {
		if (!self::$m_instance) {
			self::$m_instance = new Swift();
		}
		return self::$m_instance;
	}
	
	/**
	 * Configures the App and Router using settings from the config
	 * and then dispatches the request.
	 */
	public function run() {
		// Before running, ensure our important settings are formatted correctly:
		$this->m_config->set('app_url', rtrim($this->m_config->get('app_url'), '/')); // Remove trailing slash from 'app_url' setting
		$this->m_config->set('app_view_dir', trim($this->m_config->get('app_view_dir'), '/')); // Remove leading and trailing slash from 'app_view_dir' setting
		// Create 'app_view_url' setting based on other provided settings
		$this->m_config->set('app_view_url', $this->m_config->get('app_url') . '/' . $this->m_config->get('app_view_dir'));
		$callback = $this->m_router->dispatch();
		if (!$callback) {
			$callback_404 = $this->m_config->get('app_404');
			if ($callback_404) {
				call_user_func($callback_404);
			} else {
				die("404 Error");
			}
		}
		call_user_func($callback);
	}
	
	/**
	 * Creates a new SwiftDb object and automatically connects to it
	 * using the database settings found in the config.
	 * @return SwiftDb Db object
	 */
	public function createDb() {
		$host = $this->m_config->get('app_db_host');
		$username = $this->m_config->get('app_db_username');
		$password = $this->m_config->get('app_db_password');
		$database = $this->m_config->get('app_db_database');
		return new SwiftDb($host, $username, $password, $database);
	}
	
	/**
	 * Creates a new SwiftGoogle object and automatically initializes it
	 * using the app_url and google_key settings found in the config.
	 * @return SwiftGoogle A SwiftGoogle object
	 */
	public function createGoogle() {
		$referer = $this->m_config->get('app_url');
		$key = $this->m_config->get('app_google_key');
		return new SwiftGoogle($referer, $key);
	}
	
	/**
	 * Creates a new SwiftPinger object and configures it using
	 * the app_name, app_url, and app_feed_url settings found
	 * in the config.
	 * @return SwiftPinger A SwiftPinger object
	 */
	public function createPinger() {
		$name = $this->m_config->get('app_name');
		$url = $this->m_config->get('app_url');
		$feed_url = $this->m_config->get('app_feed_url');
		return new SwiftPinger($name, $url, null, $feed_url);
	}
	
	/**
	 * Creates and returns a new SwiftValidator object.
	 * @return SwiftValidator A SwiftValidator object
	 */
	public function createValidator() {
		return new SwiftValidator();
	}
	
	/**
	 * Creates and returns a new SwiftRss object.
	 * @return SwiftRss A SwiftRss object
	 */
	public function createRss() {
		return new SwiftRss();
	}
	
	/**
	 * Creates and returns a new Sitemap object.
	 * @return SwiftSitemap A SwiftSitemap object
	 */
	public function createSitemap() {
		return new SwiftSitemap();
	}
	
	/**
	 * Creates a new SwiftGoogleImport object and initializes it with
	 * a new Db and a new Google object.
	 * @return SwiftGoogleImport A SwiftGoogleImport object
	 */
	public function createGoogleImport() {
		return new SwiftGoogleImport($this->createDb(), $this->createGoogle());
	}
	
	/**
	 * Creates and returns a new SwiftHtml object.
	 * @return SwiftHtml A SwiftHtml object
	 */
	public function createHtml() {
		return new SwiftHtml();
	}
	
	/**
	 * Creates and returns a new SwiftImage object.
	 * @param String $filename The filename of a PNG, GIF, or JPEG image to load. (Optional)
	 * @param Integer $width The width of the image to render. (Specifiy if $filename is not provided)
	 * @param Integer $height The height of the image to render. (Specifiy if $filename is not provided)
	 * @return SwiftImage A SwiftImage object
	 */
	public function createImage($filename = null, $width, $height) {
		return new SwiftImage($filename, $width, $height);
	}
		
	/**
	 * Creates and returns a new SwiftJQuery object.
	 * @param boolean $debug_comments True to create debug comments. Otherwise false. Default: false
	 * @return SwiftJQuery A SwiftJQuery object
	 */
	public function createJQuery($debug_comments = false) {
		return new SwiftJQuery($debug_comments);
	}
	
	/**
	 * Creates and returns a new SwiftEncrypt object.
	 * @return SwiftEncrypt A SwiftEncrypt object
	 */
	public function createEncrypt() {
		return new SwiftEncrypt();
	}
	
	/**
	 * Creates and returns a new SwiftForm object.
	 * @param string $form_name The HTML name attribute for the form. (Optional)
	 * @param string $form_id The HTML id attribute for the form. (Optional)
	 * @param string $form_action The HTML action attribute for the form. Default: / (Optional)
	 * @param string $form_method The HTML method attribute for the form. Default: get (Optional)
	 * @param string $form_enctype The HTML enctype attribute for the form. (Optional)
	 * @param string $form_container_id The ID of the HTML container for the form. (Optional)
	 * @param boolean $form_ajax True for AJAX form submit or False for regular submit. Default: false (Optional)
	 * @return SwiftForm A SwiftForm object
	 */
	public function createForm($form_name = null, $form_id = null, $form_action = '/', $form_method = 'get', $form_enctype = null, $form_container_id = null, $form_ajax = false) {
		return new SwiftForm($form_name, $form_id, $form_action, $form_method, $form_enctype, $form_container_id, $form_ajax);
	}
	
	/**
	 * Creates and returns a new SwiftLog object.
	 * @param string $log_dir The base log directory to store logs in. Default: null
	 * @return SwiftLog A SwiftLog object
	 */
	public function createLog($log_dir = null) {
		return new SwiftLog($log_dir);
	}
	
	/**
	 * Creates and returns a new SwiftMinimize object.
	 * @return SwiftMinimize A SwiftMinimize object
	 */
	public function createMinimize() {
		return new SwiftMinimize();
	}
	
	/**
	 * Creates and returns a new SwiftCache object.
	 * @param string $cache_dir The directory to store all chached files.
	 * @return SwiftCache A SwiftCache object
	 */
	public function createCache($cache_dir) {
		return new SwiftCache($cache_dir);
	}
    
	/**
	 * Get or set configuration settings for Swift. Provide single parameter,
	 * $name, to get the current value of a setting. Optional: Use second paramter,
	 * $value, to change the value of the given setting.
	 * @param string $name The name of the setting
	 * @param Object $value The value to change the current setting to.
	 * @return Object The value of the request setting.
	 */
    public function config($name, $value) {
		if (isset($name) && isset($value)) {
			$this->m_config->set($name, $value);
		} else if (isset($name)) {
			return $this->m_config->get($name);
		}
    }
	
	/**
	 * Map the provided url pattern to a given callback function.
	 * The $pattern may contain one or many regular expressions 
	 * which can be matched using preg_match(). Each match in 
	 * the $pattern is stored and may be retrieved by calling
	 * param() function.
	 * The $callback function is automatically called when a
	 * url match is made with the provided $pattern.
	 * @param string $pattern A url pattern to match. May contain multiple reg exps.
	 * @param string $callback A callback function that is auto-called upon a match.
	 */
	public function map($pattern, $callback) {
		return $this->m_router->setRoute($pattern, $callback);
	}
	
	/**
	 * Get the request uri from the web server.
	 * @return string The current request uri.
	 */
	public function getRequestUri() {
		return $this->m_router->getRequestUri();
	}
	
	/**
	 * Get the method type for the current request.
	 * @return string The type of method. (GET|POST|DELETE|PUT)
	 */
	public function getRequestMethod() {
		return $this->m_router->getRequestMethod();
	}
	
	/**
	 * Retrieve the value of request variable that matches the given $key.
	 * @param string $key The name/key of the request variable.
	 * @return string The value of the specified request variable.
	 */
	public function getRequestVar($key) {
		return $this->m_router->getRequestVar($key);
	}
	
	/**
	 * Get the value of a param that was matched in the url pattern.
	 * @param string $key The name/key of the url param.
	 * @return string The value of the param.
	 */
	public function param($key) {
		return $this->m_router->getParam($key);
	}
	
	/**
	 * Retrieve an array containing all the params matched in the
	 * mapped url pattern.
	 * @return Array An array with all params.
	 */
	public function allParams() {
		return $this->m_router->getAllParams();
	}
	
	/**
	 * Set data to be stored and made available to the view.
	 * @param string $key The key/name of the data.
	 * @param string $value The value of the data.
	 */
	public function setViewData($key, $value) {
		$this->m_view_data->set($key, $value);
	}
	
	/**
	 * Get the value of stored view data with the provided $key.
	 * @param string $key The key/name of the data.
	 * @return string The value of the stored view data.
	 */
	public function getViewData($key) {
		return $this->m_view_data->get($key);
	}
	
	/**
	 * Retrieve an array of all stored view data.
	 * @return Array An array of all view data.
	 */
	public function getAllViewData() {
		return $this->m_view_data->getAll();
	}
	
	/**
	 * Returns the cache stored with the provided $cache_key if the cache
	 * is not older then the provided $cache_exp_time. If no cache exists
	 * or the cache is expired then the function returns null.
	 * @param string $cache_key An alphanumeric key to reference the stored cache.
	 * @param int $cache_exp_time The expiration time (in seconds) of the cache. Default = 600
	 * @return string The stored cache as a string. Returns null if cache does not
	 * exist or is expired.
	 */
	public function getCache($cache_key, $cache_exp_time = 600) {
		return $this->m_cache->getCache($cache_key, $cache_exp_time);
	}
	
	/**
	 * Begin storing all output into a buffer until stopCache() is called.
	 */
	public function startCache() {
		$this->m_cache->startCache();
	}
	
	/**
	 * Stop buffering output from previous call to startCache() and store buffer into
	 * cache with the provided $cache_key. Returns the stored cache on success, and
	 * returns false on error.
	 * @param string $cache_key An alphanumeric key to reference the stored cache.
	 * @return string The stored cache as a string.
	 */
	public function stopCache($cache_key) {
		return $this->m_cache->stopCache($cache_key);
	}
	
	/**
	 * Loads the provided $view file from inside the directory provided by the app_view_dir setting
	 * and loads all variables inside the $data array.
	 * @param string $view The filename of a view to render/load.
	 * @param Array $data Array of variables to load in the public scope for the $view. (Default: null)
	 * @param boolean $minimize Minimize and compress all output from the $view. (Default: false)
	 */
	public function render($view, $data = null, $minimize = false) {
		$path = $this->m_config->get('app_view_dir') . '/' . $view;
		if (isset($data)) {
			$result = array_merge($this->m_view_data->getAll(), $data);
			$this->m_view_data->setAll($result);
		}
		$all_data = $this->getAllViewData();
		extract($all_data);
		if ($minimize) {
			ob_start();
			require $path;
			$buffer = ob_get_clean();
			$swift = Swift::getInstance();
			$sm = $swift->createMinimize();
			echo $sm->minimizeString($buffer);
		} else {
			require $path;
		}
	}
	
}

?>