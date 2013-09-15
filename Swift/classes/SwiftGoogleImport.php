<?php
/**
 * SwiftGoogleImport.php
 *
 * This file contains the GoogleImport class.
 *
 * @author Derek Skeba
 * @copyright 2010 - 2013 Media Vim LLC
 * @link http://swiftphp.org
 * @license http://swiftphp.org/license
 * @version 1.2.3
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
 * This class contains functions to automatically
 * import Google API results into a MySQL database.
 * @package Swift
 */
class SwiftGoogleImport {
	
	private $m_db;
	private $m_google;
	
	/**
	 * Creates and initializes a new SwiftGoogleImport object with the given
	 * Db object and Google object.
	 * @param Db $db A Swift Db object
	 * @param Google $google A Swift Google object
	 * @return SwiftGoogleImport The new SwiftGoogleImport object
	 */
	public function __construct($db, $google) {
		$this->m_db = $db;
		$this->m_google = $google;
	}
	
	/**
	 * Private helper function for getting content of a web url.
	 */
	private function getContents($url) {
		$curl = curl_init($url);
   		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
   		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    	$curl_contents = curl_exec($curl);
    	$curl_info = curl_getinfo($curl);
    	if($curl_info['http_code'] === 200) {
    		return $curl_contents;
    	}
    	return false;
	}
	
	/**
	 * Private helper function for removing non-ascii characters from a string.
	 */
	private function removeNonAscii($str) {
		$str = preg_replace('/[^(\x20-\x7F)]*/', '', $str);
		return $str;
	}
	
	/**
	 * Private helper function for converting a string into a slug-friendly string.
	 */
	private function makeSlug($str) {
		$str = preg_replace("/[^A-Za-z0-9\s\-]/", "", $str);
		$str = str_replace(' ', '-', $str);
		$str = preg_replace('{\-+}', '-', $str);
		$str = trim($str, '-');
		return $str;
	}
	
	/**
	 * Imports web search results from Swift Google object into the given database table.
	 * @param string $table The MySQL database table to insert into
	 * @param integer $topic_id The topic id to give it
	 * @param string $query The search query to use on Google API
	 * @param string $numResults Size of results to return (small or large)
	 * @return integer Number of results imported
	 */
	public function importWebs($table, $topic_id, $query, $numResults = "small") {
		$db = $this->m_db;
		$g = $this->m_google;
		$webs_table = $table;
		$g_webs = $g->getWebs($query, $numResults);
		if (empty($g_webs)) {
			return;
		}
		foreach ($g_webs as $web) {
			$web_topicId = $topic_id;
			$web_timeAdded = time();
			$web_unescapedUrl = $db->safeString($web['unescapedUrl']);
			$web_url = $db->safeString($web['url']);
			$web_visibleUrl = $db->safeString($web['visibleUrl']);
			$web_title = $db->safeString($web['title']);
			$web_titleNoFormatting = $web['titleNoFormatting'];
			$web_titleNoFormatting = str_replace(' ...', '', $web_titleNoFormatting);
			$web_titleNoFormatting = $this->removeNonAscii($web_titleNoFormatting);
			$web_titleNoFormatting = trim($web_titleNoFormatting);
			$web_titleNoFormatting = $db->safeString($web_titleNoFormatting);
			
			$web_slug = $this->makeSlug($web_titleNoFormatting);
			$web_slug = $db->safeString($web_slug);
			
			$web_content = $db->safeString($web['content']);
			$web_content = $this->removeNonAscii($web_content);
			
			$web_cacheUrl = $db->safeString($web['cacheUrl']);
			$web_query = "INSERT INTO $webs_table (topicId, timeAdded, slug, unescapedUrl, url, visibleUrl, title, titleNoFormatting, content, cacheUrl) VALUES ('$web_topicId', '$web_timeAdded', '$web_slug', '$web_unescapedUrl', '$web_url', '$web_visibleUrl', '$web_title', '$web_titleNoFormatting', '$web_content', '$web_cacheUrl')";
			if ($db->query($web_query)) {
				$num_imports++;
			} else {
				echo($db->getError() . " from $webs_table table<br />");
			}
		}
		return $num_imports;
	}

