<style scoped>


input.screenshot:before {
  width: 71px;
  height: 32px;
  font-size: 12px;
  line-height: 32px;
  content: 'ADD Image';
  display: inline-block;
  background: #646c9a ;
  color:white;
  text-align: center;
  font-family: Helvetica, Arial, sans-serif;
  cursor: pointer;
}

input.screenshot::-webkit-file-upload-button {
  visibility: hidden;
}

.form-roww .col-lg-4{
		margin-bottom:2rem;
	}

	.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.loader {
  border: 8px solid #f3f3f3;
  border-top: 8px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

</style>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<template>

	<div>
		<form class="kt-form kt-form--label-right" id="audit_component_form" enctype="multipart/form-data">
									<!--begin::Portlet-->
		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title kt-font-light">
						Basic Details
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div v-if="loading" class="loader-overlay">
					<div class="loader"></div>
				</div>
				<div class="form-group row">
					<div class="col-lg-4">
						<a href="#" class="btn btn-primary" id="kt_dashboard_daterangepicker" data-toggle="kt-tooltip" title="Select raw data daterange" data-placement="left">
							<span class="kt-subheader__btn-daterange-title" id="kt_dashboard_daterangepicker_title">Today</span>&nbsp;
							<span class="kt-subheader__btn-daterange-date" id="kt_dashboard_daterangepicker_date">Aug 16</span>
							<i class="flaticon2-calendar-1"></i>
						</a>
						<button type="button" class="btn btn-success btn-elevate btn-icon" @click="getRawDataOnDateRange"><i class="la la-search"></i></button>
						<br/><small>By default, calls list is based on current audit Cycle {{audit_cycle}}</small>
					</div>
				</div>
				<div class="form-group row form-roww">
					<div class="col-lg-4">
						<label>Communication Instance ID(Call ID):</label>

						<!-- <select class="form-control m-select2" id="kt_select2_1" name="param" v-model="sheet_basic_data[0].comm_instance_id" v-on:input="something = $event.target.value">
							<option value="">Select a call</option>
							<option v-for="(value,key) in my_alloted_call_list" v-bind:value="key">
							    {{ value }}
							  </option>
						</select> -->

						<v-select label="value" :options="my_alloted_call_list" v-model="sheet_basic_data[0].comm_instance_id" @input="getRawData()"></v-select>
					</div>
					<div class="col-lg-4">
						<label>Client:</label>
						<input type="text" class="form-control" v-model="sheet_basic_data[0].client" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>Partner:</label>
						<input type="text" class="form-control" v-model="sheet_basic_data[0].partner_name" readonly="readonly">
					</div>
			
					<div class="col-lg-4">
						<label>Audit Date:</label>
						<!-- <input type="date" class="form-control" placeholder="yyyy-mm-dd" v-model="submission_data[0].audit_date" required="required" /> -->
						<input type="text" class="form-control" readonly :value="submission_data[0].audit_date" />
					</div>
					<div class="col-lg-4">
						<label>Agent Name:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].agent_name" readonly="readonly">
					</div>
				
					<!-- <div class="col-lg-4">
						<label>Caller ID:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].tl_name" readonly="readonly">
					</div> -->
					<div class="col-lg-4">
						<label>QA Name:</label>
						<input type="text" class="form-control" :value="auditorName" readonly="readonly">
					</div>
				
					<div class="col-lg-4">
						<label>QTL Name:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].tl_name" readonly="readonly">
					</div>
				
					<div class="col-lg-4">
						<label>Campaign Name:</label>
						<input type="text" class="form-control" v-model="submission_data[0].campaign_name">
					</div>
					<!-- <div class="col-lg-4">
						<label>Call Sub Type:</label>
						<input type="text" class="form-control" v-model="submission_data[0].call_sub_type" >
					</div> -->
					<div class="col-lg-4">
						<label>Audit Type:</label>
						<input type="text" class="form-control" v-model="submission_data[0].audit_type">
					</div>
					
				
					
					<!-- <div class="col-lg-4">
						<label>Agent Role</label>
						<input type="text" class="form-control" v-model="submission_data[0].agent_role">
					</div> -->
					<div class="col-lg-4">
						<label>Location</label>
						<select class="form-control" v-model="submission_data[0].area_location" >
							<option>Select one!</option>
							<option v-for="(vv) in area_city" :value="vv">{{vv}}</option>
						</select>
					</div>
					<div class="col-lg-4">
						<label>Call Type:</label>
						<select class="form-control" v-model="submission_data[0].call_types" required="required">
							<option value="">Select one!</option>
							<option value="Random Call">Random Call</option>
							<option value="Agent Error">Agent Error</option>
							
						</select>
					</div>
					<div class="col-lg-4">
						<label>Case ID:</label>
						<input type="text" class="form-control" v-model="submission_data[0].case_id">
					</div>
					
			
					<div class="col-lg-4">
						<label>Language 1:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].language" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>Language for QA</label>
						<input type="text" class="form-control" v-model="submission_data[0].language_for_qa">
					</div>
			
					
					<div class="col-lg-4">
						<label>Call Disposition:</label>
						<input type="text" class="form-control" v-model="submission_data[0].disposition">
					</div>
					<div class="col-lg-4">
						<label>Customer/Partner Name:</label>
						<input type="text" class="form-control" v-model="submission_data[0].customer_name">
					</div>
					<div class="col-lg-4">
						<label>Caller number:</label>
						<input type="text" class="form-control" v-model="submission_data[0].customer_phone">
					</div>
				
					<!-- <div class="col-lg-4">
						<label>QM-Sheet Version:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].version" readonly="readonly">
					</div>
					<div class="col-lg-4">
						<label>QRC 1:</label>
						<input type="text" class="form-control" :value="sheet_basic_data[0].call_type" readonly="readonly">
					</div> -->
					<div class="col-lg-4">
						<label>CRN No./Order ID</label>
						<input type="text" class="form-control" v-model="submission_data[0].order_id">
					</div>
					<div class="col-lg-4">
						<label>Caller ID:</label>
						<input type="text" class="form-control" v-model="submission_data[0].caller_id">
					</div>
					<div class="col-lg-4">
						<label>QRC for QA:</label>
						<!-- <input type="text" class="form-control" v-model="submission_data[0].qrc_2"> -->
						<!--<select class="form-control" v-model="submission_data[0].qrc_2" required="required">
							<option value="">Select one!</option>
							<option value="Query">Query</option>
							<option value="Request">Request</option>
							<option value="Complaint">Complaint</option>
						</select>-->
						<div v-if="n_client_id == 9 || n_client_id == 13">	
						<select class="form-control" v-model="submission_data[0].qrc_2" required="required">
							<option value="">Select one!</option>
							<option v-for="(v,k) in callTypeList" :value="v">{{v}}</option>
						</select>
						</div>
					   <div v-else>	
					   	<select class="form-control" v-model="submission_data[0].qrc_2" required="required">
							<option value="">Select one!</option>
							<option value="Query">Query</option>
							<option value="Request">Request</option>
							<option value="Complaint">Complaint</option>
						</select>
					   </div>
						
					</div>
			
						
						
						<!-- <div class="col-lg-4">
							<label>Scenario Code:</label>
							<input type="text" class="form-control" v-model="submission_data[0].error_code">
						</div> -->
						<!-- <div class="col-lg-4">
							<label>Vehicle Type:</label>
							<input type="text" class="form-control" v-model="submission_data[0].vehicle_type">
						</div> -->
						<div class="col-lg-4">
						<label>Call Date & Time:</label>
						<input type="text" class="form-control" v-model="submission_data[0].call_time" required="required">
					</div>
					<div class="col-lg-4">
						<label>Call Duration:</label>
						<input type="text" class="form-control" v-model="submission_data[0].call_duration" required="required">
					</div>
						
			
					
					<div class="col-lg-4">
						<label>Refrence No.:</label>
						<input type="text" class="form-control" v-model="submission_data[0].refrence_number">
					</div>
					<div class="col-lg-4">
						<label>Caller Type</label>
						<select class="form-control" v-model="submission_data[0].caller_type" required="required" @change="onCallerChange">
							<option value="">Select one!</option>
							<option value="Partner">Partner</option>
							<option value="Customer">Customer</option>
						</select>
					</div>
					<!-- <div class="col-lg-4">
						<label>Order Stage</label>
						<select class="form-control" v-model="submission_data[0].order_stage" required="required" @change="onOrderStageChange">
							<option value="Select one">Select one!</option>
							<option v-for="(vv) in order_stage_list" :value="vv">{{vv}}</option>
						</select>
					</div> -->
					<div class="col-lg-4">
						<label>Order Stage</label>
						<v-select :options="order_stage_list" v-model="submission_data[0].order_stage" @input="onOrderStageChange"></v-select>
						<!-- <v-multiselect :options="order_stage_list"  :multiple="true" v-model="submission_data[0].order_stage" @change="onOrderStageChange()"> </v-multiselect> -->
						<!-- <select class="form-control" v-model="submission_data[0].order_stage" required="required" @change="onOrderStageChange">
							<option value="Select one">Select one!</option>
							<option v-for="(vv) in order_stage_list" :value="vv">{{vv}}</option>
						</select> -->
					</div>
					
					<!-- <div class="col-lg-4">
						<label>Language for QA:</label>
					<input type="text" class="form-control" v-model="submission_data[0].language_2">
					</div> -->
					<div class="col-lg-4">
						<label>Issues</label>
						<!-- <select class="form-control" v-model="submission_data[0].issues" required="required"
						@change="onIssuesChange">
							<option>Select one!</option>
							<option v-for="(vv) in issues_list" :value="vv">{{vv}}</option>
						</select> -->
						<v-select  :options="issues_list" v-model="submission_data[0].issues" @input="onIssuesChange"></v-select>
					</div>
					<div class="col-lg-4">
						<label>Sub Issues</label>
						<!-- <select class="form-control" v-model="submission_data[0].sub_issues" required="required"
						 @change="onSubIssuesChange">
							<option>Select one!</option>
							<option v-for="(vv) in all_sub_issues" :value="vv">{{vv}}</option>
						</select> -->
						<v-select  :options="all_sub_issues" v-model="submission_data[0].sub_issues" @input="onSubIssuesChange"></v-select>
					</div>
					<div class="col-lg-4">
						<label>Scanerio</label>
						<!-- <select class="form-control" v-model="submission_data[0].scanerio" required="required" 
						@change="onScanerioChange">
							<option>Select one!</option>
							<option v-for="(vv) in scanerio_list" :value="vv">{{vv}}</option>
						</select> -->
						<v-select :options="scanerio_list" v-model="submission_data[0].scanerio" @input="onScanerioChange"></v-select>
					</div>
				
			
					<div class="col-lg-4">
						<label>Scanerio Codes</label>
						<select class="form-control" v-model="submission_data[0].scanerio_codes" required="required" @change="onScanerioCodeChange">
							<option>Select one!</option>
							<option v-for="(vv) in all_scanerios_codes" :value="vv">{{vv}}</option>
						</select>
						
					</div>
					<div class="col-lg-4">
						<label>Error Reason Type</label>
						<!-- <select class="form-control" v-model="submission_data[0].error_reason_type" 
						 required="required" @change="onErrorReasonTypeChange" >
							<option >Select one!</option>
							<option v-for="(vv) in all_error_reasons_types" :value="vv">{{vv}}</option>
						</select> -->
						<v-multiselect :options="all_error_reasons_types"  :multiple="true"  v-model="submission_data[0].error_reason_type" @input="onErrorReasonTypeChange"> </v-multiselect>
					</div>
					<div class="col-lg-4">
						<label>Error Reasons</label>
						<!-- <select class="form-control" v-model="submission_data[0].error_reasons" 
						required="required" @change="onErrorReasonChange">
							<option>Select one!</option>
							<option v-for="(vv) in all_error_erasons" :value="vv">{{vv}}</option>
						</select> -->
						<v-multiselect :options="all_error_erasons"  :multiple="true" v-model="submission_data[0].error_reasons" @input="onErrorReasonChange"> </v-multiselect>
					</div>

				
					
				
					<div class="col-lg-4">
						<label>Error Code:</label>
						<input type="text" class="form-control" v-model="submission_data[0].new_error_code">
					</div>
