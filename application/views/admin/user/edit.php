<style type="text/css">
    .field-icon {
        float: right;
        margin-left: -25px;
        margin-top: -25px;
        position: relative;
        z-index: 2;
    }
</style>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Edit user information</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/user/">
            <i class="mdi mdi-plus mr-2"></i> VIEW ALL
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <?php
                if ($this->session->flashdata('alert')) {
                ?>
                    <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                        <?php echo $this->session->flashdata('alert_message'); ?>
                    </div>
                <?php
                }
                ?>
                <form action="<?php echo base_url(); ?>admin/user/edit/<?php echo $default['user_id']; ?>" method="post" autocomplete="off">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role <span class="text-danger required">*</span></label>
                                <select name="role_id" id="role_id" class="form-control pc-selectpicker role_id" required>
                                    <option value=""> -- Select Role --</option>
                                    <?php
                                    foreach ($role as  $row) {
                                    ?>
                                        <option value="<?php echo $row->role_id; ?>" <?php if ($row->role_id == $default['role_id']) { ?> selected='selected' <?php }  ?>><?php echo $row->role_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">First Name<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter First Name" name="first_name" required value="<?php echo $default['first_name']; ?>">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Last Name <span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Last Name" name="last_name" required value="<?php echo $default['last_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Country<span class="text-danger required">*</span></label>
                                <!-- <select name="country" id="country" class="form-control pc-selectpicker country" required></select> -->
                                <select name="country" id="country" class="form-control pc-selectpicker country" required="">
                                    <option <?php if ($default["country"] == "Afghanistan") {
                                                echo 'selected';
                                            } ?> value="Afghanistan" data-code="93">Afghanistan</option>
                                    <option <?php if ($default["country"] == "Åland Islands") {
                                                echo 'selected';
                                            } ?> value="Åland Islands" data-code="358">Åland Islands</option>
                                    <option <?php if ($default["country"] == "Albania") {
                                                echo 'selected';
                                            } ?> value="Albania" data-code="355">Albania</option>
                                    <option <?php if ($default["country"] == "Algeria") {
                                                echo 'selected';
                                            } ?> value="Algeria" data-code="213">Algeria</option>
                                    <option <?php if ($default["country"] == "American Samoa") {
                                                echo 'selected';
                                            } ?> value="American Samoa" data-code="1">American Samoa</option>
                                    <option <?php if ($default["country"] == "Andorra") {
                                                echo 'selected';
                                            } ?> value="Andorra" data-code="376">Andorra</option>
                                    <option <?php if ($default["country"] == "Angola") {
                                                echo 'selected';
                                            } ?> value="Angola" data-code="244">Angola</option>
                                    <option <?php if ($default["country"] == "Anguilla") {
                                                echo 'selected';
                                            } ?> value="Anguilla" data-code="1">Anguilla</option>
                                    <option <?php if ($default["country"] == "Antarctica") {
                                                echo 'selected';
                                            } ?> value="Antarctica" data-code="672">Antarctica</option>
                                    <option <?php if ($default["country"] == "Antigua and Barbuda") {
                                                echo 'selected';
                                            } ?> value="Antigua and Barbuda" data-code="1">Antigua and Barbuda</option>
                                    <option <?php if ($default["country"] == "Argentina") {
                                                echo 'selected';
                                            } ?> value="Argentina" data-code="54">Argentina</option>
                                    <option <?php if ($default["country"] == "Armenia") {
                                                echo 'selected';
                                            } ?> value="Armenia" data-code="374">Armenia</option>
                                    <option <?php if ($default["country"] == "Aruba") {
                                                echo 'selected';
                                            } ?> value="Aruba" data-code="297">Aruba</option>
                                    <option <?php if ($default["country"] == "Australia") {
                                                echo 'selected';
                                            } ?> value="Australia" data-code="61">Australia</option>
                                    <option <?php if ($default["country"] == "Austria") {
                                                echo 'selected';
                                            } ?> value="Austria" data-code="43">Austria</option>
                                    <option <?php if ($default["country"] == "Azerbaijan") {
                                                echo 'selected';
                                            } ?> value="Azerbaijan" data-code="994">Azerbaijan</option>
                                    <option <?php if ($default["country"] == "Bahamas") {
                                                echo 'selected';
                                            } ?> value="Bahamas" data-code="1">Bahamas</option>
                                    <option <?php if ($default["country"] == "Bahrain") {
                                                echo 'selected';
                                            } ?> value="Bahrain" data-code="973">Bahrain</option>
                                    <option <?php if ($default["country"] == "Bangladesh") {
                                                echo 'selected';
                                            } ?> value="Bangladesh" data-code="880">Bangladesh</option>
                                    <option <?php if ($default["country"] == "Barbados") {
                                                echo 'selected';
                                            } ?> value="Barbados" data-code="1">Barbados</option>
                                    <option <?php if ($default["country"] == "Belarus") {
                                                echo 'selected';
                                            } ?> value="Belarus" data-code="375">Belarus</option>
                                    <option <?php if ($default["country"] == "Belgium") {
                                                echo 'selected';
                                            } ?> value="Belgium" data-code="32">Belgium</option>
                                    <option <?php if ($default["country"] == "Belize") {
                                                echo 'selected';
                                            } ?> value="Belize" data-code="501">Belize</option>
                                    <option <?php if ($default["country"] == "Benin") {
                                                echo 'selected';
                                            } ?> value="Benin" data-code="229">Benin</option>
                                    <option <?php if ($default["country"] == "Bermuda") {
                                                echo 'selected';
                                            } ?> value="Bermuda" data-code="1">Bermuda</option>
                                    <option <?php if ($default["country"] == "Bhutan") {
                                                echo 'selected';
                                            } ?> value="Bhutan" data-code="975">Bhutan</option>
                                    <option <?php if ($default["country"] == "Bolivia (Plurinational State of)") {
                                                echo 'selected';
                                            } ?> value="Bolivia (Plurinational State of)" data-code="591">Bolivia (Plurinational State of)</option>
                                    <option <?php if ($default["country"] == "Bonaire, Sint Eustatius and Saba") {
                                                echo 'selected';
                                            } ?> value="Bonaire, Sint Eustatius and Saba" data-code="599">Bonaire, Sint Eustatius and Saba</option>
                                    <option <?php if ($default["country"] == "Bosnia and Herzegovina") {
                                                echo 'selected';
                                            } ?> value="Bosnia and Herzegovina" data-code="387">Bosnia and Herzegovina</option>
                                    <option <?php if ($default["country"] == "Botswana") {
                                                echo 'selected';
                                            } ?> value="Botswana" data-code="267">Botswana</option>
                                    <option <?php if ($default["country"] == "Bouvet Island") {
                                                echo 'selected';
                                            } ?> value="Bouvet Island" data-code="47">Bouvet Island</option>
                                    <option <?php if ($default["country"] == "Brazil") {
                                                echo 'selected';
                                            } ?> value="Brazil" data-code="55">Brazil</option>
                                    <option <?php if ($default["country"] == "British Indian Ocean Territory") {
                                                echo 'selected';
                                            } ?> value="British Indian Ocean Territory" data-code="246">British Indian Ocean Territory</option>
                                    <option <?php if ($default["country"] == "United States Minor Outlying Islands") {
                                                echo 'selected';
                                            } ?> value="United States Minor Outlying Islands" data-code="1">United States Minor Outlying Islands</option>
                                    <option <?php if ($default["country"] == "Virgin Islands (British)") {
                                                echo 'selected';
                                            } ?> value="Virgin Islands (British)" data-code="1">Virgin Islands (British)</option>
                                    <option <?php if ($default["country"] == "Virgin Islands (U.S.)") {
                                                echo 'selected';
                                            } ?> value="Virgin Islands (U.S.)" data-code="1 340">Virgin Islands (U.S.)</option>
                                    <option <?php if ($default["country"] == "Brunei Darussalam") {
                                                echo 'selected';
                                            } ?> value="Brunei Darussalam" data-code="673">Brunei Darussalam</option>
                                    <option <?php if ($default["country"] == "Bulgaria") {
                                                echo 'selected';
                                            } ?> value="Bulgaria" data-code="359">Bulgaria</option>
                                    <option <?php if ($default["country"] == "Burkina Faso") {
                                                echo 'selected';
                                            } ?> value="Burkina Faso" data-code="226">Burkina Faso</option>
                                    <option <?php if ($default["country"] == "Burundi") {
                                                echo 'selected';
                                            } ?> value="Burundi" data-code="257">Burundi</option>
                                    <option <?php if ($default["country"] == "Cambodia") {
                                                echo 'selected';
                                            } ?> value="Cambodia" data-code="855">Cambodia</option>
                                    <option <?php if ($default["country"] == "Cameroon") {
                                                echo 'selected';
                                            } ?> value="Cameroon" data-code="237">Cameroon</option>
                                    <option <?php if ($default["country"] == "Canada") {
                                                echo 'selected';
                                            } ?> value="Canada" data-code="1">Canada</option>
                                    <option <?php if ($default["country"] == "Cabo Verde") {
                                                echo 'selected';
                                            } ?> value="Cabo Verde" data-code="238">Cabo Verde</option>
                                    <option <?php if ($default["country"] == "Cayman Islands") {
                                                echo 'selected';
                                            } ?> value="Cayman Islands" data-code="1">Cayman Islands</option>
                                    <option <?php if ($default["country"] == "Central African Republic") {
                                                echo 'selected';
                                            } ?> value="Central African Republic" data-code="236">Central African Republic</option>
                                    <option <?php if ($default["country"] == "Chad") {
                                                echo 'selected';
                                            } ?> value="Chad" data-code="235">Chad</option>
                                    <option <?php if ($default["country"] == "Chile") {
                                                echo 'selected';
                                            } ?> value="Chile" data-code="56">Chile</option>
                                    <option <?php if ($default["country"] == "China") {
                                                echo 'selected';
                                            } ?> value="China" data-code="86">China</option>
                                    <option <?php if ($default["country"] == "Christmas Island") {
                                                echo 'selected';
                                            } ?> value="Christmas Island" data-code="61">Christmas Island</option>
                                    <option <?php if ($default["country"] == "Cocos (Keeling) Islands") {
                                                echo 'selected';
                                            } ?> value="Cocos (Keeling) Islands" data-code="61">Cocos (Keeling) Islands</option>
                                    <option <?php if ($default["country"] == "Colombia") {
                                                echo 'selected';
                                            } ?> value="Colombia" data-code="57">Colombia</option>
                                    <option <?php if ($default["country"] == "Comoros") {
                                                echo 'selected';
                                            } ?> value="Comoros" data-code="269">Comoros</option>
                                    <option <?php if ($default["country"] == "Congo") {
                                                echo 'selected';
                                            } ?> value="Congo" data-code="242">Congo</option>
                                    <option <?php if ($default["country"] == "Congo (Democratic Republic of the)") {
                                                echo 'selected';
                                            } ?> value="Congo (Democratic Republic of the)" data-code="243">Congo (Democratic Republic of the)</option>
                                    <option <?php if ($default["country"] == "Cook Islands") {
                                                echo 'selected';
                                            } ?> value="Cook Islands" data-code="682">Cook Islands</option>
                                    <option <?php if ($default["country"] == "Costa Rica") {
                                                echo 'selected';
                                            } ?> value="Costa Rica" data-code="506">Costa Rica</option>
                                    <option <?php if ($default["country"] == "Croatia") {
                                                echo 'selected';
                                            } ?> value="Croatia" data-code="385">Croatia</option>
                                    <option <?php if ($default["country"] == "Cuba") {
                                                echo 'selected';
                                            } ?> value="Cuba" data-code="53">Cuba</option>
                                    <option <?php if ($default["country"] == "Curaçao") {
                                                echo 'selected';
                                            } ?> value="Curaçao" data-code="599">Curaçao</option>
                                    <option <?php if ($default["country"] == "Cyprus") {
                                                echo 'selected';
                                            } ?> value="Cyprus" data-code="357">Cyprus</option>
                                    <option <?php if ($default["country"] == "Czech Republic") {
                                                echo 'selected';
                                            } ?> value="Czech Republic" data-code="420">Czech Republic</option>
                                    <option <?php if ($default["country"] == "Denmark") {
                                                echo 'selected';
                                            } ?> value="Denmark" data-code="45">Denmark</option>
                                    <option <?php if ($default["country"] == "Djibouti") {
                                                echo 'selected';
                                            } ?> value="Djibouti" data-code="253">Djibouti</option>
                                    <option <?php if ($default["country"] == "Dominica") {
                                                echo 'selected';
                                            } ?> value="Dominica" data-code="1">Dominica</option>
                                    <option <?php if ($default["country"] == "Dominican Republic") {
                                                echo 'selected';
                                            } ?> value="Dominican Republic" data-code="1">Dominican Republic</option>
                                    <option <?php if ($default["country"] == "Ecuador") {
                                                echo 'selected';
                                            } ?> value="Ecuador" data-code="593">Ecuador</option>
                                    <option <?php if ($default["country"] == "Egypt") {
                                                echo 'selected';
                                            } ?> value="Egypt" data-code="20">Egypt</option>
                                    <option <?php if ($default["country"] == "El Salvador") {
                                                echo 'selected';
                                            } ?> value="El Salvador" data-code="503">El Salvador</option>
                                    <option <?php if ($default["country"] == "Equatorial Guinea") {
                                                echo 'selected';
                                            } ?> value="Equatorial Guinea" data-code="240">Equatorial Guinea</option>
                                    <option <?php if ($default["country"] == "Eritrea") {
                                                echo 'selected';
                                            } ?> value="Eritrea" data-code="291">Eritrea</option>
                                    <option <?php if ($default["country"] == "Estonia") {
                                                echo 'selected';
                                            } ?> value="Estonia" data-code="372">Estonia</option>
                                    <option <?php if ($default["country"] == "Ethiopia") {
                                                echo 'selected';
                                            } ?> value="Ethiopia" data-code="251">Ethiopia</option>
                                    <option <?php if ($default["country"] == "Falkland Islands (Malvinas)") {
                                                echo 'selected';
                                            } ?> value="Falkland Islands (Malvinas)" data-code="500">Falkland Islands (Malvinas)</option>
                                    <option <?php if ($default["country"] == "Faroe Islands") {
                                                echo 'selected';
                                            } ?> value="Faroe Islands" data-code="298">Faroe Islands</option>
                                    <option <?php if ($default["country"] == "Fiji") {
                                                echo 'selected';
                                            } ?> value="Fiji" data-code="679">Fiji</option>
                                    <option <?php if ($default["country"] == "Finland") {
                                                echo 'selected';
                                            } ?> value="Finland" data-code="358">Finland</option>
                                    <option <?php if ($default["country"] == "France") {
                                                echo 'selected';
                                            } ?> value="France" data-code="33">France</option>
                                    <option <?php if ($default["country"] == "French Guiana") {
                                                echo 'selected';
                                            } ?> value="French Guiana" data-code="594">French Guiana</option>
                                    <option <?php if ($default["country"] == "French Polynesia") {
                                                echo 'selected';
                                            } ?> value="French Polynesia" data-code="689">French Polynesia</option>
                                    <option <?php if ($default["country"] == "French Southern Territories") {
                                                echo 'selected';
                                            } ?> value="French Southern Territories" data-code="262">French Southern Territories</option>
                                    <option <?php if ($default["country"] == "Gabon") {
                                                echo 'selected';
                                            } ?> value="Gabon" data-code="241">Gabon</option>
                                    <option <?php if ($default["country"] == "Gambia") {
                                                echo 'selected';
                                            } ?> value="Gambia" data-code="220">Gambia</option>
                                    <option <?php if ($default["country"] == "Georgia") {
                                                echo 'selected';
                                            } ?> value="Georgia" data-code="995">Georgia</option>
                                    <option <?php if ($default["country"] == "Germany") {
                                                echo 'selected';
                                            } ?> value="Germany" data-code="49">Germany</option>
                                    <option <?php if ($default["country"] == "Ghana") {
                                                echo 'selected';
                                            } ?> value="Ghana" data-code="233">Ghana</option>
                                    <option <?php if ($default["country"] == "Gibraltar") {
                                                echo 'selected';
                                            } ?> value="Gibraltar" data-code="350">Gibraltar</option>
                                    <option <?php if ($default["country"] == "Greece") {
                                                echo 'selected';
                                            } ?> value="Greece" data-code="30">Greece</option>
                                    <option <?php if ($default["country"] == "Greenland") {
                                                echo 'selected';
                                            } ?> value="Greenland" data-code="299">Greenland</option>
                                    <option <?php if ($default["country"] == "Grenada") {
                                                echo 'selected';
                                            } ?> value="Grenada" data-code="1">Grenada</option>
                                    <option <?php if ($default["country"] == "Guadeloupe") {
                                                echo 'selected';
                                            } ?> value="Guadeloupe" data-code="590">Guadeloupe</option>
                                    <option <?php if ($default["country"] == "Guam") {
                                                echo 'selected';
                                            } ?> value="Guam" data-code="1">Guam</option>
                                    <option <?php if ($default["country"] == "Guatemala") {
                                                echo 'selected';
                                            } ?> value="Guatemala" data-code="502">Guatemala</option>
                                    <option <?php if ($default["country"] == "Guernsey") {
                                                echo 'selected';
                                            } ?> value="Guernsey" data-code="44">Guernsey</option>
                                    <option <?php if ($default["country"] == "Guinea") {
                                                echo 'selected';
                                            } ?> value="Guinea" data-code="224">Guinea</option>
                                    <option <?php if ($default["country"] == "Guinea-Bissau") {
                                                echo 'selected';
                                            } ?> value="Guinea-Bissau" data-code="245">Guinea-Bissau</option>
                                    <option <?php if ($default["country"] == "Guyana") {
                                                echo 'selected';
                                            } ?> value="Guyana" data-code="592">Guyana</option>
                                    <option <?php if ($default["country"] == "Haiti") {
                                                echo 'selected';
                                            } ?> value="Haiti" data-code="509">Haiti</option>
                                    <option <?php if ($default["country"] == "Heard Island and McDonald Islands") {
                                                echo 'selected';
                                            } ?> value="Heard Island and McDonald Islands" data-code="672">Heard Island and McDonald Islands</option>
                                    <option <?php if ($default["country"] == "Holy See") {
                                                echo 'selected';
                                            } ?> value="Holy See" data-code="379">Holy See</option>
                                    <option <?php if ($default["country"] == "Honduras") {
                                                echo 'selected';
                                            } ?> value="Honduras" data-code="504">Honduras</option>
                                    <option <?php if ($default["country"] == "Hong Kong") {
                                                echo 'selected';
                                            } ?> value="Hong Kong" data-code="852">Hong Kong</option>
                                    <option <?php if ($default["country"] == "Hungary") {
                                                echo 'selected';
                                            } ?> value="Hungary" data-code="36">Hungary</option>
                                    <option <?php if ($default["country"] == "Iceland") {
                                                echo 'selected';
                                            } ?> value="Iceland" data-code="354">Iceland</option>
                                    <option <?php if ($default["country"] == "India") {
                                                echo 'selected';
                                            } ?> value="India" data-code="91">India</option>
                                    <option <?php if ($default["country"] == "Indonesia") {
                                                echo 'selected';
                                            } ?> value="Indonesia" data-code="62">Indonesia</option>
                                    <option <?php if ($default["country"] == "Côte d'Ivoire") {
                                                echo 'selected';
                                            } ?> value="Côte d'Ivoire" data-code="225">Côte d'Ivoire</option>
                                    <option <?php if ($default["country"] == "Iran (Islamic Republic of)") {
                                                echo 'selected';
                                            } ?> value="Iran (Islamic Republic of)" data-code="98">Iran (Islamic Republic of)</option>
                                    <option <?php if ($default["country"] == "Iraq") {
                                                echo 'selected';
                                            } ?> value="Iraq" data-code="964">Iraq</option>
                                    <option <?php if ($default["country"] == "Ireland") {
                                                echo 'selected';
                                            } ?> value="Ireland" data-code="353">Ireland</option>
                                    <option <?php if ($default["country"] == "Isle of Man") {
                                                echo 'selected';
                                            } ?> value="Isle of Man" data-code="44">Isle of Man</option>
                                    <option <?php if ($default["country"] == "Israel") {
                                                echo 'selected';
                                            } ?> value="Israel" data-code="972">Israel</option>
                                    <option <?php if ($default["country"] == "Italy") {
                                                echo 'selected';
                                            } ?> value="Italy" data-code="39">Italy</option>
                                    <option <?php if ($default["country"] == "Jamaica") {
                                                echo 'selected';
                                            } ?> value="Jamaica" data-code="1">Jamaica</option>
                                    <option <?php if ($default["country"] == "Japan") {
                                                echo 'selected';
                                            } ?> value="Japan" data-code="81">Japan</option>
                                    <option <?php if ($default["country"] == "Jersey") {
                                                echo 'selected';
                                            } ?> value="Jersey" data-code="44">Jersey</option>
                                    <option <?php if ($default["country"] == "Jordan") {
                                                echo 'selected';
                                            } ?> value="Jordan" data-code="962">Jordan</option>
                                    <option <?php if ($default["country"] == "Kazakhstan") {
                                                echo 'selected';
                                            } ?> value="Kazakhstan" data-code="76">Kazakhstan</option>
                                    <option <?php if ($default["country"] == "Kenya") {
                                                echo 'selected';
                                            } ?> value="Kenya" data-code="254">Kenya</option>
                                    <option <?php if ($default["country"] == "Kiribati") {
                                                echo 'selected';
                                            } ?> value="Kiribati" data-code="686">Kiribati</option>
                                    <option <?php if ($default["country"] == "Kuwait") {
                                                echo 'selected';
                                            } ?> value="Kuwait" data-code="965">Kuwait</option>
                                    <option <?php if ($default["country"] == "Kyrgyzstan") {
                                                echo 'selected';
                                            } ?> value="Kyrgyzstan" data-code="996">Kyrgyzstan</option>
                                    <option <?php if ($default["country"] == "Lao People's Democratic Republic") {
                                                echo 'selected';
                                            } ?> value="Lao People's Democratic Republic" data-code="856">Lao People's Democratic Republic</option>
                                    <option <?php if ($default["country"] == "Latvia") {
                                                echo 'selected';
                                            } ?> value="Latvia" data-code="371">Latvia</option>
                                    <option <?php if ($default["country"] == "Lebanon") {
                                                echo 'selected';
                                            } ?> value="Lebanon" data-code="961">Lebanon</option>
                                    <option <?php if ($default["country"] == "Lesotho") {
                                                echo 'selected';
                                            } ?> value="Lesotho" data-code="266">Lesotho</option>
                                    <option <?php if ($default["country"] == "Liberia") {
                                                echo 'selected';
                                            } ?> value="Liberia" data-code="231">Liberia</option>
                                    <option <?php if ($default["country"] == "Libya") {
                                                echo 'selected';
                                            } ?> value="Libya" data-code="218">Libya</option>
                                    <option <?php if ($default["country"] == "Liechtenstein") {
                                                echo 'selected';
                                            } ?> value="Liechtenstein" data-code="423">Liechtenstein</option>
                                    <option <?php if ($default["country"] == "Lithuania") {
                                                echo 'selected';
                                            } ?> value="Lithuania" data-code="370">Lithuania</option>
                                    <option <?php if ($default["country"] == "Luxembourg") {
                                                echo 'selected';
                                            } ?> value="Luxembourg" data-code="352">Luxembourg</option>
                                    <option <?php if ($default["country"] == "Macao") {
                                                echo 'selected';
                                            } ?> value="Macao" data-code="853">Macao</option>
                                    <option <?php if ($default["country"] == "Macedonia (the former Yugoslav Republic of)") {
                                                echo 'selected';
                                            } ?> value="Macedonia (the former Yugoslav Republic of)" data-code="389">Macedonia (the former Yugoslav Republic of)</option>
                                    <option <?php if ($default["country"] == "Madagascar") {
                                                echo 'selected';
                                            } ?> value="Madagascar" data-code="261">Madagascar</option>
                                    <option <?php if ($default["country"] == "Malawi") {
                                                echo 'selected';
                                            } ?> value="Malawi" data-code="265">Malawi</option>
                                    <option <?php if ($default["country"] == "Malaysia") {
                                                echo 'selected';
                                            } ?> value="Malaysia" data-code="60">Malaysia</option>
                                    <option <?php if ($default["country"] == "Maldives") {
                                                echo 'selected';
                                            } ?> value="Maldives" data-code="960">Maldives</option>
                                    <option <?php if ($default["country"] == "Mali") {
                                                echo 'selected';
                                            } ?> value="Mali" data-code="223">Mali</option>
                                    <option <?php if ($default["country"] == "Malta") {
                                                echo 'selected';
                                            } ?> value="Malta" data-code="356">Malta</option>
                                    <option <?php if ($default["country"] == "Marshall Islands") {
                                                echo 'selected';
                                            } ?> value="Marshall Islands" data-code="692">Marshall Islands</option>
                                    <option <?php if ($default["country"] == "Martinique") {
                                                echo 'selected';
                                            } ?> value="Martinique" data-code="596">Martinique</option>
                                    <option <?php if ($default["country"] == "Mauritania") {
                                                echo 'selected';
                                            } ?> value="Mauritania" data-code="222">Mauritania</option>
                                    <option <?php if ($default["country"] == "Mauritius") {
                                                echo 'selected';
                                            } ?> value="Mauritius" data-code="230">Mauritius</option>
                                    <option <?php if ($default["country"] == "Mayotte") {
                                                echo 'selected';
                                            } ?> value="Mayotte" data-code="262">Mayotte</option>
                                    <option <?php if ($default["country"] == "Mexico") {
                                                echo 'selected';
                                            } ?> value="Mexico" data-code="52">Mexico</option>
                                    <option <?php if ($default["country"] == "Micronesia (Federated States of)") {
                                                echo 'selected';
                                            } ?> value="Micronesia (Federated States of)" data-code="691">Micronesia (Federated States of)</option>
                                    <option <?php if ($default["country"] == "Moldova (Republic of)") {
                                                echo 'selected';
                                            } ?> value="Moldova (Republic of)" data-code="373">Moldova (Republic of)</option>
                                    <option <?php if ($default["country"] == "Monaco") {
                                                echo 'selected';
                                            } ?> value="Monaco" data-code="377">Monaco</option>
                                    <option <?php if ($default["country"] == "Mongolia") {
                                                echo 'selected';
                                            } ?> value="Mongolia" data-code="976">Mongolia</option>
                                    <option <?php if ($default["country"] == "Montenegro") {
                                                echo 'selected';
                                            } ?> value="Montenegro" data-code="382">Montenegro</option>
                                    <option <?php if ($default["country"] == "Montserrat") {
                                                echo 'selected';
                                            } ?> value="Montserrat" data-code="1">Montserrat</option>
                                    <option <?php if ($default["country"] == "Morocco") {
                                                echo 'selected';
                                            } ?> value="Morocco" data-code="212">Morocco</option>
                                    <option <?php if ($default["country"] == "Mozambique") {
                                                echo 'selected';
                                            } ?> value="Mozambique" data-code="258">Mozambique</option>
                                    <option <?php if ($default["country"] == "Myanmar") {
                                                echo 'selected';
                                            } ?> value="Myanmar" data-code="95">Myanmar</option>
                                    <option <?php if ($default["country"] == "Namibia") {
                                                echo 'selected';
                                            } ?> value="Namibia" data-code="264">Namibia</option>
                                    <option <?php if ($default["country"] == "Nauru") {
                                                echo 'selected';
                                            } ?> value="Nauru" data-code="674">Nauru</option>
                                    <option <?php if ($default["country"] == "Nepal") {
                                                echo 'selected';
                                            } ?> value="Nepal" data-code="977">Nepal</option>
                                    <option <?php if ($default["country"] == "Netherlands") {
                                                echo 'selected';
                                            } ?> value="Netherlands" data-code="31">Netherlands</option>
                                    <option <?php if ($default["country"] == "New Caledonia") {
                                                echo 'selected';
                                            } ?> value="New Caledonia" data-code="687">New Caledonia</option>
                                    <option <?php if ($default["country"] == "New Zealand") {
                                                echo 'selected';
                                            } ?> value="New Zealand" data-code="64">New Zealand</option>
                                    <option <?php if ($default["country"] == "Nicaragua") {
                                                echo 'selected';
                                            } ?> value="Nicaragua" data-code="505">Nicaragua</option>
                                    <option <?php if ($default["country"] == "Niger") {
                                                echo 'selected';
                                            } ?> value="Niger" data-code="227">Niger</option>
                                    <option <?php if ($default["country"] == "Nigeria") {
                                                echo 'selected';
                                            } ?> value="Nigeria" data-code="234">Nigeria</option>
                                    <option <?php if ($default["country"] == "Niue") {
                                                echo 'selected';
                                            } ?> value="Niue" data-code="683">Niue</option>
                                    <option <?php if ($default["country"] == "Norfolk Island") {
                                                echo 'selected';
                                            } ?> value="Norfolk Island" data-code="672">Norfolk Island</option>
                                    <option <?php if ($default["country"] == "Korea (Democratic People's Republic of)") {
                                                echo 'selected';
                                            } ?> value="Korea (Democratic People's Republic of)" data-code="850">Korea (Democratic People's Republic of)</option>
                                    <option <?php if ($default["country"] == "Northern Mariana Islands") {
                                                echo 'selected';
                                            } ?> value="Northern Mariana Islands" data-code="1">Northern Mariana Islands</option>
                                    <option <?php if ($default["country"] == "Norway") {
                                                echo 'selected';
                                            } ?> value="Norway" data-code="47">Norway</option>
                                    <option <?php if ($default["country"] == "Oman") {
                                                echo 'selected';
                                            } ?> value="Oman" data-code="968">Oman</option>
                                    <option <?php if ($default["country"] == "Pakistan") {
                                                echo 'selected';
                                            } ?> value="Pakistan" data-code="92">Pakistan</option>
                                    <option <?php if ($default["country"] == "Palau") {
                                                echo 'selected';
                                            } ?> value="Palau" data-code="680">Palau</option>
                                    <option <?php if ($default["country"] == "Palestine, State of") {
                                                echo 'selected';
                                            } ?> value="Palestine, State of" data-code="970">Palestine, State of</option>
                                    <option <?php if ($default["country"] == "Panama") {
                                                echo 'selected';
                                            } ?> value="Panama" data-code="507">Panama</option>
                                    <option <?php if ($default["country"] == "Papua New Guinea") {
                                                echo 'selected';
                                            } ?> value="Papua New Guinea" data-code="675">Papua New Guinea</option>
                                    <option <?php if ($default["country"] == "Paraguay") {
                                                echo 'selected';
                                            } ?> value="Paraguay" data-code="595">Paraguay</option>
                                    <option <?php if ($default["country"] == "Peru") {
                                                echo 'selected';
                                            } ?> value="Peru" data-code="51">Peru</option>
                                    <option <?php if ($default["country"] == "Philippines") {
                                                echo 'selected';
                                            } ?> value="Philippines" data-code="63">Philippines</option>
                                    <option <?php if ($default["country"] == "Pitcairn") {
                                                echo 'selected';
                                            } ?> value="Pitcairn" data-code="64">Pitcairn</option>
                                    <option <?php if ($default["country"] == "Poland") {
                                                echo 'selected';
                                            } ?> value="Poland" data-code="48">Poland</option>
                                    <option <?php if ($default["country"] == "Portugal") {
                                                echo 'selected';
                                            } ?> value="Portugal" data-code="351">Portugal</option>
                                    <option <?php if ($default["country"] == "Puerto Rico") {
                                                echo 'selected';
                                            } ?> value="Puerto Rico" data-code="1">Puerto Rico</option>
                                    <option <?php if ($default["country"] == "Qatar") {
                                                echo 'selected';
                                            } ?> value="Qatar" data-code="974">Qatar</option>
                                    <option <?php if ($default["country"] == "Republic of Kosovo") {
                                                echo 'selected';
                                            } ?> value="Republic of Kosovo" data-code="383">Republic of Kosovo</option>
                                    <option <?php if ($default["country"] == "Réunion") {
                                                echo 'selected';
                                            } ?> value="Réunion" data-code="262">Réunion</option>
                                    <option <?php if ($default["country"] == "Romania") {
                                                echo 'selected';
                                            } ?> value="Romania" data-code="40">Romania</option>
                                    <option <?php if ($default["country"] == "Russian Federation") {
                                                echo 'selected';
                                            } ?> value="Russian Federation" data-code="7">Russian Federation</option>
                                    <option <?php if ($default["country"] == "Rwanda") {
                                                echo 'selected';
                                            } ?> value="Rwanda" data-code="250">Rwanda</option>
                                    <option <?php if ($default["country"] == "Saint Barthélemy") {
                                                echo 'selected';
                                            } ?> value="Saint Barthélemy" data-code="590">Saint Barthélemy</option>
                                    <option <?php if ($default["country"] == "Saint Helena, Ascension and Tristan da Cunha") {
                                                echo 'selected';
                                            } ?> value="Saint Helena, Ascension and Tristan da Cunha" data-code="290">Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option <?php if ($default["country"] == "Saint Kitts and Nevis") {
                                                echo 'selected';
                                            } ?> value="Saint Kitts and Nevis" data-code="1">Saint Kitts and Nevis</option>
                                    <option <?php if ($default["country"] == "Saint Lucia") {
                                                echo 'selected';
                                            } ?> value="Saint Lucia" data-code="1">Saint Lucia</option>
                                    <option <?php if ($default["country"] == "Saint Martin (French part)") {
                                                echo 'selected';
                                            } ?> value="Saint Martin (French part)" data-code="590">Saint Martin (French part)</option>
                                    <option <?php if ($default["country"] == "Saint Pierre and Miquelon") {
                                                echo 'selected';
                                            } ?> value="Saint Pierre and Miquelon" data-code="508">Saint Pierre and Miquelon</option>
                                    <option <?php if ($default["country"] == "Saint Vincent and the Grenadines") {
                                                echo 'selected';
                                            } ?> value="Saint Vincent and the Grenadines" data-code="1">Saint Vincent and the Grenadines</option>
                                    <option <?php if ($default["country"] == "Samoa") {
                                                echo 'selected';
                                            } ?> value="Samoa" data-code="685">Samoa</option>
                                    <option <?php if ($default["country"] == "San Marino") {
                                                echo 'selected';
                                            } ?> value="San Marino" data-code="378">San Marino</option>
                                    <option <?php if ($default["country"] == "Sao Tome and Principe") {
                                                echo 'selected';
                                            } ?> value="Sao Tome and Principe" data-code="239">Sao Tome and Principe</option>
                                    <option <?php if ($default["country"] == "Saudi Arabia") {
                                                echo 'selected';
                                            } ?> value="Saudi Arabia" data-code="966">Saudi Arabia</option>
                                    <option <?php if ($default["country"] == "Senegal") {
                                                echo 'selected';
                                            } ?> value="Senegal" data-code="221">Senegal</option>
                                    <option <?php if ($default["country"] == "Serbia") {
                                                echo 'selected';
                                            } ?> value="Serbia" data-code="381">Serbia</option>
                                    <option <?php if ($default["country"] == "Seychelles") {
                                                echo 'selected';
                                            } ?> value="Seychelles" data-code="248">Seychelles</option>
                                    <option <?php if ($default["country"] == "Sierra Leone") {
                                                echo 'selected';
                                            } ?> value="Sierra Leone" data-code="232">Sierra Leone</option>
                                    <option <?php if ($default["country"] == "Singapore") {
                                                echo 'selected';
                                            } ?> value="Singapore" data-code="65">Singapore</option>
                                    <option <?php if ($default["country"] == "Sint Maarten (Dutch part)") {
                                                echo 'selected';
                                            } ?> value="Sint Maarten (Dutch part)" data-code="1">Sint Maarten (Dutch part)</option>
                                    <option <?php if ($default["country"] == "Slovakia") {
                                                echo 'selected';
                                            } ?> value="Slovakia" data-code="421">Slovakia</option>
                                    <option <?php if ($default["country"] == "Slovenia") {
                                                echo 'selected';
                                            } ?> value="Slovenia" data-code="386">Slovenia</option>
                                    <option <?php if ($default["country"] == "Solomon Islands") {
                                                echo 'selected';
                                            } ?> value="Solomon Islands" data-code="677">Solomon Islands</option>
                                    <option <?php if ($default["country"] == "Somalia") {
                                                echo 'selected';
                                            } ?> value="Somalia" data-code="252">Somalia</option>
                                    <option <?php if ($default["country"] == "South Africa") {
                                                echo 'selected';
                                            } ?> value="South Africa" data-code="27">South Africa</option>
                                    <option <?php if ($default["country"] == "South Georgia and the South Sandwich Islands") {
                                                echo 'selected';
                                            } ?> value="South Georgia and the South Sandwich Islands" data-code="500">South Georgia and the South Sandwich Islands</option>
                                    <option <?php if ($default["country"] == "Korea (Republic of)") {
                                                echo 'selected';
                                            } ?> value="Korea (Republic of)" data-code="82">Korea (Republic of)</option>
                                    <option <?php if ($default["country"] == "South Sudan") {
                                                echo 'selected';
                                            } ?> value="South Sudan" data-code="211">South Sudan</option>
                                    <option <?php if ($default["country"] == "Spain") {
                                                echo 'selected';
                                            } ?> value="Spain" data-code="34">Spain</option>
                                    <option <?php if ($default["country"] == "Sri Lanka") {
                                                echo 'selected';
                                            } ?> value="Sri Lanka" data-code="94">Sri Lanka</option>
                                    <option <?php if ($default["country"] == "Sudan") {
                                                echo 'selected';
                                            } ?> value="Sudan" data-code="249">Sudan</option>
                                    <option <?php if ($default["country"] == "Suriname") {
                                                echo 'selected';
                                            } ?> value="Suriname" data-code="597">Suriname</option>
                                    <option <?php if ($default["country"] == "Svalbard and Jan Mayen") {
                                                echo 'selected';
                                            } ?> value="Svalbard and Jan Mayen" data-code="47">Svalbard and Jan Mayen</option>
                                    <option <?php if ($default["country"] == "Swaziland") {
                                                echo 'selected';
                                            } ?> value="Swaziland" data-code="268">Swaziland</option>
                                    <option <?php if ($default["country"] == "Sweden") {
                                                echo 'selected';
                                            } ?> value="Sweden" data-code="46">Sweden</option>
                                    <option <?php if ($default["country"] == "Switzerland") {
                                                echo 'selected';
                                            } ?> value="Switzerland" data-code="41">Switzerland</option>
                                    <option <?php if ($default["country"] == "Syrian Arab Republic") {
                                                echo 'selected';
                                            } ?> value="Syrian Arab Republic" data-code="963">Syrian Arab Republic</option>
                                    <option <?php if ($default["country"] == "Taiwan") {
                                                echo 'selected';
                                            } ?> value="Taiwan" data-code="886">Taiwan</option>
                                    <option <?php if ($default["country"] == "Tajikistan") {
                                                echo 'selected';
                                            } ?> value="Tajikistan" data-code="992">Tajikistan</option>
                                    <option <?php if ($default["country"] == "Tanzania, United Republic of") {
                                                echo 'selected';
                                            } ?> value="Tanzania, United Republic of" data-code="255">Tanzania, United Republic of</option>
                                    <option <?php if ($default["country"] == "Thailand") {
                                                echo 'selected';
                                            } ?> value="Thailand" data-code="66">Thailand</option>
                                    <option <?php if ($default["country"] == "Timor-Leste") {
                                                echo 'selected';
                                            } ?> value="Timor-Leste" data-code="670">Timor-Leste</option>
                                    <option <?php if ($default["country"] == "Togo") {
                                                echo 'selected';
                                            } ?> value="Togo" data-code="228">Togo</option>
                                    <option <?php if ($default["country"] == "Tokelau") {
                                                echo 'selected';
                                            } ?> value="Tokelau" data-code="690">Tokelau</option>
                                    <option <?php if ($default["country"] == "Tonga") {
                                                echo 'selected';
                                            } ?> value="Tonga" data-code="676">Tonga</option>
                                    <option <?php if ($default["country"] == "Trinidad and Tobago") {
                                                echo 'selected';
                                            } ?> value="Trinidad and Tobago" data-code="1">Trinidad and Tobago</option>
                                    <option <?php if ($default["country"] == "Tunisia") {
                                                echo 'selected';
                                            } ?> value="Tunisia" data-code="216">Tunisia</option>
                                    <option <?php if ($default["country"] == "Turkey") {
                                                echo 'selected';
                                            } ?> value="Turkey" data-code="90">Turkey</option>
                                    <option <?php if ($default["country"] == "Turkmenistan") {
                                                echo 'selected';
                                            } ?> value="Turkmenistan" data-code="993">Turkmenistan</option>
                                    <option <?php if ($default["country"] == "Turks and Caicos Islands") {
                                                echo 'selected';
                                            } ?> value="Turks and Caicos Islands" data-code="1">Turks and Caicos Islands</option>
                                    <option <?php if ($default["country"] == "Tuvalu") {
                                                echo 'selected';
                                            } ?> value="Tuvalu" data-code="688">Tuvalu</option>
                                    <option <?php if ($default["country"] == "Uganda") {
                                                echo 'selected';
                                            } ?> value="Uganda" data-code="256">Uganda</option>
                                    <option <?php if ($default["country"] == "Ukraine") {
                                                echo 'selected';
                                            } ?> value="Ukraine" data-code="380">Ukraine</option>
                                    <option <?php if ($default["country"] == "United Arab Emirates") {
                                                echo 'selected';
                                            } ?> value="United Arab Emirates" data-code="971">United Arab Emirates</option>
                                    <option <?php if ($default["country"] == "United Kingdom of Great Britain and Northern Ireland") {
                                                echo 'selected';
                                            } ?> value="United Kingdom of Great Britain and Northern Ireland" data-code="44">United Kingdom of Great Britain and Northern Ireland</option>
                                    <option <?php if ($default["country"] == "United States of America") {
                                                echo 'selected';
                                            } ?> value="United States of America" data-code="1">United States of America</option>
                                    <option <?php if ($default["country"] == "Uruguay") {
                                                echo 'selected';
                                            } ?> value="Uruguay" data-code="598">Uruguay</option>
                                    <option <?php if ($default["country"] == "Uzbekistan") {
                                                echo 'selected';
                                            } ?> value="Uzbekistan" data-code="998">Uzbekistan</option>
                                    <option <?php if ($default["country"] == "Vanuatu") {
                                                echo 'selected';
                                            } ?> value="Vanuatu" data-code="678">Vanuatu</option>
                                    <option <?php if ($default["country"] == "Venezuela (Bolivarian Republic of)") {
                                                echo 'selected';
                                            } ?> value="Venezuela (Bolivarian Republic of)" data-code="58">Venezuela (Bolivarian Republic of)</option>
                                    <option <?php if ($default["country"] == "Viet Nam") {
                                                echo 'selected';
                                            } ?> value="Viet Nam" data-code="84">Viet Nam</option>
                                    <option <?php if ($default["country"] == "Wallis and Futuna") {
                                                echo 'selected';
                                            } ?> value="Wallis and Futuna" data-code="681">Wallis and Futuna</option>
                                    <option <?php if ($default["country"] == "Western Sahara") {
                                                echo 'selected';
                                            } ?> value="Western Sahara" data-code="212">Western Sahara</option>
                                    <option <?php if ($default["country"] == "Yemen") {
                                                echo 'selected';
                                            } ?> value="Yemen" data-code="967">Yemen</option>
                                    <option <?php if ($default["country"] == "Zambia") {
                                                echo 'selected';
                                            } ?> value="Zambia" data-code="260">Zambia</option>
                                    <option <?php if ($default["country"] == "Zimbabwe") {
                                                echo 'selected';
                                            } ?> value="Zimbabwe" data-code="263">Zimbabwe</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Country Code<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Country Code" name="country_code" id="country_calling_code" required value="<?php echo $default['country_code']; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Mobile" name="mobile" required value="<?php echo $default['mobile']; ?>">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Username<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Username" name="username" autocomplete="off" required value="<?php echo $default['username']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email<span class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Email" name="email" autocomplete="off" required value="<?php echo $default['email']; ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="exampleInputEmailID">Employee ID<span class="text-danger required">*</span></label>
                                <input id="employee_id" type="text" class="form-control" placeholder="Enter Emp ID" name="employee_id" required value="<?php echo $default['employee_id']; ?>">
                            </div>
                        </div>

                    </div>
                    <div class="show">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmailID">POS User ID<span class="text-danger required">*</span></label>
                                    <input id="pos_user_id" type="text" class="form-control" placeholder="Enter POS User ID" name="pos_user_id" required value="<?php echo $default['pos_user_id']; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Password<span class="text-danger required">*</span></label>

                                    <input id="password-field" type="password" class="form-control" name="passwd" autocomplete="off" placeholder="Enter  Password">
                                    <span toggle="#password-field" class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
                                    <?php if (form_error('passwd')) { ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <?php echo form_error('passwd'); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Confirm Password<span class="text-danger required">*</span></label>

                                    <input id="password-field" type="password" class="form-control" name="c_password" placeholder="Enter Confirm Password">
                                    <!--  <span toggle1="#password-field" class="fa fa-fw fa-eye field-icon toggle-password1"></span> -->
                                    <?php if (form_error('c_password')) { ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <?php echo form_error('c_password'); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                    </div>
                    <input type="hidden" name="language" value="3">
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Group<span class="text-danger required">*</span></label>
                                <select class="form-control" name="groups[]" required="required" multiple>
                                    <?php
                                    foreach ($groups as $group) {
                                        if($group["status"] == 1){
                                            $is_selected = ($group["is_selected"] == 1) ? 'selected' : '';
                                            echo "<option value='" . $group["group_id"] . "'  " . $is_selected . ">" . $group["group_name"] . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Primary Group<span class="text-danger required">*</span></label>
                                <select class="form-control" id='primary_group' name="primary_group" required="required">
                                    <option value="">Select Group</option>
                                    <?php
                                        foreach ($groups as $group) {
                                            $is_selected = ($group["group_id"] == $primary_group['group_id']) ? 'selected' : '';
                                            echo $group["is_selected"] == 1 ?"<option value='" . $group["group_id"] . "' $is_selected>" . $group["group_name"] . "</option>":"";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Active</label>
                                <select name="is_active" class="form-control pc-selectpicker">
                                    <option value="1" <?php echo ($default['is_active'] == '1') ? 'selected' : '' ?>>Active
                                    </option>
                                    <option value="0" <?php echo ($default['is_active'] == '0') ? 'selected' : '' ?>>
                                        In-active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Is Sales</label>
                                <select name="is_sales" class="form-control pc-selectpicker">
                                    <option value="1" <?php echo ($default['is_sales'] == '1') ? 'selected' : '' ?>>Yes
                                    </option>
                                    <option value="0" <?php echo ($default['is_sales'] == '0') ? 'selected' : '' ?>>
                                        No</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Accessable Group</label>
                                <select class="form-control" name="users_group_accessables[]" multiple>
                                    <?php
                                    foreach ($access_groups as $group) {
                                        $is_selected = ($group["is_selected"] == 1) ? 'selected' : '';
                                        echo "<option value='" . $group["group_id"] . "'  " . $is_selected . ">" . $group["group_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Report Group</label>
                                <select class="form-control" name="users_group_report[]" multiple>
                                    <?php
                                    foreach ($report_groups as $group) {
                                        $is_selected = ($group["is_selected"] == 1) ? 'selected' : '';
                                        echo "<option value='" . $group["group_id"] . "'  " . $is_selected . ">" . $group["group_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Calendar Group</label>
                                <select class="form-control" name="users_calendar_group_accessables[]" multiple>
                                    <?php
                                    foreach ($calendar as $group) {
                                        $is_selected = ($group["is_selected"] == 1) ? 'selected' : '';
                                        echo "<option value='" . $group["calendar_id"] . "'  " . $is_selected . ">" . $group["calendar_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="calendarbranch">Calendar Branch</label>
                                <select class="form-control" name="users_calendar_branch_accessables" id="users_calendar_branch_accessables">
                                    <?php
                                    foreach ($calendar_branch as $cal_branch) {
                                        $is_selected = ($cal_branch["is_selected"] == 1) ? 'selected' : '';
                                        echo "<option value='" . $cal_branch["id"] . "'  " . $is_selected . ">" . $cal_branch["branch_name"] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Copy Payment Link Option</label>
                                    <input type="radio" name="paylink_option" id='paylink_option' value="enable" style="margin: 10px;"
                                    <?php echo ($default['paylink_option'] == 'enable')?'checked':''?> required>Enable
                                    <input type="radio" name="paylink_option" id='paylink_option' value="disable" style="margin: 10px;"
                                        <?php echo ($default['paylink_option'] == 'disable')?'checked':''?>>Disable
                                </div>
                            </div>
                        </div>

                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
    $("document").ready(function() {
        $('[name="groups[]"]').select2({
            placeholder: "Select the Group(s)"
        });

        $('[name="groups[]"]').on('select2:unselect',function(e){
           $("#primary_group").html('');
           $("#primary_group").append("<option value=''> </option>")
            var $selectedOptions = $(this).find('option:selected');
            $selectedOptions.each(function(){
                $("#primary_group").append("<option value='"+$(this).val()+"'>"+$(this).text()+" </option>")
            });
        }).on('select2:select',function(){
            $("#primary_group").html('');
            $("#primary_group").append("<option value=''> </option>")
            var $selectedOptions = $(this).find('option:selected');
            $selectedOptions.each(function(){
                $("#primary_group").append("<option value='"+$(this).val()+"'>"+$(this).text()+" </option>")
            });
        })

        $('[name="users_group_accessables[]"]').select2({
            placeholder: "Select the Accessable Group(s)"
        });
        $('[name="users_group_report[]"]').select2({
            placeholder: "Select the Report Group(s)"
        });

        $('[name="users_calendar_group_accessables[]"]').select2({
            placeholder: "Select the Accessable Calendar Group(s)"
        });

        $('#users_calendar_branch_accessables').select2({
            placeholder: "Select the Accessable Calendar Branch(s)"
        });

       
    });
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
    $('#country_calling_code').val(<?php echo ""; ?>);
    $('#country_calling_code').val($("#country option:selected").data("code"))

    $('#country').change(function() {
        // countryName = $(this).val()
        // set_country_code(countryName)
        $('#country_calling_code').val($("#country option:selected").data("code"));
    });

</script>