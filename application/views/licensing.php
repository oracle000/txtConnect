<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en" ng-app="txtconnectApp">
<head>
	<title>Astral - TxtConnect</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/nprogress.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.ripple.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/nice-select.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/select2.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/font-awesome.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.datetimepicker.css">
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>css/chosen.css">-->
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/nprogress.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.full.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/select2.min.js"></script>
	
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.nice-select.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/fastclick.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/prism.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.ripple.js"></script>
	
	<script type="text/javascript">
		$(function(){
			$("#showallgroupuserswrapper").fadeIn();
			$("#custdialogsave").click(function(){
				var licensekey = $("#licensekey").val();
				$(".errormsgs").html("");
				$.ajax({
					url:"<?php echo base_url(); ?>app/licensing",
					type:"POST",
					data:{licensekey:licensekey},
					dataType:"json",
					success:function(e){
						if(e.status == 1){
							$(".errormsgs").html("License Successfully Validated!");
							$(".errormsgs").css('color','green');
							setTimeout(function(){ 
								window.location = "<?php echo base_url(); ?>";
							}, 3000);
						}
						else{
							$(".errormsgs").html("Invalid License!!!");
							$(".errormsgs").css('color','red');
						}
					}
				});
			});
		});
	</script>
	

	<style type="text/css">
	
	</style>
</head>
<body>

<div id="container" ng-controller="appcontroller">
	<div id="dialog"><p></p></div>
	
	
	<div id="header">
		<span id="logocontainer">Mount Grace</span>
		<span id="logosignature">
			TxtConnect
		</span>
	
	</div>
	<div id="showallgroupuserswrapper" style="">
	<div id="showallgroupusers" style="height:300px;">
		<div id="custdialogtitle">
			<span style="float:left;">TxtConnect Licensing Service</span>
			<button type="button" ng-click="closepbgroup()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
				<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
				<span class="ui-button-icon-space" ></span>Close
			</button>
		</div>
		<!--<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;">Search:<input type="text" id="searchinput" ng-model="usercontactsearch" /></div>-->
		<div id="custdialogbody" style="width:100%;float:left;height:190px;">
			<table id="tbldialog">
				<tbody>
					<tr>
						<td colspan=2>Welcome to TxtConnect</td>
					</tr>
					<tr>
						<td colspan=2>Its time to insert your License Key. It should be given to you</td>
					</tr>
					<tr>
						<td colspan=2>whos in charge of installation of TxtConnect Web Application.</td>
					</tr>
					<tr>
						<td colspan=2>It Looks similar to this:</td>
					</tr>
					<tr>
						<td colspan=2><strong>XXXX-XXXX-XXXX-XXXX</strong></td>
					</tr>
					<tr>
						<td>License Key:</td>
						<td><input type="text" value="" id="licensekey" /></td>
					</tr>
					<tr>
						<td colspan=2 class="errormsgs" style="font-family:arial;"></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="custialogbtn">
			<button id="custdialogsave" >Ok</button>
			<!--<button id="custdialogcancel" >Later</button>-->
		</div>
	</div>
	</div>
</div>
</body>
</html>

