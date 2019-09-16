<?php
if ( $single ) {
	foreach ( $single as $key => $value ) {
	}
	?>
	<form id='edit_group' action="" enctype="multipart/form-data" method="post"
	      accept-charset="utf-8">
		<div class="box-body">
			<div id="status"></div>
			<div class="form-group col-md-6 col-sm-12">
				<label for=""> Group Name </label>
				<input type="text" class="form-control" id="group_name" name="group_name"
				       value="<?php echo $value['name']; ?>"
				       placeholder="">
				<input type="hidden" name="updateId" id="updateId" value="<?php echo $value['id']; ?>">
				<span id="error_group_name" class="has-error"></span>
			</div>
			<div class="form-group col-md-6 col-sm-12">
				<label for=""> Description </label>
				<input type="text" class="form-control" id="description" name="description"
				       value="<?php echo $value['description']; ?>"
				       placeholder="">
				<span id="error_description" class="has-error"></span>
			</div>
			<div class="clearfix"></div>
			<div class="form-group col-md-12">
				<input type="submit" id="submit" name="submit" value="Save" class="btn btn-primary">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				<small><img id="loader" src="<?php echo site_url( 'assets/images/loadingg.gif' ); ?>"/></small>
			</div>
		</div>
		<!-- /.box-body -->
	</form>
	<?php
} else {
	echo "Sorry!! no records found to edit";
}
?>
<script type="text/javascript">
	//Flat red color scheme for iCheck
	$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

</script>
<script>
	$('[data-toggle="tooltip"]').tooltip();
	$('#group_name').keyup(function () {

		var accountRegex = /^[a-zA-Z_ ]+$/;
		var group_name = $("#group_name").val();

		if (!(accountRegex.test(group_name))) {
			$("#error_group_name").html('The group name contains only characters and underscore.');
			return false;
		} else {
			$("#error_group_name").html('');
		}
	});
</script>
<script>
	$(document).ready(function () {
		$('#loader').hide();
		// alert();
		$('#edit_group').validate({// <- attach '.validate()' to your form
			// Rules for form validation
			rules: {
				group_name: {
					required: true
				}
			},
			// Messages for form validation
			messages: {
				group_name: {
					required: 'Please enter group name'
				}
			},
			submitHandler: function (form) {

				var myData = new FormData($("#edit_group")[0]);

				$.ajax({
					url: BASE_URL + 'admin/user_groups/edit',
					type: 'POST',
					data: myData,
					dataType: 'json',
					cache: false,
					processData: false,
					contentType: false,
					beforeSend: function () {
						$('#loader').show();
						$("#submit").prop('disabled', true); // disable button
					},
					success: function (data) {

						if (data.type === 'success') {

							$('#modalGroup').modal('hide'); // hide bootstrap modal
							notify_view(data.type, data.message);
							reload_table();

							$('#loader').hide();
							$("#submit").prop('disabled', false); // disable button
							$("html, body").animate({scrollTop: 0}, "slow");
							$("#edit_group")[0].reset();

						} else if (data.type === 'danger') {
							if (data.errors) {
								$.each(data.errors, function (key, val) {
									$('#error_' + key).html(val);
								});
							}
							$("#status").html(data.message);
							$('#loader').hide();
							$("#submit").prop('disabled', false); // disable button
							$("html, body").animate({scrollTop: 0}, "slow");

						}
					}
				});
			}
			// <- end 'submitHandler' callback
		});                    // <- end '.validate()'

	});

</script>