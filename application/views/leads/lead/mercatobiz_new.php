<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/2.8.0/css/flag-icon.min.css"
    integrity="sha256-EQjZwW4ljrt9dsonbyX+si6kbxgkVde47Ty9FQehnUg=" crossorigin="anonymous" />
<style>
    span.flag-icon-squared {
        margin-right: 10px;
        width: 2em;
        height: 1.2rem;
    }
    label span {
        color: red;
        margin-left: 3px;
        top: -3px;
        position: relative;
    }

    span.select2-selection.select2-selection--single {
        height: 38px;
        width: 100%;
    }
</style>
<div class="app-main">
    <div class="app-main__outer">
        <div class="app-main__inner p-0">
            <div class="app-page-title">
                <div class="container fiori-container">
                    <div class="page-title-wrapper">
                        <div class="page-title-heading">
                            <div> Mercato Biz Lead
                                <div class="page-title-subheading">Creation and Assign on the Lead</div>
                            </div>
                        </div>
                        <div class="page-title-actions">
                            <div class="d-inline-block dropdown">
                                <?php
                                if (in_array(44, get_access_group()) || in_array(41, get_access_group())) {
                                ?>
                                    <a href="/leads/lead/mercatobizlist">
                                        <button type="button" class="btn btn-outline-primary btn-shadow btn-wide">
                                            <span class="btn-icon-wrapper pr-1 opacity-7">
                                                <i class="fa fa-list-ul"></i>
                                            </span>
                                            Mercato Biz Leads
                                        </button>
                                    </a>
                                <?php
                                }
                                ?>
                                <!-- <a href="<?php echo base_url();?>leads/lead/new">
                                    <button type="button" class="btn btn-outline-primary btn-shadow btn-wide">
                                        <span class="btn-icon-wrapper pr-1 opacity-7">
                                            <i class="fa fa-plus"></i>
                                        </span>
                                        Create Lead
                                    </button>
                                </a> -->
                                <a href="<?php echo base_url();?>leads/lead/manage">
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
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#category_block').show();
                            $('#service_block').show();
                            $('#package_block').hide();

                            //add new
                            $('#btnAddNewAttachment').click(function(e) {
                                e.preventDefault();
                                var newDiv = $(
                                    '<div class="row mt-3"><div class="col-md-6"><input type="text" class="form-control" required="" name="attachment_name[]" placeholder="Attachment Name" /></div><div class="col-md-5"><input type="file" class="form-control" required="" name="files[]" placeholder="" /></div><div class="col-md-1 float-right"><a href="#" class="text-danger close-div"><i class="fa fa-trash"></i></a></div></div>'
                                );
                                $('body').animate({
                                    scrollTop: eval($('#attachment_area').offset().top - 70)
                                }, 1000);
                                $('#attachments').append(newDiv);

                                $('.close-div').click(function(e) {
                                    e.preventDefault();
                                    $(this).parent().parent().remove();
                                    $('body').animate({
                                        scrollTop: eval($('#attachment_area').offset().top -
                                            70)
                                    }, 1000);
                                });
                            });
                        });
                    </script>
                    <!-- <div class="row page-titles mx-0">
                        <div class="col-sm-6 p-md-0">
                            <Sdiv class="welcome-text">
                                <h4>New Lead</h4>
                                <span>Ontime Leads Management</span>
                            </div>
                        </div>
                        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                            <a class="btn btn-primary waves-effect waves-light float-right" href="<?php echo base_url(); ?>leads/lead/manage"> VIEW LEADS
                            </a>
                        </div>
                    </div> -->
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('alert')) { 
                                ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('alert'); ?>">
                                    <?php echo $this->session->flashdata('alert_message');
                                    if ($this->session->flashdata('zoho') != NULL) {
                                        $data = $this->session->flashdata('zoho');
                                        // $json_data = json_decode($data);
                                    ?>
                                        Zoho Response: <span class="font-weight-bold" id="zoho-id"></span><span id="zoho-id_1"></span>
                                        <script>
                                            var data = <?php echo $data; ?>;
                                            document.getElementById("zoho-id").innerHTML = data.data[0]['code'];
                                            var det1 = "";
                                            if (data.data[0]['code'] != "SUCCESS") {
                                                det1 = data.data[0].details.api_name + ": ";
                                            }
                                            var det2 = data.data[0].message;
                                            document.getElementById("zoho-id_1").innerHTML = " => " + det1 + " " + det2;
                                        </script>
                                    <?php
                                    }
                                    ?>
                                </div>
                            <?php  }?>
                            <form action="<?php echo base_url(); ?>leads/lead/mercatobiznew" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="">Lead Source Type</label>
                                            <select class="form-control" name="lead_source_name" id="lead_source_name"
                                                required>
                                                <option value="" selected default disabled>-- Source -</option>
                                                <option>Walk-in</option>
                                                <option>Chat</option>
                                                <option>Calls</option>
                                                <option>Kiosk</option>
                                                <option>Email</option>
                                                <option>Marketing (Adv)</option>
                                                <option>OnTime Branches</option>
                                                <option>Referral</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Lead Name&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" required="" name="lead_name" autofocus>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Nationality</label>
                                            <select name="nationality" id="" class="form-control">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Country Code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" pattern="[+][0-9]{1,}" required="" name="lead_country_code" value="+971">
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Lead Contact number without country code&nbsp;<span class="text-danger required">*</span></label>
                                            <input type="text" class="form-control" required="" name="lead_contact">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="email" class="form-control" name="lead_email">
                                        </div>
                                    </div>

                                     <!-- Bussiness Activity Starts -->
                                     <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="business_activity">Bussiness Activity</label>
                                            <select class="form-control" name="business_activity" id="business_activity"
                                                required>
                                                <option value="" selected default>-- select --</option>
                                                <?php 
                                                if(!empty($business_activities)){
                                                foreach ($business_activities as $bk => $ba) { ?>
                                                <option value="<?php echo $ba['id']; ?>">
                                                    <?php echo $ba['activity_name']; ?></option>
                                                <?php } } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12 row" id="infoblock"
                                        style="display:none;border-style: solid;border-color: #C7C8C8;border-width: 1px;margin-left: 2px;border-radius: 15px;">
                                        <?php if (!empty($bizcategory)) {
                                            foreach ($bizcategory as $b) { ?>
                                        <div class="col-md-6">
                                            <h6 class="mt-4 d-flex align-items-center">
                                                <?php echo $b['category_name']; ?>
                                                <span class="ml-auto toggle-icon" style="cursor: pointer; font-size: 1.2em;">➕</span>
                                            </h6>
                                            <div class="form-check-container" style="display: none;">
                                                <?php if (!empty($b['subdet'])) {
                                                    foreach ($b['subdet'] as $cm) { ?>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" value="1"
                                                        id="<?php echo $cm['field_name']; ?>"
                                                        name="<?php echo $cm['field_name']; ?>">
                                                    <label class="form-check-label"
                                                        for="<?php echo $cm['field_name']; ?>"><?php echo $cm['field']; ?></label>
                                                </div>
                                                <?php } } ?>
                                            </div>
                                        </div>
                                        <?php } } ?>
                                        <div class="col-md-12"><br /></div>
                                    </div>
                                    <div class="col-md-12 lead_business_activity_div">
                                        <div class="form-group">
                                            <label>Business Activity&nbsp;<span
                                                    class="text-danger required">*</span></label>
                                            <textarea rows="7" class="form-control" required=""
                                                name="lead_business_activity"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign To User <span class="text-danger required">*</span></label>
                                            <select name="lead_assigned_to" id="lead_assigned_to" class="form-control" required>
                                                <option value="">-- Select User --</option>
                                                <option value="4118254655">Aicha.t@mercatobiz.com</option>
                                                <option value="3079890878">zahra.s@mercatobiz.com</option>
                                                <option value="2723988403">Ali.i@mercatobiz.com</option>
                                                <option value="1963149950">Mohamed.i@mercatobiz.com</option>
                                                <option value="1591625470">Oumaima.a@mercatobiz.com</option>
                                                <option value="1166551310">anshif.km@mercatobiz.com</option>
                                                <option value="1166551310">anshif.km@mercatobiz.com</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    </div>
                                    
                                    <!-- Business Activity Ends -->

                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Location <span class="text-danger required">*</span></label>
                                            <select name="lead_location" id="lead_location" class="form-control" required>
                                                <option value="">-- Select Location --</option>
                                                <option value="al_manara">Al Manara</option>
                                                <option value="mazaya">Mazaya</option>
                                                <option value="business_venue">Business Venue</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign To User <span class="text-danger required">*</span></label>
                                            <select name="lead_assigned_to" id="lead_assigned_to" class="form-control" required>
                                                <option value="">-- Select User --</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks(in English/Arabic)&nbsp;<span class="text-danger required">*</span></label>
                                            <textarea rows="7" class="form-control" required="" name="lead_remarks"></textarea>
                                        </div>
                                    </div>
                                    <!-- <a id="attachment_area"></a>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Attachments&nbsp;&nbsp;&nbsp;&nbsp; <button id="btnAddNewAttachment"
                                                    class="btn btn-sm btn-primary">Add
                                                    new</button></label>
                                            <div class="" id="attachments">

                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="col-md-12">
                                        <div class="form-group text-right">
                                            <input type="hidden" name="lead_created_by" value="<?php echo $this->auth_user_id; ?>">
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
<script src="/global/vendor/select2/js/select2.full.min.js"></script>
<link rel="stylesheet" href="/global/vendor/select2/css/select2.min.css">
<script>
    $("select:not(#name_salutation)").select2();
