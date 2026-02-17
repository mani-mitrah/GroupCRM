<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="modal-content p-3">

	<img src="/global/images/ontime_digital.png" style="height: 120px;object-fit: contain;object-position: center;">
	<h4 class="text-center mb-4">Account Recovery - Stage 2</h4>

	<?php

	$showform = 1;

	if (isset($validation_errors)) {
		echo '
		<div class="alert alert-danger">
			<p>
				The following error occurred while changing your password:
			</p>
			<ul>
				' . $validation_errors . '
			</ul>
			<p>
				PASSWORD NOT UPDATED
			</p>
		</div>
	';
	} else {
		$display_instructions = 1;
	}

	if (isset($validation_passed)) {
		echo '
		<div class="alert alert-success">
			<p>
				You have successfully changed your password.
			</p>
			<p>
				You can now <a href="/' . LOGIN_PAGE . '">login</a>
			</p>
		</div>
	';

		$showform = 0;
	}

	if (isset($recovery_error)) {
		echo '
		<div class="alert alert-danger">
			<p>
				No usable data for account recovery.
			</p>
			<p>
				Account recovery links expire after 
				' . ((int) config_item('recovery_code_expiration') / (60 * 60)) . ' 
				hours.<br />You will need to use the 
				<a href="/recover">Account Recovery</a> form 
				to send yourself a new link.
			</p>
		</div>
	';

		$showform = 0;
	}
	if (isset($disabled)) {
		echo '
		<div class="alert alert-danger">
			<p>
				Account recovery is disabled.
			</p>
			<p>
				You have exceeded the maximum login attempts or exceeded the 
				allowed number of password recovery attempts. 
				Please wait ' . ((int) config_item('seconds_on_hold') / 60) . ' 
				minutes, or contact us if you require assistance gaining access to your account.
			</p>
		</div>
	';

		$showform = 0;
	}
	if ($showform == 1) {
		if (isset($recovery_code, $user_id)) {
			if (isset($display_instructions)) {
				if (isset($username)) {
					echo '<p>
					Your login user name is <i>' . $username . '</i><br />
					Please write this down, and change your password now:
				</p>';
				} else {
					echo '<p>Please change your password now:</p>';
				}
			}

	?>
			<?php echo form_open(); ?>
			<div class="form-group">
				<label class="mb-1"><strong>Your new password</strong></label>
				<input type="password" class="form-control" required autofocus name="passwd" id="passwd" placeholder="Enter your new password" value="" />
			</div>
			<div class="form-group">
				<label class="mb-1"><strong>Confirm new password</strong></label>
				<input type="password" class="form-control" required autofocus name="passwd_confirm" id="passwd_confirm" placeholder="Enter your new password" value="" />
			</div>

			<?php
			// RECOVERY CODE *****************************************************************
			echo form_hidden('recovery_code', $recovery_code);

			// USER ID *****************************************************************
			echo form_hidden('user_identification', $user_id);
			?>
			<div class="text-center">
				<button type="submit" name="form_submit" class="btn btn-primary btn-block">Reset Password</button>
			</div>

			</form>
</div>
<?php
		}
	}
/* End of file choose_password_form.php */
/* Location: /community_auth/views/examples/choose_password_form.php */