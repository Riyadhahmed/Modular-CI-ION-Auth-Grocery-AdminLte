<!-- Logo -->
<a href="<?php echo site_url( 'admin/dashboard' ); ?>" class="logo">
	<!-- mini logo for sidebar mini 50x50 pixels -->
	<span class="logo-mini">AP</span>
	<!-- logo for regular state and mobile devices -->
	<span class="logo">Dashboard </span>
</a>
<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
	<!-- Sidebar toggle button-->
	<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
		<span class="sr-only">Toggle navigation</span>
	</a>

	<div class="navbar-custom-menu">
		<ul class="nav navbar-nav">
			<!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-envelope-o"></i>
                    <span class="label label-danger" id="notification"></span>
                </a>
            </li>
			<li class="dropdown user user-menu">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
				</a>
				<ul class="dropdown-menu">
					<li class="user-header">
					 <h4 style="color: #fff;font-weight: bold;text-transform:uppercase;display:inline-block;">                        
                        <?php echo $_SESSION['settings_data']['settings_name']; ?>
                     </h4>
					 <p><?php echo $_SESSION['settings_data']['settings_email']; ?></p>
					 <p><?php echo $_SESSION['settings_data']['settings_contact']; ?></p>
					</li>
					<li class="user-footer">
						<div class="pull-left">
							<a href="<?php echo site_url( 'admin/dashboard/profile' ); ?>"
							   class="btn btn-default btn-flat">Profile</a>
						</div>
						<div class="pull-right">
							<a href="<?php echo site_url( 'auth/logout' ); ?>"
							   class="btn btn-default btn-flat">Sign out</a>
						</div>
					</li>
				</ul>
			</li>
		</ul>
	</div>
</nav>
<style>
    .logo img{
        max-width: 55%;
        margin-left: auto;
        margin-right: auto;
    }
</style>
