<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<style type="text/css">
	.field-icon {
		float: right;
		margin-left: -25px;
		margin-top: 10px;
		position: relative;
		z-index: 2;
	}

	.input-group-addon {
		padding: .5rem .75rem;
		margin-bottom: 0;
		font-size: 1rem;
		font-weight: 400;
		line-height: 1.25;
		color: #495057;
		text-align: center;
		background-color: #e9ecef;
		border: 1px solid rgba(0, 0, 0, .15);
		border-radius: .25rem;
	}

	.input-group-addon,
	.input-group-btn {
		white-space: nowrap;
		vertical-align: middle;
	}

	.input-group .form-control,
	.input-group-addon,
	.input-group-btn {
		display: -ms-flexbox;
		display: flex;
		-ms-flex-align: center;
		align-items: center;
	}
</style>

<div class="modal-content">
	<form method="post" action="<?php echo base_url();?>login?redirect=dashboard/index">
		<?php
		echo form_open($login_url, ['class' => '']);

		?>
		<div class="modal-body">
			<div class="h5 modal-title text-center">
				<h4 class="mt-2">

					<img src="<?php echo base_url(); ?>global/images/ontime_digital.png" style="height: 120px;">
					<div>Welcome back,</div>
					<span>Please sign in to your account below.</span>
					
					<?php
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
			</div>';
						}

						if ($this->input->get(AUTH_LOGOUT_PARAM)) {
							echo '<div class="alert alert-success">
				<span>
					You have successfully logged out.
				</span>
			</div>';
						}

					?>

				</h4>
			</div>
			<div class="form-row">
				<div class="col-md-12">
					<div class="position-relative form-group">
						<input name="login_string" id="exampleEmail" placeholder="Email here..." type="email" class="form-control">
					</div>
				</div>
				<div class="col-md-12">
					<div class="position-relative form-group">
						<div class="input-group mb-3 " id="show_hide_password">
							<input name="login_pass" type="password" class="form-control" placeholder="Password here..." id="basic-url" aria-describedby="basic-addon3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon3">
									<i class="fa fa-eye-slash"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="position-relative form-check">
				<input name="check" id="exampleCheck" type="checkbox" class="form-check-input">
				<label for="exampleCheck" class="form-check-label">Keep me logged in</label>
			</div>
		</div>

		<div class="modal-footer clearfix">
			<div class="float-left ml-0 mr-auto text-left"><a href="/recover" class="btn-lg btn btn-link ">Recover Password</a></div>
			<div class="float-right">
				<input type="submit" value="Login to Dashboard" class="btn btn-primary btn-lg">
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("#show_hide_password span").on('click', function(event) {
			event.preventDefault();
			if ($('#show_hide_password input').attr("type") == "text") {
				$('#show_hide_password input').attr('type', 'password');
				$('#show_hide_password i').addClass("fa-eye-slash");
				$('#show_hide_password i').removeClass("fa-eye");
			} else if ($('#show_hide_password input').attr("type") == "password") {
				$('#show_hide_password input').attr('type', 'text');
				$('#show_hide_password i').removeClass("fa-eye-slash");
				$('#show_hide_password i').addClass("fa-eye");
			}
		});
	});
</script>

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
