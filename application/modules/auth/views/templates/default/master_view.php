<?php defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' ); ?>
<!DOCTYPE html>
<html>
<head>
	<!-- Header and head section -->
	<?php require_once 'parts/header.php'; ?>
	<script type="text/javascript">
		var BASE_URL = "<?php echo base_url(); ?>";
	</script>
</head>
<body>
<div class="se-pre-con"></div>
<div class="container-fluid">
	<div class="row">
		{{CONTENT}}
	</div>
</div>
<!-- ./wrapper -->
</body>
</html>
<style>
	/* Paste this css to your style sheet file or under head tag */
	/* This only works with JavaScript,
	if it's not present, don't show loader */
	.no-js #loader {
		display: none;
	}

	.js #loader {
		display: block;
		position: absolute;
		left: 100px;
		top: 0;
	}

	.se-pre-con {
		position: fixed;
		left: 0px;
		top: 0px;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: url("<?php echo base_url('assets/images/pageloader/loader-64x/Preloader_3.gif')?>") center no-repeat #fff;
	}
</style>
<script>
	//paste this code under head tag or in a seperate js file.
	// Wait for window load
	$(window).load(function () {
		// Animate loader off screen
		$(".se-pre-con").fadeOut("slow");
	});
</script>