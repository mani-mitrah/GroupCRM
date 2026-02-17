<?php
defined('BASEPATH') OR exit('No direct script access allowed');



if( ! isset( $on_hold_message ) )
{
	if( isset( $login_error_mesg ) )
	{
		echo '
			<div class="alert alert-danger">
				<p>
					Login Error #' . $this->authentication->login_errors_count . '/' . config_item('max_allowed_attempts') . ': Invalid Username, Email Address, or Password.
				</p>
				<p>
					Username, email address and password are all case sensitive.
				</p>
			</div>
		';
	}

	if( $this->input->get(AUTH_LOGOUT_PARAM) )
	{
		echo '
			<div class="alert alert-success">
				<p>
					You have successfully logged out.
				</p>
			</div>
		';
	}

	echo form_open( $login_url, ['class' => 'mb-3'] ); 
?>

    <div class="form-group">
        <label for="email" class="sr-only">Email Address</label>
        <!--<input type="text" name="login_string" id="login_string" class="form-control form-control-md" autocomplete="off" placeholder="Enter your email" maxlength="255" />-->
        <select class="form-control form-control-md" name="login_string" id="login_string">
        	<option value="agent@thenanolab.com">Login as super agent</option>
        	<option value="info@thenanolab.com">Login as super admin</option>
        	<option value="customer@thenanolab.com">Login as super customer</option>
        </select>
    </div>
    <div class="form-group">
        <label for="password" class="sr-only">Password</label>
        <input type="password" name="login_pass" id="login_pass" placeholder="Enter Password" class="form-control form-control-md" <?php 
			if( config_item('max_chars_for_password') > 0 )
				echo 'maxlength="' . config_item('max_chars_for_password') . '"'; 
		?> autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');" value="Password!23" />
    </div>
    <div class="form-group d-flex justify-content-between">
    	<?php
		if( config_item('allow_remember_me') )
		{
		?>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" checked="" id="checkbox-remember" name="remember_me" value="yes">
            <label class="custom-control-label text-muted font-size-sm" for="checkbox-remember">Remember me</label>
        </div>
        <?php 
    	}
        ?>
        <a class="font-size-sm" href="<?php echo base_url(); ?>recover">Reset password</a>
    </div>
    <button class="btn btn-primary btn-lg btn-block text-uppercase font-weight-semibold" name="submit" type="submit">Sign in</button>
</form>

	
<?php

	}
	else
	{
		// EXCESSIVE LOGIN ATTEMPTS ERROR MESSAGE
		echo '
			<div class="alert alert-danger">
				<p>
					Excessive Login Attempts
				</p>
				<p>
					You have exceeded the maximum number of failed login<br />
					attempts that this website will allow.
				<p>
				<p>
					Your access to login and account recovery has been blocked for ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes.
				</p>
				<p>
					Please use the <a href="/examples/recover">Account Recovery</a> after ' . ( (int) config_item('seconds_on_hold') / 60 ) . ' minutes has passed,<br />
					or contact us if you require assistance gaining access to your account.
				</p>
			</div>
		';
	}