<?php
/**
 * Pinger.php
 *
 * This file contains the Pinger class.
 *
 * @author Derek Skeba <derek@mediavim.com>
 * @copyright 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.0
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2013 Media Vim LLC (http://mediavim.com)
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
 * This class contains functions to send out
 * automated XML RPC pings to search engines.
 * @package Swift
 */
class SwiftPinger {
	
	// private properties
	private $m_name = null;
	private $m_url = null;
	private $m_changedUrl = null;
	private $m_feedUrl = null;
	// list of well known xml rpc ping servers
	private $m_servers = array(
		"http://blogsearch.google.com/ping/RPC2",
		"http://rpc.pingomatic.com/",
		"http://ping.feedburner.com",
		"http://rpc.weblogs.com/RPC2",
		"http://ping.syndic8.com/xmlrpc.php",
		"http://ping.weblogalot.com/rpc.php",
		"http://rpc.blogrolling.com/pinger/",
		"http://xping.pubsub.com/ping/",
		"http://api.moreover.com/RPC2",
		"http://services.newsgator.com/ngws/xmlrpcping.aspx",
		"http://www.blogpeople.net/servlet/weblogUpdates",
	);
	
	/**
	 * Creates and initializes a new Pinger object with the provided parameters.
	 * @param string $name Name or title of website
	 * @param string $url The URL of the homepage.
	 * @param string $changedUrl The changed or updated url.
	 * @param string $feedUrl Optional RSS/ATOM feed url to include in ping.
	 */
	public function __construct($name, $url, $changedUrl = null, $feedUrl = null) {
		$this->m_name = $name;
		$this->m_url = $url;
		$this->m_changedUrl = $changedUrl;
		$this->m_feedUrl = $feedUrl;
	}
	
	/**
	 * Set the website name or title for this Pinger object.
	 * @param string $value Website name or title string
	 */
	public function setName($value) {
		$this->m_name = $value;
	}
	
	/**
	 * Gets the current website name or title for this Pinger object.
	 * @return string Website name or title string.
	 */
	public function getName() {
		return $this->m_name;
	}
	
	/**
	 * Set the website URL for this Pinger object.
	 * @param string $value Website URL string
	 */
	public function setUrl($value) {
		$this->m_url = $value;
	}
	
	/**
	 * Gets the current homepage URL for this Pinger object.
	 * @return string URL string
	 */
	public function getUrl() {
		return $this->m_url;
	}
	
	/**
	 * Set the changed or updated url for this Pinger object.
	 * @param string $value URL string
	 */
	public function setChangedUrl($value) {
		$this->m_changedUrl = $value;
	}
	
	/**
	 * Gets the current changed or updated url for this Pinger object.
	 * @return string URL string
	 */
	public function getChangedUrl() {
		return $this->m_changedUrl;
	}
	
	/**
	 * Set the optional RSS or Atom feed url for this Pinger object.
	 * @param string $value URL string
	 */
	public function setFeedUrl($value) {
		$this->m_feedUrl = $value;
	}
	
	/**
	 * Gets the current RSS or Atom feed url for this Pinger object.
	 * @return string URL string
	 */
	public function getFeedUrl() {
		return $this->m_feedUrl;
	}
	
	/**
	 * Private helper function for sending ping request to remote server.
	 */
	private function sendRequest($request, $server) {
		// find the hostname of the url
		$arr = parse_url($server);
		$host = $arr['host'];
		// create a stream content with all our HTTP information
		$context = stream_context_create(array('http' => array(
			'method' => "POST",
    		'header' => "Content-Type: text/xml\r\n" .
    					"User-Agent: PHPRPC/1.0\r\n" .
    					"Host: $host\r\n",
    		'content' => $request
		)));
		// send the request and download the result
		$data = file_get_contents($server, false, $context);
		// decode the response into an array or some acceptable format
		$response = xmlrpc_decode($data);
		// if the resonse is now in an array
		if (is_array($response)) {
			// if xmlrpc sees a response fault or if the flerror property has been set
			if (xmlrpc_is_fault($response) || $response['flerror'] != 0) {
				// xmlrpc found a fault, so failure
		    	return false;
	    	} else {
	    		// success everything seemed to work out
	    		return true;
	    	}
		} else {
			// the response could not be put into an array so probably failed
		    return false;
		}
	}
	
	/**
	 * Ping entire list of servers.
	 * @return integer Number of server a ping was sent to.
	 */
	public function pingAll() {
		foreach($this->m_servers as $server) {
			if ($this->sendPing($server)) {
				$ping_count++;
			}
		}
		return $ping_count;
	}
	
	/**
	 * Send extended ping to entire list of servers.
	 * @return integer Number of server an extended ping was sent to.
	 */
	public function extendedPingAll() {
		foreach($this->m_servers as $server) {
			if ($this->sendExtendedPing($server)) {
				$ping_count++;
			}
		}
		return $ping_count;	
	}
	
	/**
	 * Ping the given server.
	 * @return boolean True if ping sent. False on error.
	 */
	public function sendPing($server) {
		$request = xmlrpc_encode_request("weblogUpdates.ping", 
			array($this->m_name,
				$this->m_url
			)
		);
		return $this->sendRequest($request, $server);
	}
	
	/**
	 * Send extended ping the given server.
	 * @return boolean True if extended ping sent. False on error.
	 */
	public function sendExtendedPing($server) {
		$request = xmlrpc_encode_request("weblogUpdates.extendedPing", 
			array($this->m_name,
				$this->m_url,
				$this->m_changedUrl,
				$this->m_feedUrl
			)
		);
		return $this->sendRequest($request, $server);
	}
	
}

?>