

/*	Application Module */
var txtconnectApp = angular.module('txtconnectApp',[]);
txtconnectApp.controller("appcontroller",['$scope','$http','$interval',function($scope, $http, $interval){
	
	/* App Functions */

	/* Clock */

	var clock = function(){
		$http.post('app/serverdatetime').success(function(data){
			$scope.serverclock = data.datetime;
			//console.log($scope.serverclock);
		});	
	}
	
	
	/* SCHED MESSAGES */
	$scope.curactivity = 'Collating Data';
	
	var schedsms = function(){
		$scope.curactivity = 'Collating Data';
		$http.post("app/schedmsg").success(function(e){
			$scope.curactivity = 'Checking Unsuccessful Messages';
			if(e.status == '1'){
				$scope.curactivity = 'Sending SMS';
			}
			else{
				$scope.curactivity = 'Waiting for Scheduled Messages';
			}
		});	
	}
	
	$interval(schedsms,2000);
	
	
}]);