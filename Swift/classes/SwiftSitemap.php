<?php
/**
 * SwiftSitemap.php
 *
 * This file contains the SwiftSitemap class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3
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

/**
 * This class contains functions to create a
 * dynamic sitemap.xml file on the fly.
 * @package Swift
 */
class SwiftSitemap {
	
	// private class variables
	private $m_xml = null;
	
	/**
	 * Create and load a new Sitemap object. If filename to an existing XML Sitemap
	 * is provided then it will be loaded into our object. Otherwise, if a URL to an existing
	 * stylesheet is provided, then an empty Sitemap object will be setup with the given
	 * stylesheet used.
	 * @param string $filename The filename or URI to the XML Sitemap feed.
	 * @param string $stylesheet The stylesheet to use if loading a new sitemap (optional).
	 * @return SwiftSitemap The new SwiftSitemap object
	 */
	public function __construct($filename = null, $stylesheet = null) {
		$this->load($filename, $stylesheet);
	}
	
	/**
	 * Load an existing well formated XML sitemap file into this Sitemap object, or
	 * load an empty skeleton sitemap to for us to fill with elements.
	 * @param string $filename The filename or URI to the XML Sitemap feed.
	 * @param string $stylesheet The stylesheet to use if loading a new sitemap (optional).
	 */
	public function load($filename = null, $stylesheet = null) {
		if ($filename != null) {
			// create a new xml object from the specified filename
			$this->m_xml = simplexml_load_file($filename);
		} else {
			// create a new xml object with an empty skeleton
			$xml_str = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
			if ($stylesheet != null) {
				$xml_str .= "<?xml-stylesheet type=\"text/xsl\" href=\"" . $stylesheet . "\" ?>";
			}
			$xml_str .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"></urlset>";
			$this->m_xml = simplexml_load_string($xml_str);
		}
	}
	
	/**
	 * Add a new URL element to this Sitemap object.
	 * @param string $loc The location of this URL.
	 * @param string $lastMod The last modification date of the URL.
	 * @param string $changeFreq The change frequency (e.g. daily,weekly,monthly)
	 * @param string $priority The value of priority from 0 to 1. (e.g. 0.5 = 50% priority)
	 */
	public function addUrl($loc, $lastMod, $changeFreq, $priority = null) {
		// create new url child
		$elem = $this->m_xml->addChild('url');
		// add our elements to the url child
		$elem->addChild('loc', utf8_encode($loc));
		$elem->addChild('lastmod', utf8_encode($lastMod));
		$elem->addChild('changefreq', utf8_encode($changeFreq));
		// if priority param is set, then add it to our new url child
		if ($priority != null) {
			$elem->addChild('priority', utf8_encode($priority));
		}
	}
	
	/**
	 * Get the array of all URLs in this Sitemap object.
	 * @return array Array of all URLs.
	 */
	public function getUrls() {
		return $this->m_xml->children();
	}
	
	/**
	 * Get the number of URL items in this Sitemap object.
	 * @return integer Integer count of URL items.
	 */
	public function getCount() {
		return count($this->getUrls());
	}
	
	/**
	 * Get any URL item by the provided location (URL)
	 * @param string $loc The location or URL
	 * @return array The URL item
	 */
	public function getUrlByLoc($loc) {
		foreach ($this->getUrls() as $url) {
			if ($url->loc == $loc) {
				return $url;
			}
		}
	}
	
	/**
	 * Remove any URL item from the Sitemap by providing it's location/URL.
	 * @param string $loc The location or URL
	 * @return boolean True if succesfully removed, otherwise false.
	 */
	public function removeUrlByLoc($loc) {
		$urlset = $this->getUrls();
		$i = 0;
		foreach ($urlset as $url) {
			if ($url->loc == $loc) {
				unset($urlset->url[$i]);
				return true;
			}
			$i++;
		}
		return false;
	}
	
	/**
	 * Write the current Sitemap object to a XML file.
	 * @param string $filename Filename to write to.
	 * @return string XML output for this Sitemap.
	 */
	public function writeXml($filename) {
		return $this->m_xml->asXML($filename);
	}
	
	/**
	 * Get the Sitemap as XML
	 * @return string XML output for this Sitemap.
	 */
	public function getXml() {
		return $this->m_xml->asXML();
	}
	
}
	
?>