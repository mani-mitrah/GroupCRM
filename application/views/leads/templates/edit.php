<script src="https://cdn.tiny.cloud/1/gn7eycc65hlmm5lk1xyd7c239x58e8q1m5vy486xs46svb2a/tinymce/5/tinymce.min.js" referrerpolicy="origin">
</script>
<script>
 tinymce.init({
   selector: 'textarea#template_content',
   menubar: false,
   init_instance_callback: function (editor) {
     editor.setContent(`<?php echo addslashes($default['template_content']); ?>`);
   }
 });
</script>


<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                Templates
                                <div class="page-title-subheading">Creation of the template for reusable text.</div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="/leads/templates/manage">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        All Templates
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fiori-container container">
                <div class="app-inner-layout chat-layout justify-content-center mt-5">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('alert')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                    <?php echo $this->session->flashdata('alert_message'); ?>
                                </div>
                            <?php } ?>
                            <form action="<?php echo base_url();?>leads/templates/edit/<?php echo $default['id'];?>" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Select Template Type <span class="text-danger required">*</span>
                                </label><br/>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="template_type" id="email" value="1" <?php echo ($default['template_type']=='1')?'checked=""':''; ?>>
                                  <label class="form-check-label" for="normal">EMAIL</label>
                                </div>
                                <div class="form-check form-check-inline">
                                  <input class="form-check-input" type="radio" name="template_type" id="sms" value="2" <?php echo ($default['template_type']=='2')?'checked=""':''; ?>>
                                  <label class="form-check-label" for="package">SMS</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Template Name <span class="text-danger required">*</span>
                                </label>
                                <input type="text" class="form-control" placeholder="Enter Template Name" name="template_name" required="" value="<?php echo $default['template_name']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Template Content <span class="text-danger required">*</span>
                                </label>
                                <textarea rows="10" class="form-control" name="template_content" id="template_content"></textarea>
                            </div>
                        </div>
                    </div>
                    <input name="submit" type="submit" class="btn btn-primary float-right btn-lg" value="Update Template"/>
                </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>