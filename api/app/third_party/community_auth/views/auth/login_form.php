<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!isset($optional_login)) {
    echo '';
}

if (!isset($on_hold_message)) {
    if (isset($login_error_mesg)) {
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

    if ($this->input->get(AUTH_LOGOUT_PARAM)) {
        echo '
			<div class="alert alert-success">
				<p>
					You have successfully logged out.
				</p>
			</div>
		';
    }

    echo form_open(isset($login_url) ? $login_url : '', ['class' => 'user']);
    ?>


<div class="form-group">
    <input type="text" name="login_string" id="login_string" class="form-control form-control-user" autocomplete="off"
        maxlength="255" placeholder="Email Address / Username" />
</div>
<div class="form-group">
    <input type="password" name="login_pass" id="login_pass" class="form-control form-control-user password" <?php
if (config_item('max_chars_for_password') > 0) {
        echo 'maxlength="' . config_item('max_chars_for_password') . '"';
    }

    ?> autocomplete="off" readonly="readonly" onfocus="this.removeAttribute('readonly');" placeholder="Password" />
</div>

<?php
if (config_item('allow_remember_me')) {
        ?>
<div class="form-check-inline">
    <label for="remember_me" class="form-check-label">
        <input class="form-check-input" id="remember_me" name="remember_me" value="yes"
            type="checkbox"><?php echo $this->lang->line('login_remember'); ?></label>
</div>
<?php
}
    ?>
<input class="btn btn-primary btn-block" type="submit" name="submit" value="Login" id="submit_button" />


</form>

<div class="row mt-4">
    <div class="col-12 text-center">
        <p class="text-muted mb-2"><a href="<?php echo base_url(); ?>recover"
                class="text-muted font-weight-medium ml-1">Forgot your password?</a></p>
    </div> <!-- end col -->
</div>

</form>

<?php

} else {
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
					Your access to login and account recovery has been blocked for ' . ((int) config_item('seconds_on_hold') / 60) . ' minutes.
				</p>
				<p>
					Please use the <a href="/examples/recover">Account Recovery</a> after ' . ((int) config_item('seconds_on_hold') / 60) . ' minutes has passed,<br />
					or contact us if you require assistance gaining access to your account.
				</p>
			</div>
		';
}

/* End of file login_form.php */
/* Location: /community_auth/views/examples/login_form.php */