<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<div id="myApp" ng-app="myApp" ng-cloak data-ng-element-ready="">
		<div id="controlador" ng-controller="dash" style="overflow-y:auto" md-theme="custom" data-ng-init="onloadFun()">
			<md-tabs md-dynamic-height md-border-bottom md-selected="selected_tab_index" style="display: none">
		        <md-tab label="Devices">
		            <md-content ng-if="tpl.devices.length">
		                <md-list class="md-dense" flex>
		                    <md-list-item class="md-2-line" ng-repeat="device in tpl.devices">
		                        <div class="md-list-item-text">
		                            <h3>{{device.alias}}</h3>

		                            <p>{{device.deviceModel}} - {{device.deviceName}}</p>
		                        </div>
		                        <md-switch ng-change="tpl_setState($index,device.is_powered)"
		                                   ng-model="device.is_powered"></md-switch>
		                        <md-divider/>
		                    </md-list-item>
		                </md-list>
		            </md-content>
		            <md-content ng-if="!tpl.devices.length" class="md-padding">
		                <h3>Warning</h3>
		                Por favor, debes conectarte a la WIFI ADM y recargar para poder repostar.</h3>
		            </md-content>
		        </md-tab>
			</md-tabs>
		</div>
	</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-animate.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-cookies.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-aria.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular-messages.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('files/js/script_tplink2.js'); ?>"></script>