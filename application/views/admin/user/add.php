<style type="text/css">
.field-icon {
    float: right;
    margin-left: -25px;
    margin-top: -25px;
    position: relative;
    z-index: 2;
}

.select2-container,
span.select2-selection.select2-selection--multiple {
    height: 56px;
    border-radius: 10px;
    border: 0.3px solid #cecece !important;
}
</style>
<div class="row page-titles mx-0">
    <div class="col-sm-6 p-md-0">
        <div class="welcome-text">
            <h4><?php echo $page_title; ?></h4>
            <span>Add new user</span>
        </div>
    </div>
    <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
        <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>admin/user">
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
                    <?php echo $this->session->flashdata('alert-message'); ?>
                </div>
                <?php
                }
                ?>
                <form action="<?php echo base_url(); ?>admin/user/add" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Role <span class="text-danger required">*</span></label>
                                <select name="role_id" id="role_id" class="form-control pc-selectpicker role_id"
                                    required>
                                    <option value=""> -- Select Role --</option>
                                    <?php
                                    foreach ($role as  $value) {
                                    ?>
                                    <option value="<?php echo $value->role_id; ?>">
                                        <?php echo $value->role_name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">First Name<span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter First Name" name="first_name"
                                    required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Last Name <span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Last Name" name="last_name"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Country<span
                                        class="text-danger required">*</span></label>
                                <select name="country" id="country" class="form-control pc-selectpicker country"
                                    required="">
                                    <option value="" data-code="" selected=""> -- Select Country --</option>
                                    <option value="Afghanistan" data-code="93">Afghanistan</option>
                                    <option value="Åland Islands" data-code="358">Åland Islands</option>
                                    <option value="Albania" data-code="355">Albania</option>
                                    <option value="Algeria" data-code="213">Algeria</option>
                                    <option value="American Samoa" data-code="1">American Samoa</option>
                                    <option value="Andorra" data-code="376">Andorra</option>
                                    <option value="Angola" data-code="244">Angola</option>
                                    <option value="Anguilla" data-code="1">Anguilla</option>
                                    <option value="Antarctica" data-code="672">Antarctica</option>
                                    <option value="Antigua and Barbuda" data-code="1">Antigua and Barbuda</option>
                                    <option value="Argentina" data-code="54">Argentina</option>
                                    <option value="Armenia" data-code="374">Armenia</option>
                                    <option value="Aruba" data-code="297">Aruba</option>
                                    <option value="Australia" data-code="61">Australia</option>
                                    <option value="Austria" data-code="43">Austria</option>
                                    <option value="Azerbaijan" data-code="994">Azerbaijan</option>
                                    <option value="Bahamas" data-code="1">Bahamas</option>
                                    <option value="Bahrain" data-code="973">Bahrain</option>
                                    <option value="Bangladesh" data-code="880">Bangladesh</option>
                                    <option value="Barbados" data-code="1">Barbados</option>
                                    <option value="Belarus" data-code="375">Belarus</option>
                                    <option value="Belgium" data-code="32">Belgium</option>
                                    <option value="Belize" data-code="501">Belize</option>
                                    <option value="Benin" data-code="229">Benin</option>
                                    <option value="Bermuda" data-code="1">Bermuda</option>
                                    <option value="Bhutan" data-code="975">Bhutan</option>
                                    <option value="Bolivia (Plurinational State of)" data-code="591">Bolivia
                                        (Plurinational State of)</option>
                                    <option value="Bonaire, Sint Eustatius and Saba" data-code="599">Bonaire, Sint
                                        Eustatius and Saba</option>
                                    <option value="Bosnia and Herzegovina" data-code="387">Bosnia and Herzegovina
                                    </option>
                                    <option value="Botswana" data-code="267">Botswana</option>
                                    <option value="Bouvet Island" data-code="47">Bouvet Island</option>
                                    <option value="Brazil" data-code="55">Brazil</option>
                                    <option value="British Indian Ocean Territory" data-code="246">British Indian Ocean
                                        Territory</option>
                                    <option value="United States Minor Outlying Islands" data-code="1">United States
                                        Minor Outlying Islands</option>
                                    <option value="Virgin Islands (British)" data-code="1">Virgin Islands (British)
                                    </option>
                                    <option value="Virgin Islands (U.S.)" data-code="1 340">Virgin Islands (U.S.)
                                    </option>
                                    <option value="Brunei Darussalam" data-code="673">Brunei Darussalam</option>
                                    <option value="Bulgaria" data-code="359">Bulgaria</option>
                                    <option value="Burkina Faso" data-code="226">Burkina Faso</option>
                                    <option value="Burundi" data-code="257">Burundi</option>
                                    <option value="Cambodia" data-code="855">Cambodia</option>
                                    <option value="Cameroon" data-code="237">Cameroon</option>
                                    <option value="Canada" data-code="1">Canada</option>
                                    <option value="Cabo Verde" data-code="238">Cabo Verde</option>
                                    <option value="Cayman Islands" data-code="1">Cayman Islands</option>
                                    <option value="Central African Republic" data-code="236">Central African Republic
                                    </option>
                                    <option value="Chad" data-code="235">Chad</option>
                                    <option value="Chile" data-code="56">Chile</option>
                                    <option value="China" data-code="86">China</option>
                                    <option value="Christmas Island" data-code="61">Christmas Island</option>
                                    <option value="Cocos (Keeling) Islands" data-code="61">Cocos (Keeling) Islands
                                    </option>
                                    <option value="Colombia" data-code="57">Colombia</option>
                                    <option value="Comoros" data-code="269">Comoros</option>
                                    <option value="Congo" data-code="242">Congo</option>
                                    <option value="Congo (Democratic Republic of the)" data-code="243">Congo (Democratic
                                        Republic of the)</option>
                                    <option value="Cook Islands" data-code="682">Cook Islands</option>
                                    <option value="Costa Rica" data-code="506">Costa Rica</option>
                                    <option value="Croatia" data-code="385">Croatia</option>
                                    <option value="Cuba" data-code="53">Cuba</option>
                                    <option value="Curaçao" data-code="599">Curaçao</option>
                                    <option value="Cyprus" data-code="357">Cyprus</option>
                                    <option value="Czech Republic" data-code="420">Czech Republic</option>
                                    <option value="Denmark" data-code="45">Denmark</option>
                                    <option value="Djibouti" data-code="253">Djibouti</option>
                                    <option value="Dominica" data-code="1">Dominica</option>
                                    <option value="Dominican Republic" data-code="1">Dominican Republic</option>
                                    <option value="Ecuador" data-code="593">Ecuador</option>
                                    <option value="Egypt" data-code="20">Egypt</option>
                                    <option value="El Salvador" data-code="503">El Salvador</option>
                                    <option value="Equatorial Guinea" data-code="240">Equatorial Guinea</option>
                                    <option value="Eritrea" data-code="291">Eritrea</option>
                                    <option value="Estonia" data-code="372">Estonia</option>
                                    <option value="Ethiopia" data-code="251">Ethiopia</option>
                                    <option value="Falkland Islands (Malvinas)" data-code="500">Falkland Islands
                                        (Malvinas)</option>
                                    <option value="Faroe Islands" data-code="298">Faroe Islands</option>
                                    <option value="Fiji" data-code="679">Fiji</option>
                                    <option value="Finland" data-code="358">Finland</option>
                                    <option value="France" data-code="33">France</option>
                                    <option value="French Guiana" data-code="594">French Guiana</option>
                                    <option value="French Polynesia" data-code="689">French Polynesia</option>
                                    <option value="French Southern Territories" data-code="262">French Southern
                                        Territories</option>
                                    <option value="Gabon" data-code="241">Gabon</option>
                                    <option value="Gambia" data-code="220">Gambia</option>
                                    <option value="Georgia" data-code="995">Georgia</option>
                                    <option value="Germany" data-code="49">Germany</option>
                                    <option value="Ghana" data-code="233">Ghana</option>
                                    <option value="Gibraltar" data-code="350">Gibraltar</option>
                                    <option value="Greece" data-code="30">Greece</option>
                                    <option value="Greenland" data-code="299">Greenland</option>
                                    <option value="Grenada" data-code="1">Grenada</option>
                                    <option value="Guadeloupe" data-code="590">Guadeloupe</option>
                                    <option value="Guam" data-code="1">Guam</option>
                                    <option value="Guatemala" data-code="502">Guatemala</option>
                                    <option value="Guernsey" data-code="44">Guernsey</option>
                                    <option value="Guinea" data-code="224">Guinea</option>
                                    <option value="Guinea-Bissau" data-code="245">Guinea-Bissau</option>
                                    <option value="Guyana" data-code="592">Guyana</option>
                                    <option value="Haiti" data-code="509">Haiti</option>
                                    <option value="Heard Island and McDonald Islands" data-code="672">Heard Island and
                                        McDonald Islands</option>
                                    <option value="Holy See" data-code="379">Holy See</option>
                                    <option value="Honduras" data-code="504">Honduras</option>
                                    <option value="Hong Kong" data-code="852">Hong Kong</option>
                                    <option value="Hungary" data-code="36">Hungary</option>
                                    <option value="Iceland" data-code="354">Iceland</option>
                                    <option value="India" data-code="91">India</option>
                                    <option value="Indonesia" data-code="62">Indonesia</option>
                                    <option value="Côte d'Ivoire" data-code="225">Côte d'Ivoire</option>
                                    <option value="Iran (Islamic Republic of)" data-code="98">Iran (Islamic Republic of)
                                    </option>
                                    <option value="Iraq" data-code="964">Iraq</option>
                                    <option value="Ireland" data-code="353">Ireland</option>
                                    <option value="Isle of Man" data-code="44">Isle of Man</option>
                                    <option value="Israel" data-code="972">Israel</option>
                                    <option value="Italy" data-code="39">Italy</option>
                                    <option value="Jamaica" data-code="1">Jamaica</option>
                                    <option value="Japan" data-code="81">Japan</option>
                                    <option value="Jersey" data-code="44">Jersey</option>
                                    <option value="Jordan" data-code="962">Jordan</option>
                                    <option value="Kazakhstan" data-code="76">Kazakhstan</option>
                                    <option value="Kenya" data-code="254">Kenya</option>
                                    <option value="Kiribati" data-code="686">Kiribati</option>
                                    <option value="Kuwait" data-code="965">Kuwait</option>
                                    <option value="Kyrgyzstan" data-code="996">Kyrgyzstan</option>
                                    <option value="Lao People's Democratic Republic" data-code="856">Lao People's
                                        Democratic Republic</option>
                                    <option value="Latvia" data-code="371">Latvia</option>
                                    <option value="Lebanon" data-code="961">Lebanon</option>
                                    <option value="Lesotho" data-code="266">Lesotho</option>
                                    <option value="Liberia" data-code="231">Liberia</option>
                                    <option value="Libya" data-code="218">Libya</option>
                                    <option value="Liechtenstein" data-code="423">Liechtenstein</option>
                                    <option value="Lithuania" data-code="370">Lithuania</option>
                                    <option value="Luxembourg" data-code="352">Luxembourg</option>
                                    <option value="Macao" data-code="853">Macao</option>
                                    <option value="Macedonia (the former Yugoslav Republic of)" data-code="389">
                                        Macedonia (the former Yugoslav Republic of)</option>
                                    <option value="Madagascar" data-code="261">Madagascar</option>
                                    <option value="Malawi" data-code="265">Malawi</option>
                                    <option value="Malaysia" data-code="60">Malaysia</option>
                                    <option value="Maldives" data-code="960">Maldives</option>
                                    <option value="Mali" data-code="223">Mali</option>
                                    <option value="Malta" data-code="356">Malta</option>
                                    <option value="Marshall Islands" data-code="692">Marshall Islands</option>
                                    <option value="Martinique" data-code="596">Martinique</option>
                                    <option value="Mauritania" data-code="222">Mauritania</option>
                                    <option value="Mauritius" data-code="230">Mauritius</option>
                                    <option value="Mayotte" data-code="262">Mayotte</option>
                                    <option value="Mexico" data-code="52">Mexico</option>
                                    <option value="Micronesia (Federated States of)" data-code="691">Micronesia
                                        (Federated States of)</option>
                                    <option value="Moldova (Republic of)" data-code="373">Moldova (Republic of)</option>
                                    <option value="Monaco" data-code="377">Monaco</option>
                                    <option value="Mongolia" data-code="976">Mongolia</option>
                                    <option value="Montenegro" data-code="382">Montenegro</option>
                                    <option value="Montserrat" data-code="1">Montserrat</option>
                                    <option value="Morocco" data-code="212">Morocco</option>
                                    <option value="Mozambique" data-code="258">Mozambique</option>
                                    <option value="Myanmar" data-code="95">Myanmar</option>
                                    <option value="Namibia" data-code="264">Namibia</option>
                                    <option value="Nauru" data-code="674">Nauru</option>
                                    <option value="Nepal" data-code="977">Nepal</option>
                                    <option value="Netherlands" data-code="31">Netherlands</option>
                                    <option value="New Caledonia" data-code="687">New Caledonia</option>
                                    <option value="New Zealand" data-code="64">New Zealand</option>
                                    <option value="Nicaragua" data-code="505">Nicaragua</option>
                                    <option value="Niger" data-code="227">Niger</option>
                                    <option value="Nigeria" data-code="234">Nigeria</option>
                                    <option value="Niue" data-code="683">Niue</option>
                                    <option value="Norfolk Island" data-code="672">Norfolk Island</option>
                                    <option value="Korea (Democratic People's Republic of)" data-code="850">Korea
                                        (Democratic People's Republic of)</option>
                                    <option value="Northern Mariana Islands" data-code="1">Northern Mariana Islands
                                    </option>
                                    <option value="Norway" data-code="47">Norway</option>
                                    <option value="Oman" data-code="968">Oman</option>
                                    <option value="Pakistan" data-code="92">Pakistan</option>
                                    <option value="Palau" data-code="680">Palau</option>
                                    <option value="Palestine, State of" data-code="970">Palestine, State of</option>
                                    <option value="Panama" data-code="507">Panama</option>
                                    <option value="Papua New Guinea" data-code="675">Papua New Guinea</option>
                                    <option value="Paraguay" data-code="595">Paraguay</option>
                                    <option value="Peru" data-code="51">Peru</option>
                                    <option value="Philippines" data-code="63">Philippines</option>
                                    <option value="Pitcairn" data-code="64">Pitcairn</option>
                                    <option value="Poland" data-code="48">Poland</option>
                                    <option value="Portugal" data-code="351">Portugal</option>
                                    <option value="Puerto Rico" data-code="1">Puerto Rico</option>
                                    <option value="Qatar" data-code="974">Qatar</option>
                                    <option value="Republic of Kosovo" data-code="383">Republic of Kosovo</option>
                                    <option value="Réunion" data-code="262">Réunion</option>
                                    <option value="Romania" data-code="40">Romania</option>
                                    <option value="Russian Federation" data-code="7">Russian Federation</option>
                                    <option value="Rwanda" data-code="250">Rwanda</option>
                                    <option value="Saint Barthélemy" data-code="590">Saint Barthélemy</option>
                                    <option value="Saint Helena, Ascension and Tristan da Cunha" data-code="290">Saint
                                        Helena, Ascension and Tristan da Cunha</option>
                                    <option value="Saint Kitts and Nevis" data-code="1">Saint Kitts and Nevis</option>
                                    <option value="Saint Lucia" data-code="1">Saint Lucia</option>
                                    <option value="Saint Martin (French part)" data-code="590">Saint Martin (French
                                        part)</option>
                                    <option value="Saint Pierre and Miquelon" data-code="508">Saint Pierre and Miquelon
                                    </option>
                                    <option value="Saint Vincent and the Grenadines" data-code="1">Saint Vincent and the
                                        Grenadines</option>
                                    <option value="Samoa" data-code="685">Samoa</option>
                                    <option value="San Marino" data-code="378">San Marino</option>
                                    <option value="Sao Tome and Principe" data-code="239">Sao Tome and Principe</option>
                                    <option value="Saudi Arabia" data-code="966">Saudi Arabia</option>
                                    <option value="Senegal" data-code="221">Senegal</option>
                                    <option value="Serbia" data-code="381">Serbia</option>
                                    <option value="Seychelles" data-code="248">Seychelles</option>
                                    <option value="Sierra Leone" data-code="232">Sierra Leone</option>
                                    <option value="Singapore" data-code="65">Singapore</option>
                                    <option value="Sint Maarten (Dutch part)" data-code="1">Sint Maarten (Dutch part)
                                    </option>
                                    <option value="Slovakia" data-code="421">Slovakia</option>
                                    <option value="Slovenia" data-code="386">Slovenia</option>
                                    <option value="Solomon Islands" data-code="677">Solomon Islands</option>
                                    <option value="Somalia" data-code="252">Somalia</option>
                                    <option value="South Africa" data-code="27">South Africa</option>
                                    <option value="South Georgia and the South Sandwich Islands" data-code="500">South
                                        Georgia and the South Sandwich Islands</option>
                                    <option value="Korea (Republic of)" data-code="82">Korea (Republic of)</option>
                                    <option value="South Sudan" data-code="211">South Sudan</option>
                                    <option value="Spain" data-code="34">Spain</option>
                                    <option value="Sri Lanka" data-code="94">Sri Lanka</option>
                                    <option value="Sudan" data-code="249">Sudan</option>
                                    <option value="Suriname" data-code="597">Suriname</option>
                                    <option value="Svalbard and Jan Mayen" data-code="47">Svalbard and Jan Mayen
                                    </option>
                                    <option value="Swaziland" data-code="268">Swaziland</option>
                                    <option value="Sweden" data-code="46">Sweden</option>
                                    <option value="Switzerland" data-code="41">Switzerland</option>
                                    <option value="Syrian Arab Republic" data-code="963">Syrian Arab Republic</option>
                                    <option value="Taiwan" data-code="886">Taiwan</option>
                                    <option value="Tajikistan" data-code="992">Tajikistan</option>
                                    <option value="Tanzania, United Republic of" data-code="255">Tanzania, United
                                        Republic of</option>
                                    <option value="Thailand" data-code="66">Thailand</option>
                                    <option value="Timor-Leste" data-code="670">Timor-Leste</option>
                                    <option value="Togo" data-code="228">Togo</option>
                                    <option value="Tokelau" data-code="690">Tokelau</option>
                                    <option value="Tonga" data-code="676">Tonga</option>
                                    <option value="Trinidad and Tobago" data-code="1">Trinidad and Tobago</option>
                                    <option value="Tunisia" data-code="216">Tunisia</option>
                                    <option value="Turkey" data-code="90">Turkey</option>
                                    <option value="Turkmenistan" data-code="993">Turkmenistan</option>
                                    <option value="Turks and Caicos Islands" data-code="1">Turks and Caicos Islands
                                    </option>
                                    <option value="Tuvalu" data-code="688">Tuvalu</option>
                                    <option value="Uganda" data-code="256">Uganda</option>
                                    <option value="Ukraine" data-code="380">Ukraine</option>
                                    <option value="United Arab Emirates" data-code="971">United Arab
                                        Emirates</option>
                                    <option value="United Kingdom of Great Britain and Northern Ireland" data-code="44">
                                        United Kingdom of Great Britain and Northern Ireland</option>
                                    <option value="United States of America" data-code="1">United States of America
                                    </option>
                                    <option value="Uruguay" data-code="598">Uruguay</option>
                                    <option value="Uzbekistan" data-code="998">Uzbekistan</option>
                                    <option value="Vanuatu" data-code="678">Vanuatu</option>
                                    <option value="Venezuela (Bolivarian Republic of)" data-code="58">Venezuela
                                        (Bolivarian Republic of)</option>
                                    <option value="Viet Nam" data-code="84">Viet Nam</option>
                                    <option value="Wallis and Futuna" data-code="681">Wallis and Futuna</option>
                                    <option value="Western Sahara" data-code="212">Western Sahara</option>
                                    <option value="Yemen" data-code="967">Yemen</option>
                                    <option value="Zambia" data-code="260">Zambia</option>
                                    <option value="Zimbabwe" data-code="263">Zimbabwe</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Country Code<span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Country Code"
                                    name="country_code" value="971" required id="country_calling_code" readonly="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Mobile<span
                                        class="text-danger required">*</span></label>
                                <input type="number" class="form-control" placeholder="Enter Mobile" name="mobile"
                                    required>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Username<span
                                        class="text-danger required">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter Username" name="username"
                                    required>
                            </div>
                        </div> -->
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email<span class="text-danger required">*</span></label>
                                <input id="email" type="email" class="form-control" placeholder="Enter Email"
                                    name="email" required>
                                <span id="email_error" class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="exampleInputEmailID">Employee ID<span
                                        class="text-danger required">*</span></label>
                                <input id="emp_id" type="text" class="form-control" placeholder="Enter Emp ID"
                                    name="employee_id" required>
                                <span id="emp_id_error" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Password<span
                                        class="text-danger required">*</span></label>

                                <input id="password-field" type="password" class="form-control" name="passwd"
                                    placeholder="Enter  Password" value="Welcome@123" required readonly>
                                <small><span>Default password: Welcome@123</span></small>
                                <span toggle="#password-field"
                                    class="fa fa-fw fa-eye-slash field-icon toggle-password"></span>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Confirm Password<span
                                        class="text-danger required">*</span></label>

                                <input id="password-field" type="password" class="form-control" name="c_password"
                                    placeholder="Enter Confirm Password" required value="Welcome@123" readonly>
                                <small><span>Default password: Welcome@123</span></small>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmailID">POS User ID<span class="text-danger required">*</span></label>
                                <input id="pos_user_id" type="text" class="form-control" placeholder="Enter POS User ID" name="pos_user_id" required>
                            </div>
                        </div>
                        
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Group<span class="text-danger required">*</span></label>
                                <select class="form-control" id="groups" name=" groups[]" required="required" multiple>
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
                                <select class="form-control" id="primary_group" name="primary_group" required="required">
                                    <option value="">Select Group</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Language<span
                                        class="text-danger required">*</span></label>
                                <select class="form-control" name="language" required="required">
                                    <option value="0">Select Language</option>
                                    <option value="1">English</option>
                                    <option value="2">عربي</option>
                                    <option value="3">Both</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Is Sales</label>
                                <select name="is_sales" class="form-control pc-selectpicker">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
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
                                <label for="exampleInputEmail1">Calendar Group<span class="text-danger required">*</span></label>
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
                                    <option value=""> -- Select Calendar Branch --</option>
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
                                    <label for="exampleInputEmail1">Copy Payment Link Option <span
                                            class="text-danger required">*</span></label>
                                    <input type="radio" name="paylink_option" id="paylink_option" value="enable" style="margin: 10px;" checked>Enable
                                    <input type="radio" name="paylink_option" id="paylink_option" value="disable" style="margin: 10px;">Disable
                                </div>
                            </div>
                        </div>
                        
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Groups<span class="text-danger required">*</span></label>
                                <select class="form-control" name="groups[]" id="groups" required="required" multiple>
                                    <option value="" default disabled>Select the Groups</option>
                                    <?php
                                    // print_r($groups);
                                    foreach ($groups as $group) {
                                    ?>
                                        <option value="<?php echo $group['group_id']; ?>"><?php echo $group["group_name"]; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Company<span class="text-danger required">*</span></label>
                                <select class="form-control" name="company" required="required">
                                    <option value="">Select Company</option>
                                    <?php
                                    foreach ($companies as $key => $value) {
                                    ?>
                                        <option value="<?php echo $value['id']; ?>"><?php echo $value['company_name']; ?></option>
                                        <?php
                                    }
                                        ?>
                               </select>
                            </div>
                        </div> -->
                    </div>

                    <button name="submit" type="submit" class="btn btn-primary">Create</button>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
$("document").ready(function() {
    $("#groups").select2({
        placeholder: "Select the Group(s)"
    });
    $('#groups').on('select2:unselect',function(e){
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
            placeholder: "Select the Caledar Group(s)"
    });
    $('#users_calendar_branch_accessables').select2({
            placeholder: "Select the Calendar Branch"
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
$('#country').change(function() {
    // countryName = $(this).val()
    // set_country_code(countryName)
    $('#country_calling_code').val($("#country option:selected").data("code"));
});

$('#email_error').hide();
let emailError = true;
$('#email').keyup(function() {
    validateEmail();
});

function validateEmail() {
    let emailValue = $('#email').val();
    $.ajax({
        url: "<?php echo base_url(); ?>admin/user/checkemail",
        data: {
            email: emailValue
        },
        type: 'GET',
        success: function(res) {
            //console.log(res);
            if (res == 1) {
                $('#email_error').show();
                $('#email_error').html("Entered email is already exists");
                emailError = false;
                return false;
            } else {
                //console.log("error"+res);
            }
        }
    });

    let regex = /^([_\-\.0-9a-zA-Z]+)@([_\-\.0-9a-zA-Z]+)\.([a-zA-Z]){2,7}$/;
    if (regex.test(emailValue) === false) {
        $('#email_error').show();
        $('#email_error').html("Please enter valid email address");
        emailError = false;
        return false;
    } else {
        emailError = true;
        $('#email_error').hide();
    }
}
</script>