<div class="login-wrap">
    <div class="login-html">
        <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">
            Forgot Password </label>
        <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab"></label>
        <div class="login-form">
            <div class="sign-in-htm">
                <?php echo form_open("auth/forgot_password"); ?>
                <div id="infoMessage"><?php echo $message; ?></div>
                <div class="group">
                    <input name="identity" class="input" value="" id="identity" type="text" class="form-control" placeholder="Enter Your Email">
                </div>
                <div class="group">
                    <input type="submit" name="submit" class="button" value="Submit">
                </div>
                <?php echo form_close(); ?>
                <br/>
                <div class="foot-lnk">
                    <a href="login">Back to Sign In ?</a>
                </div><br/><br/>
                <div class="footer" style="color:#a5a5a5;text-transform: uppercase;"> &copy; <?php echo date( 'Y' ); ?> Chittagong Port Women College</div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<link rel='stylesheet prefetch' href='http://fonts.googleapis.com/css?family=Open+Sans:600'>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/login_style.css">

