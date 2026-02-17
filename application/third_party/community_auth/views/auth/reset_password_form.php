<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
	#password-policy {
		list-style-type: none;
		padding: 0;
	}

	#password-policy li {
		color: red;
		margin-bottom: 5px;
	}

	#password-policy li.valid {
		color: green;
		text-decoration: line-through;
	}

	#password-policy li.invalid {
		color: red;
	}
</style>
<div class="modal-content">
    <form method="post" action="/auth/update_password" id="updateForm">
        <div class="modal-body">
            <div class="h5 modal-title text-center">
                <h4 class="mt-2">
                    <img src="/global/images/ontime_digital.png" style="height: 120px;">
                    <div>Update Your Password,</div>
                    <span>As your password have been expired. It's time to change</span>
                </h4>
            </div>
            <div class="form-row">
                <div class="col-md-12">
					<div class="position-relative form-group">
						<div class="input-group mb-3 " id="show_hide_password">
							<input name="password" type="password" class="form-control validate" placeholder="Enter New Password" id="password" aria-describedby="basic-addon3" onkeyup="validatePassword()">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon3">
									<i class="fa fa-eye-slash"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
                <div class="col-md-12">
					<div class="position-relative form-group">
						<div class="input-group mb-3" id="cshow_hide_password">
							<input name="confirm_password" type="password" class="form-control validate" placeholder="Confirm Password" id="confirm_password" aria-describedby="basic-addon3">
							<div class="input-group-prepend">
								<span class="input-group-text" id="basic-addon3">
									<i class="fa fa-eye-slash"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
                <div class="col-md-12">
					<ul id="password-policy">
						<li id="length" class="invalid">At least 8 characters long</li>
						<li id="uppercase" class="invalid">Contains an uppercase letter</li>
						<li id="lowercase" class="invalid">Contains a lowercase letter</li>
						<li id="number" class="invalid">Contains a number</li>
						<li id="special" class="invalid">Contains a special character</li>
					</ul>
					<div class="position-relative">
						<div class="input-group mb-3">
                            <p class="text-danger" id="error_message"></p>
						</div>
					</div>
				</div>
            </div>
        </div>
        <div class="modal-footer justify-content-center">
            <input type="submit" value="Update Password" class="btn btn-primary btn-lg">
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
        $("#cshow_hide_password span").on('click', function(event) {
			event.preventDefault();
			if ($('#cshow_hide_password input').attr("type") == "text") {
				$('#cshow_hide_password input').attr('type', 'password');
				$('#cshow_hide_password i').addClass("fa-eye-slash");
				$('#cshow_hide_password i').removeClass("fa-eye");
			} else {
				$('#cshow_hide_password input').attr('type', 'text');
				$('#cshow_hide_password i').removeClass("fa-eye-slash");
				$('#cshow_hide_password i').addClass("fa-eye");
			}
		});
        $('.validate').focusin(function() {
            $('#error_message').empty();
        })
        $("#updateForm").on("submit", function (e) {
            e.preventDefault();
            let count = 0;
            const password = $('#password').val();
            const cpassword = $('#confirm_password').val();
            // const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/;
			const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{8,}$/;

			if (password === '' || cpassword === '') {
                $('#error_message').text("Password fields cannot be empty.");
                count++;
            }
            else if (password.toLowerCase() === "welcome@123".toLowerCase()) {
                $('#error_message').text("Given password cannot be used.");
                count++;
            }else if (password !== cpassword) {
                $('#error_message').text("Passwords do not match.");
                count++;
            }else if (!regex.test(password)) {
                $('#error_message').text("Password must have at least 8 characters, one uppercase letter, one lowercase letter, one number, and one special character.");
                count++;
            }
            if (count === 0) 
			{
				$.ajax({
					url:"<?php echo base_url('auth/check_password')?>",
					method:"POST",
					type:'ajax',
					data:{'password':password},
					success:function(response){
						console.log(response)
						if(response === 'satisfied'){
							 $('#updateForm').off('submit').submit();
						}else{
							$('#error_message').text("Given password cannot be used.");
						}
					},
					error:function(err){
                        console.log('something went wrong')
						console.log(err);
					}
				})
            }
        });
	});
	function validatePassword() {
		const password = document.getElementById("password").value;
		const length = document.getElementById("length");
		const uppercase = document.getElementById("uppercase");
		const lowercase = document.getElementById("lowercase");
		const number = document.getElementById("number");
		const special = document.getElementById("special");

		// Regex patterns
		const lengthRegex = /.{8,}/;
		const uppercaseRegex = /[A-Z]/;
		const lowercaseRegex = /[a-z]/;
		const numberRegex = /\d/;
		const specialRegex = /[.,'" \-+=^&*<>\[{}\]@#$!%?`~|\\\/()_:;]/;

		// Validate each requirement
		length.classList.toggle("valid", lengthRegex.test(password));
		length.classList.toggle("invalid", !lengthRegex.test(password));

		uppercase.classList.toggle("valid", uppercaseRegex.test(password));
		uppercase.classList.toggle("invalid", !uppercaseRegex.test(password));

		lowercase.classList.toggle("valid", lowercaseRegex.test(password));
		lowercase.classList.toggle("invalid", !lowercaseRegex.test(password));

		number.classList.toggle("valid", numberRegex.test(password));
		number.classList.toggle("invalid", !numberRegex.test(password));

		special.classList.toggle("valid", specialRegex.test(password));
		special.classList.toggle("invalid", !specialRegex.test(password));
	}

</script>