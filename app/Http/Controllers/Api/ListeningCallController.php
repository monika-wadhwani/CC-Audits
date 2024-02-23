<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\QmSheetParameter;
use App\QmSheetSubParameter;
use Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\User;
use Crypt;
use App\RawData;
use Storage;
use App\Region;
use DB;
use App\Process;
use App\ListeningCallFeedback;
use App\Audit;
use App\Client;
use App\Partner;
use App\Rebuttal;
use App\AuditParameterResult;
use App\Rca2Mode;
use App\RcaMode;
use App\RcaType;
use App\Reason;
use App\ReasonType;
use App\AuditAlertBox;
use App\TypeBScoringOption;
use App\QmSheet;
use App\Auditcycle;
use App\AuditResult;
use App\AgentCallFeedbackLog;
use App\ParameterWisePlanOfAction;
use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Facades\Storage;
use App\Rca1;
use App\Rca2;
use App\Rca3;
use App\AuditLog;
use App\TmpAudit;
use App\TmpAuditParameterResult;
use App\TmpAuditResult;



class ListeningCallController extends Controller
{
    public function index()
    {
        echo "hiiii";

    }

    public function recording_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',

        ]);

        /* echo $request->email;
              dd(); */
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $client_id = $request->client_id;
            $audit_detail = DB::select("
            select a.good_bad_call_file as call_link,a.audit_date,a.good_bad_call,r.call_id,r.lob,r.location,r.call_type,r.call_sub_type,r.disposition,r.campaign_name,r.phone_number,r.customer_name,r.circle,r.brand_name
            from audits a inner join raw_data r on a.raw_data_id = r.id where a.good_bad_call_file is not null and  a.client_id = " . $client_id . "");
            $data = array('status' => 1, 'message' => 'Data fetch successfully', 'data' => $audit_detail);
            return response(json_encode($data), 200);
        }

    }

    public function agentDashboard(Request $request)
    {
        $token = $request->header('Authorizations');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }

        $validator = Validator::make($request->all(), [
            'agent_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $start_date = date_to_db($request->start_date);
            $end_date = date_to_db($request->end_date);
            $audit_data = RawData::where('status', 1)
                ->where('agent_id', $request->agent_id)->where('client_id', 14)
                ->whereHas(
                    'audit',
                    function ($query) use ($start_date, $end_date) {
                        $query->whereDate('audit_date', '>=', $start_date)->whereDate('audit_date', '<=', $end_date);
                    }
                )->with('audit', 'agent_details')->orderby('id', 'desc')->get();

            // $audit_data = Audit::whereHas('raw_data', function (Builder $query) use ($request) {
            //     $query->where('agent_id', $request->agent_id);
            // })->with(['raw_data', 'audit_parameter_result', 'audit_results.sub_parameter_detail', 'audit_results', 'audit_results.reason_type', 'audit_results.reason']);
            return $audit_data;

        }
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $audit_detail], 200);
    }
    public function agentNotification(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $agent_notification = $userDetails->unreadnotifications;
        // $notify = array();
        // $notify['notification_details'] = $agent_notification;
        $notify['agent_details'] = $userDetails;

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $notify], 200);
    }

    public function markedAsRead(Request $request)
    {
        $token = $request->header('Authorizations');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        if ($request->notification_id) {
            $userDetails->notifications->where('id', $request->notification_id)->markAsRead();
        }
        return response()->json(['status' => 200, 'message' => "Success"], 200);
    }

    public function listenning_call_filters(Request $request)
    {
        $token = $request->header('Authorizations');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $response = array();
        $response['location'] = RawData::select('location')->distinct()->get(); //filter for location
        $response['process'] = Process::select('name', 'id')->get();
        $response['lob'] = RawData::select('lob')->distinct()->get();
        $response['call_type'] = RawData::select('call_type')->distinct()->get();
        $response['call_sub_type'] = RawData::select('call_sub_type')->distinct()->get();
        $response['disposition'] = RawData::select('disposition')->distinct()->get();
        $response['campaign_name'] = RawData::select('campaign_name')->distinct()->get();
        $response['circle'] = RawData::select('circle')->distinct()->get();
        $response['brand_name'] = RawData::select('brand_name')->distinct()->get();




        return response()->json(['status' => 200, 'message' => "Success", 'data' => $response], 200);


    }

    public function searching(Request $request)
    {
        $token = $request->header('Authorizations');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            // 'location' => 'required',
            // 'lob' => 'required',
            // 'process_id' => 'required',
            // 'call_type' => 'required',
            // 'call_sub_type' => 'required',
            // 'campaign_name' => 'required',
            // 'disposition' => 'required',
            // 'circle' => 'required',
            // 'brand_name' => 'required',
            // 'good_bad_call' => 'required',



        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $location = $request->location;
            //echo $location; die;
            $lob = $request->lob;
            //echo $lob; die;
            $process_id = $request->process_id;
            //echo $process_id; die;
            $call_type = $request->call_type;
            //echo $call_type; die;
            $call_sub_type = $request->call_sub_type;
            //echo $call_sub_type; die;
            $disposition = $request->disposition;
            $campaign_name = $request->campaign_name;
            $circle = $request->circle;

            $brand_name = $request->brand_name;
            //echo $brand_name; die;
            $audit_detail = DB::select("
            select a.good_bad_call_file as call_link,a.audit_date,a.good_bad_call,r.call_id,r.lob,r.location,r.call_type,r.call_sub_type,r.disposition,r.campaign_name,r.phone_number,r.customer_name,r.circle,r.brand_name
            from audits a inner join raw_data r on a.raw_data_id = r.id where a.good_bad_call_file is not null and a.good_bad_call = '" . $request->good_bad_call . "' and
             r.process_id like '%" . $process_id . "%' 
            and r.lob like '%" . $request->lob . "%' and r.call_type like '%" . $request->call_type . "%' and 
             r.call_sub_type like '%" . $request->call_sub_type . "%' and r.campaign_name like '%" . $request->campaign_name . "%'
             and r.circle like '%" . $request->circle . "%' and r.brand_name like '%" . $request->brand_name . "%' ");
            //print_r($audit_detail); die;

        }
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $audit_detail], 200);
    }

    public function listning_call_feedback(Request $request)
    {

        /*  $validator = Validator::make($request->all(), [
              'audit_id' => 'audit_id',
              'user_id' => 'user_id',
              'remark' => 'remark',
         ]);
         
         if($validator->fails()) {
             $data=array('status'=>0,'message'=>'Validation Errors','data' => $validator->errors());
             return response(json_encode($data), 200);
         } else { */
        $old_feedback = ListeningCallFeedback::where('user_id', $request->user_id)->where('audit_id', $request->audit_id)->first();
        $audit_detail = Audit::find($request->audit_id);

        $auditor_email = User::where('id', $audit_detail->audited_by_id)->first();

        $client_email = Client::join('client_admins', 'client_admins.client_id', '=', 'clients.id')
            ->join('users', 'client_admins.user_id', '=', 'users.id')->where('clients.id', $audit_detail->client_id)->pluck('users.email');
        $tl_email = User::where('id', $auditor_email->reporting_user_id)->pluck('email');
        $partner_eamil = Partner::where('id', $audit_detail->partner_id)->pluck('contact_email');
        // print_r($client_email);
        // die;
        if (!$old_feedback) {
            $new_feedback = new ListeningCallFeedback();
            $new_feedback->audit_id = $request->audit_id;
            $new_feedback->user_id = $request->user_id;
            $new_feedback->remark = $request->remark;

            if ($new_feedback->save()) {
                return response()->json(['status' => 1, 'message' => "Success", 'data' => 'Feedback inserted'], 200);
            } else {
                return response()->json(['status' => 0, 'message' => "fail", 'data' => 'DB error'], 200);
            }

        } else {
            return response()->json(['status' => 0, 'message' => "Fail", 'data' => 'Particular user allready provided feedback'], 200);
        }
        /* } */

    }

    public function auditList(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $start_date = date_to_db($request->start_date);
            $end_date = date_to_db($request->end_date);
            $data = RawData::where('status', 1)
                ->where('agent_id', $request->user_id)->where('client_id', 14)
                ->whereHas(
                    'audit',
                    function ($query) use ($start_date, $end_date) {
                        $query->whereDate('audit_date', '>=', $start_date)->whereDate('audit_date', '<=', $end_date);
                    }
                )
                ->whereHas('audit', function ($query) {
                    // $query->where('reporting_user_id',Auth::user()->id);
    
                })->with('audit', 'agent_details')->orderby('id', 'desc')->get();
            $data = ['status' => 1, 'message' => 'Successfully', 'data' => $data];
            return response(json_encode($data), 200);
        }

    }

    public function singleAuditDetails(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $audit_data = Audit::with(['raw_data', 'audit_parameter_result', 'audit_results.sub_parameter_detail', 'audit_results', 'audit_results.reason_type', 'audit_results.reason'])->find($request->audit_id);

            $qm_sheet_para_data = QmSheetParameter::with(['qm_sheet_sub_parameter'])->where('qm_sheet_id', $audit_data->qm_sheet_id)->get();

            $qmsheet_para_data = [];
            foreach ($qm_sheet_para_data as $key => $value) {
                $parmeater = array();
                $parmeater['parameater_name'] = $value->parameter;
                $parmeater['qmSheeetParameater_id'] = $value->id;
                $parmeater['pass_count'] = AuditResult::where('audit_id', $request->audit_id)->where('parameter_id', $value->id)->where('selected_option', 1)->count();
                $parmeater['fail_count'] = AuditResult::where('audit_id', $request->audit_id)->where('parameter_id', $value->id)->where('selected_option', 2)->count();
                $parmeater['critical_count'] = AuditResult::where('audit_id', $request->audit_id)->where('parameter_id', $value->id)->where('selected_option', 3)->count();
                $parmeater['score'] = AuditParameterResult::where('audit_id', $request->audit_id)->where('parameter_id', $value->id)->value('with_fatal_score_per');
                $parmeater['total_sub_para_count'] = AuditResult::where('audit_id', $request->audit_id)->where('parameter_id', $value->id)->count();

                $qmsheet_para_data[] = $parmeater;
            }

            $final_data = array();
            $final_data['audit_data'] = $audit_data;
            $final_data['qmsheet_para_data'] = $qmsheet_para_data;

            $data = ['status' => 1, 'message' => 'Successfully', 'data' => $final_data];
            return response(json_encode($data), 200);


            // $data = Rebuttal::whereHas('raw_data', function (Builder $query) use ($request) {
            // })->whereHas('audit_data', function (Builder $query) use ($request) {
            //     $query->where('id',$request->audit_id);
            //     // ->whereDate('audit_date', '>=', $start_date)
            //     //     ->whereDate('audit_date', '<=', $end_date);
            // })->with(['raw_data', 'audit_data', 'parameter', 'sub_parameter','audit_data.client'])->get();

            $data = ['status' => 1, 'message' => 'Successfully', 'data' => $data];
            return response(json_encode($data), 200);
        }
    }

    public function agent_dashboard_counts(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $dashboard_counts = array();
            $start_date = date_to_db($request->start_date);
            $end_date = date_to_db($request->end_date);
            $dashboard_counts['audit_count'] = RawData::where('status', 1)
                ->where('agent_id', $userDetails->id)->where('client_id', 14)
                ->whereHas(
                    'audit',
                    function ($query) use ($start_date, $end_date) {
                        $query->whereDate('audit_date', '>=', $start_date)->whereDate('audit_date', '<=', $end_date);
                    }
                )->count();

            $dashboard_counts['rebuttal_count'] = Rebuttal::whereHas(
                'raw_data',
                function ($query) use ($request, $userDetails) {
                    $query->where('agent_id', $userDetails->id)->where('client_id', 14);
                }
            )->count();

            $dashboard_counts['rebuttal_auto_accept'] = Rebuttal::where('is_auto_rebuttal_reply', 1)->whereHas(
                'raw_data',
                function ($query) use ($request, $userDetails) {
                    $query->where('agent_id', $userDetails->id)->where('client_id', 14);
                }
            )->count();

            $dashboard_counts['rebuttal_accept'] = Rebuttal::where('is_auto_rebuttal_reply', 0)->whereHas(
                'raw_data',
                function ($query) use ($request, $userDetails) {
                    $query->where('agent_id', $userDetails->id)->where('client_id', 14);
                }
            )->count();

            $dashboard_counts['rebuttal_auto_reject'] = Rebuttal::where('is_auto_invalid', 1)->whereHas(
                'raw_data',
                function ($query) use ($request, $userDetails) {
                    $query->where('agent_id', $userDetails->id)->where('client_id', 14);
                }
            )->count();

            $dashboard_counts['rebuttal_reject'] = Rebuttal::where('is_auto_invalid', 0)->whereHas(
                'raw_data',
                function ($query) use ($request, $userDetails) {
                    $query->where('agent_id', $userDetails->id)->where('client_id', 14);
                }
            )->count();
            
            $raw_data_id = RawData::where('agent_id', $userDetails->id)->where('status', 1)->where('client_id', 14)->pluck('id');
            $dashboard_counts['feedback_accept_count'] = Audit::whereIn('raw_data_id', $raw_data_id)->whereDate('audit_date', '>=', $start_date)->whereDate('audit_date', '<=', $end_date)->where('feedback_shared_status', 1)->count();
            $dashboard_counts['feedback_reject_count'] = Audit::whereIn('raw_data_id', $raw_data_id)->whereDate('audit_date', '>=', $start_date)->whereDate('audit_date', '<=', $end_date)->where('feedback_shared_status', 2)->count();
            $agent_dashboard[] = $dashboard_counts;
            if ($dashboard_counts) {
                return response()->json(['status' => 200, 'message' => 'Success', 'data' => $dashboard_counts], 200);
            } else {
                $data = array('status' => 0, 'message' => 'Error', 'data' => 'No Data Found');
                return response(json_encode($data), 200);
            }
        }
    }
    public function notification_feedback(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }

        $agentId = $userDetails->id;
        if (isset($request->start_date)) {
            $notification_logs = DB::select("
            SELECT a.id, a.audit_date, pr.name AS process_name, r.call_id, r.location, r.phone_number, r.customer_name, r.circle, u.name AS auditor_name, u.email AS auditor_email,
            a.is_critical,a.rebuttal_tat, a.overall_score AS without_fatal, a.with_fatal_score_per AS with_fatal
            FROM audits a
            INNER JOIN raw_data r ON a.raw_data_id = r.id
            INNER JOIN processes pr ON a.process_id = pr.id
            INNER JOIN users u ON a.audited_by_id = u.id
            WHERE DATE(a.rebuttal_tat) > CURDATE() AND DATE(a.audit_date) >= ? AND DATE(a.audit_date) <= ? AND DATE(a.rebuttal_tat) > date('Y-m-d H:i:s') AND a.feedback_shared_status = ? AND r.agent_id = ?
        ", [$request->start_date, $request->end_date, 0, $agentId]);

            ///////////// old query for notification logs start  //////////////
            //     $notification_logs = DB::select("
            //     SELECT al.id, al.audit_id, al.agent_feedback_date, al.agent_feedback,al.created_at, a.good_bad_call_file, a.audit_date, pr.name AS process_name, r.call_id, r.location, r.phone_number, r.customer_name, r.circle, u.name AS auditor_name, u.email AS auditor_email,
            //     a.is_critical, a.overall_score AS without_fatal, a.with_fatal_score_per AS with_fatal
            //     FROM agent_call_feedback_logs al
            //     INNER JOIN audits a ON al.audit_id = a.id
            //     INNER JOIN raw_data r ON a.raw_data_id = r.id
            //     INNER JOIN processes pr ON a.process_id = pr.id
            //     INNER JOIN users u ON a.audited_by_id = u.id
            //     WHERE DATE al.created_at >= '".$request->start_date."'
            //     WHERE DATE al.created_at <= '".$request->end_date."'
            //     WHERE al.mail_trigger_status IS NOT NULL AND a.feedback_shared_status = '". 0 ."' AND r.agent_id = '" . $agentId . "'
            // ");
            ///////////// old query for notification logs end   //////////////

        } else {
            $notification_logs = DB::select("
            SELECT a.id, a.audit_date, pr.name AS process_name, r.call_id, r.location, r.phone_number, r.customer_name, r.circle, u.name AS auditor_name, u.email AS auditor_email,
            a.is_critical, a.rebuttal_tat, a.overall_score AS without_fatal, a.with_fatal_score_per AS with_fatal
            FROM audits a
            INNER JOIN raw_data r ON a.raw_data_id = r.id
            INNER JOIN processes pr ON a.process_id = pr.id
            INNER JOIN users u ON a.audited_by_id = u.id
            WHERE DATE(a.rebuttal_tat) > CURDATE() AND a.feedback_shared_status = 0 AND r.agent_id = " . $agentId);
        }

        // return $notification_logs;
        if ($notification_logs) {
            return response()->json(['status' => 200, 'message' => 'Success', 'data' => $notification_logs], 200);
        } else {
            $data = array('status' => 0, 'message' => 'Error', 'data' => 'No Data Found');
            return response(json_encode($data), 200);
        }



    }

    public function parameter_wise_details(Request $request)
    {
        $token = $request->header('Authorizations');
        $userDetails = User::where("remember_token", $token)->first();

        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $audit_id = $request->audit_id;
            // $notification_logs = Audit::join('agent_call_feedback_logs','agent_call_feedback_logs.audit_id','=','audits.id')
            // ->join('raw_data','audits.raw_data_id','=','raw_data.id')->where('raw_data.agent_name',$email)
            // ->pluck('agent_call_feedback_logs.agent_feedback');


            $overall_sheet_parameters = AuditResult::select('audit_results.audit_id', 'qm_sheet_parameters.parameter', 'qm_sheet_parameters.id as paramter_id', 'qm_sheet_sub_parameters.id as subparameter_id', 'qm_sheet_sub_parameters.sub_parameter', 'qm_sheet_sub_parameters.weight', 'qm_sheet_parameters.is_non_scoring', 'audit_results.selected_option', 'audit_results.score', 'audit_parameter_results.with_fatal_score_per', 'reason_types.name as reason_type_name', 'reasons.name as reason_name', 'audit_results.remark as remarks', 'audit_results.is_critical')
                ->join('audits', 'audit_results.audit_id', '=', 'audits.id')
                ->join('qm_sheet_parameters', 'qm_sheet_parameters.id', '=', 'audit_results.parameter_id')
                ->join('qm_sheet_sub_parameters', 'audit_results.sub_parameter_id', '=', 'qm_sheet_sub_parameters.id')
                ->join('audit_parameter_results', 'audit_results.parameter_id', '=', 'audit_parameter_results.parameter_id')
                ->leftJoin('reason_types', 'audit_results.reason_type_id', '=', 'reason_types.id')
                ->leftJoin('reasons', 'audit_results.reason_id', '=', 'reasons.id')
                ->where('audit_results.audit_id', $audit_id)
                ->where('audit_parameter_results.audit_id', $audit_id)
                ->get();
        }

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $overall_sheet_parameters], 200);



    }

    public function accepted(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',

        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $audit_feedback = Audit::where('id', $request->audit_id)->update(['feedback_shared_status' => 1]);
            return response()->json(['status' => 200, 'message' => "Success", 'data' => $audit_feedback], 200);
        }


    }
    public function auditListFeedback(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'audit_id' => 'required',
            'feedback_status' => 'required',
            'feedback_summery' => 'required'
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = Audit::where('id', $request->audit_id)->update(['feedback_comment' => $request->feedback_summery, 'feedback_shared_status' => $request->feedback_status]);

            return response()->json(['status' => 200, 'message' => "Success", 'data' => $data], 200);
        }

    }

    public function plan_of_action(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'agent_feedback_status' => 'required',
            'feedback_log_id' => 'required',
            'feedback_remark' => 'required',
            'audit_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $agent_feedback_status = $request->agent_feedback_status;
            $feedback_log_id = $request->feedback_log_id;
            $feedback_remark = $request->feedback_remark;


            $audit_feedback = AgentCallFeedbackLog::find($feedback_log_id);
            $audit_feedback->agent_feedback_status = $agent_feedback_status;
            $audit_feedback->agent_feedback = $feedback_remark;

            if (isset($request->agent_feedback_recording)) {
                $request->agent_feedback_recording->store("agent_feedback_recording");

                $file_name = $request->agent_feedback_recording->hashName();
                $data = Storage::url('agent_feedback_recording/') . $file_name;
                $audit_feedback->agent_feedback_recording = $data;
            }
            $audit_feedback->save();


        }
        //  return response()->json(['status'=>200,'message'=>"File uploaded successfuly",
        // 'data'=>$data,'data_recording'=>$data], 200);

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $audit_feedback], 200);



    }

    public function Rebuttal(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            //   'audit_id' => 'required',
            //   'parameter_id' => 'required',
            //   'sub_parameter_id' => 'required',
            //   'remark' => 'required',
        ]);

        $dataCount = count($request->raised_rebuttal);
        $counter = 0;
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            if ($request->raised_rebuttal == null) {
                return response()->json(['status' => 0, 'message' => "Fail", 'data' => 'Data Not Found'], 200);
            } else {
                foreach ($request->raised_rebuttal as $key => $value) {
                    $old_rebuttal = Rebuttal::where('sub_parameter_id', $value['sub_parameter_id'])->where('audit_id', $value['audit_id'])->first();

                    $audit_detail = DB::select("
                select p.admin_user_id,a.raw_data_id, a.rebuttal_status
                from audits a inner join raw_data r on a.raw_data_id = r.id inner join partners p on p.id = a.partner_id where a.id = " . $value['audit_id'] . "");


                    if (!$old_rebuttal) {
                        if ($audit_detail[0]->rebuttal_status == 0) {
                            $rebuttal_status = Audit::find($value['audit_id']);
                            $rebuttal_status->rebuttal_status = 1;
                            $rebuttal_status->save();
                        }


                        $rebuttal = new Rebuttal();
                        $rebuttal->audit_id = $value['audit_id'];
                        $rebuttal->sub_parameter_id = $value['sub_parameter_id'];
                        $rebuttal->remark = $value['remark'];
                        $rebuttal->raised_by_user_id = $audit_detail[0]->admin_user_id;
                        $rebuttal->raw_data_id = $audit_detail[0]->raw_data_id;
                        $rebuttal->parameter_id = $value['parameter_id'];
                        $rebuttal->desired_option = $value['desired_option'];
                        $rebuttal->artifact = $value['artifact'];
                        $rebuttal->save();
                        $counter++;
                        // if ($value['artifact']) {
                        //     $value['artifact']->store("artifact");
                        //     $file_name = $value['artifact']->hashName();
                        //     $data = Storage::url('artifact/') . $file_name;
                        // }
                        // if ($rebuttal->save()) {
                        //     return response()->json(['status' => 1, 'message' => "pass", 'data' => 'Rebuttal raised sucessfully'], 200);
                        // }
                    } else {
                        continue;
                    }
                    //  else {
                    //     return response()->json(['status' => 0, 'message' => "Fail", 'data' => 'Rebuttal already inserted'], 200);
                    // }
                }
                if ($dataCount == $counter) {
                    return response()->json(['status' => 1, 'message' => "Success", 'data' => 'Rebuttal raised sucessfully'], 200);
                } else {
                    return response()->json(['status' => 1, 'message' => "Success", 'count' => $counter, 'data' => 'Rebuttal raised sucessfully'], 200);
                }
            }
        }

    }
    public function uploadImage(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            //   'link' => 'required',
        ]);

        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {

            $request->file_upload->store("qaviews_porter_files");
            $file_upload_name = $request->file_upload->hashName();
            $file_img_upload = Storage::url('qaviews_porter_files/') . $file_upload_name;
            return response()->json(['status' => 200, 'message' => "File uploaded successfuly", 'file_image' => $file_img_upload], 200);

        }

    }
    public function publicUrlFile(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'link' => 'required',
        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            if (strlen($request->link) > 0) {
                $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $request->link);
                $url = Storage::disk('s3')->temporaryUrl(
                    $path_name,
                    now()->addMinutes(8640) //Minutes for which the signature will stay valid
                );
            }
            return response()->json(['status' => 200, 'message' => "File uploaded successfuly", 'public_link' => $url], 200);

        }
    }
    public function parameter_wise_plan_of_action(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            // 'audit_id' => 'required',
            // 'parameter_id' => 'required',
            // 'sub_parameter_id' => 'required',
            // 'remark' => 'required',
        ]);

        $dataCount = count($request->parameter_planOfAction);
        $counter = 0;
        // return $dataCount;
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            if ($request->parameter_planOfAction == null) {
                return response()->json(['status' => 0, 'message' => "Fail", 'data' => 'Data Not Found'], 200);
            } else {
                foreach ($request->parameter_planOfAction as $key => $value) {
                    $remark = ParameterWisePlanOfAction::where('audit_id', $value['audit_id'])->where('parameter_id', $value['parameter_id'])->where('sub_parameter_id', $value['sub_parameter_id'])->first();
                    if (!$remark) {
                        $new_remark = new ParameterWisePlanOfAction();
                        $new_remark->audit_id = $value['audit_id'];
                        $new_remark->parameter_id = $value['parameter_id'];
                        $new_remark->sub_parameter_id = $value['sub_parameter_id'];
                        $new_remark->remark = $value['remark'];
                        $new_remark->recording_file = $value['recording_file'];
                        $new_remark->save();
                        $counter++;
                        // if($new_remark->recording_file){
                        //     $path_name = str_replace("https://qmtool.s3.ap-south-1.amazonaws.com/", "", $new_remark->recording_file);
                        //     $url = Storage::disk('s3')->temporaryUrl(
                        //         $path_name,
                        //         now()->addMinutes(8640) //Minutes for which the signature will stay valid
                        //     );
                        //    $file_upload  = $url;
                        //    return $file_upload;
                        // } else {
                        //     $file_upload = $new_remark->recording_file;
                        // }
                    } else {
                        continue;
                    }
                }


                if ($dataCount == $counter) {
                    return response()->json(['status' => 1, 'message' => "Success", 'data' => 'Feedback inserted'], 200);
                } else {
                    return response()->json(['status' => 1, 'message' => "Success", 'count' => $counter, 'data' => 'Feedback inserted'], 200);
                }
            }
        }
    }

    function agent_dashboard_lob(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Login expired');
            return response(json_encode($data), 200);
        }

        $validator = Validator::make($request->all(), [
            'process_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);


        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $agent_id = $userDetails->id;
            $process_id = $request->process_id;
            $start_date = $request->start_date;
            $end_date = $request->end_date;

            $process_data = array();

            $process_data['audit_count'] = Audit::where('process_id', $process_id)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                    $query->where('agent_id', $agent_id);
                })
                ->count();

            $audit_ids = Audit::where('process_id', $process_id)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                    $query->where('agent_id', $agent_id);
                })
                ->pluck('id')->toArray();

            $process_data['with_fatal_score'] = Audit::where('process_id', $process_id)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                    $query->where('agent_id', $agent_id);
                })
                ->avg('with_fatal_score_per');

            $process_data['without_fatal_score'] = Audit::where('process_id', $process_id)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                    $query->where('agent_id', $agent_id);
                })
                ->avg('overall_score');

            $qrc_list = ['Query', 'Request', 'Complaint'];
            $qrc_arr = [];
            foreach ($qrc_list as $val) {
                $qrc_object = array();

                $qrc_object['qrc'] = $val;
                $qrc_object['audit_count'] = Audit::where('process_id', $process_id)
                    ->where('qrc_2', $val)
                    ->whereDate('audit_date', '>=', $start_date)
                    ->whereDate('audit_date', '<=', $end_date)
                    ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                        $query->where('agent_id', $agent_id);
                    })
                    ->count();

                $qrc_object['with_fatal_score_per'] = Audit::where('process_id', $process_id)
                    ->where('qrc_2', $val)
                    ->whereDate('audit_date', '>=', $start_date)
                    ->whereDate('audit_date', '<=', $end_date)
                    ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                        $query->where('agent_id', $agent_id);
                    })
                    ->avg('with_fatal_score_per');

                $qrc_object['without_fatal_score'] = Audit::where('process_id', $process_id)
                    ->where('qrc_2', $val)
                    ->whereDate('audit_date', '>=', $start_date)
                    ->whereDate('audit_date', '<=', $end_date)
                    ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                        $query->where('agent_id', $agent_id);
                    })
                    ->avg('overall_score');

                $qrc_arr[] = $qrc_object;
            }
            $process_data['qrc'] = $qrc_arr;

            $qm_sheet_details = QmSheet::where('process_id', $process_id)->orderby('version', 'desc')->first();

            $parameters = QmSheetParameter::where('qm_sheet_id', $qm_sheet_details->id)->get();

            $parameter_data = [];
            foreach ($parameters as $para) {
                $para_data = array();
                $para_data['id'] = $para->id;
                $para_data['name'] = $para->parameter;
                $para_data['score'] = AuditParameterResult::whereIn('audit_id', $audit_ids)->where('parameter_id', $para->id)->avg('with_fatal_score_per');

                $parameter_data[] = $para_data;
            }
            $process_data['parameter_compiance'] = $parameter_data;


            // pareto data
            $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($process_id, $start_date, $end_date) {
                $query->where('process_id', $process_id)
                    ->whereBetween('audit_date', [$start_date, $end_date]);
            })->with('reason')->get();

            // $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($lobId,$month_first_data,$today) {
            //     $query->where('process_id',$lobId)
            //    ->whereDate('audit_date','>=',$month_first_data)
            //    ->whereDate('audit_date','<=',$today)
            //     ;
            // })->with('reason')->get();


            $all_uni_reaons = [];
            $all_uni_reaons_a = [];
            $all_uni_reaons_b = [];
            $all_uni_reaons_c = [];
            $all_uni_reaons_d = [];
            $all_uni_reaons_e = [];

            foreach ($pareto_audit_result as $key => $value) {
                if ($value->reason)
                    $all_uni_reaons_a[$value->reason_id] = ['count' => 0, 'name' => $value->reason->name];
            }
            //get count
            foreach ($all_uni_reaons_a as $key => $value) {
                $count = $pareto_audit_result->where('reason_id', $key)->count();
                $all_uni_reaons_a[$key]['count'] = $count;
            }
            usort($all_uni_reaons_a, function ($a, $b) {
                return $b['count'] - $a['count'];
            });
            $runningSum = 0;
            foreach ($all_uni_reaons_a as $kk => $vv) {
                $runningSum += $vv['count'];
                $all_uni_reaons_b[$kk] = $vv['count'];
                $all_uni_reaons_c[$kk] = $runningSum;
                $all_uni_reaons_d[$kk] = $vv['name'];
            }
            foreach ($all_uni_reaons_c as $kk => $vv) {
                $all_uni_reaons_e[$kk] = (round(($vv / $runningSum) * 100, 2));
            }
            $process_data['pareto_data']['count'] = $all_uni_reaons_b;
            $process_data['pareto_data']['per'] = $all_uni_reaons_e;
            $process_data['pareto_data']['reasons'] = $all_uni_reaons_d;
            $process_data['pareto_data']['reasons_count'] = $runningSum;
        }





        // // pareto data
        return response()->json(['status' => 0, 'message' => "success", 'final_data' => $process_data], 200);

    }
    function agent_dashboard_lob_list(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $client_id = $userDetails->parent_client;
        $lob_details = RawData::with('process:id,name')->select(DB::raw("distinct(process_id)"))
            ->where('client_id', $client_id)
            ->where('agent_id', $userDetails->id)
            ->get();
        return response()->json(['status' => 0, 'message' => "success", 'agent_details' => $userDetails, 'lob_details' => $lob_details], 200);

    }
    function agent_dashboard_details(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->first();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }

        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }

        if ($userDetails->parent_client) {

            $client_id = $userDetails->parent_client;
            // $lob_names = RawData::where('agent_id',$userDetails->id)->where('client_id',14)->pluck('lob');

            $all_cluster_processes = get_helper_cluster_processes($userDetails->id);

            $all_cluster_partners = get_helper_cluster_partners($userDetails->id);
            // return $all_cluster_partners;

            $all_cluster_locations = get_helper_cluster_locations($userDetails->id);

            // $all_cluster_lobs = get_helper_cluster_lobs(Auth::user()->id);

        } else {
            return redirect('error')->with('warning', 'Please set client details');
        }


        if ($request->isMethod('post')) {
            if ($request->materialExampleRadios == "blank") {
                return redirect('welcome_dashboard_new')->with('warning_t', 'Please select audit cycle');
                die;
            }

            if ($request->materialExampleRadios == "date_range") {
                $dates = explode("-", $request->target_month);
                $st = date("Y-m-d", strtotime(trim($dates[0])));
                $en = date("Y-m-d", strtotime(trim($dates[1])));
                $month_first_data = $st;
                $today = $en;
            } else {
                $dates = explode(" ", $request->month);
                $month_first_data = $dates[0];
                $today = $dates[1];
            }
            $audit_cycle_data = Auditcycle::where('start_date', $month_first_data)->where('end_date', $today)->first();
            $month = ($audit_cycle_data) ? $audit_cycle_data->name : '-';

        } else {
            $audit_cyle_data = Auditcycle::where('client_id', $client_id)->orderby('start_date', 'desc')->first();
            $month_first_data = "2023-09-01";
            $today = "2023-09-30";
            $month = $audit_cyle_data->name;
        }

        $user = User::where('company_id', $userDetails->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qtl');
        })->pluck('name', 'id');
        // return $user;
        // if(Auth::user()->hasRole('agent-tl')){
        if ($user) {
            $agents = User::where('reporting_user_id', $userDetails->id)->pluck('id')->toArray();
        } else {
            $agents = [$userDetails->id];
        }

        $rebuttal_data = [];
        $audit_type = $request->audit_type;

        $audit_data = Audit::with('raw_data')->where('client_id', $client_id)
            ->whereDate('audit_date', '>=', $month_first_data)
            ->whereDate('audit_date', '<=', $today)
            ->whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })
            //->where('internal_audit_status','=',$audit_type)
            ->get();

        $callType = RawData::select(DB::raw("distinct(call_type)"))
            ->where('client_id', $client_id)
            ->whereIn('partner_id', $all_cluster_partners)
            ->whereIn('process_id', $all_cluster_processes)
            ->get();


        if ($userDetails->parent_client) {
            $temp_rebuttal_data = Audit::where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->where('rebuttal_status', '>', 0)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                })
                ->withCount(['audit_rebuttal', 'audit_rebuttal_accepted'])
                ->where('internal_audit_status', '=', $audit_type)
                ->get();
        } else {
            $temp_rebuttal_data = Audit::where('client_id', $client_id)->where('rebuttal_status', '>', 0)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->where('internal_audit_status', '=', $audit_type)
                ->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                })
                ->withCount(['audit_rebuttal', 'audit_rebuttal_accepted'])->get();
        }
        if ($client_id == 1) {
            $coverage['target'] = 500;
            $coverage['achived_per'] = round(($audit_data->count() / 500) * 100);
        } else if ($client_id == 9 || $client_id == 13) {
            if ($userDetails->parent_client) {
                $target = MonthTarget::
                    join('Auditcycles', 'month_targets.month_of_target', '=', 'Auditcycles.id')
                    ->where('month_targets.client_id', $client_id)
                    ->where('Auditcycles.name', 'like', "$month%")
                    ->whereIn('month_targets.process_id', $all_cluster_processes)
                    ->sum('month_targets.eq_audit_target_mtd');
            } else {
                $target = MonthTarget::
                    join('Auditcycles', 'month_targets.month_of_target', '=', 'Auditcycles.id')
                    ->where('month_targets.client_id', $client_id)
                    ->where('Auditcycles.name', 'like', "$month%")
                    ->sum('month_targets.eq_audit_target_mtd');
            }
            if (gettype($target) == "NULL") {
                $coverage['target'] = 0;
            } else {
                $coverage['target'] = $target;
            }
            if ($target == 0) {
                $coverage['achived_per'] = 0;
            } else {
                $coverage['achived_per'] = round(($audit_data->count() / $target) * 100);
            }
        } else {
            $coverage['target'] = 2000;
            $coverage['achived_per'] = round(($audit_data->count() / 2000) * 100);
        }
        $coverage['achived'] = $audit_data->count();
        $final_data['coverage'] = $coverage;
        $temp_total_rebuttal = 0;
        $temp_accepted_rebuttal = 0;
        $temp_rejected_rebuttal = 0;
        foreach ($temp_rebuttal_data as $key => $value) {
            $temp_total_rebuttal += $value->audit_rebuttal->count();
            $temp_accepted_rebuttal += $value->audit_rebuttal_accepted->count();
            $temp_rejected_rebuttal += $value->audit_rebuttal_rejected->count();
        }
        $rebuttal_data['raised'] = $temp_total_rebuttal;
        $rebuttal_data['accepted'] = $temp_accepted_rebuttal;
        $rebuttal_data['rejected'] = $temp_rejected_rebuttal;
        $rebuttal_data['wip'] = ($temp_total_rebuttal - ($temp_accepted_rebuttal + $temp_rejected_rebuttal));

        if ($audit_data->count())
            $rebuttal_data['rebuttal_per'] = round(($rebuttal_data['raised'] / $audit_data->count()) * 100);
        else
            $rebuttal_data['rebuttal_per'] = 0;

        if ($rebuttal_data['accepted'])
            $rebuttal_data['accepted_per'] = round((($rebuttal_data['accepted'] / $audit_data->count()) * 100));
        else
            $rebuttal_data['accepted_per'] = 0;
        if ($rebuttal_data['rejected'])
            $rebuttal_data['rejected_per'] = round((($rebuttal_data['rejected'] / $audit_data->count()) * 100));
        else
            $rebuttal_data['rejected_per'] = 0;
        $final_data['rebuttal'] = $rebuttal_data;

        $query_w = "";
        $request_w = "";
        $complain_w = "";
        $s = 1;

        foreach ($callType as $key => $value) {
            if ($s == 1) {
                $query_w = $value->call_type;
            }
            if ($s == 2) {
                $request_w = $value->call_type;
            }
            if ($s == 3) {
                $complain_w = $value->call_type;
            }
            $s++;
        }

        $final_data['call_type'] = [$query_w, $request_w, $complain_w];



        if ($userDetails->parent_client) {

            if ($user) {

                $partner_list = Partner::where('client_id', $client_id)->with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])->get();

            } else {

                $partner_list = Partner::with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])

                    ->whereIn('id', $all_cluster_partners)

                    ->get();

            }



        } else {

            $partner_list = Partner::where('client_id', $client_id)->with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])->get();

        }

        $partner_process_list = [];

        foreach ($partner_list as $akey => $avalue) {
            //   return response()->json(['status'=>200,'message'=>"Success",'data'=>$avalue->id], 200); die;

            foreach ($avalue->partner_process as $bkey => $bvalue) {

                if ($userDetails->parent_client) {

                    if (gettype(array_search($bvalue->process_id, $all_cluster_processes)) == "integer") {

                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;

                    }

                } else {

                    $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;

                }

            }

        }



        $loop = 1;

        $final_score = 0;

        $final_scorable = 0;
        foreach ($partner_process_list as $key => $value) {




            if ($userDetails->parent_client) {



                $process_audit_data['score_sum'] = Audit::where('client_id', $client_id)

                    ->where('process_id', $key)

                    ->whereIn('partner_id', $all_cluster_partners)

                    ->whereDate('audit_date', '>=', $month_first_data)

                    ->whereDate('audit_date', '<=', $today)

                    ->where('internal_audit_status', '=', $audit_type)

                    ->sum('overall_score');



                $fatal_score_sum = DB::select("
    
                                                select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a
    
                                                on p.audit_id = a.id where a.process_id = " . $key . " and a.client_id = " . $client_id . " and a.partner_id = " . $avalue->id . " and a.internal_audit_status = '" . $audit_type . "'
    
                                                and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'  and a.is_critical = 0 and a.deleted = 0");



                $temp_wait_sum = DB::select("
    
                                                select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a
    
                                                on p.audit_id = a.id where a.process_id = " . $key . " and a.client_id = " . $client_id . " and a.partner_id = " . $avalue->id . " and a.internal_audit_status = '" . $audit_type . "'
    
                                                and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "' and a.deleted = 0");





                $process_audit_data['audit_count'] = Audit::where('client_id', $client_id)

                    ->where('process_id', $key)

                    ->whereIn('partner_id', $all_cluster_partners)

                    ->whereDate('audit_date', '>=', $month_first_data)

                    ->whereDate('audit_date', '<=', $today)

                    ->where('internal_audit_status', '=', $audit_type)

                    ->count();



                $process_audit_data['with_fatal'] = Audit::where('client_id', $client_id)

                    ->where('process_id', $key)

                    ->whereIn('partner_id', $all_cluster_partners)

                    //->where('is_critical',1)
                    ->where('internal_audit_status', '=', $audit_type)

                    ->whereDate('audit_date', '>=', $month_first_data)

                    ->whereDate('audit_date', '<=', $today)

                    ->sum('with_fatal_score_per');

            } else {
                $process_audit_data['score_sum'] = Audit::where('client_id', $client_id)

                    ->where('process_id', $key)
                    ->where('partner_id', $avalue->id)
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->where('internal_audit_status', '=', $audit_type)
                    ->whereDate('audit_date', '<=', $today)

                    ->sum('overall_score');



                $fatal_score_sum = DB::select("
    
                                                select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a
    
                                                on p.audit_id = a.id where a.process_id = " . $key . " and a.partner_id = " . $avalue->id . " and a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "'
    
                                                and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'  and a.is_critical = 0");



                $temp_wait_sum = DB::select("
    
                                                select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a
    
                                                on p.audit_id = a.id where a.process_id = " . $key . " and partner_id = " . $avalue->id . " and  a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "'
    
                                                and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'");





                $process_audit_data['audit_count'] = Audit::where('client_id', $client_id)

                    ->where('process_id', $key)
                    ->where('partner_id', $all_cluster_partners)
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->where('internal_audit_status', '=', $audit_type)
                    ->whereDate('audit_date', '<=', $today)

                    ->count();



                $process_audit_data['with_fatal'] = Audit::where('client_id', $client_id)

                    ->where('process_id', $key)

                    //->where('is_critical',1)
                    ->where('partner_id', $all_cluster_partners)
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->where('internal_audit_status', '=', $audit_type)
                    ->whereDate('audit_date', '<=', $today)

                    ->sum('with_fatal_score_per');



            }

            /* New added by shailendra pms ticket - 945  */

            $rebuttal_data_process = $this->Calculate_process_rebuttal_welcome_dashboard($client_id, $key, $month_first_data, $today, $process_audit_data['audit_count']);



            $process_audit_data['rebuttal_per'] = $rebuttal_data_process['rebuttal_per'];

            $process_audit_data['accepted_per'] = $rebuttal_data_process['accepted_per'];

            $process_audit_data['rejected_per'] = $rebuttal_data_process['rejected_per'];

            $process_audit_data['raised_process'] = $rebuttal_data_process['raised'];

            $process_audit_data['accepted_process'] = $rebuttal_data_process['accepted'];

            $process_audit_data['rejected_process'] = $rebuttal_data_process['rejected'];



            /* New added by shailendra pms ticket - 945  */



            if ($process_audit_data['audit_count']) {





                //  $process_audit_data['scored_with_fatal'] = round(   ($process_audit_data['with_fatal']/$process_audit_data['audit_count']));

                /* echo $process_audit_data['audit_count'];

                dd(); */
                if ($temp_wait_sum[0]->temp_sum == 0) {

                    $process_audit_data['scored_with_fatal'] = 0;

                } else {
                    $process_audit_data['scored_with_fatal'] = round(($fatal_score_sum[0]->fatal_sum / $temp_wait_sum[0]->temp_sum) * 100);

                }
                // dd($temp_wait_sum[0]->temp_sum."sdfdg");

                $process_audit_data['score'] = round(($process_audit_data['score_sum'] / $process_audit_data['audit_count']));

            } else {

                $process_audit_data['score'] = 0;

                $process_audit_data['scored_with_fatal'] = 0;

            }



            $partner_process_list[$key]['data'] = $process_audit_data;

            $final_score += $process_audit_data['score_sum'];

            $final_scorable += $process_audit_data['audit_count'];





            if ($loop == 1)

                $partner_process_list[$key]['class'] = true;
            else

                $partner_process_list[$key]['class'] = false;



            $loop++;



        }

        $ov_scored = 0;

        if ($final_scorable != 0) {

            $ov_scored = round(($final_score / $final_scorable) * 100);

        }



        $final_data['pws'] = $partner_process_list;

        // ends Process Wise Score



        // Partner & Location Wise Report

        $pl_report = [];

        $loop = 1;

        $partner_name = "";

        $loc_audit_count = 0;

        foreach ($partner_list as $key => $value) {

            //echo "<pre>"; print_r($value->partner_process); die; 

            $d = array();

            $d['partner_id'] = $value->id;

            $d['partner_name'] = $value->name;

            $plr_audits = Audit::where('client_id', $client_id)->where('partner_id', $value->id)->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today)->get();

            $d['audit_count'] = $plr_audits->count();



            $fatal_score_sum = DB::select("
    
                            select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a
    
                            on p.audit_id = a.id 
    
                            inner join raw_data r on a.raw_data_id = r.id
    
                            where a.partner_id = " . $d['partner_id'] . " and a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "'
    
                            and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'  and a.is_critical = 0");



            $temp_wait_sum = DB::select("
    
                            select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a
    
                            on p.audit_id = a.id 
    
                            inner join raw_data r on a.raw_data_id = r.id
    
                            where a.partner_id = " . $d['partner_id'] . " and a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "'
    
                            and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'");





            $fatal_audit_score_sum = 0;

            $without_fatal_audit_score_sum = 0;

            foreach ($plr_audits as $key => $value1) {

                if ($value1->is_critical == 1)

                    $fatal_audit_score_sum += 0;
                else

                    $fatal_audit_score_sum += $value1->overall_score;



                $without_fatal_audit_score_sum += $value1->overall_score;

            }

            if ($plr_audits->count()) {

                // $d['with_fatal'] = round(($fatal_audit_score_sum/$plr_audits->count()));



                if ($temp_wait_sum[0]->temp_sum == 0) {

                    $d['with_fatal'] = 0;

                } else {

                    $d['with_fatal'] = round(($fatal_score_sum[0]->fatal_sum / $temp_wait_sum[0]->temp_sum) * 100);

                }


                $d['without_fatal'] = round(($without_fatal_audit_score_sum / $plr_audits->count()));

            } else {

                $d['with_fatal'] = 0;

                $d['without_fatal'] = 0;

            }

            $d['process_data'] = array();

            foreach ($value->partner_process as $bkey => $bvalue) {

                foreach ($value->partner_location as $lkey => $lvalue) {

                    $a = array();

                    $a['process_id'] = $bvalue->process->id;

                    $a['process_name'] = $bvalue->process->name;

                    $a['location_id'] = $lvalue->location_id;

                    $a['location'] = $lvalue->location_detail->name;





                    $audits = Audit::where('client_id', $client_id)->where('process_id', $a['process_id'])->where('internal_audit_status', '=', $audit_type)->where('partner_id', $value->id)->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today)->whereHas('raw_data', function (Builder $query) use ($a) {

                        $query->where('partner_location_id', 'like', $a['location_id']);

                    })->get();





                    $fatal_score_sum = DB::select("
    
                            select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a
    
                            on p.audit_id = a.id 
    
                            inner join raw_data r on a.raw_data_id = r.id
    
                            where a.process_id = " . $a['process_id'] . " and a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "' and r.partner_location_id like '" . $a['location_id'] . "%'
    
                            and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'  and a.is_critical = 0");



                    $temp_wait_sum = DB::select("
    
                            select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a
    
                            on p.audit_id = a.id 
    
                            inner join raw_data r on a.raw_data_id = r.id
    
                            where a.process_id = " . $a['process_id'] . " and a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "' and r.partner_location_id like '" . $a['location_id'] . "%'
    
                            and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'");







                    $a['audit_count'] = $audits->count();









                    $fatal_audit_score = 0;

                    $without_fatal_audit_score = 0;

                    foreach ($audits as $k => $v) {



                        if ($v->is_critical == 1)

                            $fatal_audit_score += 0;
                        else

                            $fatal_audit_score += $v->overall_score;



                        $without_fatal_audit_score += $v->overall_score;

                    }



                    if ($audits->count()) {

                        // $a['with_fatal'] = round(($fatal_audit_score/$audits->count()));

                        if ($temp_wait_sum[0]->temp_sum == 0) {

                            $a['with_fatal'] = 0;

                        } else {

                            $a['with_fatal'] = round(($fatal_score_sum[0]->fatal_sum / $temp_wait_sum[0]->temp_sum) * 100);

                        }





                        $a['without_fatal'] = round(($without_fatal_audit_score / $audits->count()));

                    } else {

                        $a['with_fatal'] = 0;

                        $a['without_fatal'] = 0;

                    }

                    $d['process_data'][] = $a;

                }

            }

            $pl_report[] = $d;

        }

        $final_data['plr'] = $pl_report;
        $audit_cyle_data = Auditcycle::where('client_id', $client_id)->get();
        $final_data['audit_cycle'] = $audit_cyle_data;
        $final_data['ov_scored'] = $ov_scored;
        //    echo"hii 2";die;

        if ($user) {
            /* echo "h2";
            die; */
            $all_agents = User::where('reporting_user_id', $userDetails->id)
                ->pluck('id')->toArray();

            $action_list = RawData::where('status', 1)
                ->whereIn('agent_id', $all_agents)
                ->whereHas('audit', function ($query) use ($month_first_data, $today) {
                    $query->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today);
                })->whereHas('audit', function ($query) {
                // $query->where('reporting_user_id',Auth::user()->id);

            })->with('audit')->orderby('id', 'desc')
                ->get();

        } else {
            $all_agents = [$userDetails->id];


            $action_list = RawData::where('status', 1)
                ->whereIn('agent_id', $all_agents)
                ->whereHas('audit', function ($query) use ($month_first_data, $today) {
                    $query->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today);
                })->whereHas('audit', function ($query) {
                // $query->where('reporting_user_id',Auth::user()->id);

            })->with('audit')->orderby('id', 'desc')
                ->get();
        }

        return response()->json(['status' => 0, 'message' => "success", 'agent_details' => $userDetails, 'final_data' => $final_data], 200);

        // return response()->json(['status' => 0, 'message' => "success", 'final_data' => $final_data,
        // 'month_first_data'=>$month_first_data,'today'=>$today,'client_id'=>$client_id,'action_list'=>$action_list], 200);


    }

    public function Calculate_process_rebuttal_welcome_dashboard($client_id, $process_id, $month_first_data, $today, $process_audit_count)
    {


        $process_audit_data = array();

        $temp_rebuttal_data_process = Audit::where('client_id', $client_id)->where('rebuttal_status', '>', 0)

            ->where('process_id', $process_id)

            ->whereDate('audit_date', '>=', $month_first_data)

            ->whereDate('audit_date', '<=', $today)

            ->withCount(['audit_rebuttal', 'audit_rebuttal_accepted'])->get();



        $temp_total_rebuttal_process = 0;

        $temp_accepted_rebuttal_process = 0;

        $temp_rejected_rebuttal_process = 0;



        foreach ($temp_rebuttal_data_process as $kay => $value) {

            $temp_total_rebuttal_process += $value->audit_rebuttal->count();

            $temp_accepted_rebuttal_process += $value->audit_rebuttal_accepted->count();

            $temp_rejected_rebuttal_process += $value->audit_rebuttal_rejected->count();

        }



        $process_audit_data['raised'] = $temp_total_rebuttal_process;

        $process_audit_data['accepted'] = $temp_accepted_rebuttal_process;

        $process_audit_data['rejected'] = $temp_rejected_rebuttal_process;

        $process_audit_data['wip'] = ($temp_total_rebuttal_process - ($temp_accepted_rebuttal_process + $temp_rejected_rebuttal_process));



        // echo "<script>console.log('".$process_audit_data['raised']."')</script>";

        if ($process_audit_count)

            $process_audit_data['rebuttal_per'] = round(($process_audit_data['raised'] / $process_audit_count) * 100);
        else

            $process_audit_data['rebuttal_per'] = 0;



        if ($process_audit_count)

            $process_audit_data['accepted_per'] = round((($process_audit_data['accepted'] / $process_audit_count) * 100));
        else

            $process_audit_data['accepted_per'] = 0;



        if ($process_audit_count)

            $process_audit_data['rejected_per'] = round((($process_audit_data['rejected'] / $process_audit_count) * 100));
        else

            $process_audit_data['rejected_per'] = 0;



        // End Rebuttal on welcome dashboard on PWS



        return $process_audit_data;

    }
    public function agent_dashboard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agent_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = array();


            $audit_data = Audit::
                whereDate('audit_date', '>=', $request->start_date)
                ->whereDate('audit_date', '<=', $request->end_date)
                //->where('raw_data.agent_name',$request->agent_name)
                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_id', $request->agent_id);

                })
                ->get();

            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;

            $fatal_audit_count = $audit_data->where('is_critical', 1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical', 0)->count();
            $fatal_audit_score_sum = 0;
            $without_fatal_audit_score_sum = 0;
            $scr = 0;
            $sca = 0;
            foreach ($audit_data as $key => $value) {
                foreach ($value->audit_parameter_result->where('is_non_scoring', '!=', 1) as $keyb => $valueb) {
                    if ($value->is_critical == 0) {

                        $scr += $valueb->with_fatal_score;

                    }
                    $sca += $valueb->temp_weight;
                }
                $without_fatal_audit_score_sum += $value->overall_score;
            }

            if ($audit_data->count()) {

                $fatal_dialer_data['with_fatal_score'] = round(($scr / $sca) * 100);
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum / $audit_data->count()));

            } else {
                $fatal_dialer_data['with_fatal_score'] = 0;
                $fatal_dialer_data['with_fatal_score'] = 0;
            }
            $data['fatal_dialer_data'] = $fatal_dialer_data;

            $data['rebuttal_count'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_id', $request->agent_id);

                })
                ->count('rebuttals.id');


            $data['fatal_count'] = AuditResult::join('audits', 'audit_results.audit_id', '=', 'audits.id')

                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('audit_results.selected_option', 3)
                ->whereHas('audit.raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_id', $request->agent_id);

                })
                ->count('audit_results.id');


            $data['audit_count'] = Audit::whereDate('audit_date', ">=", $request->start_date)
                ->whereDate('audit_date', "<=", $request->end_date)
                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_id', $request->agent_id);

                })

                ->count('id');

            $data['rebuttal_accepted'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 1)

                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_id', $request->agent_id);

                })
                ->count('rebuttals.id');

            $data['rebuttal_rejected'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 2)

                ->whereHas('raw_data', function (Builder $query) use ($request) {

                    $query->where('agent_id', $request->agent_id);

                })
                ->count('rebuttals.id');


            return response()->json(['status' => 0, 'message' => "success", 'data' => $data], 200);
        }


    }


    public function auditor_dashboard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auditor_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = array();

            $audit_data = Audit::with('raw_data')
                ->whereDate('audit_date', '>=', $request->start_date)
                ->whereDate('audit_date', '<=', $request->end_date)
                ->where('audited_by_id', $request->auditor_id)
                ->get();

            $fatal_dialer_data['with_fatal_score'] = 0;
            $fatal_dialer_data['without_fatal_score'] = 0;

            $fatal_audit_count = $audit_data->where('is_critical', 1)->count();
            $without_fatal_audit_count = $audit_data->where('is_critical', 0)->count();
            $fatal_audit_score_sum = 0;
            $without_fatal_audit_score_sum = 0;
            $scr = 0;
            $sca = 0;
            foreach ($audit_data as $key => $value) {
                foreach ($value->audit_parameter_result->where('is_non_scoring', '!=', 1) as $keyb => $valueb) {
                    if ($value->is_critical == 0) {

                        $scr += $valueb->with_fatal_score;

                    }
                    $sca += $valueb->temp_weight;
                }
                $without_fatal_audit_score_sum += $value->overall_score;
            }

            if ($audit_data->count()) {

                $fatal_dialer_data['with_fatal_score'] = round(($scr / $sca) * 100);
                $fatal_dialer_data['without_fatal_score'] = round(($without_fatal_audit_score_sum / $audit_data->count()));

            } else {
                $fatal_dialer_data['with_fatal_score'] = 0;
                $fatal_dialer_data['with_fatal_score'] = 0;
            }
            $data['fatal_dialer_data'] = $fatal_dialer_data;

            $data['rebuttal_count'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('audited_by_id', $request->auditor_id)
                ->count('rebuttals.id');


            $data['fatal_count'] = AuditResult::join('audits', 'audit_results.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('audit_results.selected_option', 3)
                ->where('audits.audited_by_id', $request->auditor_id)
                ->count('audit_results.id');

            $data['audit_count'] = Audit::whereDate('audit_date', ">=", $request->start_date)
                ->whereDate('audit_date', "<=", $request->end_date)
                ->where('audited_by_id', $request->auditor_id)
                ->count('id');

            $data['rebuttal_accepted'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 1)
                ->where('audits.audited_by_id', $request->auditor_id)
                ->count('rebuttals.id');

            $data['rebuttal_rejected'] = Rebuttal::join('audits', 'rebuttals.audit_id', '=', 'audits.id')
                ->whereDate('audits.audit_date', ">=", $request->start_date)
                ->whereDate('audits.audit_date', "<=", $request->end_date)
                ->where('rebuttals.status', 2)
                ->where('audits.audited_by_id', $request->auditor_id)
                ->count('rebuttals.id');


            return response()->json(['status' => 0, 'message' => "success", 'data' => $data], 200);
        }


    }
    public function auditor_process_list(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->firstOrFail();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }
        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $sheet_data = [];
            $assigned_process_id = User::where('id', $request->user_id)->pluck('assigned_process_ids');
            $proList = json_decode($assigned_process_id[0]);
            //echo "<pre>"; print_r($proList); die;
            $getSheetId = array();
            foreach ($proList as $key => $value) {
                $sh = QmSheet::select('id')->where('process_id', $value)->orderBy('version', 'desc')->first();
                $getSheetId[] = $sh->id;
            }
            $sheet_data = QmSheet::whereIn('process_id', json_decode($assigned_process_id[0]))->with('process')->whereIn('id', $getSheetId)->get();
            return response()->json(['status' => 0, 'message' => "success", 'data' => $sheet_data], 200);
        }
    }

    public function audit_page_agent_list(Request $request)
    {
        $token = $request->header('Authorization');
        $userDetails = User::where("remember_token", $token)->firstOrFail();
        if (!$token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Token not found');
            return response(json_encode($data), 200);
        }
        if (!$userDetails) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'User not found');
            return response(json_encode($data), 200);
        }
        if ($token != $userDetails->remember_token) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => 'Please Login again');
            return response(json_encode($data), 200);
        }

        $validator = Validator::make($request->all(), [
            'qmsheet_id' => 'required',
        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $agents = Partner::get();
            return response()->json(['status' => 0, 'message' => "success", 'data' => $agents], 200);
        }
    }

    public function audit_page_render_sheet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qmsheet_id' => 'required',
            'user_id' => 'required',

        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = QmSheet::with(['client', 'process', 'parameter', 'parameter.qm_sheet_sub_parameter'])->find($request->qmsheet_id);


            $partners_list = Partner::where('client_id', $data->client_id)->pluck('name', 'id');
            $final_data['partners_list'] = $partners_list;

            // Tiwari code
            $sk_data['delay1'] = $data->client->qc_time;
            $sk_data['delay2'] = $data->client->rebuttal_time;
            $sk_data['holiday'] = $data->client->holiday;
            $final_data['sk_client'] = $sk_data;

            //Tiwari code

            // Code by Abhilasha QRC replacement
            /* $getUniqueCallType=RawData::select(DB::raw("distinct(call_type)"))->where('client_id',$data->client_id)->where('process_id',$data->process->id)->limit(3)->orderby('id','desc')->pluck('call_type');
            $final_data['client_id']=$data->client_id;
            $final_data['callTypeList'] = $getUniqueCallType; */
            // Code by Abhilasha QRC replacement

            // Above abhilasha code change by shailendra for Hotstar


            $getUniqueCallType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $data->client_id)->where('process_id', $data->process->id)->limit(3)->pluck('call_type');
            $final_data['client_id'] = $data->client_id;
            $final_data['callTypeList'] = $getUniqueCallType;

            // Above abhilasha code change by shailendra for Hotstar

            // get audit cycle
            $latest_audit_cycle = Auditcycle::where('qmsheet_id', $data->id)->orderBy('start_date')->first();
            $start_date = '';
            $end_date = '';
            if ($latest_audit_cycle) {
                $start_date = $latest_audit_cycle->start_date;
                $end_date = $latest_audit_cycle->end_date;
            }
            $final_data['audit_cycle'] = $start_date . " To " . $end_date;
            $final_data['audit_timestamp'] = date('Y-m-d H:i:s');
            // get audit cycle
            $temp_my_alloted_call_list = RawData::where('qa_id', $request->user_id)
                ->where('dump_date', '>=', $start_date)
                ->where('dump_date', '<=', $end_date)
                ->where('status', 0)->pluck('call_id', 'id');

            $my_alloted_call_list = [];
            foreach ($temp_my_alloted_call_list as $key => $value) {
                $my_alloted_call_list[] = ['key' => $key, "value" => $value];
            }

            $final_data['my_alloted_call_list'] = $my_alloted_call_list;

            $final_data['sheet_details'] = $data->toArray();
            //$final_data['type_b_scoring_option'] = TypeBScoringOption::all();
            $all_type_b_scoring_option = TypeBScoringOption::where('company_id', $data->company_id)->pluck('name', 'id');

            //process data
            $pds = [];
            foreach ($data->parameter as $key => $value_p) {
                $pds[$value_p->id]['name'] = $value_p->parameter;
                $pds[$value_p->id]['is_non_scoring'] = $value_p->is_non_scoring;
                $total_parameter_weight = 0;
                $pds[$value_p->id]['is_fatal'] = 0;
                $pds[$value_p->id]['score'] = 0;
                $pds[$value_p->id]['score_with_fatal'] = 0;
                $pds[$value_p->id]['score_without_fatal'] = 0;
                $pds[$value_p->id]['temp_total_weightage'] = 0;
                foreach ($value_p->qm_sheet_sub_parameter as $key => $value_s) {

                    $pds[$value_p->id]['subs'][$value_s->id]['name'] = $value_s->sub_parameter;
                    $pds[$value_p->id]['subs'][$value_s->id]['details'] = $value_s->details;
                    $pds[$value_p->id]['subs'][$value_s->id]['is_fatal'] = 0;
                    $pds[$value_p->id]['subs'][$value_s->id]['is_non_scoring'] = $value_p->is_non_scoring;
                    $pds[$value_p->id]['subs'][$value_s->id]['failure_reason'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['remark'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['orignal_weight'] = $value_s->weight;
                    $pds[$value_p->id]['subs'][$value_s->id]['temp_weight'] = 0;
                    $scoring_opts = [];
                    if ($value_p->is_non_scoring) {
                        //total weight
                        $total_parameter_weight += 0;
                        if ($value_s->non_scoring_option_group) {
                            foreach (all_non_scoring_obs_options($value_s->non_scoring_option_group) as $key_ns => $value_ns) {
                                $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_ns . "_" . $key_ns . "_0", "value" => $value_ns, "alert_box" => null];
                            }
                        } else {
                            $scoring_opts = null;
                        }


                    } else {
                        //total weight
                        $total_parameter_weight += $value_s->weight;
                        //total weight
                        $alert_box = null;

                        $all_reason_type_fail = null;
                        $all_reason_type_cric = null;
                        $all_reason_type_pwd = null;

                        if ($value_s->pass) {
                            if ($value_s->pass_alert_box_id)
                                $alert_box = AuditAlertBox::find($value_s->pass_alert_box_id);
                            else
                                $alert_box = null;


                            $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . $value_s->weight . "_1_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $value_s->weight . '_1_0', "value" => "Pass", "alert_box" => $alert_box];
                        }

                        if ($value_s->fail) {
                            if ($value_s->fail_alert_box_id)
                                $alert_box = AuditAlertBox::find($value_s->fail_alert_box_id);
                            else
                                $alert_box = null;

                            if ($value_s->fail_reason_types) {
                                $temp_index_f = $value_p->id . "_" . $value_s->id . "_" . "0" . "_2_1";
                                $temp_r_fail = ReasonType::find(explode(',', $value_s->fail_reason_types))->pluck('name', 'id');
                                foreach ($temp_r_fail as $keycc => $valuecc) {
                                    $all_reason_type_fail[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                                }
                            } else {
                                $temp_index_f = $value_p->id . "_" . $value_s->id . "_" . "0" . "_2_0";
                                $all_reason_type_fail = null;
                            }

                            $scoring_opts[$temp_index_f] = ["key" => $temp_index_f, "value" => "Fail", "alert_box" => $alert_box];
                        }

                        if ($value_s->critical) {
                            if ($value_s->critical_alert_box_id)
                                $alert_box = AuditAlertBox::find($value_s->critical_alert_box_id);
                            else
                                $alert_box = null;

                            if ($value_s->critical_reason_types) {
                                $temp_index_cri = $value_p->id . "_" . $value_s->id . "_" . "Critical" . "_3_1";
                                $temp_cric = ReasonType::find(explode(',', $value_s->critical_reason_types))->pluck('name', 'id');
                                foreach ($temp_cric as $keycc => $valuecc) {
                                    $all_reason_type_cric[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                                }
                            } else {
                                $temp_index_cri = $value_p->id . "_" . $value_s->id . "_" . "Critical" . "_3_0";
                                $all_reason_type_cric = null;
                            }


                            $scoring_opts[$temp_index_cri] = ["key" => $temp_index_cri, "value" => "Critical", "alert_box" => $alert_box];
                        }

                        if ($value_s->na) {

                            if ($value_s->na_alert_box_id)
                                $alert_box = AuditAlertBox::find($value_s->na_alert_box_id);
                            else
                                $alert_box = null;


                            $scoring_opts[$value_p->id . "_" . $value_s->id . "_" . "N/A" . "_4_0"] = ["key" => $value_p->id . "_" . $value_s->id . "_" . "N/A" . "_4_0", "value" => "N/A", "alert_box" => $alert_box];
                        }

                        if ($value_s->pwd) {
                            if ($value_s->pwd_alert_box_id)
                                $alert_box = AuditAlertBox::find($value_s->pwd_alert_box_id);
                            else
                                $alert_box = null;

                            if ($value_s->pwd_reason_types) {
                                $temp_index_pwd = $value_p->id . "_" . $value_s->id . "_" . ($value_s->weight / 2) . "_5_1";
                                $temp_pwd = ReasonType::find(explode(',', $value_s->pwd_reason_types))->pluck('name', 'id');
                                foreach ($temp_pwd as $keycc => $valuecc) {
                                    $all_reason_type_pwd[] = ["key" => $value_p->id . "_" . $value_s->id . "_" . $keycc, "value" => $valuecc];
                                }
                            } else {
                                $temp_index_pwd = $value_p->id . "_" . $value_s->id . "_" . ($value_s->weight / 2) . "_5_0";
                                $all_reason_type_pwd = null;
                            }


                            // $scoring_opts[$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0"] = ["key"=>$value_p->id."_".$value_s->id."_".($value_s->weight/2)."_5_0","value"=>"PWD","alert_box"=>$alert_box];

                            $scoring_opts[$temp_index_pwd] = ["key" => $temp_index_pwd, "value" => "PWD", "alert_box" => $alert_box];
                        }

                    }


                    $pds[$value_p->id]['subs'][$value_s->id]['options'] = $scoring_opts;
                    $pds[$value_p->id]['subs'][$value_s->id]['score'] = 0;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_options'] = null;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_option_model'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_fail'] = $all_reason_type_fail;
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_cric'] = $all_reason_type_cric;
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type_pwd'] = $all_reason_type_pwd;
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reason_type'] = null;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_reason_type'] = '';
                    $pds[$value_p->id]['subs'][$value_s->id]['all_reasons'] = null;
                    $pds[$value_p->id]['subs'][$value_s->id]['selected_reason'] = '';

                }
                $pds[$value_p->id]['parameter_weight'] = $total_parameter_weight;

            }

            $final_data['simple_data'] = $pds;

            // rca starts
            $rca_type = RcaType::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
            $rca_mode = RcaMode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');

            $final_data['rca_type'] = $rca_type;
            $final_data['rca_mode'] = $rca_mode;
            // rca end

            // rca type 2starts
            $type_2_rca_mode = Rca2Mode::where('company_id', $data->company_id)->where('process_id', $data->process_id)->pluck('name', 'id');
            $final_data['type_2_rca_mode'] = $type_2_rca_mode;
            // rca type 2 ends
        }

        return response()->json(['status' => 0, 'message' => "success", 'data' => $final_data], 200);
    }

    public function get_rca1_by_rca_mode_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rca_mode' => 'required',


        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = Rca1::where('mode_id', $request->rca_mode)->pluck('name', 'id');
            return response()->json(['status' => 200, 'data' => $data], 200);
        }
    }
    public function get_rca2_by_rca1_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rca1' => 'required',


        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = Rca2::where('rca1_id', $request->rca1)->pluck('name', 'id');
            return response()->json(['status' => 200, 'data' => $data], 200);
        }
    }
    public function get_rca3_by_rca2_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rca2' => 'required',


        ]);
        if ($validator->fails()) {
            $data = array('status' => 0, 'message' => 'Validation Errors', 'data' => $validator->errors());
            return response(json_encode($data), 200);
        } else {
            $data = Rca3::where('rca2_id', $request->rca2)->pluck('name', 'id');
            return response()->json(['status' => 200, 'data' => $data], 200);
        }
    }

    public function store_audit(Request $request)
    {
        $check_log = AuditLog::where('auditor_id', $request->submission_data[0]['auditor_id'])->orderBy('created_at', 'DESC')->first();

        if ($check_log) {
            if (gettype($check_log->end_time) == "NULL") {
                // echo $check_log->id;
                $update_old = AuditLog::find($check_log->id);

                $datetime1 = new DateTime($update_old->created_at);
                $datetime2 = new DateTime(now());

                $interval = $datetime1->diff($datetime2);
                $minuts = 0;
                $hours = 0;
                $total_minutes = 0;

                foreach ($interval as $key => $value) {
                    if ($key == "i") {
                        $minutes = $value;
                    }
                    if ($key == "h") {
                        $hours = $value;
                    }

                }

                $total_minutes = ($hours * 60) + $minutes;

                if ($total_minutes > 50) {
                    $total_minutes = 50;
                }

                $update_old->end_time = now();
                $update_old->total_minuts = $total_minutes;
                $update_old->status = 1;
                $update_old->save();
            }
        }

        $raw_data_id = Audit::where('raw_data_id', $request->submission_data[0]['raw_data_id'])->first();


        if (!$raw_data_id && $request->submit) {


            $new_ar = new Audit;
            $new_ar->company_id = $request->submission_data[0]['company_id'];
            $new_ar->client_id = $request->submission_data[0]['client_id'];
            $new_ar->partner_id = $request->submission_data[0]['partner_id'];
            $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
            $new_ar->process_id = $request->submission_data[0]['process_id'];
            $new_ar->raw_data_id = $request->submission_data[0]['raw_data_id'];
            $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
            $new_ar->is_critical = $request->submission_data[0]['is_critical'];
            $new_ar->overall_score = $request->submission_data[0]['overall_score'];
            //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
            $new_ar->audit_date = date('Y-m-d H:i:s');
            $new_ar->case_id = $request->submission_data[0]['case_id'];
            $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
            $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];

            $new_ar->qrc_2 = $request->submission_data[0]['qrc_2'];
            $new_ar->language_2 = $request->submission_data[0]['language_2'];

            $new_ar->type_id = $request->submission_data[0]['selected_rca_type'];

            $new_ar->mode_id = $request->submission_data[0]['selected_rca_mode'];
            $new_ar->rca1_id = $request->submission_data[0]['selected_rca1'];
            $new_ar->rca2_id = $request->submission_data[0]['selected_rca2'];
            $new_ar->rca3_id = $request->submission_data[0]['selected_rca3'];
            $new_ar->rca_other_detail = $request->submission_data[0]['rca_other_detail'];

            $new_ar->type_2_rca_mode_id = $request->submission_data[0]['selected_type_2_rca_mode'];
            $new_ar->type_2_rca1_id = $request->submission_data[0]['selected_type_2_rca1'];
            $new_ar->type_2_rca2_id = $request->submission_data[0]['selected_type_2_rca2'];
            $new_ar->type_2_rca3_id = $request->submission_data[0]['selected_type_2_rca3'];
            $new_ar->type_2_rca_other_detail = $request->submission_data[0]['type_2_rca_other_detail'];

            $new_ar->good_bad_call = $request->submission_data[0]['good_bad_call'];
            $new_ar->good_bad_call_file = $request->submission_data[0]['good_bad_call_file'];

            if ($request->submission_data[0]['is_critical'] == 1)
                $new_ar->with_fatal_score_per = 0;
            else
                $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];


            //feedback
            if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
                $new_ar->feedback_status = 1;
                $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
            }
            //feedback

            // function to fetch and save date
            $date = date('Y-m-d H:i:s');
            $delay1 = $request->submission_data[0]['delay1'];
            $delay2 = $request->submission_data[0]['delay2'];
            $holiday = $request->submission_data[0]['holiday'];
            $result = skipHoliday(strtotime($date), $holiday, $delay1, $delay2);
            $new_ar->qc_tat = $result[0];
            $new_ar->rebuttal_tat = $result[1];
            // function end



            $new_ar->save();

            // update raw data status
            $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
            $raw_data->customer_name = $request->submission_data[0]['customer_name'];
            $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
            $raw_data->disposition = $request->submission_data[0]['disposition'];
            $raw_data->call_time = $request->submission_data[0]['call_time'];
            $raw_data->call_duration = $request->submission_data[0]['call_duration'];
            $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
            $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
            $raw_data->status = 1;
            $raw_data->save();

            if ($new_ar->id) {
                // store parameter wise data
                foreach ($request->parameters as $key => $value) {

                    $new_arb = new AuditParameterResult;
                    $new_arb->audit_id = $new_ar->id;
                    $new_arb->parameter_id = $key;
                    $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                    $new_arb->orignal_weight = $value['parameter_weight'];
                    $new_arb->temp_weight = $value['temp_total_weightage'];
                    $new_arb->with_fatal_score = $value['score_with_fatal'];
                    $new_arb->without_fatal_score = $value['score_without_fatal'];

                    if ($value['temp_total_weightage'] != 0) {
                        $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
                        $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
                    }
                    $new_arb->is_critical = $value['is_fatal'];

                    $new_arb->save();

                    // store sub parameter wise data
                    foreach ($value['subs'] as $key_sb => $value_sb) {

                        if (isset($value_sb['type']) && !empty($value_sb['type'])) {


                            $image_64 = $value_sb['type']; //your base64 encoded data

                            $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // .jpg .png .pdf


                            $replace = substr($image_64, 0, strpos($image_64, ',') + 1);


                            $image = str_replace($replace, '', $image_64);
                            $image = str_replace(' ', '+', $image);
                            $imageName = rand() . '.' . $extension;

                            Storage::disk('public')->put($imageName, base64_decode($image));
                        }

                        if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null)
                            ; {
                            $new_arc = new AuditResult;
                            $new_arc->audit_id = $new_ar->id;
                            $new_arc->parameter_id = $key;
                            $new_arc->sub_parameter_id = $key_sb;
                            $new_arc->is_critical = $value_sb['is_fatal'];
                            $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                            $temp_selected_opt = explode("_", $value_sb['selected_option_model']);

                            if (count($temp_selected_opt) == 5) {
                                $new_arc->selected_option = $temp_selected_opt[3];

                                if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3 || $temp_selected_opt[3] == 5) {
                                    if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
                                        $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                        $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                        $new_arc->reason_id = $value_sb['selected_reason'];
                                    }
                                }

                            } else {
                                $new_arc->selected_option = 0;
                                $new_arc->reason_type_id = null;
                                $new_arc->reason_id = null;
                            }


                            $new_arc->score = $value_sb['score'];
                            $new_arc->after_audit_weight = $value_sb['temp_weight'];
                            if ($new_arc->is_critical == 1) {
                                $new_arc->screenshot = (isset($imageName)) ? $imageName : '';
                            }


                            $new_arc->remark = $value_sb['remark'];
                            $new_arc->save();
                        }
                    }

                }

            }
            return response()->json(['status' => 200, 'message' => "Audit saved successfully."], 200);
        } else {
            $temp_raw_data_id = TmpAudit::where('raw_data_id', $request->submission_data[0]['raw_data_id'])->first();
            if (!$temp_raw_data_id) {
                $new_ar = new TmpAudit;
                $new_ar->company_id = $request->submission_data[0]['company_id'];
                $new_ar->client_id = $request->submission_data[0]['client_id'];
                $new_ar->partner_id = $request->submission_data[0]['partner_id'];
                $new_ar->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                $new_ar->process_id = $request->submission_data[0]['process_id'];
                $new_ar->raw_data_id = $request->submission_data[0]['raw_data_id'];
                $new_ar->audited_by_id = Crypt::decrypt($request->submission_data[0]['audited_by_id']);
                $new_ar->is_critical = $request->submission_data[0]['is_critical'];
                $new_ar->overall_score = $request->submission_data[0]['overall_score'];
                //$new_ar->audit_date = $request->submission_data[0]['audit_date'];
                $new_ar->audit_date = date('Y-m-d H:i:s');
                $new_ar->case_id = $request->submission_data[0]['case_id'];
                $new_ar->overall_summary = $request->submission_data[0]['overall_summary'];
                $new_ar->refrence_number = $request->submission_data[0]['refrence_number'];

                $new_ar->qrc_2 = $request->submission_data[0]['qrc_2'];
                $new_ar->language_2 = $request->submission_data[0]['language_2'];

                $new_ar->type_id = $request->submission_data[0]['selected_rca_type'];

                $new_ar->mode_id = $request->submission_data[0]['selected_rca_mode'];
                $new_ar->rca1_id = $request->submission_data[0]['selected_rca1'];
                $new_ar->rca2_id = $request->submission_data[0]['selected_rca2'];
                $new_ar->rca3_id = $request->submission_data[0]['selected_rca3'];
                $new_ar->rca_other_detail = $request->submission_data[0]['rca_other_detail'];

                $new_ar->type_2_rca_mode_id = $request->submission_data[0]['selected_type_2_rca_mode'];
                $new_ar->type_2_rca1_id = $request->submission_data[0]['selected_type_2_rca1'];
                $new_ar->type_2_rca2_id = $request->submission_data[0]['selected_type_2_rca2'];
                $new_ar->type_2_rca3_id = $request->submission_data[0]['selected_type_2_rca3'];
                $new_ar->type_2_rca_other_detail = $request->submission_data[0]['type_2_rca_other_detail'];

                $new_ar->good_bad_call = $request->submission_data[0]['good_bad_call'];
                $new_ar->good_bad_call_file = $request->submission_data[0]['good_bad_call_file'];

                if ($request->submission_data[0]['is_critical'] == 1)
                    $new_ar->with_fatal_score_per = 0;
                else
                    $new_ar->with_fatal_score_per = $request->submission_data[0]['overall_score'];


                //feedback
                if (!is_null($request->submission_data[0]['feedback_to_agent'])) {
                    $new_ar->feedback_status = 1;
                    $new_ar->feedback = $request->submission_data[0]['feedback_to_agent'];
                }
                //feedback



                $new_ar->save();

                // update raw data status
                $raw_data = RawData::find($request->submission_data[0]['raw_data_id']);
                $raw_data->customer_name = $request->submission_data[0]['customer_name'];
                $raw_data->phone_number = $request->submission_data[0]['customer_phone'];
                $raw_data->disposition = $request->submission_data[0]['disposition'];
                $raw_data->call_time = $request->submission_data[0]['call_time'];
                $raw_data->call_duration = $request->submission_data[0]['call_duration'];
                $raw_data->call_sub_type = $request->submission_data[0]['call_sub_type'];
                $raw_data->campaign_name = $request->submission_data[0]['campaign_name'];
                $raw_data->status = 1;
                $raw_data->save();

                if ($new_ar->id) {
                    // store parameter wise data
                    foreach ($request->parameters as $key => $value) {

                        $new_arb = new TmpAuditParameterResult;
                        $new_arb->audit_id = $new_ar->id;
                        $new_arb->parameter_id = $key;
                        $new_arb->qm_sheet_id = $request->submission_data[0]['qm_sheet_id'];
                        $new_arb->orignal_weight = $value['parameter_weight'];
                        $new_arb->temp_weight = $value['temp_total_weightage'];
                        $new_arb->with_fatal_score = $value['score_with_fatal'];
                        $new_arb->without_fatal_score = $value['score_without_fatal'];

                        if ($value['temp_total_weightage'] != 0) {
                            $new_arb->with_fatal_score_per = ($value['score_with_fatal'] / $value['temp_total_weightage']) * 100;
                            $new_arb->without_fatal_score_pre = ($value['score_without_fatal'] / $value['temp_total_weightage']) * 100;
                        }
                        $new_arb->is_critical = $value['is_fatal'];

                        $new_arb->save();

                        // store sub parameter wise data
                        foreach ($value['subs'] as $key_sb => $value_sb) {
                            // added by nisha
                            if (isset($value_sb['type']) && !empty($value_sb['type'])) {


                                $image_64 = $value_sb['type']; //your base64 encoded data

                                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1]; // .jpg .png .pdf


                                $replace = substr($image_64, 0, strpos($image_64, ',') + 1);


                                $image = str_replace($replace, '', $image_64);
                                $image = str_replace(' ', '+', $image);
                                $imageName = rand() . '.' . $extension;

                                Storage::disk('public')->put($imageName, base64_decode($image));
                            }
                            // end by nisha

                            if ($value_sb['selected_option_model'] != '' || $value_sb['selected_option_model'] != null)
                                ; {
                                $new_arc = new TmpAuditResult;
                                $new_arc->audit_id = $new_ar->id;
                                $new_arc->parameter_id = $key;
                                $new_arc->sub_parameter_id = $key_sb;
                                $new_arc->is_critical = $value_sb['is_fatal'];
                                $new_arc->is_non_scoring = $value_sb['is_non_scoring'];
                                $temp_selected_opt = explode("_", $value_sb['selected_option_model']);

                                if (count($temp_selected_opt) == 5) {
                                    $new_arc->selected_option = $temp_selected_opt[3];

                                    if ($temp_selected_opt[3] == 2 || $temp_selected_opt[3] == 3) {
                                        if (isset($value_sb['selected_reason_type']) == 1 && $value_sb['selected_reason_type'] != '' && isset($value_sb['selected_reason']) == 1 && $value_sb['selected_reason'] != '') {
                                            $temp_selected_reason_type = explode("_", $value_sb['selected_reason_type']);
                                            $new_arc->reason_type_id = $temp_selected_reason_type[2];
                                            $new_arc->reason_id = $value_sb['selected_reason'];
                                        }
                                    }

                                } else {
                                    $new_arc->selected_option = 0;
                                    $new_arc->reason_type_id = null;
                                    $new_arc->reason_id = null;
                                }


                                $new_arc->score = $value_sb['score'];
                                $new_arc->after_audit_weight = $value_sb['temp_weight'];

                                if ($new_arc->is_critical == 1) {
                                    $new_arc->screenshot = (isset($imageName)) ? $imageName : '';
                                }


                                $new_arc->remark = $value_sb['remark'];
                                $new_arc->save();
                            }
                        }

                    }


                }
            }
            return response()->json(['status' => 200, 'message' => "Tmp Audit saved successfully."], 200);

        }
    }


}