<?php
namespace App\Http\Controllers;

ini_set('memory_limit', '-1');
use App\Audit;
use App\AuditAlertBox;
use App\AuditParameterResult;
use App\AuditResult;
use App\QmSheetParameter;
use App\RawData;
use App\Reason;
use App\ReasonType;
use App\TmpAudit;
use App\ScenerioTree;
use App\ErrorCode;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;
use Auth;
use Crypt;
use App\AuditLog;
use DateTime;
use Illuminate\Http\Request;
use Validator;


class TempAuditController extends Controller
{
    public function audit_detail($audit_id)
    {
        $audit_data = TmpAudit::with(['tmp_audit_parameter_result', 'tmp_audit_results', 'tmp_audit_results.reason_type', 'tmp_audit_results.reason'])->find(Crypt::decrypt($audit_id));
        $raw_data = RawData::find($audit_data->raw_data_id);
        $errorReasons = ErrorCode::select('error_reason_types')->distinct()->pluck('error_reason_types');
        $check_log = AuditLog::where('raw_data_id', $audit_data->raw_data_id)->first();
        if ($check_log) {
            $update_old = AuditLog::find($check_log->id);
            $update_old->start_time = now();
            $update_old->save();
        }

        $qm_sheet_para_data = QmSheetParameter::with(['qm_sheet_sub_parameter'])->where('qm_sheet_id', $audit_data->qm_sheet_id)->get();
        $audit_sp_results = $audit_data->tmp_audit_results;
        $final_data = [];
        $all_sub_parameters = [];
        $all_questions = TmpAuditResult::where('audit_id', Crypt::decrypt($audit_id))->pluck('sub_parameter_id')->toArray();
        foreach ($qm_sheet_para_data as $key => $value) {
            foreach ($value->qm_sheet_sub_parameter as $sskey => $ssvalue) {
                if (array_search($ssvalue->id, $all_questions)) {
                    $all_sub_parameters[] = ["key" => $value->id . "_" . $ssvalue->id, "value" => $ssvalue->sub_parameter];
                }
            }
            $final_data[$value->id]['name'] = $value->parameter;
            $final_data[$value->id]['id'] = $value->id;
            if ($value->is_non_scoring) {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                    $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();
                    $temp_result = $t_1[array_key_first($t_1)];
                    $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_non_scoring_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => "-", 'reason' => '-', 'remark' => $temp_result['remark']];
                }
            } else {
                foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                    if ((array_search($svalue->id, $all_questions) != null && $value->qm_sheet_id == 137) || $value->qm_sheet_id != 137) {
                        $t_1 = $audit_sp_results->where('parameter_id', $value->id)->where('sub_parameter_id', $svalue->id)->toArray();
                        $temp_result = $t_1[array_key_first($t_1)];

                        if (isset($temp_result['reason_type']['name'])) {
                            $temp_result['reason_type']['name'] = $temp_result['reason_type']['name'];
                            $temp_result['reason']['name'] = $temp_result['reason']['name'];
                            $temp_result['remark']=$temp_result['remark'];
                        } else {
                            $temp_result['reason']['name'] = "-";
                            $temp_result['reason_type']['name'] = "-";
                            $temp_result['remark'] = "-";
                        }
                        $final_data[$value->id]['sp'][] = ['is_non_scoring' => $value->is_non_scoring, 'id' => $svalue->id, 'name' => $svalue->sub_parameter, 'detail' => $svalue->details, 'selected_option' => return_general_observation($temp_result['selected_option']), 'scored' => $temp_result['score'], 'reason_type' => $temp_result['reason_type']['name'], 'reason' => $temp_result['reason']['name'], 'remark' => $temp_result['remark']];

                    }
                    
                }
            }
        }
        return view('porter_design.audit.temp_audit_detail', compact(['raw_data', 'errorReasons', 'audit_data', 'final_data', 'all_sub_parameters']));
    }
    public function getOrderStage(Request $request)
    {
        $order_stage = ScenerioTree::where('caller', $request->val)->distinct()->pluck('order_stage');
        return response()->json(['order_stage' => $order_stage]);
    }
    public function getIssues(Request $request)
    {
        $issue = ScenerioTree::where('caller', $request->caller_type_select)->where('order_stage', $request->val)->distinct()->pluck('issue');
        return response()->json(['issue' => $issue]);
    }
    public function getSubIssues(Request $request)
    {
        $sub_issue = ScenerioTree::where('caller', $request->caller_type_select)->where('order_stage', $request->orderStage)->where('issue', $request->val)->distinct()->pluck('sub_issues');
        return response()->json(['sub_issue' => $sub_issue]);
    }
    public function getScanerio(Request $request)
    {
        $scenario = ScenerioTree::where('caller', $request->caller_type_select)->where('order_stage', $request->orderStage)->where('issue', $request->issue)->where('sub_issues', $request->val)->distinct()->pluck('scenario');
        return response()->json(['scenario' => $scenario]);
    }
    public function getScanerioCode(Request $request)
    {
        $scenerio_code = ScenerioTree::where('caller', $request->caller_type_select)->where('order_stage', $request->orderStage)->where('issue', $request->issue)->where('sub_issues', $request->sub_issue)->where('scenario', $request->val)->distinct()->pluck('scenerio_code');
        return response()->json(['scenerio_code' => $scenerio_code]);
    }
    public function getErrorReasonType(Request $request)
    {
        $error_reasons = ErrorCode::where('error_reason_types', $request->val)->distinct()->pluck('error_reasons');
        return response()->json(['error_reasons' => $error_reasons]);
    }
    public function getErrorCode(Request $request)
    {
        $error_codes = ErrorCode::where('error_reason_types', $request->error_reason_type)->where('error_reasons', $request->val)->distinct()->pluck('error_codes');
        return response()->json(['error_codes' => $error_codes]);
    }
    public function update_basic_audit_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'customer_name' => 'required',
            'phone_number' => 'required',
            'qrc_2' => 'required',
            'language_2' => 'required',
            'case_id' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('temp_audit/edit/' . $request->audit_id)
                ->withErrors($validator)
                ->withInput();
        } else {
            try {
                $audit_data = TmpAudit::with('raw_data')->find(Crypt::decrypt($request->audit_id));
                $audit_data->raw_data->customer_name = $request->customer_name;
                $audit_data->raw_data->phone_number = $request->phone_number;
                $audit_data->raw_data->disposition = $request->disposition;
                $audit_data->raw_data->call_time = $request->call_time;
                $audit_data->raw_data->location = $request->location;
                $audit_data->raw_data->call_duration = $request->call_duration;
                $audit_data->audit_date = $request->audit_date;
                $audit_data->refrence_number = $request->refrence_number;
                $audit_data->qrc_2 = $request->qrc_2;
                $audit_data->language_2 = $request->language_2;
                $audit_data->case_id = $request->case_id;
                $audit_data->overall_summary = $request->overall_summary;
                $audit_data->feedback = $request->feedback;
                $audit_data->caller_type = $request->caller_type;
                $audit_data->order_stage = $request->order_stage;
                $audit_data->issues = $request->issue;
                $audit_data->sub_issues = $request->sub_issue;
                $audit_data->scanerio = $request->scanerio;
                $audit_data->scanerio_codes = $request->scanerio_code;
                $audit_data->error_reason_type = $request->error_reason_type;
                $audit_data->error_code_reasons = $request->error_reasons;
                $audit_data->new_error_code = $request->new_error_code;
                $audit_data->audit_type = $request->audit_type;
                $audit_data->order_id = $request->order_id;
                $audit_data->vehicle_type = $request->vehicle_type;
                $audit_data->save();
                return redirect('temp_audit/edit/' . $request->audit_id)->with('success', 'Audit basic data updated successfully.');
            } catch (\Exception $th) {
                dd($th->getMessage());
            }
            
        }
    }

    public function get_details_for_update_audit_sub_parameter(Request $request)
    {
        $pdata = TmpAuditParameterResult::where('audit_id', $request->audit_id)
            ->where('parameter_id', $request->parameter_id)
            ->with('parameter_detail')
            ->first();
        $sdata = TmpAuditResult::where('audit_id', $request->audit_id)
            ->where('parameter_id', $request->parameter_id)
            ->where('sub_parameter_id', $request->sub_parameter_id)
            ->with('sub_parameter_detail')
            ->first();
        $intro_data['p_name'] = $pdata->parameter_detail->parameter;
        $intro_data['s_name'] = $sdata->sub_parameter_detail->sub_parameter;
        $intro_data['weight'] = $sdata->sub_parameter_detail->weight;
        $intro_data['is_critical'] = $sdata->is_critical;

        $intro_data['s_detail'] = $sdata->sub_parameter_detail->details;
        $intro_data['remarks'] = $sdata->remark;
        $intro_data['reason_type_id'] = $sdata->reason_type_id;
        $intro_data['reason_id'] = $sdata->reason_id;

        $intro_data['selected_option'] = $sdata->selected_option;


        $sub_parameter_data = $sdata->sub_parameter_detail;

        if ($pdata->parameter_detail->is_non_scoring) {


            if ($sdata->sub_parameter_detail->non_scoring_option_group) {
                foreach (all_non_scoring_obs_options($sdata->sub_parameter_detail->non_scoring_option_group) as $key_ns => $value_ns) {
                    $scoring_opts["0_" . $key_ns . "_0"] = ["key" => "0_" . $key_ns . "_0", "value" => $value_ns, "alert_box" => null];
                }
            } else {
                $scoring_opts = null;
            }

            $intro_data['selected_observation'] = "0_" . $sdata->selected_option . "_0";

            $intro_data['score_view'] = 0;
            $intro_data['scored'] = $sdata->score;
            $intro_data['after_audit_weight'] = 0;
            $intro_data['reason_type'] = [];

            $reason_type_master = [];
            $reasons_master = [];
        } else {

            if ($sub_parameter_data->pass) {
                if ($sub_parameter_data->pass_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->pass_alert_box_id);
                else
                    $alert_box = null;

                $scoring_opts[$sub_parameter_data->weight . "_1_0"] = ["key" => $sub_parameter_data->weight . '_1_0', "value" => "Pass", "alert_box" => $alert_box];

                if ($sdata->selected_option == 1) {
                    $intro_data['selected_observation'] = $sub_parameter_data->weight . "_1_0";
                }
            }

            if ($sub_parameter_data->fail) {
                if ($sub_parameter_data->fail_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->fail_alert_box_id);
                else
                    $alert_box = null;

                if ($sub_parameter_data->fail_reason_types) {
                    $temp_index_f = "0" . "_2_1";
                    $temp_r_fail = ReasonType::find(explode(',', $sub_parameter_data->fail_reason_types))->pluck('name', 'id');
                    foreach ($temp_r_fail as $keycc => $valuecc) {
                        $all_reason_type_fail[] = ["key" => $keycc, "value" => $valuecc];
                    }
                } else {
                    $temp_index_f = "0" . "_2_0";
                    $all_reason_type_fail = [];
                }

                $scoring_opts[$temp_index_f] = ["key" => $temp_index_f, "value" => "Fail", "alert_box" => $alert_box];

                if ($sdata->selected_option == 2) {
                    $intro_data['selected_observation'] = $temp_index_f;
                }
            } else {
                $all_reason_type_fail = [];
            }

            if ($sub_parameter_data->critical) {
                if ($sub_parameter_data->critical_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->critical_alert_box_id);
                else
                    $alert_box = null;

                if ($sub_parameter_data->critical_reason_types) {
                    $temp_index_cri = "Critical" . "_3_1";
                    $temp_cric = ReasonType::find(explode(',', $sub_parameter_data->critical_reason_types))->pluck('name', 'id');
                    foreach ($temp_cric as $keycc => $valuecc) {
                        $all_reason_type_cric[] = ["key" => $keycc, "value" => $valuecc];
                    }
                } else {
                    $temp_index_cri = "Critical" . "_3_0";
                    $all_reason_type_cric = null;
                }
                $scoring_opts[$temp_index_cri] = ["key" => $temp_index_cri, "value" => "Critical", "alert_box" => $alert_box];

                if ($sdata->selected_option == 3) {
                    $intro_data['selected_observation'] = $temp_index_cri;
                }
            } else {
                $all_reason_type_cric = [];
            }

            if ($sub_parameter_data->na) {

                if ($sub_parameter_data->na_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->na_alert_box_id);
                else
                    $alert_box = null;

                $scoring_opts["N/A" . "_4_0"] = ["key" => "N/A" . "_4_0", "value" => "N/A", "alert_box" => $alert_box];

                if ($sdata->selected_option == 4) {
                    $intro_data['selected_observation'] = "N/A" . "_4_0";
                }
            }

            if ($sub_parameter_data->pwd) {
                if ($sub_parameter_data->pwd_alert_box_id)
                    $alert_box = AuditAlertBox::find($sub_parameter_data->pwd_alert_box_id);
                else
                    $alert_box = null;

                $scoring_opts[($sub_parameter_data->weight / 2) . "_5_0"] = ["key" => ($sub_parameter_data->weight / 2) . "_5_0", "value" => "PWD", "alert_box" => $alert_box];

                if ($sdata->selected_option == 5) {
                    $intro_data['selected_observation'] = ($sub_parameter_data->weight / 2) . "_5_0";
                }
            }

            switch ($sdata->selected_option) {
                case 1: {
                        $intro_data['score_view'] = $sdata->score;
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = [];
                        break;
                    }
                case 2: {
                        $intro_data['score_view'] = 0;
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = $all_reason_type_fail;
                        break;
                    }
                case 3: {
                        $intro_data['score_view'] = "Critical";
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = $all_reason_type_cric;
                        break;
                    }
                case 4: {
                        $intro_data['score_view'] = "N/A";
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = 0;
                        $intro_data['reason_type'] = [];
                        break;
                    }
                case 5: {
                        $intro_data['score_view'] = "PWA";
                        $intro_data['scored'] = $sdata->score;
                        $intro_data['after_audit_weight'] = $sdata->sub_parameter_detail->weight;
                        $intro_data['reason_type'] = [];
                        break;
                    }
            }

            $reason_type_master[2] = $all_reason_type_fail;
            $reason_type_master[3] = $all_reason_type_cric;

            $intro_data['selected_reason_type_id'] = $sdata->reason_type_id;
            $intro_data['selected_reason_id'] = $sdata->reason_id;

            if ($intro_data['selected_reason_type_id']) {
                $reasons_master = Reason::where('reason_type_id', $intro_data['selected_reason_type_id'])->pluck('name', 'id');
            } else {
                $reasons_master = [];
            }

            // non scoring else ends
        }


        $final_data['intro_data'] = $intro_data;
        $final_data['scoring_opts'] = $scoring_opts;
        $final_data['reason_type_master'] = $reason_type_master;
        $final_data['reasons_master'] = $reasons_master;

        return response()->json(['status' => 200, 'message' => ".", 'data' => $final_data], 200);
    }

    public function update_sp_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'parameter_id' => 'required',
            'sub_parameter_id' => 'required',
            'basic_data' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'message' => "Data validation error."], 422);
        } else {
            $new_data = [];

            $audit_result = TmpAuditResult::where('audit_id', $request->audit_id)
                ->where('parameter_id', $request->parameter_id)
                ->where('sub_parameter_id', $request->sub_parameter_id)
                ->first();
            $new_data['sub_parameter_id'] = $audit_result->sub_parameter_id;
            $new_data['previous_observation'] = $audit_result->selected_option;

            $audit_result->is_critical = $request->basic_data['is_critical'];
            $audit_result->selected_option = $request->basic_data['selected_option'];
            $audit_result->score = $request->basic_data['scored'];
            $audit_result->after_audit_weight = $request->basic_data['after_audit_weight'];
            $audit_result->remark = $request->basic_data['remarks'];
            if ($request->basic_data['selected_option'] == 2 || $request->basic_data['selected_option'] == 3) {
                $audit_result->reason_type_id = $request->basic_data['selected_reason_type_id'];
                $audit_result->reason_id = $request->basic_data['selected_reason_id'];
            } else {
                $audit_result->reason_type_id = 0;
                $audit_result->reason_id = 0;
            }

            $audit_result->save();

            // update Parameter score starts

            if ($audit_result->is_non_scoring) {
                //do nothing
            } else {

                $all_sub_parameter = TmpAuditResult::where('audit_id', $request->audit_id)
                    ->where('parameter_id', $request->parameter_id)
                    ->get();
                $parameter_revised_data = [];
                $parameter_revised_data['score_sum'] = 0;
                $parameter_revised_data['scorable_sum'] = 0;
                $parameter_revised_data['is_critical'] = 0;
                foreach ($all_sub_parameter as $key => $value) {
                    $parameter_revised_data['score_sum'] += $value->score;
                    $parameter_revised_data['scorable_sum'] += $value->after_audit_weight;

                    if ($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                }
                $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum'] / $parameter_revised_data['scorable_sum']) * 100, 2);

                $audit_parameter = TmpAuditParameterResult::where('audit_id', $request->audit_id)
                    ->where('parameter_id', $request->parameter_id)
                    ->first();
                $audit_parameter->is_critical = $parameter_revised_data['is_critical'];
                $audit_parameter->temp_weight = $parameter_revised_data['scorable_sum'];

                $audit_parameter->without_fatal_score = $parameter_revised_data['score_sum'];
                $audit_parameter->without_fatal_score_pre = $parameter_revised_data['final_score_per'];

                if ($parameter_revised_data['is_critical']) {
                    $audit_parameter->with_fatal_score = 0;
                    $audit_parameter->with_fatal_score_per = 0;
                } else {
                    $audit_parameter->with_fatal_score = $parameter_revised_data['score_sum'];
                    $audit_parameter->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                }

                $audit_parameter->save();
                // update Parameter score ends

                //update entire audit reised score starts
                $all_parameter = TmpAuditParameterResult::where('audit_id', $request->audit_id)->get();


                $parameter_revised_data['score_sum'] = 0;
                $parameter_revised_data['scorable_sum'] = 0;
                $parameter_revised_data['is_critical'] = 0;
                foreach ($all_parameter as $key => $value) {
                    $parameter_revised_data['score_sum'] += $value->without_fatal_score;
                    $parameter_revised_data['scorable_sum'] += $value->temp_weight;

                    if ($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                }

                $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum'] / $parameter_revised_data['scorable_sum']) * 100, 2);

                $audit_data = TmpAudit::find($request->audit_id);

                $new_data['score_with_fatal'] = $audit_data->with_fatal_score_per;
                $new_data['score_without_fatal'] = $audit_data->overall_score;

                $audit_data->is_critical = $parameter_revised_data['is_critical'];
                $audit_data->overall_score = $parameter_revised_data['final_score_per'];

                if ($parameter_revised_data['is_critical']) {
                    $audit_data->with_fatal_score_per = 0;
                } else {
                    $audit_data->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                }
                $audit_data->save();
                //update entire audit reised score ends
            }


            return response()->json(['status' => 200, 'message' => "Success"], 200);
        }
    }
    public function transfer_audit_from_temp_to_main_pool(Request $request)
    {
        $data = TmpAudit::with(['tmp_audit_parameter_result', 'tmp_audit_results', 'client'])->where('id', Crypt::decrypt($request->audit_id))->first();
        //    $update_end_time_audit_log = AuditLog::where('raw_data_id',$data->raw_data_id)->first();
        //    //print_r($update_old); die;
        //    $update_end_time_audit_log->end_time = now();

        //    $datetime1 = new DateTime($update_end_time_audit_log->start_time);
        //    $datetime2 = new DateTime(now()); 
        //    $diff = $datetime2->diff($datetime1);

        //    $daysInSecs = $diff->format('%r%a') * 24 * 60 * 60;
        //    $hoursInSecs = $diff->h * 60 * 60;
        //    $minsInSecs = $diff->i * 60;

        //    $seconds = $daysInSecs + $hoursInSecs + $minsInSecs + $diff->s;
        //    $update_end_time_audit_log->auditor_time_spend_in_secs +=  $seconds;
        //    $update_end_time_audit_log->save();
        // Change date logic by shailendra for ticket no 1980
        // $date = date("Y-m-d H:i:s",strtotime("+1 day", strtotime("$data->audit_date")));

        $date = date("Y-m-d H:i:s");

        $new_ar = new Audit;
        $new_ar->company_id = $data->company_id;
        $new_ar->client_id = $data->client_id;
        $new_ar->partner_id = $data->partner_id;
        $new_ar->qm_sheet_id = $data->qm_sheet_id;
        $new_ar->process_id = $data->process_id;
        $new_ar->raw_data_id = $data->raw_data_id;
        $new_ar->audited_by_id = $data->audited_by_id;
        $new_ar->is_critical = $data->is_critical;
        $new_ar->overall_score = $data->overall_score;
        $new_ar->with_fatal_score_per = $data->with_fatal_score_per;
        $new_ar->audit_date = $date;
        $new_ar->qrc_2 = $data->qrc_2;
        $new_ar->language_2 = $data->language_2;
        $new_ar->rebuttal_status = $data->rebuttal_status;
        $new_ar->case_id = $data->case_id;
        $new_ar->overall_summary = $data->overall_summary;
        $new_ar->feedback_status = $data->feedback_status;
        $new_ar->feedback_date = $data->feedback_date;
        $new_ar->feedback = $data->feedback;
        $new_ar->caller_type = $data->caller_type;
        $new_ar->order_stage = $data->order_stage;
        $new_ar->issues = $data->issues;
        $new_ar->sub_issues = $data->sub_issues;
        $new_ar->scanerio = $data->scanerio;
        $new_ar->scanerio_codes = $data->scanerio_codes;
        $new_ar->error_code_reasons = $data->error_code_reasons;
        $new_ar->error_reason_type = $data->error_reason_type;
        $new_ar->new_error_code = $data->new_error_code;
        $new_ar->type_id = $data->type_id;
        $new_ar->mode_id = $data->mode_id;
        $new_ar->rca1_id = $data->rca1_id;
        $new_ar->rca2_id = $data->rca2_id;
        $new_ar->rca3_id = $data->rca3_id;
        $new_ar->rca_other_detail = $data->rca_other_detail;
        $new_ar->feedback_comment = $data->feedback_comment;
        $new_ar->qc_status = $data->qc_status;
        $new_ar->qc_comment = $data->qc_comment;
        $new_ar->good_bad_call = $data->good_bad_call;
        $new_ar->good_bad_call_file = $data->good_bad_call_file;
        $new_ar->refrence_number = $data->refrence_number;
        $new_ar->qc_date = $data->qc_date;
        $new_ar->qc_revised_score_with_fatal = $data->qc_revised_score_with_fatal;
        $new_ar->qc_revised_score_with_fatal = $data->qc_revised_score_without_fatal;
        $new_ar->last_updated_at = $data->updated_at;
        // $new_ar->auditor_time_spend_in_secs = $update_end_time_audit_log->auditor_time_spend_in_secs;
        // function to fetch date
        $result = skipHoliday(strtotime($date), $data->client->holiday, $data->client->qc_time, $data->client->rebuttal_time);
        // function end

        $new_ar->qc_tat = $result[0];
        $new_ar->rebuttal_tat = $result[1];
        $new_ar->save();
        foreach ($data->tmp_audit_parameter_result as $value) {
            $new_arb = new AuditParameterResult;
            $new_arb->audit_id = $new_ar->id;
            $new_arb->parameter_id = $value->parameter_id;
            $new_arb->qm_sheet_id = $value->qm_sheet_id;
            $new_arb->orignal_weight = $value->orignal_weight;
            $new_arb->temp_weight = $value->temp_weight;
            $new_arb->with_fatal_score = $value->with_fatal_score;
            $new_arb->without_fatal_score = $value->without_fatal_score;
            $new_arb->with_fatal_score_per = $value->with_fatal_score_per;
            $new_arb->without_fatal_score_pre = $value->without_fatal_score_pre;
            $new_arb->is_critical = $value->is_critical;
            $new_arb->save();
        }
        foreach ($data->tmp_audit_results as $extra) {
            $new_arc = new AuditResult;
            $new_arc->audit_id = $new_ar->id;
            $new_arc->parameter_id = $extra->parameter_id;
            $new_arc->sub_parameter_id = $extra->sub_parameter_id;
            $new_arc->is_critical = $extra->is_critical;
            $new_arc->is_non_scoring = $extra->is_non_scoring;
            $new_arc->selected_option = $extra->selected_option;
            $new_arc->score = $extra->score;
            $new_arc->after_audit_weight = $extra->after_audit_weight;
            $new_arc->failure_reason = $extra->failure_reason;
            $new_arc->remark = $extra->remark;
            $new_arc->reason_type_id = $extra->reason_type_id;
            $new_arc->reason_id = $extra->reason_id;
            $new_arc->save();
        }
        $data->tmp_audit_results->each->delete();
        $data->tmp_audit_parameter_result->each->delete();
        $data->delete();


        return redirect('tmp_audited_list/' . $request->qm_sheet_id)->with('success', 'Audit moved to main pool successfully.');
    }

}