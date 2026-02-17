<div class="clearfix" style="clear:both"></div>
<section class="page-title" style="background:url('<?php echo base_url(); ?>assets/images/bgtitle.png') repeat-x;">
    <div class="pattern-layer"></div>
    <div class="auto-container">
        <div class="content-box">
            <div class="title-box centred">
            </div>
            <ul class="bread-crumb clearfix" style="margin: 10px;">
            </ul>
        </div>
    </div>
</section>
<section id="contact" class="contact" data-scroll-index="8">
   
    <div class="part-bottom" style="margin-top: 50px;background: #fff;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-lg-offset-3">
                  <div class="jumbotron text-center">
                    <h1 class="">Thank You!</h1>
                    <p class="lead"><strong>Please wait for 2 minutes, this contact will be created in Zoho SalesIQ.</strong></p>
                    <hr>
                    <p class="lead">
                      <a class="btn btn-danger" href="https://crm.ontimegroup.com/" role="button">Continue to homepage</a>
                    </p>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $contactnumber = $_GET["contactnumber"]; 
      $contactname = $_GET["contactname"];
      $contactemail = $_GET["contactemail"];
?>
   
<script>window.$zoho=window.$zoho || {};$zoho.salesiq=$zoho.salesiq||{ready:function(){}}</script><script id="zsiqscript" src="https://salesiq.zohopublic.com/widget?wc=siq39d5e11f207a09840af1fc335e7ac26586a62d9adceb82433a8e91ff5693349b" defer></script>

<script>
    var contactnumber = "<?php echo trim($contactnumber); ?>";
    if(contactnumber.indexOf('+') == -1){
        contactnumber = "+" + contactnumber;
    }
    var contactname = "<?php echo $contactname; ?>";
    var contactemail = "<?php echo $contactemail; ?>";

    $zoho.salesiq.reset();
    $zoho.salesiq.ready=function()
    {
        $zoho.salesiq.visitor.contactnumber(contactnumber);
        $zoho.salesiq.visitor.email(contactemail);   
        $zoho.salesiq.visitor.name(contactname);
    }
</script>