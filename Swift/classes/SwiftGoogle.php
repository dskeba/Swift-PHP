<?php
/**
 * SwiftGoogle.php
 *
 * This file contains the SwiftGoogle class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2015 Derek Skeba
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.3.8
 * @package Swift
 *
 * MIT LICENSE
 *
 * Copyright (c) 2010 - 2015 Derek Skeba
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
 * This class contains functions to extract web elements
 * Google API quick and easily.
 * @package Swift
 */
class SwiftGoogle {
	
	// private properties
	private $m_referer = null;
	private $m_key = null;
	
	/**
	 * Creates and initializes a new SwiftGoogle object with the given
	 * referer url and Google api key params.
	 * @param string $referer Referer url
	 * @param string $key Google API key
	 * @return SwiftGoogle The new SwiftGoogle object
	 */
	public function __construct($referer, $key = null) {
		$this->m_referer = $referer;
		$this->m_key = $key;
	}
	
	/**
	 * Set the referer url for the Google object.
	 * @param string $value Referer url
	 */
	public function setReferer($value) {
		$this->m_referer = $value;
	}
	
	/**
	 * Gets the current referer url for this Google object.
	 * @return string The referer url string
	 */
	public function getReferer() {
		return $this->m_referer;
	}
	
	/**
	 * Set the Google API key for this Google object.
	 * @param string $value Google API key
	 */
	public function setKey($value) {
		$this->m_key = $value;
	}
	
	/**
	 * Get the current Google API key for this Google object.
	 * @return string Google API key string
	 */
	public function getKey() {
		return $this->m_key;
	}
	
