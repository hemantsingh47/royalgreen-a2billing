<?php
include 'lib/customer.defines.php';
include 'lib/customer.module.access.php';
include 'lib/Form/Class.FormHandler.inc.php';
include 'lib/customer.smarty.php';
//$inst_table = new Table();
$DBHandle = DbConnect();

$addvalue = new Table("cc_card","*");

$currencies_list = get_currencies();


$smarty->display('signup_header.tpl');


//FOR COUNTRY NAME
$country_table = new Table('cc_country','countryname,countrycode');
$country_clause = null;
$country_result = $country_table -> Get_list($DBHandle, $country_clause, 0);
?>

<html lang="en">

	<!-- begin::Head -->
	<head>
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
			WebFont.load({
				google: {
					"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
				},
				active: function() {
					sessionStorage.fonts = true;
				}
			});
        </script>

<script language="javascript">                                                                                
    function pay_validation()
    {
         if(document.myform.firstname.value=="")
        {
        alert("Please Enter Your Name");
        return false;
        }
        if ((document.myform.lastname.value.length < 2) )
        {
        alert("Please Enter The At Least 2 Character In Your Name");
        return false;
        }
        
        if(document.myform.email.value=="")
        {
        alert("Please Enter Email");
        return false;
        }
        
        
        if(document.myform.phone.value=="")
        {
        alert("Please Enter Your Phone Number");
        return false;
        }
        if ((document.myform.phone.value.length < 8) )
        {
        alert("Please Enter The At Least 8 Digit Phone Number");
        return false;
        }
           return true;
        }
        
        function validate(evt) 
        {
            var theEvent = evt || window.event;
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode( key );
            var regex = /[0-9]|\./;
            if(!regex.test(key)) 
            {
                theEvent.returnValue = false;
                if(theEvent.preventDefault) theEvent.preventDefault();
                alert("Please Enter Numbers Only");
            }
        }
        
</script>

        <!--begin::Global Theme Styles(used by all pages) -->
        <link href="templates/default/newtheme/theme/classic/assets/css/demo1/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="templates/default/newtheme/theme/classic/assets/css/demo12/pages/general/login/login-2.css" rel="stylesheet" type="text/css" />
    </head>
    <body style=" background:#e1e1ef;">
