<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Categories assigned to user</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right"
            href="<?php echo base_url(); ?>admin/ucategories/add/">
            <i class="mdi mdi-plus mr-2"></i> ADD NEW
        </a>
    </div>
</div>
<?php
if ($this->session->flashdata('alert_success')) {
    ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <strong>Success!</strong> <?php echo $this->session->flashdata('alert_success'); ?>
</div>
<?php
}

if ($this->session->flashdata('alert_danger')) {
    ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <strong>Success!</strong> <?php echo $this->session->flashdata('alert_danger'); ?>
</div>
<?php
}

if ($this->session->flashdata('alert_warning')) {
    ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <strong>Success!</strong> <?php echo $this->session->flashdata('alert_warning'); ?>
</div>
<?php
}
?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body table-responsive">
                <table id="datatable" class="table table-bordered w-100 dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>User Email</th>
                            <th>Category Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
			                foreach ($default as $value) 
			                {
			             ?>
                        <tr>
                            <td><?php echo $value['id'];?></td>
                            <td><strong><?php echo $value['user_name'];?></strong></td>
                            <td><strong><?php echo $value['user_email'];?></strong></td>
                            <td><strong><?php echo $value['category_name'];?></strong></td>
                            <td>
                                <?php $status=$value['is_active'];
                                    if($status=='1')
                                    {
                                ?>
                                    <span class="badge badge-light-success">Active</span>
                                <?php
                                    } else {
                                ?>
                                    <span class="badge badge-light-danger">In-active</span>
                                <?php    
                                    }   
                                ?>
                            </td>
                            <td><a href="<?php echo base_url(); ?>admin/ucategories/status_change/<?php echo $value['id'];?>" class="btn btn-sm btn-primary">Change Status</a>&nbsp;<a href="<?php echo base_url(); ?>admin/ucategories/delete/<?php echo $value['id'];?>" class="btn btn-sm btn-primary">Delete</a></td>
                        </tr>
                        <?php
			                }
			             ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- <script>
    $(document).ready(function() {
    var groupColumn = 2;
    var table = $('#data_table').DataTable({
        "columnDefs": [
            { "visible": false, "targets": groupColumn }
        ],
        "order": [[ groupColumn, 'asc' ]],
        "displayLength": 10,
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
 
            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i,data,datum) {
                if ( last !== group ) {
                    console.log("data",data,data.row,datum);
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5" class="text-lowercase">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    } );
 
    // Order by the grouping
    $('#data_table tbody').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
            table.order( [ groupColumn, 'desc' ] ).draw();
        }
        else {
            table.order( [ groupColumn, 'asc' ] ).draw();
        }
    } );
} );
</script> -->