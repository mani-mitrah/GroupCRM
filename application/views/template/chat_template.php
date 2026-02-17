<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Ontime CRM</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>global/images/favicon.png">
    <link href="<?php echo base_url(); ?>global/css/style.css" rel="stylesheet">
    <script type="text/javascript" src="<?php echo base_url(); ?>global/js/jquery-3.6.0.min.js"></script>
    <link href="<?php echo base_url(); ?>global/css/uni.css" rel="stylesheet">
</head>
<body>
    <div class="overlay"></div>
    <div class="spanner">
      <div class="loader"></div>
      <p>Loading, please wait..</p>
    </div>
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
    <div id="main-wrapper" class="show menu-toggle">

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
    
    <script src="<?php echo base_url(); ?>global/js/custom.min.js"></script>
    <script src="<?php echo base_url(); ?>global/js/deznav-init.js"></script>
    <script src="<?php echo base_url(); ?>global/js/demo.js"></script>
    <script src="<?php echo base_url(); ?>global/vendor/moment/moment.min.js"></script>
    <script src="<?php echo base_url(); ?>global/js/agent.js" id="include_js_id" data-agentid="<?php echo $this->auth_user_id; ?>"></script>

</body>
</html>