	/**
	 * Imports image results from Swift Google object into the given database table.
	 * @param string $table The MySQL database table to insert into
	 * @param integer $topic_id The topic id to give it
	 * @param string $query The search query to use on Google API
	 * @param string $fileType File types to filter or null for any
	 * @param string $numResults Size of results to return (small or large)
	 * @return integer Number of results imported
	 */
	public function importImages($table, $topic_id, $query, $fileType = null, $numResults = "small") {
		$db = $this->m_db;
		$g = $this->m_google;
		$images_table = $table;
		$g_images = $g->getImages($query, $fileType, $numResults);
		if (empty($g_images)) {
			return;
		}
		foreach ($g_images as $image) {
			$image_topicId = $topic_id;
			$image_timeAdded = time();
			$image_title = $db->safeString($image['title']);
			$image_titleNoFormatting = $image['titleNoFormatting'];
			$image_titleNoFormatting = $db->safeString($image_titleNoFormatting);
			$image_titleNoFormatting = $this->removeNonAscii($image_titleNoFormatting);
			$image_titleNoFormatting = trim($image_titleNoFormatting);
			
			$image_slug = str_replace($fileType, '', $image_titleNoFormatting);
			$image_slug = $this->makeSlug($image_slug);
			$image_slug = $db->safeString($image_slug);
			
			$image_unescapedUrl = $db->safeString($image['unescapedUrl']);
			$image_url = $db->safeString($image['url']);
			$image_visibleUrl = $db->safeString($image['visibleUrl']);
			$image_originalContextUrl = $db->safeString($image['originalContextUrl']);
			$image_width = $db->safeString($image['width']);
			$image_height = $db->safeString($image['height']);
			$image_tbWidth = $db->safeString($image['tbWidth']);
			$image_tbHeight = $db->safeString($image['tbHeight']);
			$image_tbUrl = $db->safeString($image['tbUrl']);
			$image_content = $db->safeString($image['content']);
			$image_contentNoFormatting = $db->safeString($image['contentNoFormatting']);
			$image_image = $db->safeString(base64_encode($this->getContents($image_url)));
			$image_tb = $db->safeString(base64_encode($this->getContents($image_tbUrl)));
			if ($image_image && $image_tb) {
				$image_query = "INSERT INTO $images_table (topicId, timeAdded, slug, title, titleNoFormatting, unescapedUrl, url, visibleUrl, originalContextUrl, width, height, tbWidth, tbHeight, tbUrl, content, contentNoFormatting, image, tb) VALUES ('$image_topicId', '$image_timeAdded', '$image_slug', '$image_title', '$image_titleNoFormatting', '$image_unescapedUrl', '$image_url', '$image_visibleUrl', '$image_originalContextUrl', '$image_width', '$image_height', '$image_tbWidth', '$image_tbHeight', '$image_tbUrl', '$image_content', '$image_contentNoFormatting', '$image_image', '$image_tb')";
				if ($db->query($image_query)) {
					$num_imports++;
				} else {
					echo($db->getError() . " from $images_table table<br />");
				}
			}
		}
		return $num_imports;
	}
	
	/**
	 * Imports video results from Swift Google object into the given database table.
	 * @param string $table The MySQL database table to insert into
	 * @param integer $topic_id The topic id to give it
	 * @param string $query The search query to use on Google API
	 * @param boolean $sortByDate True to sort by date or false not to
	 * @param string $numResults Size of results to return (small or large)
	 * @return integer Number of results imported
	 */
	public function importVideos($table, $topic_id, $query, $sortByDate = false, $numResults = "small") {
		$db = $this->m_db;
		$g = $this->m_google;
		$videos_table = $table;
		$g_videos = $g->getVideos($query, $sortByDate, $numResults);
		if (empty($g_videos)) {
			return;
		}
		foreach ($g_videos as $video) {
			$video_topicId = $topic_id;
			$video_timeAdded = time();
			$video_title = $db->safeString($video['title']);
			$video_titleNoFormatting = $video['titleNoFormatting'];
			$video_titleNoFormatting = $this->removeNonAscii($video_titleNoFormatting);
			$video_titleNoFormatting = trim($video_titleNoFormatting);
			$video_titleNoFormatting = $db->safeString($video_titleNoFormatting);
			
			$video_slug = $this->makeSlug($video_titleNoFormatting);
			$video_slug = $db->safeString($video_slug);
			
			$video_content = $db->safeString($video['content']);
			$video_content = $this->removeNonAscii($video_content);
			
			$video_url = $db->safeString($video['url']);
			$video_published = $db->safeString($video['published']);
			$video_publisher = $db->safeString($video['publisher']);
			$video_duration = $db->safeString($video['duration']);
			$video_tbWidth = $db->safeString($video['tbWidth']);
			$video_tbHeight = $db->safeString($video['tbHeight']);
			$video_tbUrl = $db->safeString($video['tbUrl']);
			$video_playUrl = $db->safeString($video['playUrl']);
			$video_author = $db->safeString($video['author']);
			$video_viewCount = $db->safeString($video['viewCount']);
			$video_rating = $db->safeString($video['rating']);
			$video_tb = $db->safeString(base64_encode($this->getContents($video_tbUrl)));
			if ($video_tb) {
				$video_query = "INSERT INTO $videos_table (topicId, timeAdded, slug, title, titleNoFormatting, content, url, published, publisher, duration, tbWidth, tbHeight, tbUrl, playUrl, author, viewCount, rating, tb) VALUES ('$video_topicId', '$video_timeAdded', '$video_slug', '$video_title', '$video_titleNoFormatting', '$video_content', '$video_url', '$video_published', '$video_publisher', '$video_duration', '$video_tbWidth', '$video_tbHeight', '$video_tbUrl', '$video_playUrl', '$video_author', '$video_viewCount', '$video_rating', '$video_tb')";
				if ($db->query($video_query)) {
					$num_imports++;
				} else {
					echo($db->getError() . " from $videos_table table<br />");
				}
			}
		}
		return $num_imports;
	}
	
