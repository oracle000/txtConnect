

/*	Application Module */
var txtconnectApp = angular.module('txtconnectApp',[]);
txtconnectApp.controller("appcontroller",['$scope','$http','$interval','$location','$anchorScroll', '$timeout',function($scope, $http, $interval, $location, $anchorScroll, $timeout){
	
	
	
	/* App Functions */
	/* Phonebook */
	function getphonebook(){
		$http.post('getphonebook').success(function(data){
			$scope.phonebook = data;
			//console.log(data);
		});
	}
	
	/* Phonebook */
	function getgroups(){
		$http.post('getgroups').success(function(data){
			$scope.groups = data;
		});
	}
	
	
	/* Last Messages */
	function getmsgdtls(){
		$http.post('getmsgdtls').success(function(data){
			//console.log(data);
			$scope.msgdtls = data;
			console.log(data);
		});
	}
	
	/* Get Report */
	function getrptdtls(){
		$http.post('getrptdtls').success(function(data){
			//console.log(data);
			$scope.rptdtls = data;
		});
	}
	
	//Report Details

	
	/* Add Button Display */
	function displayaddbtn(){
		
		if($scope.tab == 'home'){
			$("#addnew").fadeOut(500);
		}else if($scope.tab == 'rptgrptab'){
			$("#addnew").fadeOut(500);
		}else if($scope.tab == 'msg'){
			$("#addnew").fadeIn(500);
		}else if($scope.tab == 'users'){
			$("#addnew").fadeIn(500);
		}else{
			$("#addnew").fadeIn(500);
		}
	}
	
	
	
	/* Dropdown Phonebook */
	
	$scope.phonebookselect = {};
	//$scope.pb.id = 0;
	$scope.pb = {};
	$scope.user = {};
	$scope.pbtitle = 'Add New Phonebook';
	$scope.tab = 'home';
	displayaddbtn();
	
	$scope.phonebookselect = [
		{value:1, name:'Phonebook'},
		{value:2, name:'Groups'}
	];
	
	$scope.adminflagselect = [
		{value:0, name:'No'},
		{value:1, name:'Yes'}
	];
	
	$scope.user.adminflag = $scope.adminflagselect[0].value;
	
	$scope.smsoptions = [
		{value:1, name:'Simple SMS'},
		{value:2, name:'Group Messages'}
	];
	
	$scope.smsoptions2 = [
		{value:2, name:'All SMS'}
	];
	
	
	
	$scope.changesmsoptions2 = $scope.smsoptions2[0].value;

	$scope.changepbmodel = $scope.phonebookselect[0].value;
	
	$scope.changesmsoption = $scope.smsoptions[0].value;
	
	
	
	/* Change Event Handler */
	$scope.changepb = function(){
		if($scope.changepbmodel == 1){
			$("#pbcontainer, #phonebook").fadeIn(500);
			$("#msgcontainer, #messages, #newsms, #newmsg, #reportgroup, #newphonebook, #pbgroupcontainer").hide();
			$scope.tab = 'pb';
			displayaddbtn();
			getphonebook();
		}else{
			$("#pbgroupcontainer, #phonebook").fadeIn(500);
			$("#msgcontainer, #messages, #newsms, #newmsg, #reportgroup, #newphonebook, #pbcontainer ").hide();
			$scope.tab = 'pb';
			displayaddbtn();
			getgroups();
		}
	};
	/* End Change Event Handler */
	
	/* End Dropdown Phonebook */
	
	function isloggedin(){
		$http.post("isloggedinjs").success(function(data){
			//console.log(data);
			if(data.loggedin == '1'){
				return true;
			}else{
				window.location = "admin";
				return false;
			}
		});
	}
	
	
	
	$scope.msgtab = function(){
		isloggedin();
		$("#newusers").fadeOut();
		$("#messages").fadeIn(500);
		$scope.showmsgdtls();
		$scope.tab = 'msg';
		displayaddbtn();
		getmsgdtls();
		
	};
	
	$scope.pbtab = function(){
		isloggedin();
		if($scope.changepbmodel == 1){
			$("#pbcontainer, #phonebook").fadeIn(500);
			$("#newusers, #groupmsg, #msgcontainer, #messages, #newsms, #newmsg, #reportgroup, #newphonebook, #newgroup, #reportgroup,  #userscontainer").hide();
			$scope.tab = 'pb';
			displayaddbtn();
			getphonebook();
		}else{
			$("#pbgroupcontainer, #phonebook").fadeIn(500);
			$("#newusers, #groupmsg, #msgcontainer, #messages, #newsms, #newmsg, #reportgroup, #newphonebook, #pbcontainer, #newgroup, #newusers, #userscontainer, #reportgroup").hide();
			$scope.tab = 'pb';
			displayaddbtn();
			getgroups();
		}
	};
	$scope.selectuserphonebook = [];
	
	
	$scope.rptgrptab = function(){
		isloggedin();
		$("#reportgroup, #newsms").fadeIn(500);
		$("#groupmsg, #msgcontainer, #messages, #newmsg, #pbcontainer, #phonebook, #newphonebook, #pbgroupcontainer, #newgroup, #newusers, #userscontainer").hide();
		$scope.titlelabel = "Report Groups";
		$scope.tab = 'rptgrptab';
		displayaddbtn();
		getrptdtls();
	};
	
	$scope.hometab = function(){
		$scope.tab = 'home';
		displayaddbtn();
		getphonebook();
	}
	
	$scope.usertab = function(){
		isloggedin();
		$scope.tab = 'users';
		$scope.titlelabel = "Users Masterfile";
		$("#groupmsg, #msgcontainer, #messages, #newmsg, #pbcontainer, #phonebook, #newphonebook, #pbgroupcontainer, #newgroup, #newusers, #reportgroup").hide();
		$("#userscontainer, #newsms").fadeIn(500);
		displayaddbtn();
		getallusers();
	}
	
	
	$scope.sendsms = function(e){
		//var url = 'http://172.16.82.95:7080/sendsms?phone='+e['number']+'&text='+e['msg']+'&password=emman';
		//console.log($scope.getautoinput());
		(angular.isUndefined(e.number)) ? e.number = $scope.dataselectnumber[0].id : e.number = e.number;
		//console.log(e);
		
		
		NProgress.start();
		
		//console.log(e.number)
		//console.log(e);
		$http.post("sendsms",{"scheduled":e.scheduled,"datetime":e.datetime,"type":$scope.changesmsoption,"number":e.number,"msg":e.msg}).success(function(data){
			$("#dialog").dialog({
				modal: true,
				title: 'Message Added to Queue',
				buttons: {
					Ok: function() {
					  $( this ).dialog( "close" );
					  $scope.msgtab();
					}
				}
			});
			$("#dialog p").html("Your Message has been added to queue");
			NProgress.done();
			/*
			console.log(data);
			$scope.sms.msg = "";
			if(data.status == '1'){
				$("#dialog").dialog({
					modal: true,
					title: 'Message Sent!!',
					buttons: {
						Ok: function() {
						  $( this ).dialog( "close" );
						  $scope.msgtab();
						}
					}
				});
				$("#dialog p").html("Message Successfully Send");
			}else if(data.status == '2'){
				$("#dialog").dialog({
					modal: true,
					title: 'Schedule Message Added',
					buttons: {
						Ok: function() {
						  $( this ).dialog( "close" );
						  $scope.msgtab();
						}
					}
				});
				$("#dialog p").html("Your Message are now successfully added to scheduled messages!!");
			}else{
				$("#dialog").dialog({
					modal: true,
					title: 'Sorry Message Not Sent!!',
					buttons: {
						Ok: function() {
						  $( this ).dialog( "close" );
						  $scope.msgtab();
						}
					}
				});
				$("#dialog p").html("SMS Gateway is either one of the ff: <ul><li>No Signal</li><li>Insufficient Balance</li><li>SMS Gateway is turn off</li></ul>It will be schedule to send when SMS Gateway is available");
			}
			NProgress.done();
			*/
		});
		
		
	};
	
	/* ADD NEW BUTTON */
	$scope.group = {};
	$scope.titlelabel = "New Phonebook Group";
	$scope.addnew = function(){
		//new msg
		if($scope.tab == 'msg'){
			$("#groupmsg, #pbcontainer, #msgcontainer, #messages, #phonebook, #newusers, #newgroup, #pbgroupcontainer, #userscontainer, #reportgroup").hide();
			$scope.titlelabel = "New SMS";
			$scope.getautoinput();
			$("#newsms, #newmsg").fadeIn(500);
			
		}
		else if($scope.tab == 'pb' && $scope.changepbmodel == '1'){
			$scope.pbtitle = 'Add New Phonebook';
			$scope.pb.id = 0;
			$scope.pb.number = "";
			$scope.pb.name = "";
			//console.log($scope.pb.id);
			$("#newphonebook").fadeIn(500);
			$("#groupmsg, #pbcontainer, #msgcontainer, #messages, #phonebook, #newsms, #newmsg, #newgroup, #pbgroupcontainer, #newgroup, #newusers, #userscontainer, #reportgroup").hide();
		}
		else if($scope.tab == 'pb' && $scope.changepbmodel == '2'){
			$scope.titlelabel = 'Add New Phonebook Group';
			$scope.group.id = 0;
			$scope.group.name = "";
			$("#newgroup").fadeIn(500);
			$("#groupmsg, #pbcontainer, #msgcontainer, #messages, #phonebook, #newsms, #newmsg, #newphonebook, #pbgroupcontainer, #newusers, #userscontainer, #reportgroup").hide();
		}else if($scope.tab == 'users'){
			$scope.user = {};
			$scope.titlelabel = 'Add New Users';
			$scope.user.id = 0;
			$scope.user.fullname = "";
			$scope.user.name = "";
			$scope.user.password = "";
			$scope.user.adminflag = 0;
			$scope.saveusersform.$setUntouched();
			$("#groupmsg, #pbcontainer, #msgcontainer, #messages, #phonebook, #newsms, #newmsg, #newphonebook, #pbgroupcontainer, #userscontainer, #reportgroup").hide();
			$("#newusers").fadeIn(500);
			//newusers
		}
			//newgroup
		//console.log($scope.changepbmodel);
	}
	
	/* REPORT DETAILS  */
	$scope.reportid;
	
	$scope.reportdetails = function(e){
		$http.post("showreportdetails",{"id":e}).success(function(f){
			$("#wrappercustomdialog, #showreportdetails").fadeIn(500);
			$scope.reportusers = f[0];
			$scope.selectcheck = f[1];
			$scope.reportname = f[2].name;
			//console.log(f[1]);
			$scope.reportid = e;
		});
	}
	
	/* REPORT SAVE SELECTED */
	$scope.savereportusers = function(e){
		$http.post("selecteduserreport",{"reportusers":e,"reportid":$scope.reportid}).success(function(f){;
			$("#wrappercustomdialog, #showreportdetails").fadeOut();
			//console.log(f);
			getrptdtls();
		});
	}
	
	/* CLOSE CUSTOM DIALOG */
	$scope.closecustomdialog = function(){
		$("#wrappercustomdialog, #showreportdetails, #showreportaccess").fadeOut(500);
	}
	
	/* SAVE PHONEBOOK */
	$scope.savepb = function(e){
		$http.post("savephonebook",{"id":$scope.pb.id,"number":e.number,"name":e.name}).success(function(f){
			if(f == '1'){
				$("#dialog").dialog({
					modal: true,
					title: 'Phonebook Saved',
					buttons: {
						Ok: function() {
						  $( this ).dialog( "close" );
						  $scope.pbtab();
						}
					}
				});
				$("#dialog p").html("Number Successfully Saved!!!");
				$scope.pb.number = "";
				$scope.pb.name = "";
			}
		});
	}
	
	
	/* EDIT PHONE BOOK */
	$scope.editpb = function(e){
		
		$http.post("getphonebookdetails",{"id":e}).success(function(f){
			$scope.pbtitle = 'Edit Phonebook';
			$scope.pb.number = f.number;
			$scope.pb.name = f.name;
			$scope.pb.id = e;
			$("#newphonebook").fadeIn(500);
			$("#pbcontainer, #msgcontainer, #messages, #phonebook, #newsms, #newmsg").hide();
			//$("#pbname").focus();
			$("#pbname").css('color','black');
			$("#pbname").siblings('span').css('color','skyblue');
			$("#pbname").siblings('span').animate({
				'marginTop':'-20px',
				'font-size':'12px'
			},100);
			$("#pbnumber").css('color','black');
			$("#pbnumber").siblings('span').css('color','skyblue');
			$("#pbnumber").siblings('span').animate({
				'marginTop':'-20px',
				'font-size':'12px'
			},100);
		});
		
	}
	
	
	/* GET AUTO COMPLETION INPUT */
	$scope.dataselectnumber = {};
	$scope.sms = {};
	
	
	$scope.getautoinput = function(){
		$http.post("getautoinput",{"id":$scope.changesmsoption}).success(function(e){
			if($scope.changesmsoption == 1){
				$('#selectgroup').empty();
				$scope.dataselectnumber = e;
				//console.log(e);
				$("#selectgroup").select2({
					data:e,
					selectOnBlur:true,
					tags:true
				})
				.on('select2:open', function(f){
					$("span.inputanimateselect").css('color','black');
					$("span.inputanimateselect").css('color','skyblue');
					$("span.inputanimateselect").animate({
						'marginTop':'-20px',
						'font-size':'12px'
					},100);
				});
			}else{
				$('#selectgroup').empty();
				
				$("#selectgroup").select2({
					data:e
				})
				.on('select2:open', function(f){
					$("span.inputanimateselect").css('color','black');
					$("span.inputanimateselect").css('color','skyblue');
					$("span.inputanimateselect").animate({
						'marginTop':'-20px',
						'font-size':'12px'
					},100);
				});
				$scope.dataselectnumber = e;
			}
		});
		$scope.sms.number = $scope.dataselectnumber.id;
	}
	
	/* ADD USERS TO GROUP */
	$scope.usergroup = {};
	$scope.selectusergroup = {};
	$scope.usergroupid = 0;
	$scope.addusergroup = function(e){
		$http.post("showusergroup",{"id":e}).success(function(f){
			$("#wrappercustomdialog, #showallphonebook").fadeIn(500);
			$scope.usergroupid = e;
			$scope.usergroup = f[0];
			$scope.selectusergroup = f[1];
		});
	}
	
	$scope.checkbindusergroup = function(e){
		return $scope.selectusergroup[e];
		//console.log($scope.deletemsgid['1010']);
	}
	$scope.checkbindselectcheck = function(e){
		return $scope.selectcheck[e];
		//console.log($scope.deletemsgid['1010']);
	}
	$scope.bindusergroup = function(e){
		$scope.selectusergroup[e] = $scope.selectusergroup[e];
		//console.log(e);
		//console.log($scope.deletemsgid['1010']);
	}

	
	/* SAVE USERS TO GROUP */
	$scope.saveusergroup = function(e){
		$http.post("saveusergroup",{"id":$scope.usergroupid,"data":e}).success(function(f){
			$("#wrappercustomdialog, #showallphonebook").fadeOut(500);

		});
	}
	/* DELETE GROUP */
	$scope.deletegroup = function(id){
		$("#dialog").dialog({
			modal: true,
			title: 'Delete Group',
			buttons: {
				Yes: function() {
					$http.post("deletegroups",{"id":id}).success(function(f){
						$scope.pbtab();
					});
					$( this ).dialog( "close" );
				}
			}
		});
		$("#dialog p").html("Are you sure you want to delete this group?");
	}
	
	/* ADD NEW GROUP */
	$scope.savegroup = function(e){
		//console.log(e);
		$http.post("addnewgroup",{"id":e.id,"name":e.name}).success(function(f){
			var title;
			var desc;
			//console.log(f);
			if(f.status == '0'){
				title = "Record Exist!!";
				desc = "Record with the same Group Name Exist";
			}else if(f.status == '1' && e.id == '0'){
				title = "Record Successfully Saved!!";
				desc = "Record Successfully Added to Groups";
			}else if(f.status == '1' && e.id != '0'){
				title = "Record Successfully Saved!!";
				desc = "Record Successfully Updated";
			}else{
				title = "Ooops!";
				desc = "Something went wrong. Please reload the page";
			}
			
			$("#dialog").dialog({
				modal: true,
				title: title,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
						$scope.pbtab();
					}
				}
			});
			$("#dialog p").html(desc);
		});
	};
	
	/* EDIT USER GROUP */
	$scope.editusergroup = function(e){
		$scope.group.id = e;
		$http.post("getgroupname",{"id":e}).success(function(f){
			$scope.group.name = f.name;
			$("#pbgroupname").focus();
			$("#pbgroupname").css('color','black');
			$("#pbgroupname").siblings('span').css('color','skyblue');
			$("#pbgroupname").siblings('span').animate({
				'marginTop':'-20px',
				'font-size':'12px'
			},100);
			$("#pbgroupname").css('color','black');
			$("#pbgroupname").siblings('span').css('color','skyblue');
			$("#pbgroupname").siblings('span').animate({
				'marginTop':'-20px',
				'font-size':'12px'
			},100);
			$("#newgroup").fadeIn(500);
			
			$("#groupmsg, #pbcontainer, #msgcontainer, #messages, #phonebook, #newsms, #newmsg, #newphonebook, #pbgroupcontainer").hide();
		});
	}

	/* MSG DETAILS */
	$scope.msgs = {};
	$scope.msgdetails = function(f){
		//console.log(f);
		$http.post("msgdetails",{"number":f}).success(function(e){
			//console.log(e);
			$scope.msgs = e[0];
			$scope.msgstitle = e[1].name+' - '+e[1].number;
			//$("#msgdetailswrap").scrollTop($("#msgdtlsbody").attr("scrollHeight"));
			$("#msgdetailswrap").fadeIn(100);
			$timeout(function() {
			  var scroller = document.getElementById("msgdtlsbody");
			  scroller.scrollTop = scroller.scrollHeight;
			}, 50, false);
		}); 	
		
		//$location.hash('bottom');
		//$anchorScroll();
		//alert(document.getElementById("msgdtlsbody").scrollHeight);
	}
	//$scope.msgtab();
	
	/* CLOSE MSG DETAILS */
	
	$scope.closemsgdetails = function(){
		$("#msgdetailswrap").hide();
	}
	
	/* DELETE PHONE PHONEBOOK */ 
	$scope.deletepb = function(id){
		//console.log(id);
		$("#dialog").dialog({
			modal: true,
			title: "Delete Phonebook",
			buttons: {
				Yes: function() {
					$http.post("deletephonebook",{"id":id}).success(function(e){
						getphonebook();
					});
					$( this ).dialog( "close" );
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		$("#dialog p").html("Are you sure you want to delete this group?");
		/*
		$http.post("deletephonebook").success(function(e){
			
		});
		*/
	}
	
	/* GET ALL USERS */
	$scope.allusers = {};
	function getallusers(){
		$http.post("getallusers").success(function(e){
			//console.log(e);
			$scope.allusers = e;
		});
	}
	//getallusers();
	
	/* SAVE USER */
	
	$scope.saveusers = function(data){
		var title;
		var desc;
		$http.post("saveusers",{"id":data.id,"fullname":data.fullname,"username":data.name,"password":data.password,"adminflag":data.adminflag}).success(function(e){
			
			if(e.status == '1'){
				title = "Users Successfully Added";
				desc = "New Users has been successfully added!!"
				$scope.user.fullname = "";
				$scope.user.name = "";
				$scope.user.password = "";
				$scope.saveusersform.$setUntouched();
			}else if(e.status == '2'){
				title = "Username Exist";
				desc = "Username Exist in database"
			}else if(e.status == '3'){
				title = "User Successfully Updated";
				desc = "User has been successfully updated!"
			}else{
				title = "Ooops!";
				desc = "Something went wrong. Please reload the page";
			}
			$("#dialog").dialog({
				modal: true,
				title: title,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
			$("#dialog p").html(desc);
		});
	}
	/* INACTIVE USERS BY ID */
	$scope.inactiveuser = function(e,x){
		var title;
		var desc;
		if(x == '1'){
			title = "Set as Inactive";
			desc = "Are you sure you want to set this user as inactive?";
		}else{
			title = "Set as Active";
			desc = "Are you sure you want to set this user as active?";
		}
		
		$("#dialog").dialog({
			modal: true,
			title: title,
			buttons: {
				Yes: function() {
					$http.post("inactiveuser",{"id":e}).success(function(f){
						getallusers();
					});
					$( this ).dialog( "close" );
				},
				No: function() {
					$( this ).dialog( "close" );
				}
			}
		});
		$("#dialog p").html(desc);
	}
	/* EDIT USERS */
	$scope.editusers = function(e){
		$scope.titlelabel = 'Edit Users';
		$scope.user.id = e;
		$http.post("getuserdetails",{"id":e}).success(function(f){
			//console.log(f);
			$scope.user.fullname = f.fullname;
			$scope.user.name = f.username;
			$scope.user.password = f.password;

			$scope.user.adminflag = f.adminflag;
			$("#adminflag").niceSelect('destroy');
			$timeout(function() {
			  $("#adminflag").niceSelect();
			}, 1, false);
			
		});
		$scope.saveusersform.$setUntouched();
		//$("#newusers input").focus();
		$("#newusers input").css('color','black');
		$("#newusers input").siblings('span.inputanimate').css('color','skyblue');
		$("#newusers input").siblings('span.inputanimate').animate({
			'marginTop':'-20px',
			'font-size':'12px',
		},100);
		$(".error").css('margin-bottom','30px');
		$("#newusers input").css('color','black');
		$("#newusers input").siblings('span.inputanimate').css('color','skyblue');
		$("#newusers input").siblings('span.inputanimate').animate({
			'marginTop':'-20px',
			'font-size':'12px'
		},100);
		$("#groupmsg, #pbcontainer, #msgcontainer, #messages, #phonebook, #newsms, #newmsg, #newphonebook, #pbgroupcontainer, #userscontainer, #reportgroup").hide();
		$("#newusers").fadeIn(500);
	}
	$scope.showmsgdtls = function(){
		
		if($scope.changesmsoptions2 == '1'){
			$("#msgcontainer").fadeIn(500);
			$("#userscontainer, #groupmsg, #pbcontainer, #phonebook, #newsms, #newmsg, #reportgroup, #newphonebook, #pbgroupcontainer").hide();
		}else{
			getscheduledsms();
			$("#groupmsg").fadeIn(500);
			$("#userscontainer, #msgcontainer, #pbcontainer, #phonebook, #newsms, #newmsg, #reportgroup, #newphonebook, #pbgroupcontainer").hide();
		}
	}
	$scope.deletemsgid = [];
	function getscheduledsms(){
		$http.post("getscheduledsms").success(function(e){
			$scope.schedsms = e[0];
			$scope.deletemsgid = e[1];
			//console.log(e[0]);
		});
		//console.log($scope.schedsms);
	}
	
	
	/* CHANGE PASSWORD */
	$scope.changepassword = function(){
		$("#changepasswordwrapper").fadeIn(100);
	}
	
	/* LOGOUT */
	$scope.logout = function(){
		$http.post("logout").success(function(){
			window.location = 'login';
		});
	}
	
	$scope.changepass = function(e){
		$http.post("changepassword",{"old":e.old,"new":e.new,"confirm":e.confirm}).success(function(data){
			//1 SUCCESS
			//2 WRONG OLD
			//3 CONFIRM NOT MATCH
			var title;
			var desc;
			if(data.status == '1'){
				$("#changepasswordwrapper").fadeOut(100);
				$scope.cp.old = "";
				$scope.cp.new = "";
				$scope.cp.confirm = "";
				title = "Password Updated!";
				desc = "Password is now updated!";
			}else if(data.status == '2'){
				title = "Wrong Old Password";
				desc = "Please validate your old password";
			}else if(data.status == '3'){
				title = "Password Doesnt Match";
				desc = "Your Password Doesnt Match";
			}else{
				title = "Oops";
				desc = "Something went wrong";
			}
			$("#dialog").dialog({
				modal: true,
				title: title,
				buttons: {
					OK: function() {
						$( this ).dialog( "close" );
					}
				}
			});
			$("#dialog p").html(desc);
		});
	}
	$scope.closechangepass = function(){
		$("#changepasswordwrapper").fadeOut(100);
	}
	
	/* DISCARD BUTTONS */
	$scope.deletemsg = function(e){
		$("#dialog").dialog({
			modal: true,
			title: "Delete Archive?",
			buttons: {
				Yes: function() {
					$http.post("deletemessages",{"number":e}).success(function(f){
						$scope.msgtab();
						$( this ).dialog( "close" );
					});
				},
				No: function(){
					$( this ).dialog( "close" );
				}
			}
		});
		$("#dialog p").html("Are you sure you want to delete this archive ?");
	}
	$scope.selecteduseraccess = [];
	$scope.selectuserpbgroup = [];
	$scope.edituseraccess = function(PK_users)
	{
		$http.post("useraccess",{"PK_users":PK_users}).success(function(data){
			$("#showreportaccess, #wrappercustomdialog").fadeIn();
			$scope.useraccess = data[0];
			$scope.selectuseraccess = data[1];
		});
	}
	
	
	$scope.addusercontact = function(e){
		$("#showallphonebookuserswrapper, #showallphonebookusers").fadeIn(500);
		$http.post("getuserphonebook",{id:e}).success(function(data){
			$scope.userphonebook = data[0];
			
			$scope.selectuserphonebook = data[1];
			//console.log($scope.userphonebook);
		});
	}
	
	$scope.adduserpbgroup = function(e){
		$("#showallgroupuserswrapper, #showallgroupusers").fadeIn(500);
		$http.post("getgroupphonebook",{id:e}).success(function(data){
			$scope.userpbgroup = data[0];
			$scope.selectuserpbgroup = data[1];
		});
	}
	
	
	$scope.saveuseraccess = function(e){
		$http.post("edituseraccess",{"data":e}).success(function(data){
			$("#showallphonebookuserswrapper,#wrappercustomdialog, #showreportdetails, #showreportaccess").fadeOut();
		});
		
	}
	
	$scope.saveuserphonebook = function(e){
		//console.log(e);
		$http.post("saveuserphonebook",{"data":e}).success(function(data){
			
			$("#showallphonebookusers, #wrappercustomdialog, #showreportdetails, #showreportaccess").fadeOut();
		});
	}
	$scope.saveuserphonebook = function(e){
		//console.log(e);
		$http.post("saveuserphonebook",{"data":e}).success(function(data){
			
			$("#showallphonebookuserswrapper, #showallphonebookusers, #wrappercustomdialog, #showreportdetails, #showreportaccess").fadeOut();
		});
	}
	$scope.saveuserpbgroup = function(e){
		
		$http.post("saveuserpbgroup",{"data":e}).success(function(data){
			
			$("#showallgroupuserswrapper, #showallgroupusers, #showreportdetails, #showreportaccess").fadeOut();
		});
	}
	
	$scope.closepbgroup = function(){
		$("#showallgroupuserswrapper, #showallgroupusers, #wrappercustomdialog, #showreportdetails, #showreportaccess").fadeOut();
	}
	$scope.closesapb = function(){
		$("#showallphonebookuserswrapper, #showallphonebookusers, #wrappercustomdialog, #showreportdetails, #showreportaccess").fadeOut();
	}
	
	
	$scope.bindcheckuseraccess = function (id) {
		$scope.selectuseraccess[id] = $scope.selectuseraccess[id];
		
    }
	function showdeletemsg(){
		var retval = false;
		angular.forEach($scope.deletemsgid, function(value, key){
			if(retval !== true){
				if(value == true){
					retval = true;
				}else{
					retval = false
				}
			}
		});
		if(retval == true){
			$("#deletemsgwrapper").css('visibility','visible');
		}else{
			$("#deletemsgwrapper").css('visibility','hidden');
		}
	}
	$scope.selectallmsg = false;
	$scope.selectallschedsms = function(){
		if($scope.selectallmsg == false){
			var selval = false;
		}else{
			var selval = true;
		}
		angular.forEach($scope.deletemsgid, function(value, key){
			$scope.deletemsgid[key] = selval;
		});
		showdeletemsg();
	}
	$scope.deleteselectedmsg = function(){
		
	
		$("#dialog").dialog({
			modal: true,
			title: 'Delete Message?',
			buttons: {
				No: function() {
					$( "#dialog" ).dialog( "close" );
				},
				Yes: function() {
					$( "#dialog" ).dialog( "close" );
					$http.post("deleteselectedmsg",{"id":$scope.deletemsgid}).success(function(data){
						$scope.msgtab();
					});
				}
			}
		});
		$("#dialog p").html("Delete Selected Messages?");
		
	};
	$scope.binddeletemsgid = function(e){
		$scope.deletemsgid[e] = $scope.deletemsgid[e];
		showdeletemsg();
	}
	$scope.checkbinddeletemsgid = function(e){
		return $scope.deletemsgid[e];
		//console.log($scope.deletemsgid['1010']);
	}
	
	$scope.checkbinduseraccess = function(id){
		return $scope.selectuseraccess[id];
	}
	
	$scope.selectuserpbgroup = function (id) {
		$scope.selectuserpbgroup[id] = $scope.selectuserpbgroup[id];
    }
	
	$scope.checkbinduserpbgroup = function(id){
		return $scope.selectuserpbgroup[id];
	}
	
	$scope.bindcheckuserphonebook= function (id) {
		$scope.selectuserphonebook[id] = $scope.selectuserphonebook[id];
		
    }
	
	$scope.checkbinduserphonebook = function(id){
		return $scope.selectuserphonebook[id];
	}
	
	$scope.adminmodule = 0;
	$scope.administrator = function(){
		$http.post("administrator").success(function(data){
			$scope.adminmodule = data;
		});
	}
	$scope.administrator();
	
}]);
txtconnectApp.filter('toArray', function () {
  return function (obj, addKey) {
    if (!angular.isObject(obj)) return obj;
    if ( addKey === false ) {
      return Object.keys(obj).map(function(key) {
        return obj[key];
      });
    } else {
      return Object.keys(obj).map(function (key) {
        var value = obj[key];
        return angular.isObject(value) ?
          Object.defineProperty(value, '$key', { enumerable: false, value: key}) :
          { $key: key, $value: value };
      });
    }
  };
});