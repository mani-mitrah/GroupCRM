<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ontime CRM</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>global/images/favicon.png">
	<link rel="stylesheet" href="<?php echo base_url(); ?>global/vendor/chartist/css/chartist.min.css">
    <link href="<?php echo base_url(); ?>global/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>global/vendor/owl-carousel/owl.carousel.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>global/css/style.css" rel="stylesheet">

    <!-- Datatable -->
    <link href="<?php echo base_url(); ?>global/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>global/js/jquery-3.6.0.min.js"></script>

    <!-- datetime picker-->
    <script type="text/javascript" src="<?php echo base_url(); ?>global/vendor/moment/moment.min.js"></script>

    <link href="<?php echo base_url(); ?>global/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    
	
</head>
<body>

    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <?php include('header.php'); ?>

        <?php
        include('side_menu.php');
        ?>
		<div class="content-body">
			<div class="container-fluid">
			<?php
			echo $content;
			?>

		    </div>
		</div>

        <!--**********************************
            Footer start
        ***********************************-->
        <?php
        include('footer.php');
        ?>
        <!--**********************************
            Footer end
        ***********************************-->
		
		
		
		
		
		<!--**********************************
           Support ticket button start
        ***********************************-->

        <!--**********************************
           Support ticket button end
        ***********************************-->


    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="<?php echo base_url(); ?>global/vendor/global/global.min.js"></script>
	<script src="<?php echo base_url(); ?>global/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="<?php echo base_url(); ?>global/vendor/chart.js/Chart.bundle.min.js"></script>
	
	<!-- Chart piety plugin files -->
    <script src="<?php echo base_url(); ?>global/vendor/peity/jquery.peity.min.js"></script>
	
	<!-- Apex Chart -->
	<script src="<?php echo base_url(); ?>global/vendor/apexchart/apexchart.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>global/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
	
	<!-- Dashboard 1 -->
	<script src="<?php echo base_url(); ?>global/js/dashboard/dashboard-1.js"></script>
	
	<script src="<?php echo base_url(); ?>global/vendor/owl-carousel/owl.carousel.js"></script>

	<!-- Datatable -->
    <script src="<?php echo base_url(); ?>global/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>global/js/plugins-init/datatables.init.js"></script>
    
    <script src="<?php echo base_url(); ?>global/js/custom.min.js"></script>
	<script src="<?php echo base_url(); ?>global/js/deznav-init.js"></script>
    <script src="<?php echo base_url(); ?>global/js/demo.js"></script>
    

</body>
</html>