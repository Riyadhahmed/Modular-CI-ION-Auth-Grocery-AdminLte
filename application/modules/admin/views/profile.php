<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2 class="panel-title"> Profile</h2>
			</div>
			<div class="panel-body">
				<div class="col-md-8" id="status"></div>
				<div class="col-md-6">
					<div class="box box-primary">
						<div class="box-body box-profile">
							<img class="profile-user-img  img-thumbnail"
							     src="<?php echo base_url( $user->file_path ); ?>"
							     alt="Admin profile picture"
							     style="margin-left: 150px; border: 5px solid #99cc66; box-shadow: #99cc66"
							     width="150px"
							     height="150px"/>
							<ul class="list-group list-group-unbordered"><br/>
								<li class="list-group-item">
									<b>First Name</b> <a class="pull-right"><?= $user->first_name ?></a>
								</li>
								<li class="list-group-item">
									<b>Last Name</b> <a class="pull-right"><?= $user->last_name ?></a>
								</li>
								<li class="list-group-item">
									<b>User Name</b> <a class="pull-right"><?= $user->username ?></a>
								</li>
								<li class="list-group-item">
									<b>Email</b> <a class="pull-right"><?= $user->email ?></a>
								</li>
								<li class="list-group-item">
									<b>Phone</b> <a class="pull-right"><?= $user->phone ?></a>
								</li>
							</ul>

							<a href="<?php echo site_url( 'admin/dashboard/edit_profile' ); ?>"
							   class="btn btn-success">
								<strong> Edit Profile </strong></a>
							<a href="<?php echo site_url( 'admin/dashboard/change_password' ); ?>"
							   class="btn btn-danger">
								<strong> Change Password</strong></a>
						</div>
						<!-- /.box-body -->
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
<!-- /.row -->