	/**
	 * Imports blog results from Swift Google object into the given database table.
	 * @param string $table The MySQL database table to insert into
	 * @param integer $topic_id The topic id to give it
	 * @param string $query The search query to use on Google API
	 * @param boolean $sortByDate True to sort by date or false not to
	 * @param string $numResults Size of results to return (small or large)
	 * @return integer Number of results imported
	 */
	public function importBlogs($table, $topic_id, $query, $sortByDate = false, $numResults = "small") {
		$db = $this->m_db;
		$g = $this->m_google;
		$blogs_table = $table;
		$g_blogs = $g->getBlogs($query, $sortByDate, $numResults);
		if (empty($g_blogs)) {
			return;
		}
		foreach ($g_blogs as $blog) {
			$blog_topicId = $topic_id;
			$blog_timeAdded = time();
			$blog_title = $db->safeString($blog['title']);
			$blog_titleNoFormatting = $blog['titleNoFormatting'];
			$blog_titleNoFormatting = str_replace(' ...', '', $blog_titleNoFormatting);
			$blog_titleNoFormatting = $this->removeNonAscii($blog_titleNoFormatting);
			$blog_titleNoFormatting = trim($blog_titleNoFormatting);
			$blog_titleNoFormatting = $db->safeString($blog_titleNoFormatting);
			
			$blog_slug = $this->makeSlug($blog_titleNoFormatting);
			$blog_slug = $db->safeString($blog_slug);
			
			$blog_postUrl = $db->safeString($blog['postUrl']);
			
			$blog_content = $db->safeString($blog['content']);
			$blog_content = $this->removeNonAscii($blog_content);
			
			$blog_author = $db->safeString($blog['author']);
			$blog_blogUrl = $db->safeString($blog['blogUrl']);
			$blog_publishedDate = $db->safeString($blog['publishedDate']);
			$blog_query = "INSERT INTO $blogs_table (topicId, timeAdded, slug, title, titleNoFormatting, postUrl, content, author, blogUrl, publishedDate) VALUES ('$blog_topicId', '$blog_timeAdded', '$blog_slug', '$blog_title', '$blog_titleNoFormatting', '$blog_postUrl', '$blog_content', '$blog_author', '$blog_blogUrl', '$blog_publishedDate')";
			if ($db->query($blog_query)) {
				$num_imports++;
			} else {
				echo($db->getError() . " from $blogs_table table<br />");
			}
		}
		return $num_imports;
	}
	
