<form id='create' action="" enctype="multipart/form-data" method="post"
      accept-charset="utf-8">
	<div class="box-body">
		<div id="status"></div>
		<div class="form-group col-md-4 col-sm-12">
			<label for=""> First Name </label>
			<input type="text" class="form-control" id="first_name" name="first_name" value=""
			       placeholder="" required>
			<span id="error_first_name" class="has-error"></span>
		</div>
		<div class="form-group col-md-4 col-sm-12">
			<label for=""> Last Name </label>
			<input type="text" class="form-control" id="last_name" name="last_name" value=""
			       placeholder="" required>
			<span id="error_last_name" class="has-error"></span>
		</div>
		<div class="form-group col-md-4 col-sm-12">
			<label for=""> Login Name </label>
			<input type="text" class="form-control" id="username" name="username" value=""
			       placeholder="" required>
			<span id="error_username" class="has-error"></span>
		</div>
		<div class="clearfix"></div>
		<div class="form-group col-md-4 col-sm-12">
			<label for=""> User Email </label>
			<input type="text" class="form-control" id="email" name="email" value=""
			       placeholder="" required>
			<span id="error_email" class="has-error"></span>
		</div>
		<div class="form-group col-md-4 col-sm-12">
			<label for=""> Password </label>
			<input type="password" class="form-control" id="password" name="password" value=""
			       placeholder="" required>
			<span id="error_password" class="has-error"></span>
		</div>
		<div class="form-group col-md-4 col-sm-12">
			<label for=""> Phone </label>
			<input type="text" class="form-control" id="user_phone" name="user_phone" value=""
			       placeholder="" required>
			<span id="error_user_phone" class="has-error"></span>
		</div>
		<div class="clearfix"></div>
		<div class="form-group col-md-4 col-sm-12">
			<label>User Group</label>
			<select class="form-control" id="group_id" name="group_id" required>
				<?php foreach ( $groups as $group ): ?>
					<option value="<?= $group->id ?>"><?= $group->name ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group col-md-8">
			<label> User Image </label>
			<!--     <label for = "user_image"><?php // echo $this->lang->line('admin_image'); ?></label>  -->
			<input id="user_image" type="file" name="user_image" style="display:none">

			<div class="input-group">
				<div class="input-group-btn">
					<a class="btn btn-primary" onclick="$('input[id=user_image]').click();">Browse</a>

				</div>
				<!-- /btn-group -->

				<input type="text" name="SelectedFileName" class="form-control" id="SelectedFileName"
				       value="" readonly>

			</div>
			<div style="clear:both;"></div>
			<p class="help-block">File Extension must be jpg, jpeg, png, allowed dimension less than(800*800) and Size
				less than 2MB </p>
			<script type="text/javascript">
				$('input[id=user_image]').change(function () {
					$('#SelectedFileName').val($(this).val());
				});
			</script>
			<span id="error_SelectedFileName" class="has-error"></span>
		</div>
		<div class="form-group col-md-12">
			<input type="submit" id="submit" name="submit" value="Save" class="btn btn-primary">
			<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
			<small><img id="loader" src="<?php echo site_url( 'assets/images/loadingg.gif' ); ?>"/></small>
		</div>
	</div>
	<!-- /.box-body -->
</form>
<script>
	$('[data-toggle="tooltip"]').tooltip();
	$('#user_name').keyup(function () {

		var accountRegex = /^[a-zA-Z_ ]+$/;
		var user_name = $("#user_name").val();

		if (!(accountRegex.test(user_name))) {
			$("#error_user_name").html('The user name contains only characters and underscore.');
			return false;
		} else {
			$("#error_user_name").html('');
		}
	});
</script>
<script>
	$(document).ready(function () {
		$('#loader').hide();
		$('#create').validate({// <- attach '.validate()' to your form
			// Rules for form validation
			rules: {
				username: {
					required: true
				}
			},
			// Messages for form validation
			messages: {
				user_name: {
					required: 'Please enter user name'
				}
			},
			submitHandler: function (form) {

				var myData = new FormData($("#create")[0]);

				$.ajax({
					url: BASE_URL + 'admin/user/create',
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
							reload_table();
							notify_view(data.type, data.message);
							$('#loader').hide();
							$("#submit").prop('disabled', false); // disable button
							$("html, body").animate({scrollTop: 0}, "slow");
							$('#modalUser').modal('hide'); // hide bootstrap modal

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