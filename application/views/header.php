<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en" ng-app="txtconnectApp">
<head>
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
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/section-content-panel.css">
	<!--<link rel="stylesheet" href="<?php echo base_url(); ?>css/chosen.css">-->
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/nprogress.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.datetimepicker.full.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/select2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/angular.min.js"></script>
	
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.nice-select.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/fastclick.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/prism.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.ripple.js"></script>
	
	<script type="text/javascript">
		$(document).ready(function(){
			
			$("#home[data-ripple],#msg[data-ripple],#pbook[data-ripple],#users[data-ripple]").ripple();
			$("#droppb, #dropmsg, #smsoption, #adminflag").niceSelect();
			NProgress.configure({ showSpinner: true });
			NProgress.start();
			$('#nprogress .bar').css({'background': '#16a085'});
			NProgress.done();
			$("div#hdrtabs span").click(function(){
				NProgress.start();
				$("div#hdrtabs span").each(function(){
					$(this).css('border-bottom','4px solid transparent');
				});
				$(this).css('border-bottom','4px solid #ff7979');
				NProgress.done();
				
			});
			$("#number input, #msgwrap textarea, #labelform input").focus(function(){
				$(this).css('color','black');
				$(this).siblings('span').css('color','skyblue');
				$(this).siblings('span').animate({
					'marginTop':'-20px',
					'font-size':'12px'
				},100);
			});
			$("#number input, #msgwrap textarea, #labelform input").focusout(function(){
				if($(this).val() == ""){
					$(this).siblings('span').animate({
						'marginTop':'0px',
						'font-size':'16px',
						'color':'silver'
					},100);
				}
			});
			$("#datetimepicker").datetimepicker({
				//formatDate:'Y/m/d',
				//formatTime:'H:i',
				format:'Y/m/d H:i',
				minDateTime: new Date('2017,06,04,15:00'),
				minDate : '-1970/01/01',
				value: '<?php echo date("Y/m/d H:i",strtotime(date("H:i")."+ 10 minutes")); ?>'
				//minTime : '14:00' //''
			});
			$("#datetimepicker").focusout(function(){
				if($(this).val() == ""){
					$(this).val("<?php echo date("Y/m/d H:i",strtotime(date("H:i")."+ 10 minutes")); ?>");
				}
			});
			$("#number input").keypress(function(e){
				/*if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
					return false;
				}
				*/
			});

			$("#username").keypress(function(event){
				var k = event ? event.which : window.event.keyCode;
				if (k == 32) return false;
			});
			//window.open('http://facebook.com','testing','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=yes, copyhistory=no, width=w, height=h, top=top, left=left');
		});
		
	</script>
	<title>Astral - SMS InfoBlast</title>

	<style type="text/css">
	
	</style>
</head>
<body>

<div id="container" ng-controller="appcontroller">
	<div id="dialog"><p></p></div>
	