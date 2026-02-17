



<div class="container-fluid gedf-wrapper">
    
                    <?php foreach ($data as $d) { ?>
                        <?php
                        if ($_SERVER['HTTP_HOST'] == 'localhost') {
                            $servername = 'localhost';
                            $username = 'chatbot_user';
                            $password = 'chatbot_user';
                            $dbname = 'ontimedigital_oneauth';
                        } else {
                            $servername = 'localhost';
                            $username = 'chatbot_user';
                            $password = '5700d$A4';
                            $dbname = 'ontimedigital_oneauth';
                        }
                        // Create connection
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        // Check connection
                        if ($conn->connect_error) {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }

                        $sql = "SELECT * FROM users WHERE user_id=" . $d['user_id'];

                        $result1 = $conn->query($sql);
                        if ($result1->num_rows > 0) {
                            // output data of each row
                            while ($rowa1 = $result1->fetch_assoc()) {
                                $username = $rowa1["first_name"] . ' ' . $rowa1["last_name"];
                                $email = $rowa1['email'];
                                $mobile = $rowa1['mobile'];
                            }
                        } else {
                            $username = "";
                            $email = "";
                            $mobile = "";
                        }

                        ?>

                 
            <?php } ?>
        
        <div class="col-md-12 gedf-main">



            <!--- \\\\\\\Post-->
            <div class="card gedf-card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-between align-items-center">
                          
                            <div class="ml-2">
                                <div class="h5 m-0"><?php echo $username; ?></div>
                                <div class="h7 text-muted">Mobile: <?php echo $mobile; ?></div>
                                <div class="h7 text-muted">Email :<?php echo $email; ?></div>
                            </div>
                        </div>
                        <div>
                           
                            </div>
                        </div>
                    </div>

                </div>
                <?php foreach ($data as $d) { ?>
                    <div class="card-body">
                        <div class="text-muted h7 mb-2"> <i class="fa fa-clock-o"></i>Date: <?php echo $d['c_date']; ?>, Time: <?php echo $d['created_time']; ?></div>
                        <!-- <a class="card-link" href="#">
                            <h5 class="card-title">Order Details</h5>
                        </a> -->

                        <p class="card-text">
                        <div class="container">
                        <table class="table table-bordered">
                        <tr>
                     
                        <th>
                        <label class="category_name">#:</label>
                        </th>
                        <th>
                        <label class="category_name">Category Name:</label>
                        </th>
                        <th>
                        <label class="sub_category_name">Sub Category Name :</label>
                        </th>
                        <th>
                        <label class="sub_category_name">Sub Category Two :</label>
                        </th>
                        <th>
                        <label class="service_times">Gender :</label>
                        </th>
                        <th>
                        <label class="service_times">Service Hours :</label>
                        </th>
                        </tr>
                        <tbody>
                        <td>
                        <?php echo $d['order_id']; ?>
                        </td>
                        <td>
                        <?php echo $d['category_name']; ?>
                        </td>
                        <td>
                        <?php echo $d['sub_category_name']; ?>
                        </td>
                        <td>
                        <?php echo $d['sub_categorytwo']; ?>
                        </td>
                        <td>
                        <?php if ($d['gender'] == 1) {
                                echo "Male";
                            } else {
                                echo "Female";
                            } ?>
                        </td>
                        <td>
                        <?php echo $d['service_hour']; ?>
                        </td>
                        </tbody>
                        </table>

                        <table class="table table-bordered">
                        <tr>
                     
                        <th>
                        <label class="service_times">Medical Ref.no :</label>
                        </th>
                        <th>
                        <label class="service_times">Pregnancy :</label>
                        </th>
                        <th>
                        <label class="service_times">LMP :</label>
                        </th>
                        <th>
                        <label class="service_times">Abortion :</label>
                        </th>
                        <th>
                        <label class="service_times">Contraceptive Pills :</label>
                        </th>
                        <th>
                        <label class="amount">Source :</label>
                        </th>
                        </tr>
                        <tbody>
                        <td>
                        <?php if ($d['med_number'] != 0) {
                                echo $d['med_number'];
                            } ?>
                        </td>
                        <td>
                        <?php if ($d['pregnancy'] == 1) {
                                echo "YES";
                            } else {
                                echo "No";
                            } ?>
                        </td>
                        <td>
                        <?php if ($d['period']) {
                                echo $d['menstrual_period'];
                            } ?>
                        </td>
                      
                        <td>
                        <?php if ($d['abortion'] == 1) {
                                echo "YES";
                            } else {
                                echo "No";
                            } ?>
                        </td>
                        <td>
                        <?php if ($d['contraceptive_pills'] == 1) {
                                echo "YES";
                            } else {
                                echo "No";
                            } ?>
                        </td>
                        <td>
                        <?php if($d['source']!=''){ echo "Web"; } else { echo "Wifi"; } ?>
                        </td>
                        </tbody>
                        </table>


<div class="row">

<div class="col-md-4">
                            <label class="amount">Amount :</label>
                            <?php echo 'AED ' . $d['amount']; ?>
</div>


                            <label class="attachment">Attachments</label>
                            <div class="drive-item module text-center">
                                <div class="drive-item-inner module-inner">
                                    <div class="drive-item-title"><a href="#">Project Brief.pdf</a></div>
                                    <div class="drive-item-thumb">
                                        <a href="#"><i class="fa fa-file-pdf-o text-danger"></i></a>
                                    </div>
                                </div>
                            </p>
                        </div>
        </div>
                    <?php } ?>
                    <!-- <div class="card-footer">
                        <a href="#" class="card-link"><i class="fa fa-gittip"></i> Like</a>
                        <a href="#" class="card-link"><i class="fa fa-comment"></i> Comment</a>
                        <a href="#" class="card-link"><i class="fa fa-mail-forward"></i> Share</a>
                    </div> -->
                    </div>
                    <!-- Post /////-->





            </div>

        </div>
    </div>