</script>

<script>
    $(document).ready(function () {
        $('#business_activity').on('change', function () {
            var selectedOptionText = $(this).find('option:selected').text();
            if (selectedOptionText != 'Other') {
                $('textarea[name="lead_business_activity"]').val(selectedOptionText);
                $('.lead_business_activity_div').hide();
            } else {
                $('textarea[name="lead_business_activity"]').val('');
                $('.lead_business_activity_div').show();
            }
        });
    });

    $(function () {
        $('#infoblock').hide();
        $('#business_activity').change(function () {
            if ($('#business_activity').val() == '') {
                $('#infoblock').hide();
            } else {
                $('#infoblock').show();
            }
        });
    });

    $(document).ready(function () {
        (function ($) {
            $(function () {
                var isoCountriesWithPhone = [{
                        flag: "",
                        id: "",
                        text: ""
                    },
                    {
                        id: '+93',
                        text: '+93  Afghanistan',
                        flag: 'AF '
                    },
                    {
                        id: '+355',
                        text: '+355  Albania',
                        flag: 'AL '
                    },
                    {
                        id: '+213',
                        text: '+213  Algeria',
                        flag: 'DZ '
                    },
                    {
                        id: '+1684',
                        text: '+1684  American Samoa',
                        flag: 'AS '
                    },
                    {
                        id: '+376',
                        text: '+376  Andorra',
                        flag: 'AD '
                    },
                    {
                        id: '+244',
                        text: '+244  Angola',
                        flag: 'AO '
                    },
                    {
                        id: '+1264',
                        text: '+1264  Anguilla',
                        flag: 'AI '
                    },
                    {
                        id: '+672',
                        text: '+672  Antarctica',
                        flag: 'AQ '
                    },
                    {
                        id: '+1268',
                        text: '+1268  Antigua and Barbuda',
                        flag: 'AG '
                    },
                    {
                        id: '+54',
                        text: '+54  Argentina',
                        flag: 'AR '
                    },
                    {
                        id: '+374',
                        text: '+374  Armenia',
                        flag: 'AM '
                    },
                    {
                        id: '+297',
                        text: '+297  Aruba',
                        flag: 'AW '
                    },
                    {
                        id: '+61',
                        text: '+61  Australia',
                        flag: 'AU '
                    },
                    {
                        id: '+43',
                        text: '+43  Austria',
                        flag: 'AT '
                    },
                    {
                        id: '+994',
                        text: '+994  Azerbaijan',
                        flag: 'AZ '
                    },
                    {
                        id: '+1242',
                        text: '+1242  Bahamas',
                        flag: 'BS '
                    },
                    {
                        id: '+973',
                        text: '+973  Bahrain',
                        flag: 'BH '
                    },
                    {
                        id: '+880',
                        text: '+880  Bangladesh',
                        flag: 'BD '
                    },
                    {
                        id: '+1246',
                        text: '+1246  Barbados',
                        flag: 'BB '
                    },
                    {
                        id: '+375',
                        text: '+375  Belarus',
                        flag: 'BY '
                    },
                    {
                        id: '+32',
                        text: '+32  Belgium',
                        flag: 'BE '
                    },
                    {
                        id: '+501',
                        text: '+501  Belize',
                        flag: 'BZ '
                    },
                    {
                        id: '+229',
                        text: '+229  Benin',
                        flag: 'BJ '
                    },
                    {
                        id: '+1441',
                        text: '+1441  Bermuda',
                        flag: 'BM '
                    },
                    {
                        id: '+975',
                        text: '+975  Bhutan',
                        flag: 'BT '
                    },
                    {
                        id: '+591',
                        text: '+591  Bolivia',
                        flag: 'BO '
                    },
                    {
                        id: '+387',
                        text: '+387  Bosnia and Herzegovina',
                        flag: 'BA '
                    },
                    {
                        id: '+267',
                        text: '+267  Botswana',
                        flag: 'BW '
                    },
                    {
                        id: '+55',
                        text: '+55  Brazil',
                        flag: 'BR '
                    },
                    {
                        id: '+246',
                        text: '+246  British Indian Ocean Territory',
                        flag: 'IO '
                    },
                    {
                        id: '+1284',
                        text: '+1284  British Virgin Islands',
                        flag: 'VG '
                    },
                    {
                        id: '+673',
                        text: '+673  Brunei',
                        flag: 'BN '
                    },
                    {
                        id: '+359',
                        text: '+359  Bulgaria',
                        flag: 'BG '
                    },
                    {
                        id: '+226',
                        text: '+226  Burkina Faso',
                        flag: 'BF '
                    },
                    {
                        id: '+257',
                        text: '+257  Burundi',
                        flag: 'BI '
                    },
                    {
                        id: '+855',
                        text: '+855  Cambodia',
                        flag: 'KH '
                    },
                    {
                        id: '+237',
                        text: '+237  Cameroon',
                        flag: 'CM '
                    },
                    {
                        id: '+1',
                        text: '+1  Canada',
                        flag: 'CA '
                    },
                    {
                        id: '+238',
                        text: '+238  Cape Verde',
                        flag: 'CV '
                    },
                    {
                        id: '+1345',
                        text: '+1345  Cayman Islands',
                        flag: 'KY '
                    },
                    {
                        id: '+236',
                        text: '+236  Central African Republic',
                        flag: 'CF '
                    },
                    {
                        id: '+235',
                        text: '+235  Chad',
                        flag: 'TD '
                    },
                    {
                        id: '+56',
                        text: '+56  Chile',
                        flag: 'CL '
                    },
                    {
                        id: '+86',
                        text: '+86  China',
                        flag: 'CN '
                    },
                    {
                        id: '+61',
                        text: '+61  Christmas Island',
                        flag: 'CX '
                    },
                    {
                        id: '+61',
                        text: '+61  Cocos Islands',
                        flag: 'CC '
                    },
                    {
                        id: '+57',
                        text: '+57  Colombia',
                        flag: 'CO '
                    },
                    {
                        id: '+269',
                        text: '+269  Comoros',
                        flag: 'KM '
                    },
                    {
                        id: '+682',
                        text: '+682  Cook Islands',
                        flag: 'CK '
                    },
                    {
                        id: '+506',
                        text: '+506  Costa Rica',
                        flag: 'CR '
                    },
                    {
                        id: '+385',
                        text: '+385  Croatia',
                        flag: 'HR '
                    },
                    {
                        id: '+53',
                        text: '+53  Cuba',
                        flag: 'CU '
                    },
                    {
                        id: '+599',
                        text: '+599  Curacao',
                        flag: 'CW '
                    },
                    {
                        id: '+357',
                        text: '+357  Cyprus',
                        flag: 'CY '
                    },
                    {
                        id: '+420',
                        text: '+420  Czech Republic',
                        flag: 'CZ '
                    },
                    {
                        id: '+243',
                        text: '+243  Democratic Republic of the Congo',
                        flag: 'CD '
                    },
                    {
                        id: '+45',
                        text: '+45  Denmark',
                        flag: 'DK '
                    },
                    {
                        id: '+253',
                        text: '+253  Djibouti',
                        flag: 'DJ '
                    },
                    {
                        id: '+1767',
                        text: '+1767  Dominica',
                        flag: 'DM '
                    },
                    {
                        id: '+1809, 1829, 1849',
                        text: '+1809, 1829, 1849  Dominican Republic',
                        flag: 'DO '
                    },
                    {
                        id: '+670',
                        text: '+670  East Timor',
                        flag: 'TL '
                    },
                    {
                        id: '+593',
                        text: '+593  Ecuador',
                        flag: 'EC '
                    },
                    {
                        id: '+20',
                        text: '+20  Egypt',
                        flag: 'EG '
                    },
                    {
                        id: '+503',
                        text: '+503  El Salvador',
                        flag: 'SV '
                    },
                    {
                        id: '+240',
                        text: '+240  Equatorial Guinea',
                        flag: 'GQ '
                    },
                    {
                        id: '+291',
                        text: '+291  Eritrea',
                        flag: 'ER '
                    },
                    {
                        id: '+372',
                        text: '+372  Estonia',
                        flag: 'EE '
                    },
                    {
                        id: '+251',
                        text: '+251  Ethiopia',
                        flag: 'ET '
                    },
                    {
                        id: '+500',
                        text: '+500  Falkland Islands',
                        flag: 'FK '
                    },
                    {
                        id: '+298',
                        text: '+298  Faroe Islands',
                        flag: 'FO '
                    },
                    {
                        id: '+679',
                        text: '+679  Fiji',
                        flag: 'FJ '
                    },
                    {
                        id: '+358',
                        text: '+358  Finland',
                        flag: 'FI '
                    },
                    {
                        id: '+33',
                        text: '+33  France',
                        flag: 'FR '
                    },
                    {
                        id: '+689',
                        text: '+689  French Polynesia',
                        flag: 'PF '
                    },
                    {
                        id: '+241',
                        text: '+241  Gabon',
                        flag: 'GA '
                    },
                    {
                        id: '+220',
                        text: '+220  Gambia',
                        flag: 'GM '
                    },
                    {
                        id: '+995',
                        text: '+995  Georgia',
                        flag: 'GE '
                    },
                    {
                        id: '+49',
                        text: '+49  Germany',
                        flag: 'DE '
                    },
                    {
                        id: '+233',
                        text: '+233  Ghana',
                        flag: 'GH '
                    },
                    {
                        id: '+350',
                        text: '+350  Gibraltar',
                        flag: 'GI '
                    },
                    {
                        id: '+30',
                        text: '+30  Greece',
                        flag: 'GR '
                    },
                    {
                        id: '+299',
                        text: '+299  Greenland',
                        flag: 'GL '
                    },
                    {
                        id: '+1473',
                        text: '+1473  Grenada',
                        flag: 'GD '
                    },
                    {
                        id: '+1671',
                        text: '+1671  Guam',
                        flag: 'GU '
                    },
                    {
                        id: '+502',
                        text: '+502  Guatemala',
                        flag: 'GT '
                    },
                    {
                        id: '+441481',
                        text: '+441481  Guernsey',
                        flag: 'GG '
                    },
                    {
                        id: '+224',
                        text: '+224  Guinea',
                        flag: 'GN '
                    },
                    {
                        id: '+245',
                        text: '+245  Guinea-Bissau',
                        flag: 'GW '
                    },
                    {
                        id: '+592',
                        text: '+592  Guyana',
                        flag: 'GY '
                    },
                    {
                        id: '+509',
                        text: '+509  Haiti',
                        flag: 'HT '
                    },
                    {
                        id: '+504',
                        text: '+504  Honduras',
                        flag: 'HN '
                    },
                    {
                        id: '+852',
                        text: '+852  Hong Kong',
                        flag: 'HK '
                    },
                    {
                        id: '+36',
                        text: '+36  Hungary',
                        flag: 'HU '
                    },
                    {
                        id: '+354',
                        text: '+354  Iceland',
                        flag: 'IS '
                    },
                    {
                        id: '+91',
                        text: '+91  India',
                        flag: 'IN '
                    },
                    {
                        id: '+62',
                        text: '+62  Indonesia',
                        flag: 'ID '
                    },
                    {
                        id: '+98',
                        text: '+98  Iran',
                        flag: 'IR '
                    },
                    {
                        id: '+964',
                        text: '+964  Iraq',
                        flag: 'IQ '
                    },
                    {
                        id: '+353',
                        text: '+353  Ireland',
                        flag: 'IE '
                    },
                    {
                        id: '+441624',
                        text: '+441624  Isle of Man',
                        flag: 'IM '
                    },
                    {
                        id: '+972',
                        text: '+972  Israel',
                        flag: 'IL '
                    },
                    {
                        id: '+39',
                        text: '+39  Italy',
                        flag: 'IT '
                    },
                    {
                        id: '+225',
                        text: '+225  Ivory Coast',
                        flag: 'CI '
                    },
                    {
                        id: '+1876',
                        text: '+1876  Jamaica',
                        flag: 'JM '
                    },
                    {
                        id: '+81',
                        text: '+81  Japan',
                        flag: 'JP '
                    },
                    {
                        id: '+441534',
                        text: '+441534  Jersey',
                        flag: 'JE '
                    },
                    {
                        id: '+962',
                        text: '+962  Jordan',
                        flag: 'JO '
                    },
                    {
                        id: '+7',
                        text: '+7  Kazakhstan',
                        flag: 'KZ '
                    },
                    {
                        id: '+254',
                        text: '+254  Kenya',
                        flag: 'KE '
                    },
                    {
                        id: '+686',
                        text: '+686  Kiribati',
                        flag: 'KI '
                    },
                    {
                        id: '+383',
                        text: '+383  Kosovo',
                        flag: 'XK '
                    },
                    {
                        id: '+965',
                        text: '+965  Kuwait',
                        flag: 'KW '
                    },
                    {
                        id: '+996',
                        text: '+996  Kyrgyzstan',
                        flag: 'KG '
                    },
                    {
                        id: '+856',
                        text: '+856  Laos',
                        flag: 'LA '
                    },
                    {
                        id: '+371',
                        text: '+371  Latvia',
                        flag: 'LV '
                    },
                    {
                        id: '+961',
                        text: '+961  Lebanon',
                        flag: 'LB '
                    },
                    {
                        id: '+266',
                        text: '+266  Lesotho',
                        flag: 'LS '
                    },
                    {
                        id: '+231',
                        text: '+231  Liberia',
                        flag: 'LR '
                    },
                    {
                        id: '+218',
                        text: '+218  Libya',
                        flag: 'LY '
                    },
                    {
                        id: '+423',
                        text: '+423  Liechtenstein',
                        flag: 'LI '
                    },
                    {
                        id: '+370',
                        text: '+370  Lithuania',
                        flag: 'LT '
                    },
                    {
                        id: '+352',
                        text: '+352  Luxembourg',
                        flag: 'LU '
                    },
                    {
                        id: '+853',
                        text: '+853  Macau',
                        flag: 'MO '
                    },
                    {
                        id: '+389',
                        text: '+389  Macedonia',
                        flag: 'MK '
                    },
                    {
                        id: '+261',
                        text: '+261  Madagascar',
                        flag: 'MG '
                    },
                    {
                        id: '+265',
                        text: '+265  Malawi',
                        flag: 'MW '
                    },
                    {
                        id: '+60',
                        text: '+60  Malaysia',
                        flag: 'MY '
                    },
                    {
                        id: '+960',
                        text: '+960  Maldives',
                        flag: 'MV '
                    },
                    {
                        id: '+223',
                        text: '+223  Mali',
                        flag: 'ML '
                    },
                    {
                        id: '+356',
                        text: '+356  Malta',
                        flag: 'MT '
                    },
                    {
                        id: '+692',
                        text: '+692  Marshall Islands',
                        flag: 'MH '
                    },
                    {
                        id: '+222',
                        text: '+222  Mauritania',
                        flag: 'MR '
                    },
                    {
                        id: '+230',
                        text: '+230  Mauritius',
                        flag: 'MU '
                    },
                    {
                        id: '+262',
                        text: '+262  Mayotte',
                        flag: 'YT '
                    },
                    {
                        id: '+52',
                        text: '+52  Mexico',
                        flag: 'MX '
                    },
                    {
                        id: '+691',
                        text: '+691  Micronesia',
                        flag: 'FM '
                    },
                    {
                        id: '+373',
                        text: '+373  Moldova',
                        flag: 'MD '
                    },
                    {
                        id: '+377',
                        text: '+377  Monaco',
                        flag: 'MC '
                    },
                    {
                        id: '+976',
                        text: '+976  Mongolia',
                        flag: 'MN '
                    },
                    {
                        id: '+382',
                        text: '+382  Montenegro',
                        flag: 'ME '
                    },
                    {
                        id: '+1664',
                        text: '+1664  Montserrat',
                        flag: 'MS '
                    },
                    {
                        id: '+212',
                        text: '+212  Morocco',
                        flag: 'MA '
                    },
                    {
                        id: '+258',
                        text: '+258  Mozambique',
                        flag: 'MZ '
                    },
                    {
                        id: '+95',
                        text: '+95  Myanmar',
                        flag: 'MM '
                    },
                    {
                        id: '+264',
                        text: '+264  Namibia',
                        flag: 'NA '
                    },
                    {
                        id: '+674',
                        text: '+674  Nauru',
                        flag: 'NR '
                    },
                    {
                        id: '+977',
                        text: '+977  Nepal',
                        flag: 'NP '
                    },
                    {
                        id: '+31',
                        text: '+31  Netherlands',
                        flag: 'NL '
                    },
                    {
                        id: '+599',
                        text: '+599  Netherlands Antilles',
                        flag: 'AN '
                    },
                    {
                        id: '+687',
                        text: '+687  New Caledonia',
                        flag: 'NC '
                    },
                    {
                        id: '+64',
                        text: '+64  New Zealand',
                        flag: 'NZ '
                    },
                    {
                        id: '+505',
                        text: '+505  Nicaragua',
                        flag: 'NI '
                    },
                    {
                        id: '+227',
                        text: '+227  Niger',
                        flag: 'NE '
                    },
                    {
                        id: '+234',
                        text: '+234  Nigeria',
                        flag: 'NG '
                    },
                    {
                        id: '+683',
                        text: '+683  Niue',
                        flag: 'NU '
                    },
                    {
                        id: '+850',
                        text: '+850  North Korea',
                        flag: 'KP '
                    },
                    {
                        id: '+1670',
                        text: '+1670  Northern Mariana Islands',
                        flag: 'MP '
                    },
                    {
                        id: '+47',
                        text: '+47  Norway',
                        flag: 'NO '
                    },
                    {
                        id: '+968',
                        text: '+968  Oman',
                        flag: 'OM '
                    },
                    {
                        id: '+92',
                        text: '+92  Pakistan',
                        flag: 'PK '
                    },
                    {
                        id: '+680',
                        text: '+680  Palau',
                        flag: 'PW '
                    },
                    {
                        id: '+970',
                        text: '+970  Palestine',
                        flag: 'PS '
                    },
                    {
                        id: '+507',
                        text: '+507  Panama',
                        flag: 'PA '
                    },
                    {
                        id: '+675',
                        text: '+675  Papua New Guinea',
                        flag: 'PG '
                    },
                    {
                        id: '+595',
                        text: '+595  Paraguay',
                        flag: 'PY '
                    },
                    {
                        id: '+51',
                        text: '+51  Peru',
                        flag: 'PE '
                    },
                    {
                        id: '+63',
                        text: '+63  Philippines',
                        flag: 'PH '
                    },
                    {
                        id: '+64',
                        text: '+64  Pitcairn',
                        flag: 'PN '
                    },
                    {
                        id: '+48',
                        text: '+48  Poland',
                        flag: 'PL '
                    },
                    {
                        id: '+351',
                        text: '+351  Portugal',
                        flag: 'PT '
                    },
                    {
                        id: '+1787, 1939',
                        text: '+1787, 1939  Puerto Rico',
                        flag: 'PR '
                    },
                    {
                        id: '+974',
                        text: '+974  Qatar',
                        flag: 'QA '
                    },
                    {
                        id: '+242',
                        text: '+242  Republic of the Congo',
                        flag: 'CG '
                    },
                    {
                        id: '+262',
                        text: '+262  Reunion',
                        flag: 'RE '
                    },
                    {
                        id: '+40',
                        text: '+40  Romania',
                        flag: 'RO '
                    },
                    {
                        id: '+7',
                        text: '+7  Russia',
                        flag: 'RU '
                    },
                    {
                        id: '+250',
                        text: '+250  Rwanda',
                        flag: 'RW '
                    },
                    {
                        id: '+590',
                        text: '+590  Saint Barthelemy',
                        flag: 'BL '
                    },
                    {
                        id: '+290',
                        text: '+290  Saint Helena',
                        flag: 'SH '
                    },
                    {
                        id: '+1869',
                        text: '+1869  Saint Kitts and Nevis',
                        flag: 'KN '
                    },
                    {
                        id: '+1758',
                        text: '+1758  Saint Lucia',
                        flag: 'LC '
                    },
                    {
                        id: '+590',
                        text: '+590  Saint Martin',
                        flag: 'MF '
                    },
                    {
                        id: '+508',
                        text: '+508  Saint Pierre and Miquelon',
                        flag: 'PM '
                    },
                    {
                        id: '+1784',
                        text: '+1784  Saint Vincent and the Grenadines',
                        flag: 'VC '
                    },
                    {
                        id: '+685',
                        text: '+685  Samoa',
                        flag: 'WS '
                    },
                    {
                        id: '+378',
                        text: '+378  San Marino',
                        flag: 'SM '
                    },
                    {
                        id: '+239',
                        text: '+239  Sao Tome and Principe',
                        flag: 'ST '
                    },
                    {
                        id: '+966',
                        text: '+966  Saudi Arabia',
                        flag: 'SA '
                    },
                    {
                        id: '+221',
                        text: '+221  Senegal',
                        flag: 'SN '
                    },
                    {
                        id: '+381',
                        text: '+381  Serbia',
                        flag: 'RS '
                    },
                    {
                        id: '+248',
                        text: '+248  Seychelles',
                        flag: 'SC '
                    },
                    {
                        id: '+232',
                        text: '+232  Sierra Leone',
                        flag: 'SL '
                    },
                    {
                        id: '+65',
                        text: '+65  Singapore',
                        flag: 'SG '
                    },
                    {
                        id: '+1721',
                        text: '+1721  Sint Maarten',
                        flag: 'SX '
                    },
                    {
                        id: '+421',
                        text: '+421  Slovakia',
                        flag: 'SK '
                    },
                    {
                        id: '+386',
                        text: '+386  Slovenia',
                        flag: 'SI '
                    },
                    {
                        id: '+677',
                        text: '+677  Solomon Islands',
                        flag: 'SB '
                    },
                    {
                        id: '+252',
                        text: '+252  Somalia',
                        flag: 'SO '
                    },
                    {
                        id: '+27',
                        text: '+27  South Africa',
                        flag: 'ZA '
                    },
                    {
                        id: '+82',
                        text: '+82  South Korea',
                        flag: 'KR '
                    },
                    {
                        id: '+211',
                        text: '+211  South Sudan',
                        flag: 'SS '
                    },
                    {
                        id: '+34',
                        text: '+34  Spain',
                        flag: 'ES '
                    },
                    {
                        id: '+94',
                        text: '+94  Sri Lanka',
                        flag: 'LK '
                    },
                    {
                        id: '+249',
                        text: '+249  Sudan',
                        flag: 'SD '
                    },
                    {
                        id: '+597',
                        text: '+597  Suriname',
                        flag: 'SR '
                    },
                    {
                        id: '+47',
                        text: '+47  Svalbard and Jan Mayen',
                        flag: 'SJ '
                    },
                    {
                        id: '+268',
                        text: '+268  Swaziland',
                        flag: 'SZ '
                    },
                    {
                        id: '+46',
                        text: '+46  Sweden',
                        flag: 'SE '
                    },
                    {
                        id: '+41',
                        text: '+41  Switzerland',
                        flag: 'CH '
                    },
                    {
                        id: '+963',
                        text: '+963  Syria',
                        flag: 'SY '
                    },
                    {
                        id: '+886',
                        text: '+886  Taiwan',
                        flag: 'TW '
                    },
                    {
                        id: '+992',
                        text: '+992  Tajikistan',
                        flag: 'TJ '
                    },
                    {
                        id: '+255',
                        text: '+255  Tanzania',
                        flag: 'TZ '
                    },
                    {
                        id: '+66',
                        text: '+66  Thailand',
                        flag: 'TH '
                    },
                    {
                        id: '+228',
                        text: '+228  Togo',
                        flag: 'TG '
                    },
                    {
                        id: '+690',
                        text: '+690  Tokelau',
                        flag: 'TK '
                    },
                    {
                        id: '+676',
                        text: '+676  Tonga',
                        flag: 'TO '
                    },
                    {
                        id: '+1868',
                        text: '+1868  Trinidad and Tobago',
                        flag: 'TT '
                    },
                    {
                        id: '+216',
                        text: '+216  Tunisia',
                        flag: 'TN '
                    },
                    {
                        id: '+90',
                        text: '+90  Turkey',
                        flag: 'TR '
                    },
                    {
                        id: '+993',
                        text: '+993  Turkmenistan',
                        flag: 'TM '
                    },
                    {
                        id: '+1649',
                        text: '+1649  Turks and Caicos Islands',
                        flag: 'TC '
                    },
                    {
                        id: '+688',
                        text: '+688  Tuvalu',
                        flag: 'TV '
                    },
                    {
                        id: '+1340',
                        text: '+1340  U.S. Virgin Islands',
                        flag: 'VI '
                    },
                    {
                        id: '+256',
                        text: '+256  Uganda',
                        flag: 'UG '
                    },
                    {
                        id: '+380',
                        text: '+380  Ukraine',
                        flag: 'UA '
                    },
                    {
                        id: '+971',
                        text: '+971  United Arab Emirates',
                        flag: 'AE ',
                        selected: true
                    },
                    {
                        id: '+44',
                        text: '+44  United Kingdom',
                        flag: 'GB '
                    },
                    {
                        id: '+1',
                        text: '+1  United States',
                        flag: 'US '
                    },
                    {
                        id: '+598',
                        text: '+598  Uruguay',
                        flag: 'UY '
                    },
                    {
                        id: '+998',
                        text: '+998  Uzbekistan',
                        flag: 'UZ '
                    },
                    {
                        id: '+678',
                        text: '+678  Vanuatu',
                        flag: 'VU '
                    },
                    {
                        id: '+379',
                        text: '+379  Vatican',
                        flag: 'VA '
                    },
                    {
                        id: '+58',
                        text: '+58  Venezuela',
                        flag: 'VE '
                    },
                    {
                        id: '+84',
                        text: '+84  Vietnam',
                        flag: 'VN '
                    },
                    {
                        id: '+681',
                        text: '+681  Wallis and Futuna',
                        flag: 'WF '
                    },
                    {
                        id: '+212',
                        text: '+212  Western Sahara',
                        flag: 'EH '
                    },
                    {
                        id: '+967',
                        text: '+967  Yemen',
                        flag: 'YE '
                    },
                    {
                        id: '+260',
                        text: '+260  Zambia',
                        flag: 'ZM '
                    },
                    {
                        id: '+263',
                        text: '+263  Zimbabwe',
                        flag: 'ZW '
                    },

                ];
                var isoCountries = [{
                        flag: "",
                        id: "",
                        text: ""
                    },
                    {
                        id: 'Afghanistan',
                        text: 'Afghanistan',
                        flag: 'AF '
                    },
                    {
                        id: 'Albania',
                        text: 'Albania',
                        flag: 'AL '
                    },
                    {
                        id: 'Algeria',
                        text: 'Algeria',
                        flag: 'DZ '
                    },
                    {
                        id: 'American Samoa',
                        text: 'American Samoa',
                        flag: 'AS '
                    },
                    {
                        id: 'Andorra',
                        text: 'Andorra',
                        flag: 'AD '
                    },
                    {
                        id: 'Angola',
                        text: 'Angola',
                        flag: 'AO '
                    },
                    {
                        id: 'Anguilla',
                        text: 'Anguilla',
                        flag: 'AI '
                    },
                    {
                        id: 'Antarctica',
                        text: 'Antarctica',
                        flag: 'AQ '
                    },
                    {
                        id: 'Antigua and Barbuda',
                        text: 'Antigua and Barbuda',
                        flag: 'AG '
                    },
                    {
                        id: 'Argentina',
                        text: 'Argentina',
                        flag: 'AR '
                    },
                    {
                        id: 'Armenia',
                        text: 'Armenia',
                        flag: 'AM '
                    },
                    {
                        id: 'Aruba',
                        text: 'Aruba',
                        flag: 'AW '
                    },
                    {
                        id: 'Australia',
                        text: 'Australia',
                        flag: 'AU '
                    },
                    {
                        id: 'Austria',
                        text: 'Austria',
                        flag: 'AT '
                    },
                    {
                        id: 'Azerbaijan',
                        text: 'Azerbaijan',
                        flag: 'AZ '
                    },
                    {
                        id: 'Bahamas',
                        text: 'Bahamas',
                        flag: 'BS '
                    },
                    {
                        id: 'Bahrain',
                        text: 'Bahrain',
                        flag: 'BH '
                    },
                    {
                        id: 'Bangladesh',
                        text: 'Bangladesh',
                        flag: 'BD '
                    },
                    {
                        id: 'Barbados',
                        text: 'Barbados',
                        flag: 'BB '
                    },
                    {
                        id: 'Belarus',
                        text: 'Belarus',
                        flag: 'BY '
                    },
                    {
                        id: 'Belgium',
                        text: 'Belgium',
                        flag: 'BE '
                    },
                    {
                        id: 'Belize',
                        text: 'Belize',
                        flag: 'BZ '
                    },
                    {
                        id: 'Benin',
                        text: 'Benin',
                        flag: 'BJ '
                    },
                    {
                        id: 'Bermuda',
                        text: 'Bermuda',
                        flag: 'BM '
                    },
                    {
                        id: 'Bhutan',
                        text: 'Bhutan',
                        flag: 'BT '
                    },
                    {
                        id: 'Bolivia',
                        text: 'Bolivia',
                        flag: 'BO '
                    },
                    {
                        id: 'Bosnia and Herzegovina',
                        text: 'Bosnia and Herzegovina',
                        flag: 'BA '
                    },
                    {
                        id: 'Botswana',
                        text: 'Botswana',
                        flag: 'BW '
                    },
                    {
                        id: 'Brazil',
                        text: 'Brazil',
                        flag: 'BR '
                    },
                    {
                        id: 'British Indian Ocean Territory',
                        text: 'British Indian Ocean Territory',
                        flag: 'IO '
                    },
                    {
                        id: 'British Virgin Islands',
                        text: 'British Virgin Islands',
                        flag: 'VG '
                    },
                    {
                        id: 'Brunei',
                        text: 'Brunei',
                        flag: 'BN '
                    },
                    {
                        id: 'Bulgaria',
                        text: 'Bulgaria',
                        flag: 'BG '
                    },
                    {
                        id: 'Burkina Faso',
                        text: 'Burkina Faso',
                        flag: 'BF '
                    },
                    {
                        id: 'Burundi',
                        text: 'Burundi',
                        flag: 'BI '
                    },
                    {
                        id: 'Cambodia',
                        text: 'Cambodia',
                        flag: 'KH '
                    },
                    {
                        id: 'Cameroon',
                        text: 'Cameroon',
                        flag: 'CM '
                    },
                    {
                        id: 'Canada',
                        text: 'Canada',
                        flag: 'CA '
                    },
                    {
                        id: 'Cape Verde',
                        text: 'Cape Verde',
                        flag: 'CV '
                    },
                    {
                        id: 'Cayman Islands',
                        text: 'Cayman Islands',
                        flag: 'KY '
                    },
                    {
                        id: 'Central African Republic',
                        text: 'Central African Republic',
                        flag: 'CF '
                    },
                    {
                        id: 'Chad',
                        text: 'Chad',
                        flag: 'TD '
                    },
                    {
                        id: 'Chile',
                        text: 'Chile',
                        flag: 'CL '
                    },
                    {
                        id: 'China',
                        text: 'China',
                        flag: 'CN '
                    },
                    {
                        id: 'Christmas Island',
                        text: 'Christmas Island',
                        flag: 'CX '
                    },
                    {
                        id: 'Cocos Islands',
                        text: 'Cocos Islands',
                        flag: 'CC '
                    },
                    {
                        id: 'Colombia',
                        text: 'Colombia',
                        flag: 'CO '
                    },
                    {
                        id: 'Comoros',
                        text: 'Comoros',
                        flag: 'KM '
                    },
                    {
                        id: 'Cook Islands',
                        text: 'Cook Islands',
                        flag: 'CK '
                    },
                    {
                        id: 'Costa Rica',
                        text: 'Costa Rica',
                        flag: 'CR '
                    },
                    {
                        id: 'Croatia',
                        text: 'Croatia',
                        flag: 'HR '
                    },
                    {
                        id: 'Cuba',
                        text: 'Cuba',
                        flag: 'CU '
                    },
                    {
                        id: 'Curacao',
                        text: 'Curacao',
                        flag: 'CW '
                    },
                    {
                        id: 'Cyprus',
                        text: 'Cyprus',
                        flag: 'CY '
                    },
                    {
                        id: 'Czech Republic',
                        text: 'Czech Republic',
                        flag: 'CZ '
                    },
                    {
                        id: 'Democratic Republic of the Congo',
                        text: 'Democratic Republic of the Congo',
                        flag: 'CD '
                    },
                    {
                        id: 'Denmark',
                        text: 'Denmark',
                        flag: 'DK '
                    },
                    {
                        id: 'Djibouti',
                        text: 'Djibouti',
                        flag: 'DJ '
                    },
                    {
                        id: 'Dominica',
                        text: 'Dominica',
                        flag: 'DM '
                    },
                    {
                        id: 'Dominican Republic',
                        text: 'Dominican Republic',
                        flag: 'DO '
                    },
                    {
                        id: 'East Timor',
                        text: 'East Timor',
                        flag: 'TL '
                    },
                    {
                        id: 'Ecuador',
                        text: 'Ecuador',
                        flag: 'EC '
                    },
                    {
                        id: 'Egypt',
                        text: 'Egypt',
                        flag: 'EG '
                    },
                    {
                        id: 'El Salvador',
                        text: 'El Salvador',
                        flag: 'SV '
                    },
                    {
                        id: 'Equatorial Guinea',
                        text: 'Equatorial Guinea',
                        flag: 'GQ '
                    },
                    {
                        id: 'Eritrea',
                        text: 'Eritrea',
                        flag: 'ER '
                    },
                    {
                        id: 'Estonia',
                        text: 'Estonia',
                        flag: 'EE '
                    },
                    {
                        id: 'Ethiopia',
                        text: 'Ethiopia',
                        flag: 'ET '
                    },
                    {
                        id: 'Falkland Islands',
                        text: 'Falkland Islands',
                        flag: 'FK '
                    },
                    {
                        id: 'Faroe Islands',
                        text: 'Faroe Islands',
                        flag: 'FO '
                    },
                    {
                        id: 'Fiji',
                        text: 'Fiji',
                        flag: 'FJ '
                    },
                    {
                        id: 'Finland',
                        text: 'Finland',
                        flag: 'FI '
                    },
                    {
                        id: 'France',
                        text: 'France',
                        flag: 'FR '
                    },
                    {
                        id: 'French Polynesia',
                        text: 'French Polynesia',
                        flag: 'PF '
                    },
                    {
                        id: 'Gabon',
                        text: 'Gabon',
                        flag: 'GA '
                    },
                    {
                        id: 'Gambia',
                        text: 'Gambia',
                        flag: 'GM '
                    },
                    {
                        id: 'Georgia',
                        text: 'Georgia',
                        flag: 'GE '
                    },
                    {
                        id: 'Germany',
                        text: 'Germany',
                        flag: 'DE '
                    },
                    {
                        id: 'Ghana',
                        text: 'Ghana',
                        flag: 'GH '
                    },
                    {
                        id: 'Gibraltar',
                        text: 'Gibraltar',
                        flag: 'GI '
                    },
                    {
                        id: 'Greece',
                        text: 'Greece',
                        flag: 'GR '
                    },
                    {
                        id: 'Greenland',
                        text: 'Greenland',
                        flag: 'GL '
                    },
                    {
                        id: 'Grenada',
                        text: 'Grenada',
                        flag: 'GD '
                    },
                    {
                        id: 'Guam',
                        text: 'Guam',
                        flag: 'GU '
                    },
                    {
                        id: 'Guatemala',
                        text: 'Guatemala',
                        flag: 'GT '
                    },
                    {
                        id: 'Guernsey',
                        text: 'Guernsey',
                        flag: 'GG '
                    },
                    {
                        id: 'Guinea',
                        text: 'Guinea',
                        flag: 'GN '
                    },
                    {
                        id: 'Guinea-Bissau',
                        text: 'Guinea-Bissau',
                        flag: 'GW '
                    },
                    {
                        id: 'Guyana',
                        text: 'Guyana',
                        flag: 'GY '
                    },
                    {
                        id: 'Haiti',
                        text: 'Haiti',
                        flag: 'HT '
                    },
                    {
                        id: 'Honduras',
                        text: 'Honduras',
                        flag: 'HN '
                    },
                    {
                        id: 'Hong Kong',
                        text: 'Hong Kong',
                        flag: 'HK '
                    },
                    {
                        id: 'Hungary',
                        text: 'Hungary',
                        flag: 'HU '
                    },
                    {
                        id: 'Iceland',
                        text: 'Iceland',
                        flag: 'IS '
                    },
                    {
                        id: 'India',
                        text: 'India',
                        flag: 'IN '
                    },
                    {
                        id: 'Indonesia',
                        text: 'Indonesia',
                        flag: 'ID '
                    },
                    {
                        id: 'Iran',
                        text: 'Iran',
                        flag: 'IR '
                    },
                    {
                        id: 'Iraq',
                        text: 'Iraq',
                        flag: 'IQ '
                    },
                    {
                        id: 'Ireland',
                        text: 'Ireland',
                        flag: 'IE '
                    },
                    {
                        id: 'Isle of Man',
                        text: 'Isle of Man',
                        flag: 'IM '
                    },
                    {
                        id: 'Israel',
                        text: 'Israel',
                        flag: 'IL '
                    },
                    {
                        id: 'Italy',
                        text: 'Italy',
                        flag: 'IT '
                    },
                    {
                        id: 'Ivory Coast',
                        text: 'Ivory Coast',
                        flag: 'CI '
                    },
                    {
                        id: 'Jamaica',
                        text: 'Jamaica',
                        flag: 'JM '
                    },
                    {
                        id: 'Japan',
                        text: 'Japan',
                        flag: 'JP '
                    },
                    {
                        id: 'Jersey',
                        text: 'Jersey',
                        flag: 'JE '
                    },
                    {
                        id: 'Jordan',
                        text: 'Jordan',
                        flag: 'JO '
                    },
                    {
                        id: 'Kazakhstan',
                        text: 'Kazakhstan',
                        flag: 'KZ '
                    },
                    {
                        id: 'Kenya',
                        text: 'Kenya',
                        flag: 'KE '
                    },
                    {
                        id: 'Kiribati',
                        text: 'Kiribati',
                        flag: 'KI '
                    },
                    {
                        id: 'Kosovo',
                        text: 'Kosovo',
                        flag: 'XK '
                    },
                    {
                        id: 'Kuwait',
                        text: 'Kuwait',
                        flag: 'KW '
                    },
                    {
                        id: 'Kyrgyzstan',
                        text: 'Kyrgyzstan',
                        flag: 'KG '
                    },
                    {
                        id: 'Laos',
                        text: 'Laos',
                        flag: 'LA '
                    },
                    {
                        id: 'Latvia',
                        text: 'Latvia',
                        flag: 'LV '
                    },
                    {
                        id: 'Lebanon',
                        text: 'Lebanon',
                        flag: 'LB '
                    },
                    {
                        id: 'Lesotho',
                        text: 'Lesotho',
                        flag: 'LS '
                    },
                    {
                        id: 'Liberia',
                        text: 'Liberia',
                        flag: 'LR '
                    },
                    {
                        id: 'Libya',
                        text: 'Libya',
                        flag: 'LY '
                    },
                    {
                        id: 'Liechtenstein',
                        text: 'Liechtenstein',
                        flag: 'LI '
                    },
                    {
                        id: 'Lithuania',
                        text: 'Lithuania',
                        flag: 'LT '
                    },
                    {
                        id: 'Luxembourg',
                        text: 'Luxembourg',
                        flag: 'LU '
                    },
                    {
                        id: 'Macau',
                        text: 'Macau',
                        flag: 'MO '
                    },
                    {
                        id: 'Macedonia',
                        text: 'Macedonia',
                        flag: 'MK '
                    },
                    {
                        id: 'Madagascar',
                        text: 'Madagascar',
                        flag: 'MG '
                    },
                    {
                        id: 'Malawi',
                        text: 'Malawi',
                        flag: 'MW '
                    },
                    {
                        id: 'Malaysia',
                        text: 'Malaysia',
                        flag: 'MY '
                    },
                    {
                        id: 'Maldives',
                        text: 'Maldives',
                        flag: 'MV '
                    },
                    {
                        id: 'Mali',
                        text: 'Mali',
                        flag: 'ML '
                    },
                    {
                        id: 'Malta',
                        text: 'Malta',
                        flag: 'MT '
                    },
                    {
                        id: 'Marshall Islands',
                        text: 'Marshall Islands',
                        flag: 'MH '
                    },
                    {
                        id: 'Mauritania',
                        text: 'Mauritania',
                        flag: 'MR '
                    },
                    {
                        id: 'Mauritius',
                        text: 'Mauritius',
                        flag: 'MU '
                    },
                    {
                        id: 'Mayotte',
                        text: 'Mayotte',
                        flag: 'YT '
                    },
                    {
                        id: 'Mexico',
                        text: 'Mexico',
                        flag: 'MX '
                    },
                    {
                        id: 'Micronesia',
                        text: 'Micronesia',
                        flag: 'FM '
                    },
                    {
                        id: 'Moldova',
                        text: 'Moldova',
                        flag: 'MD '
                    },
                    {
                        id: 'Monaco',
                        text: 'Monaco',
                        flag: 'MC '
                    },
                    {
                        id: 'Mongolia',
                        text: 'Mongolia',
                        flag: 'MN '
                    },
                    {
                        id: 'Montenegro',
                        text: 'Montenegro',
                        flag: 'ME '
                    },
                    {
                        id: 'Montserrat',
                        text: 'Montserrat',
                        flag: 'MS '
                    },
                    {
                        id: 'Morocco',
                        text: 'Morocco',
                        flag: 'MA '
                    },
                    {
                        id: 'Mozambique',
                        text: 'Mozambique',
                        flag: 'MZ '
                    },
                    {
                        id: 'Myanmar',
                        text: 'Myanmar',
                        flag: 'MM '
                    },
                    {
                        id: 'Namibia',
                        text: 'Namibia',
                        flag: 'NA '
                    },
                    {
                        id: 'Nauru',
                        text: 'Nauru',
                        flag: 'NR '
                    },
                    {
                        id: 'Nepal',
                        text: 'Nepal',
                        flag: 'NP '
                    },
                    {
                        id: 'Netherlands',
                        text: 'Netherlands',
                        flag: 'NL '
                    },
                    {
                        id: 'Netherlands Antilles',
                        text: 'Netherlands Antilles',
                        flag: 'AN '
                    },
                    {
                        id: 'New Caledonia',
                        text: 'New Caledonia',
                        flag: 'NC '
                    },
                    {
                        id: 'New Zealand',
                        text: 'New Zealand',
                        flag: 'NZ '
                    },
                    {
                        id: 'Nicaragua',
                        text: 'Nicaragua',
                        flag: 'NI '
                    },
                    {
                        id: 'Niger',
                        text: 'Niger',
                        flag: 'NE '
                    },
                    {
                        id: 'Nigeria',
                        text: 'Nigeria',
                        flag: 'NG '
                    },
                    {
                        id: 'Niue',
                        text: 'Niue',
                        flag: 'NU '
                    },
                    {
                        id: 'North Korea',
                        text: 'North Korea',
                        flag: 'KP '
                    },
                    {
                        id: 'Northern Mariana Islands',
                        text: 'Northern Mariana Islands',
                        flag: 'MP '
                    },
                    {
                        id: 'Norway',
                        text: 'Norway',
                        flag: 'NO '
                    },
                    {
                        id: 'Oman',
                        text: 'Oman',
                        flag: 'OM '
                    },
                    {
                        id: 'Pakistan',
                        text: 'Pakistan',
                        flag: 'PK '
                    },
                    {
                        id: 'Palau',
                        text: 'Palau',
                        flag: 'PW '
                    },
                    {
                        id: 'Palestine',
                        text: 'Palestine',
                        flag: 'PS '
                    },
                    {
                        id: 'Panama',
                        text: 'Panama',
                        flag: 'PA '
                    },
                    {
                        id: 'Papua New Guinea',
                        text: 'Papua New Guinea',
                        flag: 'PG '
                    },
                    {
                        id: 'Paraguay',
                        text: 'Paraguay',
                        flag: 'PY '
                    },
                    {
                        id: 'Peru',
                        text: 'Peru',
                        flag: 'PE '
                    },
                    {
                        id: 'Philippines',
                        text: 'Philippines',
                        flag: 'PH '
                    },
                    {
                        id: 'Pitcairn',
                        text: 'Pitcairn',
                        flag: 'PN '
                    },
                    {
                        id: 'Poland',
                        text: 'Poland',
                        flag: 'PL '
                    },
                    {
                        id: 'Portugal',
                        text: 'Portugal',
                        flag: 'PT '
                    },
                    {
                        id: 'Puerto Rico',
                        text: 'Puerto Rico',
                        flag: 'PR '
                    },
                    {
                        id: 'Qatar',
                        text: 'Qatar',
                        flag: 'QA '
                    },
                    {
                        id: 'Republic of the Congo',
                        text: 'Republic of the Congo',
                        flag: 'CG '
                    },
                    {
                        id: 'Reunion',
                        text: 'Reunion',
                        flag: 'RE '
                    },
                    {
                        id: 'Romania',
                        text: 'Romania',
                        flag: 'RO '
                    },
                    {
                        id: 'Russia',
                        text: 'Russia',
                        flag: 'RU '
                    },
                    {
                        id: 'Rwanda',
                        text: 'Rwanda',
                        flag: 'RW '
                    },
                    {
                        id: 'Saint Barthelemy',
                        text: 'Saint Barthelemy',
                        flag: 'BL '
                    },
                    {
                        id: 'Saint Helena',
                        text: 'Saint Helena',
                        flag: 'SH '
                    },
                    {
                        id: 'Saint Kitts and Nevis',
                        text: 'Saint Kitts and Nevis',
                        flag: 'KN '
                    },
                    {
                        id: 'Saint Lucia',
                        text: 'Saint Lucia',
                        flag: 'LC '
                    },
                    {
                        id: 'Saint Martin',
                        text: 'Saint Martin',
                        flag: 'MF '
                    },
                    {
                        id: 'Saint Pierre and Miquelon',
                        text: 'Saint Pierre and Miquelon',
                        flag: 'PM '
                    },
                    {
                        id: 'Saint Vincent and the Grenadines',
                        text: 'Saint Vincent and the Grenadines',
                        flag: 'VC '
                    },
                    {
                        id: 'Samoa',
                        text: 'Samoa',
                        flag: 'WS '
                    },
                    {
                        id: 'San Marino',
                        text: 'San Marino',
                        flag: 'SM '
                    },
                    {
                        id: 'Sao Tome and Principe',
                        text: 'Sao Tome and Principe',
                        flag: 'ST '
                    },
                    {
                        id: 'Saudi Arabia',
                        text: 'Saudi Arabia',
                        flag: 'SA '
                    },
                    {
                        id: 'Senegal',
                        text: 'Senegal',
                        flag: 'SN '
                    },
                    {
                        id: 'Serbia',
                        text: 'Serbia',
                        flag: 'RS '
                    },
                    {
                        id: 'Seychelles',
                        text: 'Seychelles',
                        flag: 'SC '
                    },
                    {
                        id: 'Sierra Leone',
                        text: 'Sierra Leone',
                        flag: 'SL '
                    },
                    {
                        id: 'Singapore',
                        text: 'Singapore',
                        flag: 'SG '
                    },
                    {
                        id: 'Sint Maarten',
                        text: 'Sint Maarten',
                        flag: 'SX '
                    },
                    {
                        id: 'Slovakia',
                        text: 'Slovakia',
                        flag: 'SK '
                    },
                    {
                        id: 'Slovenia',
                        text: 'Slovenia',
                        flag: 'SI '
                    },
                    {
                        id: 'Solomon Islands',
                        text: 'Solomon Islands',
                        flag: 'SB '
                    },
                    {
                        id: 'Somalia',
                        text: 'Somalia',
                        flag: 'SO '
                    },
                    {
                        id: 'South Africa',
                        text: 'South Africa',
                        flag: 'ZA '
                    },
                    {
                        id: 'South Korea',
                        text: 'South Korea',
                        flag: 'KR '
                    },
                    {
                        id: 'South Sudan',
                        text: 'South Sudan',
                        flag: 'SS '
                    },
                    {
                        id: 'Spain',
                        text: 'Spain',
                        flag: 'ES '
                    },
                    {
                        id: 'Sri Lanka',
                        text: 'Sri Lanka',
                        flag: 'LK '
                    },
                    {
                        id: 'Sudan',
                        text: 'Sudan',
                        flag: 'SD '
                    },
                    {
                        id: 'Suriname',
                        text: 'Suriname',
                        flag: 'SR '
                    },
                    {
                        id: 'Svalbard and Jan Mayen',
                        text: 'Svalbard and Jan Mayen',
                        flag: 'SJ '
                    },
                    {
                        id: 'Swaziland',
                        text: 'Swaziland',
                        flag: 'SZ '
                    },
                    {
                        id: 'Sweden',
                        text: 'Sweden',
                        flag: 'SE '
                    },
                    {
                        id: 'Switzerland',
                        text: 'Switzerland',
                        flag: 'CH '
                    },
                    {
                        id: 'Syria',
                        text: 'Syria',
                        flag: 'SY '
                    },
                    {
                        id: 'Taiwan',
                        text: 'Taiwan',
                        flag: 'TW '
                    },
                    {
                        id: 'Tajikistan',
                        text: 'Tajikistan',
                        flag: 'TJ '
                    },
                    {
                        id: 'Tanzania',
                        text: 'Tanzania',
                        flag: 'TZ '
                    },
                    {
                        id: 'Thailand',
                        text: 'Thailand',
                        flag: 'TH '
                    },
                    {
                        id: 'Togo',
                        text: 'Togo',
                        flag: 'TG '
                    },
                    {
                        id: 'Tokelau',
                        text: 'Tokelau',
                        flag: 'TK '
                    },
                    {
                        id: 'Tonga',
                        text: 'Tonga',
                        flag: 'TO '
                    },
                    {
                        id: 'Trinidad and Tobago',
                        text: 'Trinidad and Tobago',
                        flag: 'TT '
                    },
                    {
                        id: 'Tunisia',
                        text: 'Tunisia',
                        flag: 'TN '
                    },
                    {
                        id: 'Turkey',
                        text: 'Turkey',
                        flag: 'TR '
                    },
                    {
                        id: 'Turkmenistan',
                        text: 'Turkmenistan',
                        flag: 'TM '
                    },
                    {
                        id: 'Turks and Caicos Islands',
                        text: 'Turks and Caicos Islands',
                        flag: 'TC '
                    },
                    {
                        id: 'Tuvalu',
                        text: 'Tuvalu',
                        flag: 'TV '
                    },
                    {
                        id: 'U.S. Virgin Islands',
                        text: 'U.S. Virgin Islands',
                        flag: 'VI '
                    },
                    {
                        id: 'Uganda',
                        text: 'Uganda',
                        flag: 'UG '
                    },
                    {
                        id: 'Ukraine',
                        text: 'Ukraine',
                        flag: 'UA '
                    },
                    {
                        id: 'United Arab Emirates',
                        text: 'United Arab Emirates',
                        flag: 'AE '
                    },
                    {
                        id: 'United Kingdom',
                        text: 'United Kingdom',
                        flag: 'GB '
                    },
                    {
                        id: 'United States',
                        text: 'United States',
                        flag: 'US '
                    },
                    {
                        id: 'Uruguay',
                        text: 'Uruguay',
                        flag: 'UY '
                    },
                    {
                        id: 'Uzbekistan',
                        text: 'Uzbekistan',
                        flag: 'UZ '
                    },
                    {
                        id: 'Vanuatu',
                        text: 'Vanuatu',
                        flag: 'VU '
                    },
                    {
                        id: 'Vatican',
                        text: 'Vatican',
                        flag: 'VA '
                    },
                    {
                        id: 'Venezuela',
                        text: 'Venezuela',
                        flag: 'VE '
                    },
                    {
                        id: 'Vietnam',
                        text: 'Vietnam',
                        flag: 'VN '
                    },
                    {
                        id: 'Wallis and Futuna',
                        text: 'Wallis and Futuna',
                        flag: 'WF '
                    },
                    {
                        id: 'Western Sahara',
                        text: 'Western Sahara',
                        flag: 'EH '
                    },
                    {
                        id: 'Yemen',
                        text: 'Yemen',
                        flag: 'YE '
                    },
                    {
                        id: 'Zambia',
                        text: 'Zambia',
                        flag: 'ZM '
                    },
                    {
                        id: 'Zimbabwe',
                        text: 'Zimbabwe',
                        flag: 'ZW '
                    },

                ];

                // console.log(isoCountries.length);

                function formatCountry(country) {
                    if (!country.id) {
                        return country.text;
                    }
                    var $country = $(
                        '<span class="flag-icon flag-icon-' +
                        country.flag.toLowerCase() +
                        ' flag-icon-squared"></span>' +
                        '<span class="flag-text">' +
                        country.text +
                        "</span>"
                    );
                    return $country;
                }

                //Assuming you have a select element with name country
                // e.g. <select name="name"></select>

                $("[name='nationality']").select2({
                    placeholder: "Select a country",
                    templateResult: formatCountry,
                    data: isoCountries
                });

                //Assuming you have a select element with name country
                // e.g. <select name="name"></select>

                $("[name='lead_country_code']").select2({
                    placeholder: "Select Code",
                    templateResult: formatCountry,
                    data: isoCountriesWithPhone
                });
            });
        })(jQuery);
    });

        // business activities enable toggle option
        document.addEventListener('DOMContentLoaded', function () {
        const toggleIcons = document.querySelectorAll('.toggle-icon');
        
        toggleIcons.forEach(icon => {
            icon.addEventListener('click', function () {
                const container = this.closest('.col-md-6').querySelector('.form-check-container');
                if (container.style.display === 'none') {
                    container.style.display = 'block';
                    this.textContent = '➖'; // Change to close icon
                } else {
                    container.style.display = 'none';
                    this.textContent = '➕'; // Change to extend icon
                }
            });
        });
    });