	/**
	 * Imports news results from Swift Google object into the given database table.
	 * @param string $table The MySQL database table to insert into
	 * @param integer $topic_id The topic id to give it
	 * @param string $query The search query to use on Google API
	 * @param integer $topic The Google news topic id to filter by
	 * @param string $edition us or other country code
	 * @param boolean $sortByDate True to sort by date or false not to
	 * @param string $numResults Size of results to return (small or large)
	 * @return integer Number of results imported
	 */
	public function importNews($table, $topic_id, $query, $topic = null, $edition = "us", $sortByDate = false, $numResults = "small") {
		$db = $this->m_db;
		$g = $this->m_google;
		$news_table = $table;
		$g_news = $g->getNews($query, $topic, $edition, $sortByDate, $numResults);
		if (empty($g_news)) {
			return;
		}
		foreach ($g_news as $news) {
			$news_topicId = $topic_id;
			$news_timeAdded = time();
			$news_title = $db->safeString($news['title']);
			$news_titleNoFormatting = $news['titleNoFormatting'];
			$news_titleNoFormatting = str_replace(' ...', '', $news_titleNoFormatting);
			$news_titleNoFormatting = $this->removeNonAscii($news_titleNoFormatting);
			$news_titleNoFormatting = trim($news_titleNoFormatting);
			$news_titleNoFormatting = $db->safeString($news_titleNoFormatting);
			
			$news_slug = $this->makeSlug($news_titleNoFormatting);
			$news_slug = $db->safeString($news_slug);
			
			$news_unescapedUrl = $db->safeString($news['unescapedUrl']);
			$news_url = $db->safeString($news['url']);
			$news_clusterUrl = $db->safeString($news['clusterUrl']);
			
			$news_content = $db->safeString($news['content']);
			$news_content = $this->removeNonAscii($news_content);
			
			$news_publisher = $db->safeString($news['publisher']);
			$news_location = $db->safeString($news['location']);
			$news_publishedDate = $db->safeString($news['publishedDate']);
			$news_language = $db->safeString($news['language']);
			$news_query = "INSERT INTO $news_table (topicId, timeAdded, slug, title, titleNoFormatting, unescapedUrl, url, clusterUrl, content, publisher, location, publishedDate, language) VALUES ('$news_topicId', '$news_timeAdded', '$news_slug', '$news_title', '$news_titleNoFormatting', '$news_unescapedUrl', '$news_url', '$news_clusterUrl', '$news_content', '$news_publisher', '$news_location', '$news_publishedDate', '$news_language')";
			if ($db->query($news_query)) {
				$num_imports++;
			} else {
				echo($db->getError() . " from $news_table table<br />");
			}
		}
		return $num_imports;
	}
	
	/**
	 * Imports book results from Swift Google object into the given database table.
	 * @param string $table The MySQL database table to insert into
	 * @param integer $topic_id The topic id to give it
	 * @param string $query The search query to use on Google API
	 * @param integer $fullView True for full view only book results or false (default) for any type
	 * @param string $numResults Size of results to return (small or large)
	 * @return integer Number of results imported
	 */
	public function importBooks($table, $topic_id, $query, $fullView = false, $numResults = "small") {
		$db = $this->m_db;
		$g = $this->m_google;
		$books_table = $table;
		$g_books = $g->getBooks($query, $fullView, $numResults);
		if (empty($g_books)) {
			return;
		}
		foreach ($g_books as $book) {
			$book_topicId = $topic_id;
			$book_timeAdded = time();
			$book_title = $db->safeString($book['title']);
			$book_titleNoFormatting = $book['titleNoFormatting'];
			$book_titleNoFormatting = str_replace(' ...', '', $book_titleNoFormatting);
			$book_titleNoFormatting = $this->removeNonAscii($book_titleNoFormatting);
			$book_titleNoFormatting = trim($book_titleNoFormatting);
			$book_titleNoFormatting = $db->safeString($book_titleNoFormatting);
			
			$book_slug = $this->makeSlug($book_titleNoFormatting);
			$book_slug = $db->safeString($book_slug);
			
			$book_unescapedUrl = $db->safeString($book['unescapedUrl']);
			$book_url = $db->safeString($book['url']);
			$book_authors = $db->safeString($book['authors']);
			$book_bookId = $db->safeString($book['bookId']);
			$book_publishedYear = $db->safeString($book['publishedYear']);
			$book_pageCount = $db->safeString($book['pageCount']);
			$book_query = "INSERT INTO $books_table (topicId, timeAdded, slug, title, titleNoFormatting, unescapedUrl, url, authors, bookId, publishedYear, pageCount) VALUES ('$book_topicId', '$book_timeAdded', '$book_slug', '$book_title', '$book_titleNoFormatting', '$book_unescapedUrl', '$book_url', '$book_authors', '$book_bookId', '$book_publishedYear', '$book_pageCount')";
			if ($db->query($book_query)) {
				$num_imports++;
			} else {
				echo($db->getError() . " from $books_table table<br />");
			}
		}
		return $num_imports;
	}
	
}

?>