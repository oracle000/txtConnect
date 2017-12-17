<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	
	<div id="header">
		<div class="smsBlastHeader">
			<div class="icon-container">
				<i class="fa fa-mobile" aria-hidden="true"></i>
				<p>Astral</p>
			</div>
			<p class="icon-title">SMS InfoBlast</p>
		</div>
		<div class="title-display">
			<div>		
				<span id="logocontainer">Mount Grace</span>
				<span id="logosignature">TxtConnect</span>
			</div>
			<div class="logout-container">
				<span class="userinfo" ng-click="changepassword()">
					<i class="fa fa-user-secret" aria-hidden="true"></i><?php echo $fullname; ?>
				</span>
				<span class="userlogout" ng-click="logout()">
					<i class="fa fa-sign-out" aria-hidden="true"></i>Logout
				</span>
			</div>
		</div>
	
	</div>

	<article>
		<section class="left-nav">
			<div id="hdrtabs">
				<!--<span id="home" data-ripple ng-click="hometab()">HOME</span>-->
				<span id="msg" data-ripple ng-click="msgtab()">
					<i class="fa fa-envelope-o" aria-hidden="true"></i>MESSAGES
				</span>
				<span id="pbook" data-ripple ng-click="pbtab()">
					<i class="fa fa-address-book-o" aria-hidden="true"></i>PHONEBOOK
				</span>
				<span ng-show="adminmodule == 1" id="rptgroup" data-ripple ng-click="rptgrptab()">
					<i class="fa fa-book" aria-hidden="true"></i>REPORT GROUPS
				</span>
				<span ng-show="adminmodule == 1" id="users" data-ripple ng-click="usertab()">
					<i class="fa fa-users" aria-hidden="true"></i>USERS
				</span>
				<span ng-show="adminmodule == 1" id="users" data-ripple ng-click="sysconfigtab()">
				<i class="fa fa-cogs" aria-hidden="true"></i>SYSTEM CONFIG
				</span>
			</div>
		</section>
		<section class="right-nav">
			<div id="title">
				<div id="titlewrapper">
					<div id="phonebook" style="display:none">
						<span class="titletext" style="margin-top:15px;">
							<select id="droppb" class="wide" ng-change="changepb()" ng-model="changepbmodel" ng-options="e.value as e.name for e in phonebookselect" ></select>
						</span>
						<span ng-show="changepbmodel == 1 && filteredpb != 'null'" style="float:left;display:inline-block;margin-left:20px;">Total Record: {{filteredpb.length}}</span>
						<span ng-show="changepbmodel == 2 && filteredgroups != 'null'" style="float:left;display:inline-block;margin-left:20px;">Total Record: {{filteredgroups.length}}</span>
						<span id="searchinputwrap" ng-show="changepbmodel == 1">Search:<input type="text" id="searchinput" ng-model="filterpb" /></span>
						<span id="searchinputwrap" ng-show="changepbmodel == 2">Search:<input type="text" id="searchinput" ng-model="filtergroup" /></span>
						
					</div>
					<div id="messages" style="display:none">
						<span class="titletext" style="margin-top:15px;">
							<select id="dropmsg" ng-change="showmsgdtls()" ng-click="showmsgdtls()" class="wide" ng-model="changesmsoptions2" ng-options="e.value as e.name for e in smsoptions2"></select>
						</span>
						<span id="searchinputwrap" ng-show="changesmsoptions2 == 1">Search:<input type="text" id="searchinput" ng-model="filtermsg" /></span>
						<span id="searchinputwrap" ng-show="changesmsoptions2 == 2">Search:<input type="text" id="searchinput" ng-model="filtergroupmsg" /></span>
					</div>
					<div id="newsms" style="display:none">
						<span class="titletext">{{titlelabel}}</span>
						<span class="newsmsbtnwrapper" ng-show="tab == 'msg'">
							<button ng-click="msgtab()" class="cancelbtn" id="cancelsms">CANCEL</button>
							<button class="savebtn" ng-click="sendsms(sms)" id="sendsms" ng-disabled="sendsmsform.smsnumber.$invalid && sendsmsform.smsmsg.$invalid">SEND</button>
							<!--  || sendsmsform.smsmsg.$dirty && sendsmsform.smsmsg.$invalid -->
						</span>
						<span id="searchinputwrap" ng-show="tab == 'users'">Search:<input type="text" id="searchinput" ng-model="filterusersmodel" /></span>
					</div>			
					
				</div>
				</div>
				<div>	
				<div id="addnew" ng-click="addnew()">+</div>
				<div id="body">
					<div id="reportgroup" style="display:none;">
						<table id="tblmsg">
							<thead>
								<tr>
									<td style="width:300px">REPORT NAME</td>
									<td>PHONEBOOK</td>
									<td style="width:150px">ISACTIVE</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="detail in rptdtls" id="rptcol" ng-click="reportdetails(detail.PK_reports)">
									<td data-id="{{detail.PK_reports}}">{{detail.description}}</td>
									<td><div style="height:30px; line-height:30px; overflow:hidden; text-overflow:ellipsis">{{detail.name}}</div></td>
									<td>{{detail.isactive}}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="msgcontainer" style="display:none">
						<table id="tblmsg">
							<thead>
								<tr>
									<td style="width:150px;">NAME</td>
									<td>NUMBER</td>
									<td>LAST MESSAGE</td>
									<td style="width:150px;">DATETIME</td>
									<td>ACTIONS</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-repeat="detail in msgdtls | filter:filtermsg">
									<td ng-click="msgdetails(detail.number)">{{detail.fullname}}</td>
									<td ng-click="msgdetails(detail.number)" style="font-weight:bold;">{{detail.number}}</td>
									<td ng-click="msgdetails(detail.number)"><div id="msgwrappertxt">{{detail.msg}}</div></td>
									<td ng-click="msgdetails(detail.number)">{{detail.timestamp}}</td>
									<td><i ng-click="deletemsg(detail.number)" class="fa fa-trash-o fa-lg" aria-hidden="true"></i></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="pbcontainer" style="display:none">
						<table id="tblmsg">
							<thead>
								<tr>
									<td>CODE</td>
									<td>FULLNAME</td>
									<td>NUMBER</td>
									<td>LAST MSG DATE</td>
									<td>UNSUBSCRIBE FLAG</td>
									<td>ACTIONS</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-show="phonebook.length > 0" ng-repeat="detail in phonebook | filter:filterpb as filteredpb" >
									<td>{{detail.PK_phonebook}}</td>
									<td>{{detail.name}}</td>
									<td>{{detail.number}}</td>
									<td>{{detail.datetime}}</td>
									<td>{{detail.unsubscribeflag}}</td>
									<td>
										<i style="margin-left:-20px" ng-click="editpb(detail.PK_phonebook)" class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
										<i ng-show="adminmodule == '1'" ng-click="deletepb(detail.PK_phonebook)" class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="pbgroupcontainer" style="display:none">
						<table id="tblmsg">
							<thead>
								<tr>
									<td>CODE</td>
									<td>DESCRIPTION</td>
									<td>ACTIONS</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-show="groups.length > 0" ng-repeat="detail in groups | filter:filtergroup as filteredgroups">
									<td>{{detail.PK_groups}}</td>
									<td>{{detail.description}}</td>
									<td>
										<i ng-show="adminmodule == 1" ng-click="addusergroup(detail.PK_groups)" style="margin-left:-20px" class="fa fa-plus fa-lg" aria-hidden="true"></i>
										<i ng-click="editusergroup(detail.PK_groups)" class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
										<i ng-show="adminmodule == 1" ng-click="deletegroup(detail.PK_groups)" class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="userscontainer" style="display:none">
						<table id="tblmsg">
							<thead>
								<tr>
									<td>CODE</td>
									<td>FULLNAME</td>
									<td>USERNAME</td>
									<td>ISACTIVE</td>
									<td>ACTIONS</td>
								</tr>
							</thead>
							<tbody>
								<tr ng-show="allusers.length > 0" ng-repeat="detail in allusers | toArray:false | filter:filterusersmodel">
									<td>{{detail.PK_users}}</td>
									<td>{{detail.fullname}}</td>
									<td>{{detail.username}}</td>
									<td><input type="checkbox" ng-checked="detail.isactive == 1" disabled></td>
									<td>
										<i ng-click="editusers(detail.PK_users)" style="margin-left:-20px" class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i>
										<i ng-show="detail.isactive == 1" ng-click="inactiveuser(detail.PK_users,detail.isactive)" class="fa fa-lock fa-lg" aria-hidden="true"></i>
										<i ng-show="detail.isactive == 0" ng-click="inactiveuser(detail.PK_users,detail.isactive)" class="fa fa-unlock fa-lg" aria-hidden="true"></i>
										<!-- <i ng-click="edituseraccess(detail.PK_users)" class="fa fa-list fa-lg" aria-hidden="true"></i> -->
										<i ng-click="addusercontact(detail.PK_users)" class="fa fa-address-book fa-lg" aria-hidden="true"></i>
										<i ng-click="adduserpbgroup(detail.PK_users)" class="fa fa-users fa-lg" aria-hidden="true"></i>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="sysconfig">
						<div class="cont">
							<div class="ipadd">
								<p>Set Local IP Address : </p>
								<input type="text" id="searchinput" ng-model="filterusersmodel" class="ng-pristine ng-valid ng-empty ng-touched">
							</div>						
							<div class="ipadd">
								<p>Set Server IP Address : </p>
								<input type="text" id="searchinput" ng-model="filterusersmodel" class="ng-pristine ng-valid ng-empty ng-touched">
							</div>
						</div>
						<div class="model-table">
							<p>MODEMS</p>
							<div class="table-inside">
								<table id="tblmsg">
									<thead>
										<tr>
											<td>MODEM NAME</td>
											<td>MODEM NUMBER</td>
											<td>MODEM IP ADDRES</td>
											<td>PORT NUMBER</td>
											<td>MODEM USERS</td>
											<td>PASSWORD</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
										<tr>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
											<td>Lorem Ipsum</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>					
					</div>
					<div id="groupmsg" style="display:none">
						<table id="tblmsg">
							<thead>
								<tr>
									<td><input type="checkbox" ng-model="selectallmsg" ng-click="selectallschedsms()"></td>
									<td style="width:20px;">NO</td>
									<td style="width:150px;">TRACKING NO</td>
									<td>NUMBER</td>
									<td style="width:150px;">NAME</td>
									<td>MSG</td>
									<td style="width:150px;">STATUS</td>
									<td>DATETIME</td>
									<td>ACKNOWLEDGE</td>
								</tr>
							</thead>
							<tbody>
							
								<tr ng-show="schedsms.length >= 1 && schedsms != 'n'" ng-repeat="detail in schedsms | toArray:false | filter:filtergroupmsg">
									<td><input type="checkbox" ng-click="binddeletemsgid(detail.PK_schedmsgs)" type="checkbox" ng-model="deletemsgid[detail.PK_schedmsgs]" ng-checked="checkbinddeletemsgid(detail.PK_schedmsgs)"/></td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)">{{detail.counter}}</td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)">{{detail.PK_schedmsgs}}</td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)"style="font-weight:bold">{{detail.number}}</td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)" style="font-weight:bold"><div style="float:left;overflow:hidden;text-overflow:ellipsis;height:20px;line-height:20px;">{{detail.name}}</div></td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)"><div id="msgwrappertxt">{{detail.msg}}</div></td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)" ng-show="detail.status == 1">SENT</td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)" ng-show="detail.status == 0">FAILED</td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)" style="width:150px">{{detail.datetime}}</td>
									<td ng-click="msgdetails(detail.PK_schedmsgs)">{{detail.isacknowledge}}</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div id="newmsg" style="display:none">
						<span id="titlewrapper">Text Messaging Service</span>
						<span style="float:left;height:40px;width:calc(100% - 80px);margin-left:40px;">
							<span style="line-height:40px;float:left;">Schedule SMS</span>
							<span><input style="margin-left:10px;margin-top:13px;float:left" type="checkbox" id="schedulemsg" ng-init="sms.scheduled = 0" ng-model="sms.scheduled" /></span>
						</span>
						<span ng-show="sms.scheduled == 1" style="float:left;height:40px;width:calc(100% - 80px);margin-left:40px;">
							<span>Schedule DateTime:</span>
							<span><input ng-init="sms.datetime ='<?php echo date("Y/m/d H:i",strtotime(date("H:i")."+ 10 minutes")); ?>'" type="text" id="datetimepicker" ng-model="sms.datetime" /></span>
						</span>
						<span style="float:left;margin-left:40px;margin-bottom:30px;">
							<span class="titletext" style="margin-top:15px;">
								<select ng-init="sms.number = e[0].id;" ng-change="getautoinput()" id="smsoption" ng-model="changesmsoption" class="wide" ng-options="e.value as e.name for e in smsoptions"></select>
							</span>
						</span>
						<form name="sendsmsform" novalidate>
						<span id="numberselect"><span class="inputanimateselect">Number / Groups / Name</span>
							<select id="selectgroup" style="width:250px;" ng-model="sms.number"></select>
						</span>
						<span id="msgwrap"><span class="inputanimate">Your Message</span><textarea name="smsmsg" ng-model="sms.msg" required></textarea>			
						</span>
						</form>
					</div>
					<div id="newphonebook" style="display:none">
						<span id="titlewrapper">{{ pbtitle }}</span>
						<form name="savepbform" novalidate>
							<input type="hidden" ng-model="pb.id" />
							
							<span id="number"><span class="inputanimate">Number</span><input id="pbnumber" name="pbnumber" type="text" ng-model="pb.number" ng-minlength="11" required /></span>
							<span id="number"><span class="inputanimate">Fullname</span><input id="pbname" name="pbname" type="text" ng-model="pb.name" ng-minlength="2" required /></span>
							<span class="btnaddpb">
								<button ng-click="pbtab()" class="cancelbtn">DISCARD</button>
								<button ng-click="savepb(pb)" class="savebtn" ng-disabled="savepbform.pbnumber.$invalid || savepbform.pbnumber.$error.required || savepbform.pbname.$invalid || savepbform.pbname.$error.required">SAVE</button>
								<!--  || sendsmsform.smsmsg.$dirty && sendsmsform.smsmsg.$invalid -->
							</span>
						</form>
						</span>
					</div>
					<div id="newusers" style="display:none;">
						<span id="titlewrapper" style="margin-bottom:30px;">{{ titlelabel }}</span>
						<form name="saveusersform" novalidate>
							<input type="hidden" ng-model="user.id" />
							
							<span id="labelform">
								<span class="inputanimate">Fullname</span>
								<input id="pbnumber" name="fullname" type="text" ng-model="user.fullname" ng-minlength="5" required />
							</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.fullname.$invalid && saveusersform.fullname.$touched">*Required</span>
							</div>
							<span id="labelform">
								<span class="inputanimate">Username</span>
								<input id="username" name="username" type="text" ng-model="user.name" ng-minlength="2" required />
							</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.username.$invalid && saveusersform.username.$touched">*Required</span>
							</div>
							<span id="labelform">
								<span class="inputanimate">Password</span>
									<input id="pbname" name="password" type="password" ng-model="user.password" ng-minlength="2" required />
								</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.password.$invalid && saveusersform.password.$touched">*Required</span>
							</div>
							<span id="labelform1" style="color:silver;margin-left:0px;);">Admin Flag</span>
							<div style="float:left;margin-left:40px;width:calc(100% - 40px">
								<select ng-model="user.adminflag" id="adminflag" name="adminflag" ng-options="e.value as e.name for e in adminflagselect"></select>
							</div>
							
							<span class="btnaddpb" style="float:left;margin-top:20px;height:60px;">
								<button ng-click="usertab()" class="cancelbtn">DISCARD</button>
								<button ng-click="saveusers(user)" class="savebtn" ng-disabled="saveusersform.fullname.$invalid || saveusersform.fullname.$error.required || saveusersform.username.$invalid || saveusersform.username.$error.required || saveusersform.password.$invalid || saveusersform.password.$error.required">SAVE</button>
								<!--  || sendsmsform.smsmsg.$dirty && sendsmsform.smsmsg.$invalid -->
							</span>
						
						
						</form>
					</div>
					<div id="newgroup" style="display:none;">
						<span id="titlewrapper">{{ titlelabel }}</span>
						<form name="savepbform" novalidate>
							<input type="hidden" ng-model="group.id" />
							<span id="number"><span class="inputanimate">Group Name</span><input id="pbgroupname" name="pbgroupname" type="text" ng-model="group.name" ng-minlength="2" required /></span>
							<span class="btnaddpb">
								<button ng-click="pbtab()" class="cancelbtn">DISCARD</button>
								<button ng-click="savegroup(group)" class="savebtn" ng-disabled="savepbform.pbgroupname.$invalid || savepbform.pbgroupname.$error.required">SAVE</button>
								<!--  || sendsmsform.smsmsg.$dirty && sendsmsform.smsmsg.$invalid -->
							</span>
						</form>
						</span>
					</div>

					<div id="newmodem" style="display: none;">
						<span id="titlewrapper" style="margin-bottom:30px;">{{ titlelabel }}</span>
						<form name="saveusersform" novalidate>
							<input type="hidden" ng-model="user.id" />														
							<span id="labelform">
								<span class="inputanimate">Modem Name</span>
								<input id="pbnumber" name="fullname" type="text" ng-model="user.fullname" ng-minlength="5" required />
							</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.fullname.$invalid && saveusersform.fullname.$touched">*Required</span>
							</div>

							<span id="labelform">
								<span class="inputanimate">Modem Number</span>
								<input id="username" name="username" type="text" ng-model="user.name" ng-minlength="2" required />
							</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.username.$invalid && saveusersform.username.$touched">*Required</span>
							</div>

							<span id="labelform">
								<span class="inputanimate">Modem IP Address</span>
									<input id="pbname" name="password" type="text"  ng-minlength="2" required />
								</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.password.$invalid && saveusersform.password.$touched">*Required</span>
							</div>

							<span id="labelform">
								<span class="inputanimate">Port Number</span>
									<input id="pbname" name="password" type="text"  ng-minlength="2" required />
								</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.password.$invalid && saveusersform.password.$touched">*Required</span>
							</div>

							<span id="labelform">
								<span class="inputanimate">Modem Users</span>
									<input id="pbname" name="password" type="text"  ng-minlength="2" required />
								</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.password.$invalid && saveusersform.password.$touched">*Required</span>
							</div>

							<span id="labelform">
								<span class="inputanimate">Password</span>
									<input id="pbname" name="password" type="password" ng-model="user.password" ng-minlength="2" required />
								</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.password.$invalid && saveusersform.password.$touched">*Required</span>
							</div>

							
							
							<span class="btnaddpb" style="float:left;margin-top:20px;height:60px;">
								<button ng-click="usertab()" class="cancelbtn">DISCARD</button>
								<button ng-click="saveusers(user)" class="savebtn" ng-disabled="saveusersform.fullname.$invalid || saveusersform.fullname.$error.required || saveusersform.username.$invalid || saveusersform.username.$error.required || saveusersform.password.$invalid || saveusersform.password.$error.required">SAVE</button>
								<!--  || sendsmsform.smsmsg.$dirty && sendsmsform.smsmsg.$invalid -->
							</span>
						
						</form>
					</div>


					<div id="newreport" style="display: none">
						<span id="titlewrapper" style="margin-bottom:30px;">{{ titlelabel }}</span>
						<form name="saveusersform" novalidate>
							<input type="hidden" ng-model="user.id" />
							
							<span id="labelform">
								<span class="inputanimate">Report Name</span>
								<input id="pbnumber" name="fullname" type="text" ng-model="user.fullname" ng-minlength="5" required />
							</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.fullname.$invalid && saveusersform.fullname.$touched">*Required</span>
							</div>
							<span id="labelform">
								<span class="inputanimate">PhoneBook</span>
								<input id="username" name="username" type="text"  ng-minlength="2" required />
							</span>
							<div class="error" style="float:left;text-indent:40px;height:40px;">&nbsp;
								<span ng-show="saveusersform.username.$invalid && saveusersform.username.$touched">*Required</span>
							</div>
							
							<span id="labelform1" style="color:silver;margin-left:0px;);">Is Active</span>
							<div style="float:left;margin-left:40px;width:calc(100% - 40px">
								<select ng-model="user.adminflag" id="adminflag" name="IsActive" ng-options="e.value as e.name for e in adminflagselect"></select>
							</div>																		
						</form>


					</div>
					<!--
					<div id="newschedsms" style="display:none;">
						<span id="titlewrapper">Scheduled Text Messaging Service</span>
						<span id="number">
							<span class="inputanimate">Number / Name / Groups</span>
							
							<input name="smsnumber" type="text" value="" ng-model="sms.number" ng-minlength="11" required />
						</span>
						<span id="number"><span class="inputanimate">Schedule Datetime</span><input name="smsnumber" type="text" value="" ng-model="sms.number" ng-minlength="11" required /></span>
						<span id="msgwrap"><span class="inputanimate">Your Message</span><textarea name="smsmsg" ng-model="sms.msg" required></textarea></span>			
						
					</div>
					-->
				</div>
				<form name="savereportuser" novalidate>
				<div id="wrappercustomdialog" style="display:none">
					<div id="showreportdetails" style="display:none">
						<div id="custdialogtitle">
							<span style="float:left;">Subscription - {{reportname}}</span>
							<button type="button" ng-click="closecustomdialog()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
								<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
								<span class="ui-button-icon-space"></span>Close
							</button>
						</div>
						<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;">Search:<input type="text" id="searchinput" ng-model="phonebooksearch" /></div>
						<div id="custdialogbody" style="float:left;width:100%;">
							<table id="tbldialog">
								<thead>
									<tr>
										<td>Name</td>
										<td>Number</td>
										<td>Selected</td>
									</tr>
								</thead>
								<tbody>
									
									<tr ng-repeat="detail in reportusers | filter:phonebooksearch">
										<td>{{detail.name}}</td>
										<td>{{detail.number}}</td>
										<td>
											<input type="checkbox" ng-model="selectcheck[detail.PK_reportgroup]" value="detail.selected" ng-checked="checkbindselectcheck(detail.PK_reportgroup)" />
										</td>							
									</tr>
								</tbody>
							</table>
						</div>
						<div id="custialogbtn">
							<button id="custdialogsave" ng-click="savereportusers(selectcheck)" >Ok</button>
							<button id="custdialogcancel" ng-click="closecustomdialog()" >Discard</button>
						</div>
					</div>
					<div id="showreportaccess" style="display:none">
						<div id="custdialogtitle">
							<span style="float:left;">User Messages Access</span>
							<button type="button" ng-click="closecustomdialog()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
								<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
								<span class="ui-button-icon-space"></span>Close
							</button>
						</div>
						<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;">Search:<input type="text" id="searchinput" ng-model="useraccessmodel" /></div>
						<div id="custdialogbody" style="float:left;width:100%;">
							<table id="tbldialog">
								<thead>
									<tr>
										<td>Name</td>
										<td>Selected</td>
									</tr>
								</thead>
								<tbody>
									<tr ng-show="useraccess != 'n'" ng-repeat="detail in useraccess | filter:useraccessmodel">
										<td>{{detail.description}}</td>
										<td>
											<input ng-click="bindcheckuseraccess(detail.PK_useraccess)" type="checkbox" ng-model="selectuseraccess[detail.PK_useraccess]" value="detail.selected" ng-checked="checkbinduseraccess(detail.PK_useraccess)" />
										</td>							
									</tr>
								</tbody>
							</table>
						</div>
						<div id="custialogbtn">
							<button id="custdialogsave" ng-click="saveuseraccess(selectuseraccess)" >Ok</button>
							<button id="custdialogcancel" ng-click="closecustomdialog()" >Discard</button>
						</div>
					</div>
					<div id="showallphonebook" style="display:none">
						<div id="custdialogtitle">
							<span style="float:left;">Add Contacts</span>
							<button type="button" ng-click="closecustomdialog()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
								<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
								<span class="ui-button-icon-space"></span>Close
							</button>
						</div>
						<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;">Search:<input type="text" id="searchinput" ng-model="contactsearch" /></div>
						<div id="custdialogbody" style="width:100%;float:left;">
							<table id="tbldialog">
								<thead>
									<tr>
										<td>Name</td>
										<td>Number</td>
										<td>Selected</td>
									</tr>
								</thead>
								<tbody>
									
									<tr ng-repeat="detail in usergroup | toArray:false | filter:contactsearch">
										<td>{{detail.name}}</td>
										<td>{{detail.number}}</td>
										<td>
											<input type="checkbox" ng-model="selectusergroup[detail.PK_pbgroup]" value="detail.selected" ng-checked="checkbindusergroup(detail.PK_pbgroup)"/>
										</td>							
									</tr>
								</tbody>
							</table>
						</div>
						<div id="custialogbtn">
							<button id="custdialogsave" ng-click="saveusergroup(selectusergroup)" >Ok</button>
							<button id="custdialogcancel" ng-click="closecustomdialog()" >Discard</button>
						</div>
					</div>
				</div>
				<div id="showallphonebookuserswrapper">
				<div id="showallphonebookusers" style="display:none">
					<div id="custdialogtitle">
						<span style="float:left;">Add Contacts</span>
						<button type="button" ng-click="closesapb()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
							<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
							<span class="ui-button-icon-space"></span>Close
						</button>
					</div>
					<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;">Search:<input type="text" id="searchinput" ng-model="usercontactsearch" /></div>
					<div id="custdialogbody" style="width:100%;float:left;">
						<table id="tbldialog">
							<thead>
								<tr>
									<td>Name</td>
									<td>Number</td>
									<td>Selected</td>
								</tr>
							</thead>
							<tbody>
							
								<tr ng-show="userphonebook != 'n'" ng-repeat="detail in userphonebook | toArray:false | filter:usercontactsearch">
									<td>{{detail.name}}</td>
									<td>{{detail.number}}</td>
									<td>
										<input ng-click="bindcheckuserphonebook(detail.PK_userphonebook)" type="checkbox" ng-model="selectuserphonebook[detail.PK_userphonebook]" value="detail.selected" ng-checked="checkbinduserphonebook(detail.PK_userphonebook)" />
									</td>							
								</tr>
							</tbody>
						</table>
					</div>
					<div id="custialogbtn">
						<button id="custdialogsave" ng-click="saveuserphonebook(selectuserphonebook)" >Ok</button>
						<button id="custdialogcancel"  ng-click="closesapb()">Discard</button>
					</div>
				</div>
				</div>
				<div id="showallgroupuserswrapper" style="display:none;">
				<div id="showallgroupusers">
					<div id="custdialogtitle">
						<span style="float:left;">Add Groups</span>
						<button type="button" ng-click="closepbgroup()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
							<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
							<span class="ui-button-icon-space" ></span>Close
						</button>
					</div>
					<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;">Search:<input type="text" id="searchinput" ng-model="usercontactsearch" /></div>
					<div id="custdialogbody" style="width:100%;float:left;">
						<table id="tbldialog">
							<thead>
								<tr>
									<td>Code</td>
									<td>Description</td>
									<td>Selected</td>
								</tr>
							</thead>
							<tbody>
								
								<tr ng-show="userpbgroup != 'n'" ng-repeat="detail in userpbgroup | toArray:false | filter:usercontactsearch">
									<td>{{detail.PK_userpbgroup}}</td>
									<td>{{detail.name}}</td>
									<td>
										<input ng-click="bindcheckuserpbgroup(detail.PK_userpbgroup)" type="checkbox" ng-model="selectuserpbgroup[detail.PK_userpbgroup]" value="detail.selected" ng-checked="checkbinduserpbgroup(detail.PK_userpbgroup)" />
									</td>							
								</tr>
							</tbody>
						</table>
					</div>
					<div id="custialogbtn">
						<button id="custdialogsave" ng-click="saveuserpbgroup(selectuserpbgroup)" >Ok</button>
						<button id="custdialogcancel" ng-click="closepbgroup()" >Discard</button>
					</div>
				</div>
				</div>
				<div id="msgdetailswrap" style="display:none">
					<div id="msgdetails">
						<div id="msgdtlstitle">
							<span title="{{msgstitle}}" style="float:left;width:calc(100% - 50px);height:50px;text-overflow:ellipsis;overflow:hidden">{{msgstitle}}</span>
							<button type="button" ng-click="closemsgdetails()" id="closemsgdetails" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
								<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
								<span class="ui-button-icon-space"></span>Close
							</button>
						</div>
						<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;"><!--Search:<input type="text" id="searchinput" ng-model="msgdtlsfilter" />--></div>
						<div id="msgdtlsbody" style="width:100%;">
							<div id="msgbubblecontainer" ng-show="msgs.length > 0" ng-repeat="detail in msgs | toArray:false | filter:msgdtlsfilter">
								<div class="msgdatetime">{{detail.datetime}}</div>
								<div class="msgbubblewrapper">
									<div class="speech-bubble-left" ng-show="detail.msgposition == 1">
										{{detail.msg}}
									</div>
								</div>
							</div>
							<div id="bottom" name="bottom" style="float:left;">&nbsp;</div>
							
							<!--
							<div id="msgbubblecontainer">
								<div class="msgdatetime">Jan 4, 2017 4:05PM</div>
								<div class="msgbubblewrapper">
									<div class="speech-bubble-left">
										Yes po???
									</div>
								</div>
							</div>
							-->
							
							
						</div>
					</div>
				</div>
				</form>
				<div id="changepasswordwrapper">
					<div id="changepassword">
						<form name="changepassform" novalidate>
						<div id="cptitle">
							Change Password
							<button style="margin-top:0px;" type="button" ng-click="closechangepass()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
								<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
								<span class="ui-button-icon-space"></span>Close
							</button>
						</div>
						
						<div id="cpbody">
							<table>
								<tr>
									<td>Old Password</td>
									<td><input type="password" name="oldpass" ng-model="cp.old" /></td>
								</tr>
								<tr>
									<td>New Password</td>
									<td><input type="password" name="newpass" ng-model="cp.new" /></td>
								</tr>
								<tr>
									<td>Confirm Password</td>
									<td><input type="password" name="confpass" ng-model="cp.confirm" /></td>
								</tr>
							</table>
						</div>
						<div id="cpbtn"><button id="cpbtn" ng-click="changepass(cp)" >Change Password</button></div>
						</form>
					</div>
				</div>
				<div id="deletemsgwrapper" ng-click="deleteselectedmsg()">
					<i class="fa fa-trash-o fa-3x" style="margin-left:17px;margin-top:13px;"></i>
				</div>

			</div>
		</section>		
	</article>



	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/angularadmin.js"></script>
</body>
</html>