<!-- begin:: Content -->
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
<div class="col-md-6" style="margin: 0 auto;">
		<!--begin::Portlet-->
		<div class="kt-portlet">
			<div class="kt-portlet__head">
				<div class="kt-portlet__head-label">
                    <div class="kt-portlet__head-title" style="text-align:center;"><?php echo gettext("SIGNUP HERE"); ?></div>
				</div>
			</div>
			<!--begin::Form-->
			<form name="myform" action="./customer_check.php" method="post" onSubmit="return secure_validation();">
			 <input type="hidden" name="currency" value="USD">	
					<input type="hidden" name="languag" value="en" />
					<div class="kt-portlet__body">
					<div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="firstname">First Name <span style="color:red">*</span></label>
						<!-- <font class="col-lg-2 col-sm-12"><?php echo gettext("Title");?> </font> -->
                        <div class="col-lg-7 col-md-9 col-sm-12">
						    <input type="text" name="firstname" class="form-control" id="firstname" required="Please Enter the First Name" placeholder="Enter First name here">
						    <span class="form-text text-muted">Please enter your First Name here.</span>
                        </div>  
                        <div class="col-lg-1"></div>  
                    </div>
                    
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="lastname">Last Name <span style="color:red">*</span></label>
                        <div class="col-lg-7 col-md-9 col-sm-12">
						    <input type="text" name="lastname" class="form-control" id="lastname" required="Please Enter the Last Name" placeholder="Enter Last name here">
						    <span class="form-text text-muted">Please enter your Last Name here.</span>
                        </div>   
                        <div class="col-lg-1"></div> 
                    </div>
                    
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="email">E-mail <span style="color:red">*</span></label>
                        <div class="col-lg-7 col-md-9 col-sm-12">
						    <input type="text" name="email" class="form-control" id="email" required="Please Enter the E-mail" placeholder="Enter E-mail here">
                            <span class="form-text text-muted">Please enter your e-mail here.</span>
                            <font style="font-size:13px; font-weight:bold; color:#FF0000;"><?php if((strcmp($msg,"")!=0)){ echo "<br />".$msg; }?></font>
                        </div>    
                        <div class="col-lg-1"></div>
                    </div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="country">Country </label>
                        <div class="col-lg-7 col-md-9 col-sm-12">
                            <select name="country" id="country" class="form-control">
                                <?php     
						            for($i=0;$i<count($country_result);$i++)
						            {
						                ?>
						                    <option value="<?php echo $country_result[$i]['countrycode'] ?>"><?php echo $country_result[$i]['countryname'] ?></option>
						                <?php
						            }
						        ?>
                            </select>
                            
                        </div> 
                        <div class="col-lg-1"></div>   
					</div>
                    
                    <div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="lastname">Phone <span style="color:red">*</span></label>
                        <div class="col-lg-7 col-md-9 col-sm-12">
						    <input type="text" name="phone" class="form-control" id="phone" required="Please Enter the Phone" placeholder="Enter Last name here">
						    <span class="form-text text-muted">Please enter your Phone here.</span>
                        </div>    
                        <div class="col-lg-1"></div>
                    </div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="state">State </label>
                        <div class="col-lg-7 col-md-9 col-sm-12">
						    <input type="text" name="state" class="form-control" id="state" required="Please Enter the State" placeholder="Enter Last name here">
						    <span class="form-text text-muted">Please enter your State here.</span>
                        </div>    
                        <div class="col-lg-1"></div>
                    </div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="state">City </label>
                        <div class="col-lg-7 col-md-9 col-sm-12">
						    <input type="text" name="city" class="form-control" id="city" required="Please Enter the City" placeholder="Enter Last name here">
						    <span class="form-text text-muted">Please enter your City here.</span>
                        </div> 
                        <div class="col-lg-1"></div>   
                    </div>

                    <div class="form-group row">
                    <div class="col-lg-1"></div>
                        <label class="col-lg-3 col-sm-12" for="zipcode">ZipCode </label>
                        <div class="col-lg-7 col-md-9 col-sm-12">
						    <input type="text" name="zipcode" class="form-control" id="zipcode" required="Please Enter Zipcode" placeholder="Enter Last name here">
						    <span class="form-text text-muted">Please enter your ZipCode here.</span>
                        </div>  
                        <div class="col-lg-1"></div>  
                    </div>
				</div>

                <div align="center" class="kt-portlet__foot">
                <div class="form-actions">
                    <input type="submit" name="submit123" value="Submit" class="btn btn-brand" >&nbsp;&nbsp;
                    <input type="reset" name="clear" value="Clear" class="btn btn-secondary">
                </div>
                </div>
			</form>
			<!--end::Form-->			
		</div>
		
		<div class="uk-margin-top uk-text-center">
            <a href="#" id="signup_form_show" style="display: none;">Create an account</a>
        </div>
		<!--end::Portlet-->
	</div>

	<!-- common functions -->
    <script src="templates/default/theme/assets/js/common.js"></script>
    <script src="templates/default/theme/assets/js/uikit_custom.js"></script>
    <script src="templates/default/theme/assets/js/altair_admin_common.js"></script>
    <script src="templates/default/theme/bower_components/datatables/media/js/jquery.dataTables.js"></script>
    <script src="templates/default/theme/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
    <script src="templates/default/theme/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
    <script src="templates/default/theme/assets/js/custom/datatables_uikit.js"></script>
    <script src="templates/default/theme/assets/js/pages/plugins_datatables.js"></script>
 
    
<script type="text/javascript" >
        $(document).ready(function() {
        $("#ui_language").change(function(){
          self.location.href= "?ui_language="+$("#ui_language option:selected").val();
        });
        });
</script>
<!-- google web fonts -->
    <script>
        WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>
    <script>
        $(function() {
            if(isHighDensity) {
                // enable hires images
                altair_helpers.retina_images();
            }
            if(Modernizr.touch) {
                // fastClick (touch devices)
                FastClick.attach(document.body);
            }
        });
    </script>
</body>
</html>

