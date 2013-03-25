<?php include('header.php'); ?>
<article> 
	<h1><?php echo $username ?></h1>
	<p>Welcome to <?php echo $username ?>'s profile page!</p>
	<p><a href="<?php echo $swift->config('app_url'); ?>">Go back</a></p>
</article>
<?php include('footer.php'); ?>