<!-- start -->
					<!-- <p>Add One or More Contacts</p>
						<div class="form-group row" v-for="(input, index) in inputs">
						<div class="col-lg-3">
							<input type="text" :name="'contact[' + index + '][name]'" class="form-control" placeholder="Contact name">
						</div>
						<div class="col-lg-3">
							<input type="text" :name="'contact[' + index + '][phone]'" class="form-control" placeholder="Phone">
						</div>
						<div class="col-lg-3">
							<input type="text" :name="'contact[' + index + '][email]'" class="form-control" placeholder="Email">
						</div>
						<div class="col-lg-3">
							<button type="button" @click="deleteRow(index)" class="btn btn-outline-danger rounded-circle">
							<i class="fa fa-times"></i>
							</button>
						</div>
						</div>
					<div class="form-group row">
					<div class="col-lg-12">
						<button type="button" @click="addRow" class="btn btn-outline-secondary">Add Row</button>
					</div>
					</div>
					<div class="form-group row">
					<div class="col-lg-12">
						<button class="btn btn-primary">Submit</button>
					</div>
					</div> -->
	<!-- end			 -->


					<div class="col-lg-4">
						<label>Vehicle Type</label>
						<select class="form-control" v-model="submission_data[0].vehicle_type" required="required" @change="onCallerChange">
							<option value="">Select one!</option>
							<option value="2 Wheeler">2 Wheeler</option>
							<option value="14ft canter">14ft canter</option>
							<option value="E-Loader">E-Loader</option>
							<option value="Pickup 8ft">Pickup 8ft</option>
							<option value="Tata 407">Tata 407</option>
							<option value="3 Wheeler">3 Wheeler</option>
							<option value="3 Wheeler Electric">3 Wheeler Electric</option>
							<option value="Champion">Champion</option>
							<option value="Eeco">Eeco</option>
							<option value="Tata Ace">Tata Ace</option>
							<option value="8ft 1 Ton">8ft 1 Ton</option>
							<option value="NA">NA</option>
						</select>
					</div>

				</div>

				
				<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
				<div class="row" v-if="is_rca_enabled">
					<div class="col-md-2">
						<label>RCA Type:</label>
						<select class="form-control" v-model="selected_rca_type">
							<option v-for="(vv,kk) in rca_type" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA Mode:</label>
						<select class="form-control" v-model="selected_rca_mode" @change="on_rca_mode_change">
							<option v-for="(vv,kk) in rca_mode" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA 1:</label>
						<select class="form-control" v-model="selected_rca1" @change="on_rca1_change">
							<option v-for="(vv,kk) in rca1" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA 2:</label>
						<select class="form-control" v-model="selected_rca2" @change="on_rca2_change">
							<option v-for="(vv,kk) in rca2" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>RCA 3:</label>
						<select class="form-control" v-model="selected_rca3">
							<option v-for="(vv,kk) in rca3" :value="kk">{{vv}}</option>
						</select>
					</div>
					<div class="col-md-2">
						<label>Other Detail:</label>
						<input type="text" name="" class="form-control" v-model="rca_other_detail">
					</div>
				</div>

				<div v-if="is_type_2_rca_enabled">
					<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
					<div class="row">
						<div class="col-md-2">
							<label>Type II RCA Mode:</label>
							<select class="form-control" v-model="selected_type_2_rca_mode" @change="on_type_2_rca_mode_change">
								<option v-for="(vv,kk) in type_2_rca_mode" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA 1:</label>
							<select class="form-control" v-model="selected_type_2_rca1" @change="on_type_2_rca1_change">
								<option v-for="(vv,kk) in type_2_rca1" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA 2:</label>
							<select class="form-control" v-model="selected_type_2_rca2" @change="on_type_2_rca2_change">
								<option v-for="(vv,kk) in type_2_rca2" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA 3:</label>
							<select class="form-control" v-model="selected_type_2_rca3">
								<option v-for="(vv,kk) in type_2_rca3" :value="kk">{{vv}}</option>
							</select>
						</div>
						<div class="col-md-2">
							<label>Type II RCA Other Detail:</label>
							<input type="text" name="" class="form-control" v-model="type_2_rca_other_detail">
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<!--end::Portlet-->

		<div class="kt-portlet kt-portlet--mobile">
			<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #845ec2, #906dc6, #9c7dc9, #a88ccd, #b39cd0);">
				<div class="kt-portlet__head-label">
					<h3 class="kt-portlet__head-title kt-font-light">
						Parameter Details
					</h3>
				</div>
			</div>
			<div class="kt-portlet__body">
				<div class="row">
					<div class="col-md-1 kt-font-bolder text-center">Behavior</div>
					<div class="col-md-11 kt-font-bolder">
						<div class="row">
							<div class="col-md-2 kt-font-bolder text-center">Parameter</div>
							<div class="col-md-1 kt-font-bolder text-center">Observation</div>
							<div class="col-md-1 kt-font-bolder text-center">Demarking</div>
							<div  class="col-md-2 kt-font-bolder text-center">Failure Type</div>
							<div class="col-md-2 kt-font-bolder text-center">Failure Reason</div>
							<div class="col-md-2 kt-font-bolder text-center">Remarks</div>
							<div class="col-md-2 kt-font-bolder text-center">Screen Shot</div>	
						</div>
					</div>
				</div>

				<div class="kt-separator kt-separator--border-dashed kt-separator--space-lg" id="seprator"></div>

				<div class="row flex-container" v-for="(pm,index) in parameters"  style="border-bottom: 1px solid rgb(204, 204, 204); padding: 20px 0px; height: 100%;">
					<div  class="col-md-1 kt-font-bolder kt-font-primary flex-item">{{pm.name}}</div>
					<div  class="col-md-11 sp-row" style="padding: 0px;">
						<div class="row flex-container" v-for="(sp,i) in pm.subs"
						
						v-show="!!checkQuestion(sp.id)">
							<div class="col-md-2 kt-font-bold">
								{{sp.name}} <i class="la la-info-circle kt-font-warning sp-details-top" :title="sp.details"></i>
							</div>
							<div class="col-md-1 pl-0">
								<select class="form-control" v-model="sp.selected_option_model" :ref="'sp_select_'+sp.id" @change="onObservationChange($event)" required="required">
									
									<option v-for="subs_opt in sp.options" :value="subs_opt.key" v-if="subs_opt.value == 'Pass'">Yes</option>
									<option v-for="subs_opt in sp.options" :value="subs_opt.key" v-if="subs_opt.value == 'Fail'">No</option>
									<option v-for="subs_opt in sp.options" :value="subs_opt.key" v-if="subs_opt.value == 'N/A'">{{subs_opt.value}}</option>
								</select>

							</div>
							<div class="col-md-1 pl-0">
								<input type="text" class="form-control" :id="'sp_id_' + sp.id" @change="onScoreChange($event,sp.id,pm.id)" v-if="sp.is_fatal == 1" :value = "100" min="0" readonly>
								<input type="text" class="form-control" :id="'sp_id_' + sp.id" @change="onScoreChange($event,sp.id,pm.id)" v-if="sp.is_fatal == 0" v-model="sp.demark" min="0" readonly>
							</div>
							<div class="col-md-2 pl-0 mt-0">
								<select class="form-control mt-0 pt-0" @change="onReasonTypeChange($event)" v-model="sp.selected_reason_type">
									
									<option v-for="(rtype) in sp.all_reason_type" :value="rtype.key">{{rtype.value}}</option>
								</select>
							</div>
							<div class="col-md-2 pl-0 mt-0">
								<select class="form-control" v-model="sp.selected_reason">
									
									<option v-for="(rtype,key) in sp.all_reasons" :value="key">{{rtype}}</option>
								</select>
							</div> 
							<div class="col-md-2">
							<textarea class="form-control" v-model="sp.remark"></textarea>
								<!-- <input type="text" class="form-control" v-model="sp.remark"> -->
							</div>
							<!-- <div class="col-md-1" style="position:relative;">
            				<label for="choose-file" class="custom-file-upload" id="choose-file-label" style="background:linear-gradient(to right, rgb(132, 94, 194), rgb(144, 109, 198), rgb(156, 125, 201), rgb(168, 140, 205), rgb(179, 156, 208));color:#fff; padding: 8px;border: 1px solid #e3e3e3; border-radius: 5px; border: 1px solid #ccc; display: inline-block;padding: 6px 12px;cursor: pointer;width: 85px;overflow: hidden;   text-overflow: ellipsis;">
   									Add Image
							</label>
							<input name="uploadDocument" type="file" id="choose-file" onclick="myFunction()"  accept=".jpg,.jpeg,.pdf,doc,docx,application/msword,.png" style="display: none;" />
         					</div>	 -->
							 <div class="col-md-2" style="position:relative;">
							<label v-for="subs_opt in sp.options" >

								<div class="col-md-1" style="position:relative;" v-if="subs_opt.value =='Critical'" >
									<input type="file"  title="Select" class="screenshot" :ref="'file_'+i+index" v-if="subs_opt.value =='Critical'"  accept="image/jpeg, image/png" name="screenshot" @change="handleUpload($event,i,index,'screenshot')">
											
								</div>
								
								<label>
								<a v-if="subs_opt.value =='Critical'" @click="removeImage($event,i,index)">
					                        <i class="fas fa-trash"></i>
					            </a>
					        	</label>
							</label>
							</div>
							
				            
								

							
						</div>
					</div>
					

				</div>

				<div class="row" style="margin-top: 15px;">
						<div class="col-lg-2">
							<label>Overall Call Summary:</label>
							<textarea class="form-control" v-model="submission_data[0].overall_summary"></textarea>
						</div>
						<div class="col-lg-2">
							<label>Feedback to Agent:</label>
							<textarea class="form-control" v-model="submission_data[0].feedback_to_agent"></textarea>
						</div>
						<div class="col-lg-3">
							<label>Feedback to Agent Recording:</label>
							<center>
								<button type="button" id="start-aud-recording" class="bg-transparent  border-0 text-success"
									@click="startRecording(this)"><img class="mr-2" height="20" src="https://mamamoney.qaviews.com/img/play.png" alt=""/>
								 
								</button>
						
								<button type="button" id="stop-aud-recording" class="bg-transparent border-0 text-danger"
									@click="stopRecording(this)"><img class="mr-2" height="20" src="https://mamamoney.qaviews.com/img/stop.png" alt=""/>
									
								</button>
							</center>
							<div id="aud-recorder">
								
								<div class="recording" id="aud-record-status">
									
								</div>
								
								
								
							</div>
						</div>
						<div class="col-lg-2">
							<label>Good or Bad Call ?:</label>
							<select class="form-control" v-model="submission_data[0].good_bad_call">
								<option value="0">Select one!</option>
								<option value="1">Good Call</option>
								<option value="2">Bad Call</option>
							</select>
						</div>
						<div class="col-lg-2">
							<label>Upload Call:</label>
							<input type="file" name="good_bad_call_file" class="form-control" ref="good_bad_call_file" multiple v-on:change="handleCallUpload()">
							<div id="myProgress">
								<div id="myBar" >10%</div>
							</div>
						</div>
						<div class="col-lg-1">
							<button type="button" class="btn btn-success" id="myupload" @click="upload_call(1)">Upload</button>
						</div>
					</div>
			</div>
		</div>

			<div class="row">
				<div class="col-lg-12">
				<div class="kt-portlet kt-portlet--mobile">
					<div class="kt-portlet__head" style="background-image: linear-gradient(to right, #ffc75f, #ffd361, #fedf65, #fcec6a, #f9f871);">
						<div class="kt-portlet__head-label">
							<h3 class="kt-portlet__head-title kt-font-light">
								Score Result
							</h3>
						</div>
					</div>
					<div class="kt-portlet__body">
						<div class="row" style="border-bottom:1px solid #ccc;">
							<div class="col-lg-4 kt-font-bolder">&nbsp;</div>
							<div class="col-lg-4 kt-font-bolder">Scored</div>
							<div class="col-lg-4 kt-font-bolder">Scores%</div>
						</div>
						<div class="row" style="padding: 15px 0;">
							<div class="col-lg-2 kt-font-bolder">Behaviour</div>
							<div class="col-lg-2 kt-font-bolder">Scorable</div>
							<div class="col-lg-2 kt-font-bolder">With FATAL</div>
							<div class="col-lg-2 kt-font-bolder">Without FATAL</div>
							<div class="col-lg-2 kt-font-bolder">With FATAL</div>
							<div class="col-lg-2 kt-font-bolder">Without FATAL</div>
						</div>
						<div class="row" v-for="pm in parameters" style="border-bottom:1px solid #ccc; padding: 20px 0; height: 100%; " v-if="pm.is_non_scoring==0">
							
							<div class="col-lg-2 kt-font-bold kt-font-primary">{{pm.name}}</div>
							<div class="col-lg-2">{{pm.temp_total_weightage}}</div>
							<div class="col-lg-2 kt-font-danger">{{pm.score_with_fatal}}</div>
							<div class="col-lg-2">{{pm.score_without_fatal}}</div>
							<div class="col-lg-2 kt-font-danger">{{(pm.is_non_scoring)?0.00:((pm.score_with_fatal/pm.temp_total_weightage)*100).toFixed(2)}} %</div>
							<div class="col-lg-2">{{(pm.is_non_scoring)?0.00:((pm.score_without_fatal/pm.temp_total_weightage)*100).toFixed(2)}} %</div>
							
							
						</div>
						<div class="row" style="padding: 20px 0; height: 100%; ">
							<div class="col-lg-2 kt-font-bold kt-font-success">Over All</div>
							<div class="col-lg-2 kt-font-bold" >{{overAllTotalScoreRow[0].total_scorable}}</div>
							<div class="col-lg-2 kt-font-bold kt-font-danger">{{overAllTotalScoreRow[0].with_fatal}}</div>
							<div class="col-lg-2 kt-font-bold">{{overAllTotalScoreRow[0].without_fatal}}</div>
							<div class="col-lg-2 kt-font-bold kt-font-danger">{{(overAllTotalScoreRow[0].with_fatal_per ==0)?"0":overAllTotalScoreRow[0].with_fatal_per}} %</div>
							<div class="col-lg-2 kt-font-bold">{{(overAllTotalScoreRow[0].without_fatal_per==0)?"0":overAllTotalScoreRow[0].without_fatal_per}} %</div>
						</div>
						
					</div>
				</div>
				</div>
			</div>

			<div class="row">
										<div class="col-md-1">
											<button type="button" class="btn btn-success" id="MySubmitButton" @click="submitMe(1)">Final Submit</button>&nbsp;&nbsp;&nbsp;
										</div>
										<div class="col-md-2">
											<button type="button" class="btn btn-warning" id="MySaveButton" @click="submitMe(0)">Save</button>&nbsp;&nbsp;&nbsp;
										</div>
										<div class="col-md-2">
											<button type="button" class="btn btn-danger">Cancel</button>
										</div>
										<div class="col-md-6">
											<p align="right"><a :href="'/audited_list/'+qmSheetId" class="btn btn-warning">Back to List</a></p>
										</div>
									</div>
	
	</form>

	</div>
