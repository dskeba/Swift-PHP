<?php
/**
 * Step 1: Include Swift PHP
 *
 * By default, you should be able to include Swift PHP class by
 * using the file path provided below ('Swift/Swift.php'). However,
 * if you move the 'Swift' directory somewhere else, then you may
 * need to update the file path below.
 */
require_once 'Swift/Swift.php';

/**
 * Step 2: Get an instance of Swift PHP
 *
 * The core of the Swift PHP Framework is the Swift static class.
 * Here we simply get an instance of this class by calling
 * the Swift::getInstance() static function.
 */
$swift = Swift::getInstance();

/**
 * Step 3: Configure your Swift PHP app
 *
 * By calling the config() function using our Swift PHP object
 * we can set the first param to be the setting we want to change
 * and optionally add a second parameter to assign a value to
 * this setting.
 */
$swift->config('app_url', 'http://localhost/');
$swift->config('app_view_dir', 'view');

/**
 * Step 4: Add routes to Swift PHP
 *
 * Before running the Swift PHP Framework, we need to define
 * some URL routes for our application. We use the map() function
 * to associate a specific request URI or regular expression
 * with a callback function. If the user enters a request that
 * matches the regular expression, then it will be stored and
 * accessible with the $swift->param($key) function, where $key
 * is the index of all matches.
 */
$swift->map('/', loadHomePage);
$swift->map('/user/([a-bA-Z0-9_\-]+)/', loadUserPage);

/**
 * Step 5: Create functions for loading pages
 *
 * Create functions for rendering our pages that we referenced 
 * above when mapping our URI's to a callback function.
 */
function loadHomePage() {
	global $swift;
	$swift->render('home.php');
}

function loadUserPage() {
	global $swift;
	$data['username'] = $swift->param(0);
	$swift->render('user.php', $data);
}

/**
 * Step 6: Run Swift PHP
 *
 * Lastly, we run the Swift PHP Framework by calling the run()
 * function. This function should be called last and is
 * responsible for executing the application using the
 * configuration and routes defined above.
 */
$swift->run();

?>