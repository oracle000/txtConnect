

/*	Application Module */
var txtconnectApp = angular.module('txtconnectApp',[]);
txtconnectApp.controller("appcontroller",['$scope','$http','$interval','$timeout',function($scope, $http, $interval, $timeout){
	
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
			$timeout(function () {
				console.log(e.status);
				if(e.status == '1'){
					$scope.curactivity = 'Sending SMS';
				}else if(e.status == '2'){
					$scope.curactivity = 'SMS Gateway Not Reachable';
				}else if(e.status == '0'){
					$scope.curactivity = 'Sending Failed';
				}else{
					$scope.curactivity = 'Waiting for Scheduled Messages';
				}
			}, 1000);
			//$scope.curactivity = 'Collating Data';
		});	
	}
	
	//$interval(schedsms,20000);
	$scope.timer = 5000;
	$scope.intervalcounter = 0;
	$scope.loop = function(){
		$timeout(function(){
			if($scope.stopper != 1){
				$http.post("app/schedmsg").success(function(e){
					$scope.timer = e[0]['interval'];
					//console.log($scope.timer);
					$scope.curactivity = 'Checking Unsuccessful Messages';
					$timeout(function () {
						//console.log(e[1].status);
						if(e[1]['status'] == '1'){
							$scope.curactivity = 'Sending SMS';
						}else if(e[1]['status'] == '2'){
							$scope.curactivity = 'SMS Gateway Not Reachable';
						}else if(e[1]['status'] == '0'){
							$scope.curactivity = 'Sending Failed';
						}else if(e[1]['status'] == '3'){
							$scope.curactivity = 'Waiting for Scheduled Messages';
							$scope.intervalcounter++;
							console.log($scope.intervalcounter);
							if($scope.intervalcounter == 15){
								$scope.curactivity = 'Prevent Page from Sleeping';
								$http.post("app/addlogs").success(function(){
									window.location = self.location;
								}); //ADDTL ALGORITHM
							}
						}else{
							window.location = self.location; //ADDTL ALGORITHM
						}
					}, 1000);
					$scope.loop();
				}).error(function(){
					window.location = self.location; //ADDTL ALGORITHM
				});
			}else{
				window.location = self.location; //ADDTL ALGORITHM
			}
		},$scope.timer)
	}
	
	$scope.loop();
	$interval(clock,1000);
	
	$scope.closedialog = function()
	{
		$("#showallgroupuserswrapper").css('display','none');
	}
	$scope.days = 0;
	var povalidation = function(){
		$http.post("app/validatelicense").success(function(e){
			
			$scope.days = e.days;
			if(e.days == 0){
				window.location = document.URL;
			}else if(e.days <= 5 && e.days != 0){
				$("#showallgroupuserswrapper, #showallgroupusers1").css('display','block');
			}
		});
	}
	povalidation();
	$interval(povalidation,5000);
	
}]);