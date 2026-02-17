<style>
    .selected_row {cursor: pointer !important;}
</style>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                New Translation Invoice Payment
                                <div class="page-title-subheading">Process payment with send payment link to the user</div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <a href="/leads/lead/manage#manage">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        All Leads
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
                            <?php } 
                            // echo "<pre>";
                            // print_r($_SESSION);
                            // echo "</pre>";
                            // exit();
                            ?>
                            <form action="<?php echo base_url(); ?>leads/lead/translator" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <label>Customer Search</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"  readonly
                                                            name="" value="CMP" id="">
                                                    </div> 
                                                </div>
                                        
                                                <div class="col-md-7">
                                                    <input type="text" class="form-control" placeholder="Customer Search" id="lead_value" title="search lead" value="">                                                    
                                                </div>
                                                 <div class="col-md-3">
                                                    <button type="button" class="btn btn-primary" id="lead_search"> Customer Search</button>                                                
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="cust_id" class="form-control" name="cust_id" value="">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Name&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" id="lead_name" class="form-control selected_field" required="" name="customer_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Country Code</label>
                                            <input type="text" class="form-control" required="" readonly name="lead_country_code" value="+971">
                                        </div>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            <label>Customer contact number without country code</label>
                                            <input type="number" id="lead_contact" class="form-control selected_field" required="" name="lead_contact">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Customer Email Address&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="email" id="email" class="form-control selected_field" name="customer_email" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>POS Reference&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" name="pos_reference" required>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Invoice Amount&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="number" class="form-control" name="invoice_amount" required>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="7" class="form-control" required="" name="remarks"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <input type="submit" class="btn btn-lg btn-primary btn-square p-3 pl-5 pr-5" name="submit" value="CREATE LEAD" />
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
<!-- leads customer name search details-->
<div class="modal fade" id="data_search" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title details-title">Customers</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive" id="searchDTtable_container">
                <table class="table table-striped table-hoverable table-hover w-100" id="searchDTtable"></table>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="<?php echo base_url(); ?>global/js/leads/search-data.js"></script>