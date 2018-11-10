<div id="page-wrapper">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					Change Password
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-10">
							<div id="infoMessage" class="text-bold text-red"><?php echo $message; ?></div>
							<form id="change_password" role="form" method="POST"
							      action="<?= base_url( 'admin/admin_dashboard/change_password' ) ?>">
								<div class="form-group col-md-6">
									<label>Old Password</label>
									<input class="form-control" placeholder="Enter old password" id="old"
									       name="old" value="<?php echo set_value( 'old' ); ?>" required>
									<span class="text-danger"><?php echo form_error( 'old' ); ?></span>
								</div>
								<div class="form-group col-md-6">
									<label>New Password ( Password min <?php echo $min_password_length; ?> characters
										long)</label>
									<input name="new" class="form-control" placeholder="Enter new password"
									       id="new" type="password"
									       value="<?php echo set_value( 'new' ); ?>" required>
									<span class="text-danger"><?php echo form_error( 'new' ); ?></span>
								</div>
								<div class="form-group col-md-12">
									<label>Confirm New Password</label>
									<input name="new_confirm" class="form-control" placeholder="Confirm new password"
									       id="new_confirm" type="password"
									       value="<?php echo set_value( 'new_confirm' ); ?>" required>
									<span class="text-danger"><?php echo form_error( 'new_confirm' ); ?></span>
								</div>
								<?php echo form_input( $user_id ); ?>
								<div class="form-group col-md-6">
									<button type="submit" name="submit" value="save" class="btn btn-success"><i
											class="fa fa-check"></i> Save
									</button>
									<button type="reset" class="btn btn-primary"><i class="fa fa-refresh"></i> Reset
									</button>
									<a href="<?= base_url( 'admin/admin_dashboard/' ) ?>" class="btn btn-default"> <i
											class="fa fa-warning"></i> Cancel</a>
								</div>
							</form>
						</div>


					</div>
					<!-- /.row (nested) -->
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
</div>
<!-- /#page-wrapper -->
<script type="text/javascript">
	$(document).ready(function () {
		$('#change_password').validate({

			// <- end 'submitHandler' callback
		});                    // <- end '.validate()'

	});
</script>

