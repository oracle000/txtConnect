

/*	Application Module */
var txtconnectApp = angular.module('txtconnectApp',[]);
txtconnectApp.controller("appcontroller",['$scope','$http','$interval',function($scope, $http, $interval){
	$scope.login = function(e){
		$http.post("checklogin",{"username":e.name,"password":e.pass}).success(function(data){
			if(data.status == '1'){
				window.location = "admin";
			}else{
				$("#dialog").dialog({
					modal: true,
					title: 'Wrong Credentials',
					buttons: {
						Ok: function() {
						  $( this ).dialog( "close" );
						}
					}
				});
				$("#dialog p").html("Wrong username or password");
			}
		});
	}
}]);