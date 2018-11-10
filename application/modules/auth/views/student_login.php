<div class="login-wrap">
	<div class="login-html">
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">
			Student Sign In</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
		<div class="login-form">
			<div class="sign-in-htm">
			   <?php
				$attributes = array( "class" => "form-horizontal", "id" => "loginform", "name" => "loginform" );
				echo form_open( "auth/student_login/login", $attributes );
				?>
				<div id="infoMessage"><?php echo $this->session->flashdata( 'msg' ); ?></div>
				<div class="group">
					<input type="text" id="student_id" name="student_id" class="input" placeholder="Student ID"
					value="<?php echo set_value( 'student_id' ); ?>"/>
					<span class="text-danger"><?php echo form_error( 'student_id' ); ?></span>
				</div>
				<div class="group">
						<input type="password" id="password" name="password" placeholder="Password"
						class="input" data-type="password"
						value="<?php echo set_value( 'password' ); ?>"/>
						<span class="text-danger"><?php echo form_error( 'password' ); ?></span>
				</div>
				<div class="group">
					<input id="check" type="checkbox" remember name="remember" class="check" checked>
					<label for="check"><span class="icon"></span> Keep me Signed in</label>
<!--                    <a href="forgot_password" class="pull-right">Forgot Password ?</a>-->
				</div>
				<div class="group">
					<input type="submit" name="submit" class="button" value="Sign In">
				</div>
				<div class="footer" style="color:#a5a5a5;text-transform: uppercase;"> &copy; <?php echo date( 'Y' ); ?> Chittagong Port Women College</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/login_style.css">