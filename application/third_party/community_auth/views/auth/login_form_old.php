<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<?php
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

	echo form_open( $login_url, ['class' => 'form-horizontal auth-form my-4'] ); 
?>
	<div class="form-group">
        <label for="username">Username</label>
        <div class="input-group mb-3">
            <span class="auth-form-icon">
                <i data-feather="user" class="icon-xs"></i>
            </span>                                                                                                              
            <input type="text" class="form-control" required autofocus name="login_string" id="login_string" maxlength="255" placeholder="Enter your email" value="info@ontimegroup.com" />
        </div>                                    
    </div>
    <div class="form-group">
        <label for="userpassword">Password</label>                                            
        <div class="input-group mb-3"> 
            <span class="auth-form-icon">
                <i data-feather="key" class="icon-xs"></i>
            </span>                                                       
            <input 
            	value="Password!23" 
            	type="password" 
            	class="form-control" 
            	required  
            	name="login_pass" 
            	id="login_pass" 
            	placeholder="Enter password"
            	<?php 
				if( config_item('max_chars_for_password') > 0 )
					echo 'maxlength="' . config_item('max_chars_for_password') . '"'; 
				?>
				readonly="readonly" 
				onfocus="this.removeAttribute('readonly');"
            />
        </div>                               
    </div>
    <div class="form-group row mt-4">
        <div class="col-sm-6">
            <div class="custom-control custom-switch switch-success">
                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember_me" value="yes">
                <label class="custom-control-label text-muted" for="remember_me">Remember me</label>
            </div>
        </div><!--end col--> 
        <div class="col-sm-6 text-right">
            <a href="<?php echo base_url(); ?>recover" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>                                    
        </div><!--end col--> 
    </div><!--end form-group--> 
    <div class="form-group mb-0 row">
        <div class="col-12 mt-2">
            <button class="btn btn-primary btn-round btn-block waves-effect waves-light" type="submit" name="submit">Log In <i class="fas fa-sign-in-alt ml-1"></i></button>
        </div><!--end col--> 
    </div>
		

        <!-- <div class="form-group">
            <input value="Password!23" type="password" class="form-control" placeholder="Password" required  name="login_pass" id="login_pass"  <?php 
			if( config_item('max_chars_for_password') > 0 )
				echo 'maxlength="' . config_item('max_chars_for_password') . '"'; 
			?> readonly="readonly" onfocus="this.removeAttribute('readonly');">
        </div> -->
        <?php
			/*if( config_item('allow_remember_me') )
			{
		?>
			<div class="form-group d-flex justify-content-between">
	            <div class="custom-control custom-checkbox">
	                <input type="checkbox" class="custom-control-input" id="remember_me" name="remember_me" value="yes">
	                <label class="custom-control-label" for="remember_me">Remember me</label>
	            </div>
	            <a href="<?php echo base_url(); ?>reset">Reset password</a>
	        </div>

		<?php
			}*/
		?>

		<!-- <button name="submit" class="btn btn-primary btn-block">Sign in</button> -->

		<!-- <hr>
        <p class="text-muted">Don't have an account?</p>
        <a href="<?php echo base_url(); ?>register" class="btn btn-outline-light btn-sm">Register now!</a> -->
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

/* End of file login_form.php */
/* Location: /community_auth/views/examples/login_form.php */ 