<meta charset="utf-8">
<link rel="icon" href="<?php echo base_url(); ?>assets/images/fav_icon.png" type="image/x-icon" />
<meta http-equiv = "X-UA-Compatible" content = "IE=edge">
<meta name = "viewport" content = "width=device-width, initial-scale=1">
<meta name = "description" content = "">
<meta name = "author" content = "">
<title><?php echo @$title; ?></title>
<meta content = "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name = "viewport">
<!-- style sheet -->
<link rel = "stylesheet" href = "<?php echo base_url(); ?>assets/css/AdminLTE.min.css">
<link rel = "stylesheet" href = "<?php echo base_url(); ?>assets/css/_all-skins.min.css">


<?php
foreach ( $css_files as $file ): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>"/>
<?php endforeach; ?>
<?php foreach ( $js_files as $file ): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<script type="text/javascript">
	var BASE_URL = "<?php echo base_url(); ?>";
</script>