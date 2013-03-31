<?php
/**
 * SwiftRouter.php
 *
 * This file contains the SwiftRouter class.
 *
 * @author Derek Skeba <derek@mediavim.com>
 * @copyright 2012 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.1
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2012 - 2013 Media Vim LLC (http://mediavim.com)
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

/**
 * Contains all of our functions for getting the
 * requested route and dispatching it.
 * @package Swift
 */
class SwiftRouter {
	
	private $m_request_uri;
	private $m_request_method;
	private $m_request_vars;
	private $m_request_uri_paths;
	private $m_routes;
	private $m_params;
	
	/**
	 * Creates a new SwiftRouter object
	 * @return SwiftRouter The new SwiftRouter object
	 */
	public function __construct() {
		// Get and trim the request uri
		$this->m_request_uri = $_SERVER['REQUEST_URI'];
		// Retrieve the method used for the request
		$this->m_request_method = strtolower($_SERVER['REQUEST_METHOD']);
		// Parse out any script variables found in the request
		$this->m_request_vars = $this->parseRequestVars();
		// Parse out each path in the request uri
		$this->m_request_uri_paths = $this->parseUriPaths($this->m_request_uri);
	}
	
	/**
	 * Finds and returns the first route that matches the request uri.
	 * @return string The callback function for the matching route. Returns null
	 * when no match is found.
	 */
	public function dispatch() {
		$keys_arr = array_keys($this->m_routes);
		foreach ($keys_arr as $key) {
			//$pattern = str_replace("*", "[a-bA-Z0-9_\-]+", $key);
			$pattern = "#^" . $key . "$#i";
			if (preg_match($pattern, $this->m_request_uri, $this->m_params)) {
				array_shift($this->m_params);
				return $this->m_routes[$key];
			}
		}
		return null;
	}
	
	/**
	 * Returns true if the request used a POST method.
	 * @return boolean True if POST method used, false otherwise.
	 */
	public function isPostRequest() {
		if ($this->m_request_method == "post") {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns true if the request used a GET method.
	 * @return boolean True if GET method used, false otherwise.
	 */
	public function isGetRequest() {
		if ($this->m_request_method == "get") {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Get the entire routes array.
	 * @return array All routes for the Router object.
	 */
	public function getRoutes() {
		return $this->m_routes;
	}
	
	/**
	 * Add a route with the given pattern and callback function
	 * @param string $path Pattern to match against the request URI.
	 * The pattern may be a regular expression. Each match via preg_match()
	 * will be stored as a param.
	 * @param string $callback Callback function to call on a match.
	 * @return string The given route for this path.
	 */
	public function setRoute($pattern, $callback) {
		$this->m_routes[$pattern] = $callback;
	}
	
	/**
	 * Get the path in the request URI at the given level.
	 * @param integer $num Level of path to retrive from request URI.
	 * @return string|null The path string or null if does not exist.
	 */
	public function getPath($num) {
		if (array_key_exists($num, $this->m_request_uri_paths)) {
			return $this->m_request_uri_paths[$num];
		} else {
			return null;
		}
	}
	
	/**
	 * Get the paths array created from the Request URI during init.
	 * @return array Array of all paths from request URI.
	 */
	public function getPaths() {
		return $this->m_request_uri_paths;
	}
	
	/**
	 * Get the path count in the request URI.
	 * @return integer Number of paths.
	 */
	public function getPathCount() {
		return count($this->m_request_uri_paths);
	}
	
	/**
	 * Get the request URI.
	 * @return string The request URI string.
	 */
	public function getRequestUri() {
		return $this->m_request_uri;
	}
	
	/**
	 * Get the method used for the request.
	 * @return string Name of method. (GET|POST|PUT|DELETE)
	 */
	public function getRequestMethod() {
		return $this->m_request_method;
	}
	
	/**
	 * Get and return an array of all request variables.
	 * @return array Array of script vars.
	 */
	public function getAllRequestVars() {
		return $this->m_request_vars;
	}
	
	/**
	 * Get a script var with the given name.
	 * @param string $key The key of the request variable.
	 * @return string|null Value of the given var or null if does not exist.
	 */
	public function getRequestVar($key) {
		if (array_key_exists($key, $this->m_request_vars)) {
			return $this->m_request_vars[$key];
		} else {
			return null;
		}
	}
	
	/**
	 * Get the params that were matched after dispatching the last URL.
	 * @return Array Array of params.
	 */
	public function getAllParams() {
		return $this->m_params;
	}
	
	/**
	 * Get a param that was matched after dispatching the last URL.
	 * @param string $key The key of the param.
	 * @return string|null Value of the given param or null if does not exist.
	 */
	public function getParam($key) {
		if (array_key_exists($key, $this->m_params)) {
			return $this->m_params[$key];
		} else {
			return null;
		}
	}
	
	/**
	 * Private helper function to localize the URI with our root url.
	 */
	/*
	private function localizeUri($root_url, $uri) {
		$root_url = str_replace("http://", "", $root_url);
		$root_url_arr = explode("/", $root_url);
		array_shift($root_url_arr);
		$root_url = implode("/", $root_url_arr);
		if ($root_url) {
			$uri = preg_replace('#^/(' . $root_url . ')#', "", $uri);
			if (!$uri) {
				$uri = "/";
			}
		}
		return $uri;
	}
	*/
	
	/**
	 * Private helper function to parse all paths out of the URI.
	 */
	private function parseUriPaths($uri) {
		$paths = array();
		if (!empty($uri)) {
			$uri = ltrim($uri, "/");
			$uri = rtrim($uri, "/");
			$paths = explode("/", $uri);
		}
		return $paths;
	}
	
	/**
	 * Private helper function to store all vars from the request, regardless
	 * of the method used.
	 */
	private function parseRequestVars() {
		$vars = array();
		if (!empty($_GET)) {
			foreach ($_GET as $key => $value) {
				$vars[$key] = $value;
			}
		} elseif (!empty($_POST)) {
			foreach ($_POST as $key => $value) {
				$vars[$key] = $value;
			}
		}
		return $vars;
	}
	
}

?>