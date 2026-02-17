<div class="card">
	<div class="card-header"><strong>Create New Company</strong></div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-12">
				<?php
				if($this->session->flashdata('alert'))
				{
					?>
					<div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
						<?php echo $this->session->flashdata('alert_message'); ?>
					</div>
					<?php
				}
				?>
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<div class="form-group">
				        <label for="exampleInputEmail1">Company Name <span class="text-danger required">*</span></label>
				        <input type="text" class="form-control" placeholder="Enter the company name" name="company_name" required="">
				    </div>
				    <div class="form-group">
				        <label for="exampleInputEmail1">Industry<span class="text-danger required">*</span></label>
				        <select name="project_status" class="form-control">
				        </select>
				    </div>
				    
				    <button name="submit" type="submit" class="btn btn-primary">Create</button>
				 </form>
			</div>
		</div>
	</div>
</div>
