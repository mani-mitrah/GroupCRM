<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container-fluid">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                New Service Creation
                                <div class="page-title-subheading">Service creation with the group details
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="/leads/service">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        All Created Services
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid mb-5">
                <div class="app-inner-layout chat-layout justify-content-center mt-5">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('alert')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                    <?php echo $this->session->flashdata('alert_message'); ?>
                                </div>
                            <?php } ?>
                            <form action="<?php echo base_url(); ?>leads/service/new" method="post" enctype="multipart/form-data">

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h5 class="font-weight-bold">Service Details</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Service Name&nbsp;<span class="text-danger required">*</span></label>
                                                    <input type="text" class="form-control" required="" name="service_name" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Branch</label>
                                                    <select name="service_branch" class="form-control" required>
                                                        <option></option>
                                                        <option value="106">Golden Cube</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Service Group</label>
                                                    <?php
                                                        $options = array();
                                                        $options[0] = '';
                                                        foreach ($groups as $group) {
                                                            $options[$group['group_id']] = $group['group_name'];
                                                        }
                                                        echo form_dropdown('service_group', $options, "", array('class' => 'form-control select2 service_group', 'required' => 'required'));
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="checkbox" name="is_task" id="" value="1"> Is Task
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="is_active" id="" value="1" checked required> Active
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label">
                                                        <input class="form-check-input" type="radio" name="is_active" id="" value="0"> In-Active
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row justify-content-end mt-3">
                                    <div class="col-2">
                                        <div class="form-group mb-0">
                                            <button type="submit" name="submit" value="Save" class="btn btn-primary form-control" placeholder="" aria-describedby="helpId">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url(); ?>global/node_modules/select2/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>global/node_modules/select2/dist/css/select2.min.css">
<script>
    $(".service_group").select2();
</script>