	/**
	 * Private helper function for fetching results from Google API.
	 */
	private function fetchResults($url) {
		// add our api key if we have one
		if ($this->key != null) {
			$url .= "&key=$this->key";
		}
		// get a handle on curl
		$ch = curl_init();
		// setup our options
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, $this->referer);
		// get our data from google
		$data = curl_exec($ch);
		// close our curl handle
		curl_close($ch);
		if (strpos($data, "<title>400 Bad Request</title>")) {
			return false;
		}
		// return the json data decoded into an object
		return json_decode($data);
	}
	
	/**
	 * Fetch book results from Google API using the given query keyphrase and settings
	 * @param string $query The search query or keyword(s) to use
	 * @param string $fullView Whether to fetch only full view books or all types
	 * @param string $numResults Size of results to fetch (small or large)
	 * @return array An array containing all results
	 */
	public function getBooks($query, $fullView = false, $numResults = "small") {
		// make the search query url friendly
		$query = str_replace(" ", "%20", $query);
		// construct our url
		$url = "http://ajax.googleapis.com/ajax/services/search/books?v=1.0&q=$query&rsz=$numResults";
		// if only full view books are requested
		if ($fullView) {
			$url .= "&as_brr=1";
		}
		// fetch our results
		$obj = $this->fetchResults($url);
		if (!$obj) {
			return null;
		}
		// throw all the data into an easy to use array
		foreach($obj->responseData->results as $result) {
			$arr[$i++] = array(
				"title" => $result->title,
				"titleNoFormatting" => $result->titleNoFormatting,
				"unescapedUrl" => $result->unescapedUrl,
				"url" => $result->url,
				"authors" => $result->authors,
				"bookId" => $result->bookId,
				"publishedYear" => $result->publishedYear,
				"pageCount" => $result->pageCount,
				"thumbnailHtml" => $result->thumbnailHtml,
			);
		}
		// return our multi-dimensional associative array
		return $arr;
	}
	
	/**
	 * Fetch news results from Google API using the given query keyphrase and settings
	 * @param string $query The search query or keyword(s) to use
	 * @param string $topic Google news topic id or null
	 * @param string $edition us or other country codes from Google
	 * @param boolean $sortByDate Whether to sort by date or not to
	 * @param string $numResults Size of results to fetch (small or large)
	 * @return array An array containing all results
	 */
	public function getNews($query, $topic = null, $edition = "us", $sortByDate = false, $numResults = "small") {
		// make the search query url friendly
		$query = str_replace(" ", "%20", $query);
		// construct our url
		$url = "http://ajax.googleapis.com/ajax/services/search/news?v=1.0&q=$query&rsz=$numResults&ned=$edition";
		// see if the topic has been set
		if ($topic != null) {
			$url .= "&topic=$topic";
		}
		// sort by date if requested
		if ($sortByDate) {
			$url .= "&scoring=d";
		}
		// fetch our results
		$obj = $this->fetchResults($url);
		if (!$obj) {
			return null;
		}
		// throw all the data into an easy to use array
		foreach($obj->responseData->results as $result) {
			$arr[$i++] = array(
				"title" => $result->title,
				"titleNoFormatting" => $result->titleNoFormatting,
				"unescapedUrl" => $result->unescapedUrl,
				"url" => $result->url,
				"clusterUrl" => $result->clusterUrl,
				"content" => $result->content,
				"publisher" => $result->publisher,
				"location" => $result->location,
				"publishedDate" => $result->publishedDate,
				"relatedStories" => $result->relatedStories,
				"image" => $result->image,
				"language" => $result->language,
			);
		}
		// return our multi-dimensional associative array
		return $arr;
	}
	
	/**
	 * Fetch blog results from Google API using the given query keyphrase and settings
	 * @param string $query The search query or keyword(s) to use
	 * @param boolean $sortByDate Whether to sort by date or not to
	 * @param string $numResults Size of results to fetch (small or large)
	 * @return array An array containing all results
	 */
	public function getBlogs($query, $sortByDate = false, $numResults = "small") {
		// make the search query url friendly
		$query = str_replace(" ", "%20", $query);
		// construct our url
		$url = "http://ajax.googleapis.com/ajax/services/search/blogs?v=1.0&q=$query&rsz=$numResults";
		// sort by date if requested
		if ($sortByDate) {
			$url .= "&scoring=d";
		}
		// fetch our results
		$obj = $this->fetchResults($url);
		if (!$obj) {
			return null;
		}
		// throw all the data into an easy to use array
		foreach($obj->responseData->results as $result) {
			$arr[$i++] = array(
				"title" => $result->title,
				"titleNoFormatting" => $result->titleNoFormatting,
				"postUrl" => $result->postUrl,
				"content" => $result->content,
				"author" => $result->author,
				"blogUrl" => $result->blogUrl,
				"publishedDate" => $result->publishedDate,
			);
		}
		// return our multi-dimensional associative array
		return $arr;
	}
	
	/**
	 * Fetch video results from Google API using the given query keyphrase and settings
	 * @param string $query The search query or keyword(s) to use
	 * @param boolean $sortByDate Whether to sort by date or not to
	 * @param string $numResults Size of results to fetch (small or large)
	 * @return array An array containing all results
	 */
	public function getVideos($query, $sortByDate = false, $numResults = "small") {
		// make the search query url friendly
		$query = str_replace(" ", "%20", $query);
		// construct our url
		$url = "http://ajax.googleapis.com/ajax/services/search/video?v=1.0&q=$query&rsz=$numResults";
		// sort by date if requested
		if ($sortByDate) {
			$url .= "&scoring=d";
		}
		// fetch our results
		$obj = $this->fetchResults($url);
		if (!$obj) {
			return null;
		}
		// throw all the data into an easy to use array
		foreach($obj->responseData->results as $result) {
			$arr[$i++] = array(
				"title" => $result->title,
				"titleNoFormatting" => $result->titleNoFormatting,
				"content" => $result->content,
				"url" => $result->url,
				"published" => $result->published,
				"publisher" => $result->publisher,
				"duration" => $result->duration,
				"tbWidth" => $result->tbWidth,
				"tbHeight" => $result->tbHeight,
				"tbUrl" => $result->tbUrl,
				"playUrl" => $result->playUrl,
				"author" => $result->author,
				"viewCount" => $result->viewCount,
				"rating" => $result->rating
			);
		}
		// return our multi-dimensional associative array
		return $arr;
	}
	
	/**
	 * Fetch image results from Google API using the given query keyphrase and settings
	 * @param string $query The search query or keyword(s) to use
	 * @param string $fileType Specific file type (.jpg,.png,etc) or null for any
	 * @param string $numResults Size of results to fetch (small or large)
	 * @return array An array containing all results
	 */
	public function getImages($query, $fileType = null, $numResults = "small") {
		// make the search query url friendly
		$query = str_replace(" ", "%20", $query);
		// construct our url
		$url = "http://ajax.googleapis.com/ajax/services/search/images?v=1.0&q=$query&rsz=$numResults";
		// specify the filetype if we have it
		if ($fileType != null) {
			$url .= "&as_filetype=$fileType";
		}
		// fetch our results
		$obj = $this->fetchResults($url);
		if (!$obj) {
			return null;
		}
		// throw all the data into an easy to use array
		foreach($obj->responseData->results as $result) {
			$arr[$i++] = array(
				"title" => $result->title,
				"titleNoFormatting" => $result->titleNoFormatting,
				"unescapedUrl" => $result->unescapedUrl,
				"url" => $result->url,
				"visibleUrl" => $result->visibleUrl,
				"originalContextUrl" => $result->originalContextUrl,
				"width" => $result->width,
				"height" => $result->height,
				"tbWidth" => $result->tbWidth,
				"tbHeight" => $result->tbHeight,
				"tbUrl" => $result->tbUrl,
				"content" => $result->content,
				"contentNoFormatting" => $result->contentNoFormatting,
			);
		}
		// return our multi-dimensional associative array
		return $arr;
	}

	/**
	 * Fetch web search results from Google API using the given query keyphrase and settings
	 * @param string $query The search query or keyword(s) to use
	 * @param string $numResults Size of results to fetch (small or large)
	 * @return array An array containing all results
	 */
	public function getWebs($query, $numResults = "small") {
		// make the search query url friendly
		$query = str_replace(" ", "%20", $query);
		// construct our url
		$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=$query&rsz=$numResults";
		// fetch our results
		$obj = $this->fetchResults($url);
		if (!$obj) {
			return null;
		}
		// throw all the data into an easy to use array
		foreach($obj->responseData->results as $result) {
			$arr[$i++] = array(
				"unescapedUrl" => $result->unescapedUrl,
				"url" => $result->url,
				"visibleUrl" =>  $result->visibleUrl,
				"title" =>  $result->title,
				"titleNoFormatting" =>  $result->titleNoFormatting,
				"content" =>  $result->content,
				"cacheUrl" =>  $result->cacheUrl
			);
		}
		// return our multi-dimensional associative array
		return $arr;
	}
	
}

?>