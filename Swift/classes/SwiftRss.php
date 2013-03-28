<?php
/**
 * SwiftRss.php
 *
 * This file contains the SwiftRss class.
 *
 * @author Derek Skeba <derek@mediavim.com>
 * @copyright 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.1
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
 * This class contains functions to create a
 * dynamic RSS feed on the fly.
 * @package Swift
 */
class SwiftRss {
	
	// private class variables
	private $m_xml = null;
	private $m_skeleton = "<rss version=\"2.0\"><channel></channel></rss>";
	
	/**
	 * Create and load a new SwiftRss object. If a filename to a well-formed
	 * RSS feed is provided then it will be loaded into new object. Otherwise,
	 * if filename is null then an empty feed will be loaded which elements may
	 * added to.
	 * @param string $filename The filename or URI to the XML RSS feed.
	 * @return SwiftRss The new SwiftRss object
	 */
	public function __construct($filename = null) {
		$this->load($filename);
	}
	
	/**
	 * Load an existing well formated XML RSS feed into our object, or
	 * load an empty skeleton feed for us to add elements to.
	 * @param string $filename The filename or URI to the XML RSS feed.
	 */
	public function load($filename = null) {
		// if there is no filename specified then load the empty skeleton
		if ($filename == null) {
			$this->m_xml = simplexml_load_string($this->m_skeleton);
		// otherwise create our rss feed from the specified filename
		} else {
			$this->m_xml = simplexml_load_file($filename);
		}
	}
	
	/**
	 * Set the channel data for the current RSS feed loaded or created in this Rss object.
	 * @param string $title Title of the channel
	 * @param string $link URL of the channel
	 * @param string $desc Short description of the feed
	 * @param string $lang Language-code of the feed (e.g. en-us)
	 * @param string $lastBuild Date of last-build of RSS feed.
	 * @param string $generator Name to use in generator tag of RSS feed.
	 */
	public function setChannel($title, $link, $desc, $lang = "en-us", $lastBuild = null, $generator = null) {
		$channel = $this->getChannel();
		if (!isset($channel->title)) {
			$channel->addChild('title', utf8_encode($title));
		}
		if (!isset($channel->link)) {
			$channel->addChild('link', utf8_encode($link));
		}
		if (!isset($channel->description)) {
			$channel->addChild('description', utf8_encode($desc));
		}
		if (!isset($channel->language)) {
			$channel->addChild('language', utf8_encode($lang));
		}
		if (!isset($channel->lastBuildDate)) {
			if ($lastBuild == null) {
				$lastBuild = date('D, d M Y g:i:s O');
			}
			$channel->addChild('lastBuildDate', utf8_encode($lastBuild));
		}
		if (!isset($channel->generator)) {
			if ($generator != null) {
				$channel->addChild('generator', utf8_encode($generator));
			}
		}
	}
	
	/**
	 * Get the current channel object from the feed.
	 * @return array Channel array
	 */
	public function getChannel() {
		return $this->m_xml->children()->channel;
	}
	
	/**
	 * Get the current channel itmes from the feed.
	 * @return array Array of channel data
	 */
	public function getItems() {
		return $this->m_xml->children()->channel->item;
	}
	
	/**
	 * Get the number of items from the feed
	 * @return integer Number of items
	 */
	public function getCount() {
		return count($this->getItems());
	}
	
	/**
	 * Add a new item to the RSS feed.
	 * @param string $title Title of the channel
	 * @param string $link URL of the channel
	 * @param string $desc Short description of the feed
	 * @param string $pubDate Date of publication (current date of null)
	 * @param string $comments URL to comments or comments feed (optional)
	 * @param string $author Name of the author (optional)
	 * @param string $category Name of category (optional)
	 */
	public function addItem($title, $link, $desc, $pubDate = null, $comments = null, $author = null, $category = null) {
		$item = $this->getChannel()->addChild('item');
		$item->addChild('title', utf8_encode(htmlspecialchars($title)));
		$item->addChild('link', utf8_encode($link));
		$item->addChild('description', utf8_encode(htmlspecialchars($desc)));
		if ($pubDate != null) {
			$item->addChild('pubDate', utf8_encode($pubDate));
		}
		if ($comments != null) {
			$item->addChild('comments', utf8_encode($comments));
		}
		if ($author != null) {
			$item->addChild('author', utf8_encode($author));
		}
		if ($category != null) {
			$item->addChild('category', utf8_encode($category));
		}
	}
	
	/**
	 * Get the RSS feed as XML
	 * @return string XML output for this RSS feed.
	 */
	public function getRss() {
		return $this->m_xml->asXML();
	}
	
	/**
	 * Write the current RSS feed to a XML file.
	 * @param string $filename Filename to write to.
	 * @return string XML output for this RSS feed.
	 */
	public function writeRss($filename) {
		return $this->m_xml->asXML($filename);
	}
	
}

?>