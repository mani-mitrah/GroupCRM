<script type="text/javascript">
    $(document).ready(function() {
        $('#existing').hide();
        $('#existing_card').hide();
    });
</script>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add new user category</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>admin/ucategories/">
            <i class="mdi mdi-plus mr-2"></i> VIEW ALL
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php if ($this->session->flashdata('alert')) { ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                        <?php echo $this->session->flashdata('alert_message'); ?>
                    </div>
                <?php } ?>
                <form action="<?php echo base_url(); ?>admin/ucategories/add" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">User <span class="text-danger required">*</span>
                                </label>
                                <select class="form-control" onchange="get_exisiting_categories(this.value);" name="user_id" id="single-select">
                                    <option value="">--Select User --</option>
                                    <?php foreach ($users as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value['user_id']; ?>"><?php echo $value['first_name'] . ' ' . $value['last_name']; ?> -
                                            <?php
                                            $role_id = $value['role_id'];
                                            if ($role_id == 2) {
                                                echo 'CSA';
                                            }

                                            if ($role_id == 6) {
                                                echo 'COORDINATOR';
                                            }
                                            ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group" id="existing">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Select Categories <span class="text-danger required">*</span>
                                </label><br />
                                <?php
                                foreach ($categories as $key => $value) {
                                ?>
                                    <div class="form-check form-check-inline" id="cbox<?php echo $value['category_id']; ?>">
                                        <input name="chkid[]" class="form-check-input" type="checkbox" id="c<?php echo $value['category_id']; ?>" value="<?php echo $value['category_id']; ?>">
                                        <label class="form-check-label" for="c<?php echo $value['category_id']; ?>"><?php echo strtoupper($value['category_name']); ?></label>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <input name="submit" type="submit" class="btn btn-primary" value="Create" />

                </form>
            </div>
        </div>
    </div>
</div>
<div class="card" id="existing_card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h5>Manage Existing Categories</h5>
                <table class="table" id="fill_existing">

                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // function get_exisiting_categories(user_id) 
    // {
    //     if(user_id!='')
    //     {
    //         $.ajax({
    //             url:"<?php echo site_url('admin/ucategories/get_exisiting_categories'); ?>/"+user_id,
    //             method:"GET",
    //             type:'ajax',
    //             success:function(res){
    //                 var result=JSON.parse(res);
    //                 $('#existing').html("");
    //                 $('#existing').html("<h5>Existing Categories</h5>");
    //                 $.each(result,function(key, value)
    //                 {
    //                     $('#cbox'+value.category_id).hide();
    //                     let ecat = $('<span id="s'+value.category_id+'" class="badge badge-primary text-white">'+value.category_name+'</span>&nbsp;&nbsp;');
    //                     ecat.appendTo('#existing');

    //                     let trow = $('<tr id="t'+value.category_id+'"><td>'+value.category_name+'</td><td><button class="btn btn-sm btn-danger" onclick="javascript:remove_user_category('+value.category_id+','+value.user_id+');">Remove</button></td>');
    //                     trow.appendTo('#fill_existing');
    //                 });
    //                 if(res.length > 0)
    //                 {
    //                     $('#existing').show();
    //                     $('#existing_card').show();    
    //                 }
    //             },err:function(error){
    //                 console.log(error);
    //             }
    //         });
    //     }
    // }

    // function remove_user_category(category_id,user_id) 
    // {
    //     if(user_id!='' && category_id!='')
    //     {
    //         $.ajax({
    //             url:"<?php echo site_url('admin/ucategories/remove_exisiting_categories'); ?>",
    //             method:"GET",
    //             data:{user_id:user_id,category_id:category_id},
    //             type:'ajax',
    //             success:function(res){
    //                 if(res)
    //                 {
    //                     $('#t'+category_id).hide();
    //                     $('#s'+category_id).hide();
    //                     get_exisiting_categories(user_id);
    //                 }
    //             },err:function(error){
    //                 console.log(error);
    //             }
    //         });
    //     }   
    // }
    function get_exisiting_categories(user_id) {
        console.log('Fetching existing categories...');
        if (user_id != '') {
            $.ajax({
                url: "<?php echo site_url('admin/ucategories/get_exisiting_categories'); ?>/" + user_id,
                method: "GET",
                type: 'ajax',
                success: function(res) {
                    var result = JSON.parse(res);

                    // Clear the existing list to prevent duplicates
                    $('#fill_existing').html("");
                    $('#existing').html("<h5>Existing Categories</h5>");

                    if (result.length > 0) {
                        $.each(result, function(key, value) {
                            // Hide checkbox for existing categories
                            $('#cbox' + value.category_id).hide();
                            if (value.is_active == 1) {
                                // Append category badge
                                let ecat = $('<span id="s' + value.category_id + '" class="badge badge-primary text-white">' + value.category_name + '</span>&nbsp;&nbsp;');
                                ecat.appendTo('#existing');

                                // Append category to the table if it doesn't already exist
                                if ($('#t' + value.category_id).length === 0) {
                                    let trow = $('<tr id="t' + value.category_id + '"><td>' + value.category_name + '</td><td><button class="btn btn-sm btn-danger" onclick="javascript:remove_user_category(' + value.category_id + ',' + value.user_id + ');">Remove</button></td></tr>');
                                    trow.appendTo('#fill_existing');
                                }
                                $('#existing').show();
                                $('#existing_card').show();
                            }else{
                                $('#existing').hide();
                                $('#existing_card').hide();
                            }
                        });
                       
                    } else {
                        // Reset UI when no categories exist
                        $('[id^=cbox]').show(); // Show all checkboxes
                        $('#fill_existing').html("");
                        $('#existing').hide();
                        $('#existing_card').hide();
                    }
                },
                error: function(error) {
                    console.log('Error fetching categories:', error);
                }
            });
        } else {
            // Reset the UI when no user is selected
            $('#existing').hide();
            $('#existing_card').hide();
            $('#fill_existing').html("");
            $('[id^=cbox]').show(); // Reset all checkboxes
        }
    }

    function remove_user_category(category_id, user_id) {
        if (user_id != '' && category_id != '') {
            $.ajax({
                url: "<?php echo site_url('admin/ucategories/remove_exisiting_categories'); ?>",
                method: "GET",
                data: {
                    user_id: user_id,
                    category_id: category_id
                },
                type: 'ajax',
                success: function(res) {
                    if (res) {
                        // Remove the category row and badge from the UI
                        $('#t' + category_id).remove();
                        $('#s' + category_id).remove();

                        // Re-show the checkbox for the removed category
                        $('#cbox' + category_id).show();

                        // Refresh the category list
                        get_exisiting_categories(user_id);
                    }
                },
                error: function(error) {
                    console.log('Error removing category:', error);
                }
            });
        }
    }
</script>