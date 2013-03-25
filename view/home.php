<?php include('header.php'); ?>
<article> 
	<h1>Welcome to the Swift PHP Framework!</h1>
	<p>We recommend reading the <a href="http://swiftphp.org/learn/beginners-guide/">beginner's guide</a> to help you get started.</p>
	<h3>Test Pages:</h3>
	<a href="<?php echo $swift->config('app_url'); ?>/user/Derek"><?php echo $swift->config('app_url'); ?>/user/Derek</a><br/>
	<a href="<?php echo $swift->config('app_url'); ?>/user/Colleen"><?php echo $swift->config('app_url'); ?>/user/Colleen</a>
</article>
<?php include('footer.php'); ?>