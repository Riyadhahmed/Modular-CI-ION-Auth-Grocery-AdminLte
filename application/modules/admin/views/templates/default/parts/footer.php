<div class="pull-right hidden-xs">
	<b>Developed by </b> <a href="#">Riyadh Ahmed</a>
</div>
<strong>Copyright &copy; <?php echo date( 'Y' ) ?> .</strong> All rights reserved.

<!-- AdminLTE App -->
<script src="<?php echo base_url(); ?>assets/js/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-notify.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.form.js"></script>
<script src="<?php echo base_url(); ?>assets/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>

<!-- Select2 library -->
<link rel="stylesheet" href="<?php echo base_url( 'assets/plugins/select2/select2.min.css' ); ?>">
<script src="<?php echo base_url( 'assets/plugins/select2/select2.full.min.js' ); ?>"></script>

<!-- Date picker library -->
<link href="<?php echo base_url(); ?>assets/css/datepicker.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>

<!-- Radio Checkbox library -->
<link rel="stylesheet" href="<?php echo base_url( 'assets/plugins/iCheck/all.css' ); ?>">
<script src="<?php echo base_url( 'assets/plugins/iCheck/icheck.min.js' ); ?>"></script>


<!-- Sweet Alert library -->
<link rel="stylesheet" href="<?php echo base_url( 'assets/plugins/sweet-alert/sweetalert.css' ); ?>">
<script src="<?php echo base_url( 'assets/plugins/sweet-alert/sweetalert.min.js' ); ?>"></script>

<?php if ( isset( $before_body ) ) {
	echo $before_body;
}
?>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-notify.min.js"></script>
<script>
	$.fn.modal.Constructor.prototype.enforceFocus = function () {
	};

	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	setTimeout(function () {
		$('.alert').fadeOut(2000);
	}, 10000); // <-- time in milliseconds
</script>
<script>

	function notify_view(type, message) {

		$.notify({
			message: message
		}, {
			type: type,
			offset: {
				x: '30',
				y: '85'
			},
			spacing: 10,
			z_index: 1031,
			delay: 200,
			animate: {
				enter: 'animated fadeInDown',
				exit: 'animated fadeOutUp'
			},
			template: '<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert">' +
			'<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
			'<span data-notify="icon"></span> ' +
			'<span data-notify="title">{1}</span> ' +
			'<span data-notify="message">{2}</span>' +
			'<div class="progress" data-notify="progressbar">' +
			'<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
			'</div>' +
			'<a href="{3}" target="{4}" data-notify="url"></a>' +
			'</div>'
		});
	}


</script>