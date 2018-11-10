<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Create New Group
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-6">
						<?php
						echo form_open("admin/usergroups/create");
						?>
						<form role="form" method="POST" action="<?= base_url( 'admin/usergroups/create' ) ?>">
							<div class="form-group">
								<label>Name</label>
								<input class="form-control" placeholder="Enter group name" id="name" name="name"
								       value="<?php echo set_value('name'); ?>">
								<span class="text-danger"><?php echo form_error('name'); ?></span>
							</div>
							<div class="form-group">
								<label>Description</label>
								<input class="form-control" placeholder="Enter group description" id="description"
								       name="description" value="<?php echo set_value('description'); ?>">
								<span class="text-danger"><?php echo form_error('description'); ?></span>
							</div>
							<button type="submit" name="submit"  value="save" class="btn btn-success"> <i class="fa fa-check"></i> Save</button>
							<button type="reset" class="btn btn-primary"><i class="fa fa-refresh"></i> Reset</button>
							<a href="<?= base_url( 'admin/usergroups/' ) ?>" class="btn btn-default"> <i
									class="fa fa-warning"></i> Cancel</a>
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