</script>
<style type="text/css">
    .lead_business_activity_div {
        display: none;
    }

    h6 {
        color: #4D77B2;
    }
    #infoblock .col-md-6 {
        display: flex;
        flex-direction: column;
    }

    .form-check-container {
        margin-top: 10px; /* Optional: Adds spacing when expanded */
        overflow: hidden; /* Ensures no extra spacing when collapsed */
    }
</style>
<script>
/*$(document).ready(function() {
    $('#lead_location').on('change', function() {
        let location = $(this).val();
        let $userDropdown = $('#lead_assigned_to');

        if (location === 'business_venue') {
            $userDropdown.prop('disabled', true).html('<option value="">Not Applicable</option>');
        } else {
            $.ajax({
                url: "<?php echo base_url('leads/lead/get_b2b_assigned_users'); ?>",
                type: 'POST',
                data: { location: location },
                dataType: 'json',
                success: function(response) {
                    $userDropdown.prop('disabled', false);
                    $userDropdown.empty().append('<option value="">-- Select User --</option>');
                    $.each(response, function(index, user) {
                        $userDropdown.append('<option value="' + user.user_id + '">' +
                            user.name + ' (' + user.employee_id + ')</option>');
                    });
                },
                error: function() {
                    $userDropdown.html('<option value="">No users found</option>');
                }
            });
        }
    });
});*/
</script>