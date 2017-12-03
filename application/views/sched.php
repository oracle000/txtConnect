	<div id="header">
		<span id="logocontainer">Mount Grace</span>
		<span id="logosignature">
			TxtConnect
		</span>
	
	</div>
	<div id="title">
		<div id="titlewrapper">
			<span class="refdate">Reference Date Time</span>
			<span class="serverdate">{{serverclock}}</span>
		</div>
	</div>
	
	<div id="loader" style="position:absolute;top:185px;width:100%;height:calc(100% - 185px);">
		<div style="width:95%;margin:auto;">
			<span class="curactivitytitle">Current Activity:</span>
			<span class="curactivitytitle">{{curactivity}}<!--Collating Data / Checking Unsuccessful Messages / Sending SMS--></span>
		</div>
		<div id="loaderwrapper"><img src="<?php echo base_url(); ?>css/img/loading.gif" /></div>
	</div>
	<div id="showallgroupuserswrapper">
	<div id="showallgroupusers1" style="height:250px;">
		<div id="custdialogtitle">
			<span style="float:left;">TxtConnect Licensing Service</span>
			<button type="button" ng-click="closedialog()" id="closecustomdialog" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close">
				<span class="ui-button-icon ui-icon ui-icon-closethick"></span>
				<span class="ui-button-icon-space" ></span>Close
			</button>
		</div>
		<!--<div id="msgdtlssearch" style="margin-top:5px; margin-bottom:5px; float:right;">Search:<input type="text" id="searchinput" ng-model="usercontactsearch" /></div>-->
		<div id="custdialogbody" style="width:100%;float:left;height:140px;">
			<table id="tbldialog">
				<tbody>
					<tr>
						<td colspan=2>TxtConnect Trial Period.</td>
					</tr>
					<tr>
						<td colspan=2>You only have {{days}} days left before trial period ends.</td>
					</tr>
					<tr>
						<td colspan=2>This will automatically disable the crosspoint to run.</td>
					</tr>
					<tr>
						<td colspan=2>Please contact your product vendor to continue TxtConnect usage.</td>
					</tr>
					<tr>
						<td colspan=2>Thank you!</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="custialogbtn">
			<button id="custdialogsave" ng-click="closedialog()">Ok</button>
			<!--<button id="custdialogcancel" >Later</button>-->
		</div>
	</div>
	</div>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/angularsched.js"></script>
</div>
</body>
</html>

