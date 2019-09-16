<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <div class="login100-form-title"
                 style="background-image: url('<?php echo base_url(); ?>assets/login/images/bg-01.jpg');">
					<span class="login100-form-title-1">
						Sign In
					</span>
            </div>
           <?php
           $attributes = array("class" => "login100-form validate-form");
           echo form_open("auth/login", $attributes);
           ?>
            <span class="error"> <?php echo $message; ?> </span>
            <div class="wrap-input100 validate-input m-b-26" data-validate="email is required">
                <span class="label-input100">Identity</span>
                <input class="input100" type="text" id="identity" name="identity" placeholder="Enter Username or Email"
                       value="<?php echo set_value('identity'); ?>">
                <span class="focus-input100"></span>
            </div>
            <div class="wrap-input100 validate-input m-b-18" data-validate="Password is required">
                <span class="label-input100">Password</span>
                <input class="input100" type="password" name="password" placeholder="Enter password">
                <span class="focus-input100"></span>
            </div>
            <div class="container-login100-form-btn">
                <input type="submit" name="submit" class="button login100-form-btn" value="Sign In">
            </div>
           <?php echo form_close(); ?>
        </div>
    </div>
</div>
<style>
    .error {
        color: #fb5f5c;
        font-size: 11px;
        font-family: verdana;

    }

</style>