</template>
<script>
	let blob = null;
	let selectedMedia = "aud";
	let chunks = [];
	const audioMediaConstraints = {
	audio: true,
	video: false,
	};
	const videoMediaConstraints = {
	audio: true,
	video: true,
	};

	import vSelect from 'vue-select';
	import vMultiselect from 'vue-multiselect';
 	Vue.component('v-select', vSelect);
 	Vue.component('v-multiselect', vMultiselect);


	export default {
		props:['qmSheetId','todayDate','auditorName','auditorId'],
		data(){
		  	return{
		  		submit_type:0,
				call_file:'',
				recording_file:'',
		  		sheet_basic_data:[{client:null,
		  						   comm_instance_id:'',
		  						   agent_name:null,
		  						   qa_name:null,
		  						   tl_name:null,
								   caller_id:null,
		  						   language:null,
		  						   call_type:null,
		  						   call_sub_type:null,
								   agent_role:null,
		  						   campaign_name:null,
								   audit_type:null,
		  						   disposition:null,
		  						   customer_name:null,
								   order_id:null,
								   error_reasons:null,
								   error_reason_type:null,
		  						   customer_phone:null,
		  						   qm_sheet_version:null,
		  						   partner_name:null
		  						}],
				parameters:[],
				subs:[],
				screenshot:[],
				image:'',
				type_b_scoring_option:[],
				overAllTotalScore:0,
				overAllTotalScoreRow:[{total_scorable:0,with_fatal:0,without_fatal:0,with_fatal_per:0,without_fatal_per:0}],
				submission_data:[{company_id:null,
								  client_id:null,
								  partner_id:null,
								  qm_sheet_id:null,
								  audit_type:null,
								  process_id:null,
								  raw_data_id:null,
								  order_id:null,
								  area_location:null,
								  call_types:null,
								  caller_id:null,
								  audited_by_id:null,
								  is_critical:null,
								  agent_role:null,
								  error_reasons:null,
								  overall_score:null,
								  partner_id:null,
								  qrc_2:null,
								  language_2:null,
								  audit_date:'',
								  case_id:null,
								  overall_summary:null,
								  feedback_to_agent:null,
								  feedback_to_agent_recording:null,
								  disposition:null,
								  call_time:null,
								  call_disposition:null,
								  error_reason_type:null,
								  call_duration:null,
								  customer_name:null,
		  						  customer_phone:null,
		  						  good_bad_call:0,
		  						  good_bad_call_file:null,
		  						  call_sub_type:null,
		  						  delay1:null,
		  						  delay2:null,
								  delay3:null,
		  						  delay4:null,
		  						  holiday:null,
								  error_code:null,
								  vehicle_type:null,
								  new_error_code:null,
								  order_stage:null,
								  caller_type:null,
								  vehicle_type:null,
								  issues:null,
								  sub_issues:null,
								  scanerio_codes:null,
								  scanerio:null,
								  language_for_qa:null,
		  						  refrence_number:null}],
			    my_alloted_call_list:[],
			    n_client_id:0,
			    callTypeList:[],
			    final_call_fatal_status:0,
			    total_non_scoring_parms:0,
			    is_rca_enabled:0,
				loading : false,
			    is_type_2_rca_enabled:0,
			    rca_type:[],
			    selected_rca_type:0,
			    rca_mode:[],
			    selected_rca_mode:0,
			    rca1:[],
			    selected_rca1:0,
			    rca2:[],
			    selected_rca2:0,
			    rca3:[],
			    selected_rca3:0,
			    rca_other_detail:null,
			    type_2_rca_mode:[],
			    selected_type_2_rca_mode:0,
			    type_2_rca1:[],
			    selected_type_2_rca1:0,
			    type_2_rca2:[],
			    selected_type_2_rca2:0,
			    type_2_rca3:[],
			    selected_type_2_rca3:0,
			    type_2_rca_other_detail:null,
			    errors:[],
				all_error_reasons_types:[],
				order_stage_list:[],
				issues_list:[],
				area_city:[],
				scanerio_list:[],
				all_error_erasons:[],
				all_sub_issues:[],
				all_scanerios_codes:[],
				all_questions_list:[],
			    audit_cycle:'',
				inputs: [],
		  	}
		  },
		  mounted() {
		  	var self = this;
	        axios.get('/get_qm_sheet_details_for_audit/'+this.qmSheetId).then(function (response){
	        	var temp_first_data = response.data.data;
	        	console.log(temp_first_data);
	        	//my_alloted_call_list
	          	self.my_alloted_call_list = temp_first_data.my_alloted_call_list;
	          	//my_alloted_call_list
	          	self.callTypeList=temp_first_data.callTypeList;
	          	self.n_client_id=temp_first_data.client_id;
	        	
	          	self.sheet_basic_data[0].client = temp_first_data.sheet_details.client.name;
	          	self.sheet_basic_data[0].version = temp_first_data.sheet_details.version;

	          	self.parameters = temp_first_data.simple_data;
	          	self.type_b_scoring_option = temp_first_data.type_b_scoring_option;
	          	//prep for submission

	          	self.submission_data[0].company_id = temp_first_data.sheet_details.company_id;
	          	self.submission_data[0].client_id = temp_first_data.sheet_details.client_id;
	          	self.submission_data[0].process_id = temp_first_data.sheet_details.process_id;
	          	self.submission_data[0].audited_by_id = self.auditorId;
	          	self.submission_data[0].qm_sheet_id = temp_first_data.sheet_details.id;
	          	self.submission_data[0].audit_date = temp_first_data.audit_timestamp;

	          	// Tiwari code
				self.submission_data[0].delay1 = temp_first_data.sk_client.delay1;
				self.submission_data[0].delay2 = temp_first_data.sk_client.delay2;
				self.submission_data[0].holiday = temp_first_data.sk_client.holiday;
				self.submission_data[0].delay3 = temp_first_data.sk_client.delay3;
				self.submission_data[0].delay4 = temp_first_data.sk_client.delay4;
				// Tiwari Code

	          	//prep for submission

	          	self.audit_cycle = temp_first_data.audit_cycle;

	          	self.is_rca_enabled = temp_first_data.sheet_details.client.rca_enabled;
	          	self.is_type_2_rca_enabled = temp_first_data.sheet_details.client.rca_two_enabled;

	          	self.rca_type = temp_first_data.rca_type;
	          	self.rca_mode = temp_first_data.rca_mode;
	          	self.type_2_rca_mode = temp_first_data.type_2_rca_mode;

	          
	        }).catch(function (error) {
	            console.log(error);
	        });


			axios.get('/getAllRegion').then(function(response){
				self.area_city = response.data.data; 
				
			}).catch(function (error) {
	            console.log(error);
	        });

			
			axios.get('/error_code_mapping/').then(function (response){
				self.all_error_reasons_types = response.data.data;    
	        }).catch(function (error) {
	            console.log(error);
	        });

			// axios.get('/order_stage_mapping/').then(function (response){
			// 	self.order_stage_list = response.data.data;    
	        // }).catch(function (error) {
	        //     console.log(error);
	        // });

			// axios.get('/issues_mapping/').then(function (response){
			// 	self.issues_list = response.data.data;    
	        // }).catch(function (error) {
	        //     console.log(error);
	        // });
			
			// axios.get('/snaerios_mapping/').then(function (response){
			// 	self.scanerio_list = response.data.data;    
	        // }).catch(function (error) {
	        //     console.log(error);
	        // });
		//	self.addRow();

		  },
		  methods:{
		  	getRawData: function(){
				this.loading = true;
		  		document.getElementById("audit_component_form").reset();
		  		Object.keys(this.parameters).forEach(key_param => {
						  Object.keys(this.parameters[key_param].subs).forEach(key_sub_param => {
						  	this.parameters[key_param].subs[key_sub_param].selected_option_model=null;
						  	this.parameters[key_param].subs[key_sub_param].remark=null;
						  	this.parameters[key_param].subs[key_sub_param].selected_options=null;
						  	this.parameters[key_param].subs[key_sub_param].selected_reason=null;
						  	this.parameters[key_param].subs[key_sub_param].selected_reason_type=null;
						  	this.parameters[key_param].subs[key_sub_param].temp_weight=0;
						  	this.parameters[key_param].subs[key_sub_param].screenshot= 0;
						  })
				});
		            this.submission_data[0].customer_phone =null;
		            this.submission_data[0].customer_name =null;
		            this.submission_data[0].qrc_2 =null;
					this.submission_data[0].audit_type = null;
					this.submission_data[0].call_types = null;
					
		            this.submission_data[0].disposition =null;
					this.submission_data[0].call_disposition =null;
					
  					this.submission_data[0].order_id =null;
					this.submission_data[0].location =null;
					this.submission_data[0].caller_id =null;
					
					this.submission_data[0].call_time =null;
  					this.submission_data[0].call_duration =null;
  					this.submission_data[0].overall_summary =null;
  					this.submission_data[0].feedback_to_agent =null;
  					this.submission_data[0].case_id =null;
  					this.submission_data[0].refrence_number =null;
					this.submission_data[0].language_for_qa =null;
					  
					this.submission_data[0].order_stage =null;
					// this.submission_data[0].error_reason_type =null;
					// this.submission_data[0].error_reasons =null;
					
					this.submission_data[0].caller_type =null;
					this.submission_data[0].vehicle_type =null;
					  
					this.submission_data[0].agent_role =null;
					  
					this.submission_data[0].error_code =null;
					this.submission_data[0].vehicle_type =null;
					this.submission_data[0].new_error_code =null;
					this.submission_data[0].issues =null;
					this.submission_data[0].scanerio_codes =null;
					
					this.submission_data[0].scanerio =null;
					
					this.submission_data[0].sub_issues =null;
					
  					this.submission_data[0].language_2 =null;
  					this.submission_data[0].good_bad_call =0;

		  		var self = this;
		          axios.get('/get_raw_data_for_audit/'+this.sheet_basic_data[0].comm_instance_id.key).then(function (response){
		            var temp_data_b = response.data.data;
		            self.sheet_basic_data[0].agent_name = temp_data_b.agent_name;
		            self.sheet_basic_data[0].tl_name = temp_data_b.tl_details.name;
					//self.sheet_basic_data[0].agent_tl_name = temp_data_b.agent_details.get_qtl_name.name;
		            self.sheet_basic_data[0].language = temp_data_b.language;
		            self.sheet_basic_data[0].call_type = temp_data_b.call_type;
		            self.sheet_basic_data[0].partner_name = temp_data_b.partner_detail.name;

		            self.submission_data[0].customer_phone = temp_data_b.phone_number;
		            self.submission_data[0].customer_name = temp_data_b.customer_name;
		            
		            self.submission_data[0].partner_id = temp_data_b.partner_id;
		            self.submission_data[0].raw_data_id = temp_data_b.id;
					self.submission_data[0].case_id = temp_data_b.case_id;
					self.submission_data[0].caller_id = temp_data_b.caller_id;
					
		            self.submission_data[0].disposition = temp_data_b.disposition;

					self.submission_data[0].order_stage = temp_data_b.order_stage;
					self.submission_data[0].issues = temp_data_b.issues;
					self.submission_data[0].sub_issues = temp_data_b.sub_issues;
					self.submission_data[0].scanerio = temp_data_b.scanerio;
					self.submission_data[0].scanerio_codes = temp_data_b.scanerio_codes;
					self.submission_data[0].error_reason_type = temp_data_b.error_reason_type;
					self.submission_data[0].error_reasons = temp_data_b.error_code_reason;
					self.submission_data[0].new_error_code = temp_data_b.error_code;

					self.submission_data[0].vehicle_type = temp_data_b.vehicle_type;
  					self.submission_data[0].call_time = temp_data_b.call_time;
					self.submission_data[0].audit_type = temp_data_b.audit_type;
					self.submission_data[0].order_id = temp_data_b.order_id;
					self.submission_data[0].location = temp_data_b.location;
					
  					self.submission_data[0].call_duration = temp_data_b.call_duration;
  					self.submission_data[0].call_sub_type = temp_data_b.call_sub_type;
  					self.submission_data[0].campaign_name = temp_data_b.campaign_name;
					self.submission_data[0].area_location = temp_data_b.location;
					self.onErrorReasonTypeChange();
					self.onCallerChange();
					self.onOrderStageChange();
					self.onIssuesChange();
					self.onScanerioChange();
					self.loading = false;
				}).catch(function (error) {
		              swal.fire("Oop's!", "Call not found, please check your call Id.", "error");
					  
		              self.sheet_basic_data[0].comm_instance_id=null;
		          });
		  	},

			onErrorReasonTypeChange: function()
			{
			var self = this;
			var params = {
				error_reason_type:this.submission_data[0].error_reason_type
			};
			axios.post('/get_error_reasons_list',params).then(function (response){
				//console.log(response.data.data);
				self.all_error_erasons = response.data.data;
			}).catch(function (error) {
				console.log(error);
			});
			},

			onErrorReasonChange: function()
			{
			var self = this;
			var params = {
				error_reason_type:this.submission_data[0].error_reason_type,
				error_reasons:this.submission_data[0].error_reasons
			};
			axios.post('/get_error_code_list',params).then(function (response){
				//console.log(response.data.data);
				self.submission_data[0].new_error_code = response.data.data;
			}).catch(function (error) {
				console.log(error);
			});
			},

			onCallerChange: function()
			{
			var self = this;
			self.loading = true;
			var params = {
				caller_type:this.submission_data[0].caller_type
			};
			axios.post('/order_stage_mapping',params).then(function (response){
				//console.log(response.data.data);
				self.order_stage_list = response.data.data;
				self.onOrderStageChange();
				self.onIssuesChange();
				self.onSubIssuesChange();
				self.onScanerioChange();
				self.loading = false;
			}).catch(function (error) {
				console.log(error);
			});
			},

			onOrderStageChange: function()
			{
			var self = this;
			var params = {
				caller_type:this.submission_data[0].caller_type,
				order_stage:this.submission_data[0].order_stage
			};
			axios.post('/issues_mapping',params).then(function (response){
				//console.log(response.data.data);
				self.issues_list = response.data.data;
			}).catch(function (error) {
				console.log(error);
			});
			},
			
			
			onIssuesChange: function()
			{
			var self = this;
			var params = {
				caller_type:this.submission_data[0].caller_type,
				order_stage:this.submission_data[0].order_stage,
				issues:this.submission_data[0].issues
			};
			axios.post('/get_sub_issues_list',params).then(function (response){
				//console.log(response.data.data);
				self.all_sub_issues = response.data.data;
			}).catch(function (error) {
				console.log(error);
			});
			},

			onScanerioChange: function()
			{
			var self = this;
			var params = {
				caller_type:this.submission_data[0].caller_type,
				order_stage:this.submission_data[0].order_stage,
				issues:this.submission_data[0].issues,
				sub_issues:this.submission_data[0].sub_issues,
				scanerio:this.submission_data[0].scanerio

			};
			axios.post('/get_scanerios_codes_list',params).then(function (response){
				//console.log(response.data.data);
				self.all_scanerios_codes = response.data.data;
			}).catch(function (error) {
				console.log(error);
			});
			},

			onScanerioCodeChange:function()
			{
			var self = this;
			var params = {
				caller_type:this.submission_data[0].caller_type,
				order_stage:this.submission_data[0].order_stage,
				issues:this.submission_data[0].issues,
				sub_issues:this.submission_data[0].sub_issues,
				scanerio:this.submission_data[0].scanerio,
				scanerio_codes:this.submission_data[0].scanerio_codes

			};
			axios.post('/get_questions_list',params).then(function (response){
				//console.log(response.data.data);
				 //let jsonArray = JSON.parse(response.data.data);
				 //const keysOnly = Object.keys(jsonArray);
				// let array_conversion = Object.entries(jsonArray);
				//console.log(jsonArray);
				// var myobj = JSON.parse(JSON.stringify(response.data.data));
				self.all_questions_list = JSON.parse(response.data.data);
			
				//this.parameters[key_param].subs[key_sub_param].selected_option_model
				console.log((self.parameters))
				let parameterss = self.parameters
			
				//this.all_questions_list.hasOwnProperty(keyToCheck)
				const all_parameters = Object.values(parameterss);
				
				all_parameters.forEach((parameter, para_id)=> {
					const all_sub_parameters = Object.values(parameter.subs);
					all_sub_parameters.forEach((sub_parameter, sub_para_id) =>{

						if(self.all_questions_list.hasOwnProperty(sub_parameter.id)){
							self.parameters[parameter.id].subs[sub_parameter.id]['demark'] = 0;
							self.parameters[parameter.id].subs[sub_parameter.id]['score'] = self.parameters[parameter.id].subs[sub_parameter.id]['orignal_weight'];
							let key =  Object.keys(self.parameters[parameter.id].subs[sub_parameter.id].options);

							// to show yes or no by default in observation
							if(self.all_questions_list[sub_parameter.id]=='Yes'){
							
								//console.log(key[0]);
								//self.parameters[parameter.id].subs[sub_parameter.id].selected_option_model = key[0];
								// let ref = 'sp_select_'+sub_parameter.id;
								// let element = self.$refs[ref];
								// //console.log(self.$refs.good_bad_call_file.files[0]);
								// //console.log(self.$refs.good_bad_call_file.files[0][ref]);
								// //console.log(self.$refs.ref);
								// //console.log(self.$refs[ref]);
								// element.$el.style.backgroundColor = 'green';
								
							}else{
								// self.parameters[parameter.id].subs[sub_parameter.id].selected_option_model = key[1];
								
							} 

						}else{
							delete self.parameters[parameter.id].subs[sub_parameter.id]
						}
					//console.log(sub_para_id)
					});
				});
				self.calculateSummary();
			}).catch(function (error) {
				console.log(error);
			});
			},
			onSubIssuesChange: function()
			{
			var self = this;
			var params = {
				caller_type:this.submission_data[0].caller_type,
				order_stage:this.submission_data[0].order_stage,
				issues:this.submission_data[0].issues,
				sub_issues:this.submission_data[0].sub_issues
			};
			axios.post('/snaerios_mapping',params).then(function (response){
				//console.log(response.data.data);
				self.scanerio_list = response.data.data;
			}).catch(function (error) {
				console.log(error);
			});
			},

		  	handleUpload(e,sb_id,pm_id,type) {
				console.log(Type);
		  		var reforfile = 'file_'+sb_id+pm_id;
		    	
		  		//this.parameters[pm_id].subs[sb_id].type = null;
			      const img = e.target.files[0];
			      if (sb_id) {
			        var reg = /(.*?)\.(jpg|bmp|jpeg|png|JPG|PNG|JPEG)$/;
			        if (!img.name.match(reg)) {
			          
			          	alert('File Extension Not Supported.\n Only JPG & PNG Extension Format Images are Allowed');
			          	this.$refs[reforfile][0].value = '';
			          	return ;

			        }
			        if (img.size / 1000 > 2048) {

			        alert('Image size must not exceed 2 MB');
			        	this.$refs[reforfile][0].value = '';
			          	return ;
			        }
			        else{
			        	this.createBase64(img,sb_id,pm_id,type)
			        }

			      } 
			      
		    },
				addRow() {
				this.inputs.push({
					name: "",
					phone: "",
					email: ""
				});
				},
				deleteRow(index) {
				this.inputs.splice(index, 1);
				},
		    
		    removeImage(e,sb_id,pm_id){
		    	//console.log(e.target);
		    	//e.target.value = '';
		    	console.log(sb_id+pm_id);
		    	var reforfile = 'file_'+sb_id+pm_id;
		    	//console.log(this.$refs[reforfile][0].value);
		    	this.$refs[reforfile][0].value = '';
		    	//this.$refs.reforfile.value = '';
		    	//this.$refs['file_'+sb_id+pm_id].value = '';
		    	//this.parameters[pm_id].subs[sb_id].screenshot = '';
		    },

		    /*removeImage: function (e) {
		      this.parameters[pm_id].subs[sb_id].screenshot = '';
		    },*/

		    createBase64(fileObj, sb_id,pm_id,type) {
		      const reader = new FileReader()
		      reader.onload = e => {
		      	this.parameters[pm_id].subs[sb_id].type = e.target.result
		      }
		      reader.readAsDataURL(fileObj)
		    },
			
			onScoreChange: function (event, sp_id, p_id){
				var str = event.target.value;
				console.log("demark",str)
				this.parameters[parseInt(p_id)].subs[parseInt(sp_id)]['demark']=parseInt(str);
				this.calculateSummary();
			},

			checkQuestion:function(sp_id){
				if(this.all_questions_list.hasOwnProperty(sp_id)){
					return true;
				}else{
					return false;
				}
			},

		  	onObservationChange: function(event){
		  		console.log(event.target.value);
		  		var str = event.target.value;
		  		var res = str.split("_"); // pid_spid_score_option
				
				  let key = res[1];
					let correct_answer = this.all_questions_list[key];
					let correct_observation = 0;
					let incorrect_observation = 0;
					if(correct_answer == "Yes"){
						correct_observation = 1; 
						incorrect_observation = 2;
					}else if(correct_answer == "No"){
						correct_observation = 2;
						incorrect_observation = 1;
					}

				console.log("selected_option_model",this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_option_model'])	
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type'] = "#";
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reasons'] = '';

				  if(res[3]==incorrect_observation){
				 // console.log(this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type'])
		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type'] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['all_reason_type_fail'];	//set failure type
				  }
		  	

		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_reason_type']=null;
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_reason']=null;
		  		//set failure type

		  		//show me alert box
		  		if(this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['options'][event.target.value]['alert_box']!=null)
		  		{
		  			swal.fire("Alert!", this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['options'][event.target.value]['alert_box'].details, "warning");
		  		}
		  		//show me alert box

		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_options']=res[2];
		  		this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['selected_observation'] = res[3];
				
		  		if(this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['is_non_scoring'])
		  		{
		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['score'] = 0;

		  			this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['temp_weight'] = 0;
		  		}else
		  		{	
		  			
					//console.log(this.all_questions_list,"sp",res[1])
				
				


					if(res[3]==incorrect_observation){
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['demark']= this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['orignal_weight'];
					}else{
						this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['demark']=0;
					}

					if(res[3]==correct_observation)
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['score']= this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['orignal_weight'];
		  			else
						this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['score']=0;

		  			if(res[3]!=correct_observation && res[5]==1){    //res[5]==1 = fatal parameter or not
						
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['is_fatal']=1;
						
					}
		  			else{
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['is_fatal']=0;
					}

		  			if(res[2]=='N/A')
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['temp_weight']=0;
		  			else
		  				this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['temp_weight'] = this.parameters[parseInt(res[0])].subs[parseInt(res[1])]['orignal_weight'];

					//console.log(res[1],"->",correct_answer,"selected observation->",res[3])
		  		}

		  		//calculate total tabe;
		  		this.calculateSummary();
		  	},
		  	calculateSummary: function(){

		  			var self = this;

					this.overAllTotalScoreRow[0].with_fatal=0;
			    	this.overAllTotalScoreRow[0].without_fatal=0;
			    	this.overAllTotalScoreRow[0].with_fatal_per=0;
			    	this.overAllTotalScoreRow[0].without_fatal_per=0;

			    	this.total_non_scoring_parms=0;

			    	var temp_param_fatal_status = 0;

			    	this.final_call_fatal_status = 0;
			    	var total_scorable = 0;
			    	var total_with_fatal_score = 0;
			    	var total_without_fatal_score = 0;
			    	var param_is_fatal=0;

	  				for (const key of Object.keys(this.parameters)) {
					    //console.log(key, this.parameters[key]);
					    var temp_total_para_score = 0;
						var temp_total_para_demark = 0;
					    temp_param_fatal_status = 0;
					    param_is_fatal=0;

					    this.parameters[key]['temp_total_weightage'] = 0;

					    if(this.parameters[key]['is_non_scoring']==0)
					    	this.total_non_scoring_parms =this.total_non_scoring_parms+1;

							
					    for (const key_s of Object.keys(this.parameters[key].subs)) {

							if(this.parameters[key].subs[key_s]['score'] == 0)
					    	//temp_total_para_score += this.parameters[key].subs[key_s]['score'];
					    	temp_total_para_demark += Number(this.parameters[key].subs[key_s]['demark']);
					    
							//console.log("temp_total_para_demark",temp_total_para_demark);
					    	if(this.parameters[key].subs[key_s]['is_fatal'])
					    		temp_param_fatal_status = 1;

					    		// this.parameters[key]['temp_total_weightage'] += this.parameters[key].subs[key_s]['temp_weight'];
					    		//total_scorable = total_scorable+Number(this.parameters[key].subs[key_s]['temp_weight']);

					    		if(this.parameters[(key)].subs[key_s]['is_fatal']==1)
					    			{
					    				this.final_call_fatal_status = 1;
					    				param_is_fatal=1;

					    			}
					    }
						this.parameters[key]['temp_total_weightage'] =  Number(this.parameters[key]['weight']);
						total_scorable += Number(this.parameters[key]['weight']);
						
						
						//console.log("temp_total_para_demark",temp_total_para_demark)
					    if(temp_total_para_demark > Number(this.parameters[key]['max_demark'])){
							console.log("entered");
							temp_total_para_demark = Number(this.parameters[key]['max_demark']);
						}

						temp_total_para_score = this.parameters[key]['weight'] - temp_total_para_demark;

						console.log("total weight",this.parameters[key]['weight'])
						console.log("deamrk",temp_total_para_demark)
						 console.log("temp_total_para_score",temp_total_para_score)
					
						
					

					    if(temp_param_fatal_status)
					    	{
								this.parameters[key].score = temp_total_para_score;
						    	this.parameters[key].score_with_fatal = 0;
						    	this.parameters[key].score_without_fatal = temp_total_para_score;
						    }
					    else
					    	{	
								this.parameters[key].score = temp_total_para_score;
						    	this.parameters[key].score_with_fatal = temp_total_para_score;
						    	this.parameters[key].score_without_fatal = temp_total_para_score;
						    }

						    this.parameters[key]['is_fatal'] = param_is_fatal;
							console.log("para score",this.parameters[key].score)
						    total_with_fatal_score += this.parameters[key].score_with_fatal;
						    total_without_fatal_score += this.parameters[key].score_without_fatal;
					}
						this.overAllTotalScoreRow[0].total_scorable = total_scorable;
						if(this.final_call_fatal_status==1)
						{								
							this.overAllTotalScoreRow[0].with_fatal = 0;
							this.overAllTotalScoreRow[0].without_fatal = total_without_fatal_score;

							this.overAllTotalScoreRow[0].with_fatal_per = 0;
					    	this.overAllTotalScoreRow[0].without_fatal_per = ((this.overAllTotalScoreRow[0].without_fatal/this.overAllTotalScoreRow[0].total_scorable)*100).toFixed(2);
					    }else
					    {
					    	this.overAllTotalScoreRow[0].with_fatal = total_with_fatal_score;
							this.overAllTotalScoreRow[0].without_fatal = total_without_fatal_score;

							this.overAllTotalScoreRow[0].with_fatal_per = ((this.overAllTotalScoreRow[0].with_fatal/this.overAllTotalScoreRow[0].total_scorable)*100).toFixed(2);
					    	this.overAllTotalScoreRow[0].without_fatal_per = ((this.overAllTotalScoreRow[0].without_fatal/this.overAllTotalScoreRow[0].total_scorable)*100).toFixed(2);
					    }
					    self.submission_data[0].is_critical = this.final_call_fatal_status;
					    self.submission_data[0].overall_score = this.overAllTotalScoreRow[0].without_fatal_per;
					    //self.submission_data[0].qm_sheet_id = 
		  		},
		  		submitMe: function(submit_type){
		  		var self = this;

	  			this.submit_type = submit_type;
		  			
		  		if(this.submit_type)
	  			{
	  				document.getElementById("MySubmitButton").className = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
	  				document.getElementById("MySubmitButton").disabled = true;
	  			}else{
	  				document.getElementById("MySaveButton").className = "btn btn-primary kt-spinner kt-spinner--right kt-spinner--sm kt-spinner--light";
	  				document.getElementById("MySaveButton").disabled = true;
	  			}
  				this.submission_data[0].selected_rca_type = this.selected_rca_type;

  				this.submission_data[0].selected_rca_mode = this.selected_rca_mode;
  				this.submission_data[0].selected_rca1 = this.selected_rca1;
  				this.submission_data[0].selected_rca2 = this.selected_rca2;
  				this.submission_data[0].selected_rca3 = this.selected_rca3;
  				this.submission_data[0].rca_other_detail = this.rca_other_detail;

  				this.submission_data[0].selected_type_2_rca_mode = this.selected_type_2_rca_mode;
  				this.submission_data[0].selected_type_2_rca1 = this.selected_type_2_rca1;
  				this.submission_data[0].selected_type_2_rca2 = this.selected_type_2_rca2;
  				this.submission_data[0].selected_type_2_rca3 = this.selected_type_2_rca3;
  				this.submission_data[0].type_2_rca_other_detail = this.type_2_rca_other_detail;

  				var validation_errors = this.validate_me();
  				if(validation_errors.length > 0)
				{
					if(this.submit_type)
					{
						document.getElementById("MySubmitButton").className = "btn btn-success";
						document.getElementById("MySubmitButton").disabled = false;
					}else
					{
						document.getElementById("MySaveButton").className = "btn btn-warning";
						document.getElementById("MySaveButton").disabled = false;
					}
					var x='<strong>Validation!</strong><br/>';
					validation_errors.forEach(function(value){
						x+= value+'</br/>';
					});
					
					swal.fire("Oop's!",x, "error");
					return null;
				}

			     var params = {
			      submit:this.submit_type,
			      submission_data:this.submission_data,
			      parameters:this.parameters
			      };
			     axios.post('/allocation/store_audit',params).then(function (response){
			     	swal.fire("Success", "Audit saved successfully.", "success").then(function(result) {
		                if (result.value) {
		                    location.reload();
		                }
		            });
		            if(self.submit_type)
		            	document.getElementById("MySubmitButton").className = "btn btn-success";
		        	else
		        		document.getElementById("MySaveButton").className = "btn btn-warning";
			      }).catch(function (error) {
			      	if(this.submit_type)
			      		document.getElementById("MySubmitButton").className = "btn btn-success";
			      	else
			      		document.getElementById("MySaveButton").className = "btn btn-warning";


			          console.log(error);
			          alert('Error: Audit saved, but please inform Tech Support.');
			      });
		  		},
		  		validate_me: function(){
		  				this.errors = [];
		  				
		  				if(this.submission_data[0].qrc_2==''||this.submission_data[0].qrc_2==null)
		  					this.errors.push("QRC required.");

		  				if(this.submission_data[0].customer_phone==''||this.submission_data[0].customer_phone==null)
		  					this.errors.push("Customer phone required.");

		  				if(this.submission_data[0].call_time==''||this.submission_data[0].call_time==null)
		  					this.errors.push("Call time required.");

		  				if(this.submission_data[0].call_duration==''||this.submission_data[0].call_duration==null)
		  					this.errors.push("Call duration required.");

		  				if(this.submission_data[0].qrc_2==''||this.submission_data[0].qrc_2==null)
		  					this.errors.push("QRC required.");

		  				if(this.submission_data[0].disposition==''||this.submission_data[0].disposition==null)
		  					this.errors.push("Disposition required.");

		  				if(this.submission_data[0].campaign_name==''||this.submission_data[0].campaign_name==null)
		  					this.errors.push("Campaign name required.");

		  				if(this.submission_data[0].customer_name==''||this.submission_data[0].customer_name==null)
		  					this.errors.push("Cusotmer name required.");

		  				if(this.submission_data[0].audit_date==''||this.submission_data[0].audit_date==null)
		  					this.errors.push("Audit date required.");

		  				if(this.submission_data[0].case_id==''||this.submission_data[0].case_id==null)
		  					this.errors.push("Case Id/Ticket required.");

						
						if(this.submission_data[0].error_reasons==''||this.submission_data[0].error_reasons==null)
		  					this.errors.push("Error Reasons required.");

						if(this.submission_data[0].caller_type==''||this.submission_data[0].caller_type==null)
		  					this.errors.push("Caller Type required.");

						if(this.submission_data[0].vehicle_type==''||this.submission_data[0].vehicle_type==null)
		  					this.errors.push("Vehicle Type required.");
								
				     	if(this.submission_data[0].order_stage==''||this.submission_data[0].order_stage==null)
		  					this.errors.push("Order Stage required.");

						if(this.submission_data[0].issues==''||this.submission_data[0].issues==null)
		  					this.errors.push("Issues required.");

						if(this.submission_data[0].scanerio==''||this.submission_data[0].scanerio==null)
		  					this.errors.push("Scanerio required.");

						if(this.submission_data[0].sub_issues==''||this.submission_data[0].sub_issues==null)
		  					this.errors.push("Sub Issues is required.");

						if(this.submission_data[0].error_reason_type==''||this.submission_data[0].error_reason_type==null)
		  					this.errors.push("Error Reason Type is required.");

						if(this.submission_data[0].order_id==''||this.submission_data[0].order_id==null)
		  					this.errors.push("CRN No./Order ID required.");

							// if(this.submission_data[0].language_2==''||this.submission_data[0].language_2==null)
		  				// 	this.errors.push("Language QA required.");

						if(this.n_client_id == 14){
						// if(this.submission_data[0].error_code==''||this.submission_data[0].error_code==null)
		  				// 	this.errors.push("Scenario Code required.");
						if(this.submission_data[0].vehicle_type==''||this.submission_data[0].vehicle_type==null)
		  					this.errors.push("Vehicle Type required.");
						if(this.submission_data[0].new_error_code==''||this.submission_data[0].new_error_code==null)
		  					this.errors.push("Error Code required.");
						}
		  				// if(this.submission_data[0].overall_summary==''||this.submission_data[0].overall_summary==null)
		  				// 	this.errors.push("Overall summary QA required.");


		  				Object.keys(this.parameters).forEach(key_param => {
						  Object.keys(this.parameters[key_param].subs).forEach(key_sub_param => {
						  	console.log(key_sub_param); 
							  if(this.all_questions_list.hasOwnProperty(key_sub_param)){

							  if(this.parameters[key_param].subs[key_sub_param].selected_option_model==''||this.parameters[key_param].subs[key_sub_param].selected_option_model==null)
					  		{
								  
					  			this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" observation required.");
					  		}else
					  		{	//console.log(this.parameters[key_param].subs[key_sub_param].selected_option_model);
					  			if(this.parameters[key_param].subs[key_sub_param].is_non_scoring)
					  			{
					  				if(this.parameters[key_param].subs[key_sub_param].selected_observation==1||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==5||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==8||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==14||
										 this.parameters[key_param].subs[key_sub_param].selected_observation==7||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==15||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==16||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==17||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==18||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==19||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==20||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==21||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==22||
					  				   this.parameters[key_param].subs[key_sub_param].selected_observation==23)
					  				{

						  				if(this.parameters[key_param].subs[key_sub_param].remark==null)
		  								{this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark required.");}
			  							else
			  							{
			  								if(this.parameters[key_param].subs[key_sub_param].remark.length < 25)
			  									this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark minimum 25 characters.");
			  							}
					  				}
					  			}
  
								// else
					  			// {
					  			// 	if(this.parameters[key_param].subs[key_sub_param].demark>0)
					  			// 	{

								// 		// if(this.parameters[key_param].subs[key_sub_param].remark==null)
								// 		// 	{this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark required.");}
								// 		// else
								// 		// 	{
								// 		// 		if(this.parameters[key_param].subs[key_sub_param].remark.length < 25)
								// 		// 			this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark minimum 25 characters.");
								// 		// 	}

								// 		if(this.parameters[key_param].subs[key_sub_param].selected_reason_type==null||this.parameters[key_param].subs[key_sub_param].selected_reason==null)
								// 			this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" Failure reason required.");
					  			// 	}
									
								// 	/* if(this.parameters[key_param].subs[key_sub_param].selected_observation==1)
					  				   
					  			// 	{

								// 		if(this.parameters[key_param].subs[key_sub_param].remark==null)
								// 			{this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark required.");}
								// 			else
								// 			{
								// 				if(this.parameters[key_param].subs[key_sub_param].remark.length < 25)
								// 					this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" remark minimum 25 characters.");
								// 			}

								// 		if(this.parameters[key_param].subs[key_sub_param].selected_reason_type==null||this.parameters[key_param].subs[key_sub_param].selected_reason==null)
								// 			this.errors.push(this.parameters[key_param].subs[key_sub_param].name+" Failure reason required.");
					  			// 	} */

					  			// }
					  		}


							}	
						  });
						});

		  				return this.errors;
		  		},
				onReasonTypeChange: function(eve){
					var str = event.target.value;
			  		var srt = str.split("_");

					var self = this

					axios.get('/get_reasons_by_type/'+srt[2]).then(function (response){
      					self.parameters[parseInt(srt[0])].subs[parseInt(srt[1])]['all_reasons'] = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_rca_mode_change: function(){
					var self=this;
					axios.get('/get_rca1_by_rca_mode_id/'+this.selected_rca_mode).then(function (response){
	        			self.rca1 = response.data.data;
	        			self.rca2 = null;
	        			self.rca3 = null;
	        			
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_rca1_change: function(){
					var self=this;
					axios.get('/get_rca2_by_rca1_id/'+this.selected_rca1).then(function (response){
	        			self.rca2 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_rca2_change: function(){
					var self=this;
					axios.get('/get_rca3_by_rca2_id/'+this.selected_rca2).then(function (response){
	        			self.rca3 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_type_2_rca_mode_change: function(){
					var self=this;
					axios.get('/get_type_2_rca1_by_rca_mode_id/'+this.selected_type_2_rca_mode).then(function (response){
	        			self.type_2_rca1 = response.data.data;
	        			self.type_2_rca2 = null;
	        			self.type_2_rca3 = null;
	        			
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_type_2_rca1_change: function(){
					var self=this;
					axios.get('/get_type_2_rca2_by_rca1_id/'+this.selected_type_2_rca1).then(function (response){
	        			self.type_2_rca2 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				on_type_2_rca2_change: function(){
					var self=this;
					axios.get('/get_type_2_rca3_by_rca2_id/'+this.selected_type_2_rca2).then(function (response){
	        			self.type_2_rca3 = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				getRawDataOnDateRange: function(){
					
					var self=this;
					var params = {
				      date_range:document.getElementById("kt_dashboard_daterangepicker_date").innerHTML,
				      process_id: this.submission_data[0].process_id,
				      };
			     	axios.post('/get_raw_data_on_data_range',params).then(function (response){
	        			self.my_alloted_call_list = response.data.data;
			        }).catch(function (error) {
			            console.log(error);
			        });
				},
				upload_call(){

					// code for progress bar
					document.getElementById("MySubmitButton").disabled = true;
					document.getElementById("MySaveButton").disabled = true;
					var i = 0;
					document.getElementById("myBar").style.display="block";
					function move() {
						if (i == 0) {
							i = 1;
							var elem = document.getElementById("myBar");
							var width = 10;
							var id = setInterval(frame, 10);
							function frame() {
								if (width >= 100) {
									clearInterval(id);
									i = 0;
								} else {
									width++;
									elem.style.width = width + "%";
									elem.innerHTML = width  + "%";
								}
							}
						}
					}
					// code for progress bar


					var self=this;
						let formData = new FormData();
						if(this.call_file)
						{
							formData.append('call_file', this.call_file);
						}
						
						if(blob){
						formData.append('recording_file', blob,'audio.mp3');
						}
						axios.post( '/allocation/upload_call',
							formData,
							{
								headers: {
									'Content-Type': 'multipart/form-data'
								}
							}
							).then(function(response){
							move();
							document.getElementById("MySubmitButton").disabled = false;
							document.getElementById("MySaveButton").disabled = false;
							self.submission_data[0].good_bad_call_file = response.data.data;
							self.submission_data[0].feedback_to_agent_recording = response.data.data_recording
							console.log(self.submission_data[0].good_bad_call_file);
						})
					.catch(function(error){
						console.log(error);
					});
				},

				handleCallUpload(){
					this.call_file = this.$refs.good_bad_call_file.files[0];
				},

				startRecording:function (
					thisButton) {
// by akhil
						let myNode = document.getElementById("aud-recorder");
					if(myNode) {
						myNode.innerHTML = '';
					}
					// by akhil
					navigator.mediaDevices.getUserMedia(
						audioMediaConstraints)
						.then((mediaStream) => {

						// Create a new MediaRecorder instance
						const mediaRecorder =
							new MediaRecorder(mediaStream);

						//Make the mediaStream global
						window.mediaStream = mediaStream;
						//Make the mediaRecorder global
						window.mediaRecorder = mediaRecorder;

						mediaRecorder.start();

						// Whenever (here when the recorder
						// stops recording) data is available
						// the MediaRecorder emits a "dataavailable"
						// event with the recorded media data.
						mediaRecorder.ondataavailable = (e) => {

							// Push the recorded media data to
							// the chunks array
							chunks.push(e.data);
						};

						// When the MediaRecorder stops
						// recording, it emits "stop"
						// event
						mediaRecorder.onstop = () => {

							blob = new Blob(
								chunks, {
									type: "audio/mpeg"
								});
							chunks = [];

							
							const recordedMedia = document.createElement(
								"audio");
							recordedMedia.controls = true;

							// You can not directly set the blob as
							// the source of the video or audio element
							// Instead, you need to create a URL for blob
							// using URL.createObjectURL() method.
							const recordedMediaURL = URL.createObjectURL(blob);

							// Now you can use the created URL as the
							// source of the video or audio element
							recordedMedia.src = recordedMediaURL;

							// Create a download button that lets the
							// user download the recorded media
							const downloadButton = document.createElement("a");

							// Set the download attribute to true so that
							// when the user clicks the link the recorded
							// media is automatically gets downloaded.
							downloadButton.download = "Recorded-Media";
							const deleteButton = document.createElement('button');
							deleteButton.innerHTML = "Delete";

							downloadButton.href = recordedMediaURL;
							//console.log(recordedMediaURL);
							//downloadButton.innerText = "Download it!";

							downloadButton.onclick = () => {

								/* After download revoke the created URL
								using URL.revokeObjectURL() method to
								avoid possible memory leak. Though,
								the browser automatically revokes the
								created URL when the document is unloaded,
								but still it is good to revoke the created
								URLs */
								URL.revokeObjectURL(recordedMedia);
							};
							deleteButton.onclick = function (e) {
								let myNode = document.getElementById("aud-recorder");
								if(myNode) {
									myNode.innerHTML = '';
								}
                    		};

							document.getElementById(
								`${selectedMedia}-recorder`).append(
								recordedMedia, downloadButton);
						};

						

						document.getElementById(
								`${selectedMedia}-record-status`)
								.innerText = "Recording";

						//thisButton.disabled = true;
						//document.getElementById('stop-aud-recording').disabled = false;
					});
				},

				stopRecording:function(thisButton) {

					// Stop the recording
					window.mediaRecorder.stop();

					// Stop all the tracks in the
					// received media stream
					window.mediaStream.getTracks()
					.forEach((track) => {
						track.stop();
					});

					document.getElementById(
							`${selectedMedia}-record-status`)
							.innerText = "Recording done!";
					//thisButton.disabled = true;
					//document.getElementById('start-aud-recording').disabled = false;
				},

		  }

	}
	
</script>

<style lang="scss">
@import "vue-select/src/scss/vue-select.scss";

.sp-details-top
{
	cursor: pointer;

}
.flex-container
{
	display: flex;
	align-items:center;

}
.sp-row
{
	.row
	{
		margin-bottom: 15px;
	}
}
#seprator
{
	margin: 2.5rem 0 0 0;
}
@media screen and (min-width: 480px) {
  .sp-row
  {
  	.row{
  		.col-md-2
  		{
  			margin-top: 15px;
  		}
  	}
  }
}

#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 10%;
  height: 30px;
  background-color: #04AA6D;
  text-align: center;
  display:none;
  line-height: 30px;
  color: white;
}
.multiselect__option--highlight {
    background: #2265ea;
}
.multiselect__tag {
	background: #2265ea;
	white-space: normal;
}
.multiselect__option{
	white-space: normal;
}
.multiselect__current {
	white-space: normal;
}
</style>