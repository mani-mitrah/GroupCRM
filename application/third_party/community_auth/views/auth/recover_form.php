<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="modal-content p-3">

<img src="/global/images/ontime_digital.png" style="height: 120px;object-fit: contain;object-position: center;">
	<h4 class="text-center mb-4">Account Recovery</h4>
	<?php
	if ($disabled != '' && $disabled == 1) {
		echo '
		<div class="alert alert-danger">
			<p>
				Account Recovery is Disabled.
			</p>
			<p>
				If you have exceeded the maximum login attempts, or exceeded
				the allowed number of password recovery attempts, account recovery 
				will be disabled for a short period of time. 
				Please wait ' . ((int) config_item('seconds_on_hold') / 60) . ' 
				minutes, or contact us if you require assistance gaining access to your account.
			</p>
		</div>
	';
	}

	if ($banned != '' && $banned == 1) {
		echo '
		<div class="alert alert-danger">
			<p>
				Account Locked.
			</p>
			<p>
				You have attempted to use the password recovery system using 
				an email address that belongs to an account that has been 
				purposely denied access to the authenticated areas of this website. 
				If you feel this is an error, you may contact us  
				to make an inquiry regarding the status of the account.
			</p>
		</div>
	';
	}

	if ($confirmation != '' && $confirmation == 1) {
		echo '
		<div class="alert alert-success">
			<p>
				Congratulations, you have created an account recovery link.<br />
				"We have sent you an email with instructions on how 
				to recover your account."
			</p>
		</div>
	';
	}

	if ($no_match != '' && $no_match == 1) {
		echo '
		<div  class="alert alert-danger">
			<p class="feedback_header">
				Supplied email did not match any record.
			</p>
		</div>
	';

		$show_form = 1;
	} else {
		echo '
		<p>
			If you\'ve forgotten your password and/or username, 
			enter the email address used for your account, 
			and we will send you an e-mail 
			with instructions on how to access your account.
		</p>
	';

		$show_form = 1;
	}
	if (isset($show_form)) {
	?>

		<?php echo form_open(); ?>
		<div class="form-group">
			<label class="mb-1"><strong>Registered Email Address</strong></label>
			<input type="email" class="form-control" required autofocus name="email" id="email" maxlength="255" placeholder="Enter your email" value="" />
		</div>
		<div class="text-center">
			<button type="submit" name="submit" class="btn btn-primary btn-block">Send Email</button>
		</div>
		<!-- <div>
				<fieldset>
					<legend>Enter your account's email address:</legend>
					<div>

						<?php
						// EMAIL ADDRESS *************************************************
						echo form_label('Email Address', 'email', ['class' => 'form_label']);

						$input_data = [
							'name'		=> 'email',
							'id'		=> 'email',
							'class'		=> 'form_input',
							'maxlength' => 255,
							'required'  => 'required',
							'type'  => 'email'

						];
						echo form_input($input_data);
						?>

					</div>
				</fieldset>
				<div> -->
		<!-- <div>

						<?php
						// SUBMIT BUTTON **************************************************************
						$input_data = [
							'name'  => 'submit',
							'id'    => 'submit_button',
							'value' => 'Send Email'
						];
						echo form_submit($input_data);
						?>

					</div> 
				</div>
			</div>-->
		</form>
		<a href="<?php echo base_url();?>" class="mt-3 text-right"><button class="btn">Sign in</button></a> 
</div>
<?php
	}
/* End of file recover_form.php */
/* Location: /community_auth/views/examples/recover_form.php */