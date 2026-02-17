<style>
    .service-row {
        counter-increment: service;
    }

    .count::after {
        content: counter(service) ".";
    }

    .service-row:not(#service_row) label {
        display: none;
    }

    #service_row .count {
        padding-top: 1.5rem !important;
    }

    .service-row .action-column {
        display: none;
    }

    #service_row .action-column,
    .service-row:nth-last-child(1) .action-column {
        display: block !important;
    }

    #service_row .fa:before {
        content: "\f067";
    }

    .service-row:not(#service_row):nth-last-child(1) .fa:after {
        content: "\f068";
    }

    div#service_row label {
        font-size: 95%;
    }

    [name='is_direct_invoice_chk[]'] {
        width: 20px;
        margin: auto;
    }

    input[type='checkbox']:focus {
        outline: none !important;
        box-shadow: none !important;
    }
.form-group.one_list {
    width: 31%;
    float: left;
    margin-right: 5px;
}
</style>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div>
                                <?php if(isset($_GET['action'])) { echo "Save As";}else{ echo "Edit General Package";};?>
                                <div class="page-title-subheading">Edit General Package with the exist Services and
                                    Amount details
                                </div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                
                                <a href="<?php echo base_url()?>/leads/package/general">
                                    <button type="button" class="btn btn-gradient-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-list"></i>
                                        </span>
                                        All Created General Packages
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="fiori-container container mb-2">
                <div class="app-inner-layout chat-layout justify-content-center mt-5">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('alert')) { ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                    <?php echo $this->session->flashdata('alert_message'); ?>
                                </div>
                            <?php } ?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="package_id" value="<?php echo $data->package_id; ?>">
                                <input type="hidden" name="package_category_id"
                                    value="<?php echo $data->package_category_id; ?>">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <h5 class="font-weight-bold">Package Details</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Package Name&nbsp;<span
                                                            class="text-danger required">*</span></label>
                                                    <input type="text" class="form-control" required=""
                                                        name="package_name" value="<?php echo $data->package_name; ?>"
                                                        autofocus>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Category Name&nbsp;<span
                                                            class="text-danger required">*</span></label>
                                                    <input type="text" class="form-control" required=""
                                                        name="category_name"
                                                        value="<?php echo $data->package_category_name; ?>" autofocus>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>Branch&nbsp;<span class="text-danger required">*</span></label>
                                                    <select name="under_branch" class="form-control" required>
                                                      <?php
                                                        foreach ($branches as $branch) {
                                                            if ($branch['branch_code'] == 106 || $branch['branch_code'] == 109)
                                                                continue;
                                                            ?>
                                                            <option value="<?php echo $branch['branch_code']; ?>" <?php if($data->package_branch == $branch["branch_code"]){ echo "selected";} ?>>
                                                                <?php echo $branch["branch_name"] ?>
                                                            </option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                             <div class="col-6">
                                                <div class="form-group">
                                                    <label>Payment Type&nbsp;<span class="text-danger required">*</span></label>
                                                    <?php
                                                        $selected = $data->payment_type;
                                                        $options = array(
                                                            'card' => 'Card',
                                                            'cash' => 'Cash',
                                                            'online' => 'Online',
                                                        );
                                                        echo form_dropdown('payment_type', $options, $selected, array('class' => 'form-control payment_type2', 'required' => 'required'));
                                                        ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Package Description</label>
                                            <textarea name="package_desc" class="form-control" cols="30"
                                                rows="8"><?php echo $data->package_description; ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 mt-4">
                                    <div class="col-12">
                                        <h5 class="font-weight-bold">Package Services</h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 services">
                                        <?php
                                        foreach ($details as $key => $detail) {
                                            ?>
                                            <div class="row service-row" <?php if ($key == 0) { ?> id="service_row" <?php } ?>>
                                                <input type="hidden" name="service_id[]"
                                                    value="<?php echo $detail["service_id"]; ?>">
                                                <div class="col-2 d-flex">
                                                    <div class="m-auto count"></div>
                                                    <div class="form-group">
                                                        <label for="">Service Name&nbsp;<span
                                                                class="text-danger required">*</span></label>
                                                        <input type="text" name="service_name[]"
                                                            value="<?php echo $detail["service_name"]; ?>"
                                                            class="form-control" required placeholder="Service Name"
                                                            aria-describedby="helpId">
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <div class="form-group">
                                                                <label for="">Typing Fee(Incl VAT)&nbsp;<span
                                                                        class="text-danger required">*</span></label>
                                                                <input type="number" min="0" step="0.01" name="typing_fee[]"
                                                                    value="<?php echo $detail["typing_fee"]; ?>" required
                                                                    class="form-control" placeholder="Typing Fee"
                                                                    aria-describedby="helpId">
                                                            </div>
                                                        </div>
                                                        <div class="col-5">
                                                            <div class="form-group">
                                                                <label for="">Govt Fee&nbsp;<span
                                                                        class="text-danger required">*</span></label>
                                                                <input type="number" min="0" step="0.01" name="govt_fee[]"
                                                                    value="<?php echo $detail["govt_fee"]; ?>" required
                                                                    class="form-control" placeholder="Govt Fee"
                                                                    aria-describedby="helpId">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-1 pl-0">
                                                    <div class="form-group text-center">
                                                        <label for="">Card Amt&nbsp;<span
                                                                class="text-danger required">*</span></label>
                                                        <input type="text" min="0" name="card_amount[]"
                                                            value="<?php echo $detail["card_amount"]; ?>"
                                                            class="form-control p-0 text-center" placeholder="Cart Amount %"
                                                            readonly required>
                                                    </div>
                                                </div>
                                                <div class="col-1 p-0">
                                                    <div class="form-group text-center">
                                                        <label for="">Total&nbsp;<span
                                                                class="text-danger required">*</span></label>
                                                        <input type="text" min="0" name="total[]"
                                                            value="<?php echo $detail["total"]; ?>"
                                                            class="form-control p-0 text-center" placeholder="Total"
                                                            aria-describedby="helpId" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-1">
                                                    <div class="form-group">
                                                        <label for="">Description</label>
                                                        <input type="text" name="service_desc[]" class="form-control"
                                                            placeholder="Description"
                                                            value="<?php echo $detail["service_desc"]; ?>"
                                                            aria-describedby="helpId">
                                                    </div>
                                                </div>
                                                 <div class="col-3">
                                                    <div class="form-group one_list">
                                                        <label for="">DirectInv</label>
                                                        <input type="hidden" name="is_direct_invoice[]" class="form-control" placeholder="Description" value="<?php echo $detail["is_direct_invoice"]; ?>">
                                                        <input type="checkbox" name="is_direct_invoice_chk[]" class="form-control" placeholder="Description" value="<?php echo $detail["is_direct_invoice"]; ?>" <?php if ($detail["is_direct_invoice"] == 1) {
                                                                                                                                                                                                                        echo "checked";
                                                                                                                                                                                                                    } ?> aria-describedby="helpId">
                    </div>
                                                     <div class="form-group one_list">
                                                            <label for="">Dep</label>
                                                          

                                                            <?php
                                                           //echo "<pre>";
                                                           // print_r($msd_dep);
                                                            $depr = array();
                                                            $depr[0]= '--';
                                                            foreach ($msd_dep as $dp){

                                                                $depr[$dp['dep_key']]= $dp['dep_name'];

                                                            }
                                                            echo form_dropdown('msd_dep[]', $depr, $detail["msd_key"],'class="form-control p-0"');
                                                             ?>

                                                    </div>
                                                     <div class="form-group one_list">
                                                            <label for="">Typing Fee CRM</label>
                                                            <input type="hidden" name="is_pos_typing_fee[]" class="form-control" value="<?php echo $detail["is_pos_typing_fee"]; ?>">
                                                            <input type="checkbox" name="is_pos_typing_fee_chk[]" class="form-control" value="<?php echo $detail["is_pos_typing_fee"]; ?>" <?php if ($detail["is_pos_typing_fee"] == 1) { echo "checked";} ?> aria-describedby="helpId">
                                                    </div>

                                                </div>
                                                
                                                <div class="col-1">
                                                    <div class="form-group action-column">
                                                        <label for="">Action</label>
                                                        <button class="btn btn-primary add-action" type="button">
                                                            <i class="fa"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                                <div class="row justify-content-end mt-3">
                                    <div class="col-7 pl-5">
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="is_active" id="" value="1" <?php if ($data->is_active == 1) {
                                                    echo "checked";
                                                } ?> required> Active
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label">
                                                <input class="form-check-input" type="radio" name="is_active" id="" value="0" <?php if ($data->is_active == 0) {
                                                    echo "checked";
                                                } ?>> In-Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <input type="number" name="package_amount" class="form-control border-dark"
                                                placeholder="Package Total Amount" aria-describedby="helpId" readonly>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="form-group mb-0">
                                             <button type="submit" name="submit[]" value="Save" class="btn btn-primary form-control" placeholder="" aria-describedby="helpId">Save</button>
                                            
                                           
                                        </div>
                                    </div>
                                     <div class="col-2">
                                        <div class="form-group mb-0">
                                          <button type="submit" name="submit[]" value="Save As" class="btn btn-primary form-control" placeholder="" aria-describedby="helpId">Save As</button>
                                           
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
    
    var card_fee_percentage = 1;
    var online_fee_percentage = 2.25;

    function directInvoice() {
        $('[name="is_direct_invoice_chk[]"]').off();
        $('[name="is_direct_invoice_chk[]"]').on("change", function() {
            var value = $(this).closest(".form-group").find('[name="is_direct_invoice[]"]');
            var chk_val = $(this).prop("checked");
            //chk_val = parseInt(chk_val);
            var toggle = (chk_val == true) ? "1" : "0";
            //alert(chk_val, " ==> ", toggle);
            value.val(toggle);
            $(this).val(toggle);
        });

        $('[name="is_meeting_contain_chk[]"]').off();
        $('[name="is_meeting_contain_chk[]"]').change(function(e) {
            e.preventDefault();
            var value = $(this).closest(".form-group").find('[name="is_meeting_contain[]"]');
            var chk_val = $(this).prop("checked");
            // alert(chk_val);
            //chk_val = parseInt(chk_val);
            var toggle = (chk_val == true) ? "1" : "0";
            //alert(chk_val, " ==> ", toggle);
            value.val(toggle);
            $(this).val(toggle);
        });
        $('[name="is_pos_typing_fee_chk[]"]').off();
        $('[name="is_pos_typing_fee_chk[]"]').change(function(e) {
            e.preventDefault();
            var value = $(this).closest(".form-group").find('[name="is_pos_typing_fee[]"]');
            var chk_val = $(this).prop("checked");
            // alert(chk_val);
            //chk_val = parseInt(chk_val);
            var toggle = (chk_val == true) ? "1" : "0";
            //alert(chk_val, " ==> ", toggle);
            value.val(toggle);
            $(this).val(toggle);
        });

    }

    function calculation() {
        var grand_total = 0;
        $(".service-row").each(function () {
            var typing_fee = $(this).find("[name='typing_fee[]']").val();
            var govt_fee = $(this).find("[name='govt_fee[]']").val();
            var card_amount = $(this).find("[name='card_amount[]']");
            
            var card_fee = (govt_fee * card_fee_percentage) / 100;
            var online_fee = (govt_fee * online_fee_percentage) / 100;
            //console.log($(".payment_type2").val());

            if($(".payment_type2").val()=='cash'){
                card_amount.val(0.00);
            }else if($(".payment_type2").val()=='online'){
                card_amount.val(online_fee.toFixed(2));
            }else{
                card_amount.val(card_fee.toFixed(2));
            }

            var total = $(this).find("[name='total[]']");
            var subtotal = parseFloat(typing_fee) + parseFloat(govt_fee) + parseFloat(card_amount.val());
            subtotal = subtotal.toFixed(2);
            total.val(subtotal);
            grand_total = parseFloat(grand_total) + parseFloat(subtotal);

            $('[name="is_direct_invoice_chk[]"]').each(function (e) {
                var value = $(this).closest(".form-group").find('[name="is_direct_invoice[]"]');
                var chk_val = $(this).prop("checked");
                // alert(chk_val);
                //chk_val = parseInt(chk_val);
                var toggle = (chk_val == true) ? "1" : "0";
                //alert(chk_val, " ==> ", toggle);
                value.val(toggle);
                $(this).val(toggle);
            });
        });
        $("[name='package_amount']").val(parseFloat(grand_total).toFixed(2));
        directInvoice();
    }

    calculation();
    $("document").ready(function () {
        directInvoice();
    });
    $('.payment_type2').change(function() {
        calculation();
      var selectedValue = $(this).val();
      console.log(selectedValue);
    });

    function init() {
        directInvoice();
        $(".service-row input").off();
        $(".service-row input").on("focus", function () {
            $(this).select();
        });

        $("#service_row .add-action,.service-row:nth-last-child(1) .add-action").off();
        
         $("#service_row .add-action").on("click", function(e) {
            e.preventDefault();
            var service = $("#service_row").html();
            var newService = $(service);
            newService.find("select, input[type='number'], input[type='text'], input[type='hidden'], input[type='checkbox']").val("");
            newService.find("option:selected").prop("selected", false);
            $(".services").append($("<div class='row service-row'></div>").append(newService));
            init();
        });

        $(".service-row:not(#service_row):nth-last-child(1) .add-action").on("click", function (e) {
            e.preventDefault();
            if ($(".service-row").length > 1) {
                $(this).closest(".service-row").remove();
            }
            init();
        });

        $(".service-row input").on("keyup change keydown", function () {
            calculation();
        });
        calculation();

    }

    init();


    $("#service_id").select2({
        closeOnSelect: true
    });

    $(document).on('select2:open', () => {
        document.querySelector('.select2-search__field').focus();
    });
</script>