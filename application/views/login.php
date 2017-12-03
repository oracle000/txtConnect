<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en" ng-app="txtconnectApp">
<title>Astral - Login</title>
<head>
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css">
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/angular.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/angular-login.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<script type="text/javascript">
		$(function(){
			$(".inputloginanimate input").focus(function(){
				//$(this).parent('span').siblings('span.inputloginlbl').css('color','skyblue');
				$(this).parent('span').siblings('span.inputloginlbl').animate({
					'marginTop':'-20px',
					'font-size':'12px'
				});
			});
			$(".inputloginanimate input").focusout(function(){
				//$(this).parent('span').siblings('span.inputloginlbl').css('color','skyblue');
				if($(this).val() == "" || $(this).val == " "){
					$(this).parent('span').siblings('span.inputloginlbl').animate({
						'marginTop':'0px',
						'font-size':'16px'
					});
				}
			});
		});
	</script>
</head>
<body>
	<div id="container" ng-controller="appcontroller">
		<div id="loginwrapper">
			<div id="dialog" style="display:none"><p></p></div>
			<div id="logincontainer">
				<form name="loginform">
				<span class="compname">Astral</span>
				<span class="compsystem">SMS InfoBlast</span>
				<span class="inputloginanimate">
					<span class="inputloginlbl">Username</span>
					<span class="inputlogin"><input type="text" name="username" ng-model="user.name" id="usernamelogin" /></span>
					<span class="inputerror">*Required</span>
				</span>
				<span class="inputloginanimate">
					<span class="inputloginlbl">Password</span>
					<span class="inputlogin"><input type="password" ng-model="user.pass" id="passwordlogin" /></span>
					<span class="inputerror">*Required</span>
				</span>
				<span class="loginbtnwrapper">
					<button class="login" ng-click="login(user)">LOGIN</button>
				</span>
				<span class="loginbtnwrapper" style="color:white;font-size:12px;">version 1.2</span>
				 </form>
			</div>
		</div>
	</div>
</body>
</html>