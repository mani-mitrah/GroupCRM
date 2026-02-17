<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title><?php echo ($title!='')?$title:config_item('app_name'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url(); ?>public/assets/images/favicon.png">

        <!-- jvectormap 
        <link href="<?php echo base_url(); ?>public/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet">-->
        <!-- DataTables -->
        <link href="<?php echo base_url(); ?>public/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/plugins/datatable/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
          <!-- Responsive datatable examples -->
        <link href="<?php echo base_url(); ?>public/assets/plugins/datatable/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url(); ?>public/assets/plugins/daterangepicker/daterangepicker.css" rel="stylesheet" />

          <!-- Css Select2 -->
        <link href="<?php echo base_url(); ?>public/assets/plugins/select2/select2.min.css" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url(); ?>public/assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/plugins/timepicker/bootstrap-material-datetimepicker.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>public/assets/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <!-- App css -->
        <!-- App css -->

        <link href="<?php echo base_url(); ?>public/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/css/jquery-ui.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>public/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/css/metisMenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url(); ?>public/assets/css/app.min.css" rel="stylesheet" type="text/css" />
        <!-- jQuery  -->
         <script src="<?php echo base_url(); ?>public/assets/js/jquery.min.js"></script>
      <!--  <script src="<?php echo base_url(); ?>public/assets/js/jquery.min.js"></script>
         <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/js/bootstrap3-typeahead.min.js"></script>

        <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/js/multiselect_bundle.js"></script>

        
        <script type="text/javascript" src="<?php echo base_url(); ?>public/assets/js/multiselect.js"></script>
         <link href="<?php echo base_url(); ?>public/assets/css/multiselect.css" rel="stylesheet" type="text/css" /> -->
        <link href="<?php echo base_url(); ?>public/assets/css/typeaheadjs.css" rel="stylesheet" type="text/css" />
        <!--<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>-->
        
        <!-- js file tree -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>public/assets/css/themes/default/arparec.css" />
        <script src="<?php echo base_url(); ?>public/assets/js/jstree.min.js"></script>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/file-icon-vectors@1.0.0/dist/file-icon-vectors.min.css" />
        

    </head>

    <body class="mm-show enlarge-menu">
        <?php include('menu.php'); ?>

        <?php include('top_bar.php'); ?>

        <div class="page-wrapper">

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    
                    <?php echo $content; ?>

                </div><!-- container -->


                <?php include('footer.php'); ?>
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

       
        <script src="<?php echo base_url(); ?>public/assets/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/metismenu.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/waves.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/feather.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/js/jquery.slimscroll.min.js"></script>
        <!-- <script src="<?php echo base_url(); ?>public/assets/plugins/apexcharts/apexcharts.min.js"></script>
        
        <script src="<?php echo base_url(); ?>public/assets/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script> 
        
        <script src="<?php echo base_url(); ?>public/assets/pages/jquery.analytics_dashboard.init.js"></script>-->
        

        <!-- Required datatable js -->
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/jquery.dataTables.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
         <!-- Buttons examples -->
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/dataTables.buttons.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/buttons.bootstrap4.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/jszip.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/pdfmake.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/vfs_fonts.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/buttons.html5.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/buttons.print.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/buttons.colVis.min.js"></script>
         <!-- Responsive examples -->
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/dataTables.responsive.min.js"></script>
         <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/responsive.bootstrap4.min.js"></script>

         <script src="<?php echo base_url(); ?>public/assets/plugins/moment/moment.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/plugins/daterangepicker/daterangepicker.js"></script>
       
        <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>

         <script src="<?php echo base_url(); ?>public/assets/plugins/select2/select2.min.js"></script>
          <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
        <script src="<?php echo base_url(); ?>public/assets/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
         <!--Select2 JS  -->
          <script src="<?php echo base_url(); ?>public/assets/plugins/jquery.forms-advanced.js"></script>
        <!-- App js -->
        <script src="<?php echo base_url(); ?>public/assets/plugins/datatable/jquery.datatable.init.js"></script>

        <script src="<?php echo base_url(); ?>public/assets/js/app.js"></script>
       <script type="text/javascript">
        $(".ganesh").on("click", function(e) {
            console.log('dashboard_clicked');
            e.preventDefault();
            $("body").toggleClass("enlarge-menu");
        });
        
       </script> 
    </body>
</html>