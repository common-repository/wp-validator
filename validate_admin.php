<h1>WP-Validator</h1>


<?php if ($_GET["validate_step"] == 0): ?>

<p><?php _e("Hi, and welcome to WP-Validator! What we are going to do is crawl your site and validate every page, then show you the results!</p>
	<p>The first step is to index your site. This way we have a cache, and know exactly what pages are and aren&#39;t in your site. Then we can send them off to the W3C to validate them.</p>"); ?>

	<form action="" method="get">
		<input name="validate_step" value="1" type="hidden" />
		<input name="page" value="WP-Validator" type="hidden" />
		<input type="submit" class="button-primary" name="submit" value="<?php _e("Index"); ?>" />
	</form>

<?php elseif ($_GET["validate_step"] == 1): ?>

	<p><?php _e("All done! Now we need to send the request for each page to the W3C&#39;s validator. PLEASE NOTE - this may take some time!</p>"); ?>
	
	<form action="" method="get">
		<input name="validate_step" value="2" type="hidden" />
		<input name="page" value="WP-Validator" type="hidden" />
		<input type="submit" class="button-primary" name="submit" value="<?php _e("Validate!"); ?>" />
	</form>

<?php else: ?>

	<p>All done!</p>

<?php endif; ?>