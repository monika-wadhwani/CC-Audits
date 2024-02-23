<?php

namespace App\Http\Controllers;

ini_set('memory_limit', '-1');
use Illuminate\Http\Request;
use App\Target;
use App\Client;
use App\Process;
use App\Partner;
use App\Auditcycle;
use App\RawData;
use App\User;
use App\QaTarget;
use App\QaDailyTarget;
use Crypt;
use Carbon\Carbon;
use DateTime;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Audit;
use App\AuditParameterResult;

use App\AuditResult;
use App\Exports\AgentQuartileExport;
use App\PartnerLocation;

use App\PartnersProcess;

use App\QmSheet;

use App\QmSheetParameter;

use App\QmSheetSubParameter;

use App\ReLabel;

use App\Reason;

use App\ReasonType;

use Illuminate\Database\Eloquent\Builder;

use Maatwebsite\Excel\Facades\Excel;

use App\PartnersProcessSpoc;

use PDF;

use App\MonthTarget;
use App\Rebuttal;
use App\ErrorCode;

class AgentController extends Controller
{
    public function agent_wise_performance_report(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();

        if ($request->target_month) {
            $dates = explode("-", $request->target_month);
            $start_date = date_to_db($dates[0]);
            $end_date = date_to_db($dates[1]);
        } else {
            $start_date = Carbon::now()->subDays(14);
            $start_date = $start_date->copy();
            $start_date = $start_date->toDateString();

            $end_date = Carbon::now()->subDays(7);
            $end_date = $end_date->copy();
            $end_date = $end_date->toDateString();
        }

        /* echo $end_date. "<br>". $start_date;
        dd(); */


        // echo $data;
        // dd();
        if (Auth::user()->hasRole('qa')) {
            $data = DB::select("

                select p.*, 
                (select count(ad.id) from audits ad where ad.partner_id = p.id and 
                ad.audit_date >= '" . $start_date . "' and ad.audit_date <= '" . $end_date . "' and ad.audited_by_id = " . Auth::Id() . ") as audits
                from partners p " . " order by audits desc");
        } else {
            $data = DB::select("

                select p.*, 
                (select count(ad.id) from audits ad where ad.partner_id = p.id and 
                ad.audit_date >= '" . $start_date . "' and ad.audit_date <= '" . $end_date . " ') as audits
                from partners p " . " order by audits desc");
        }


        return view('reports.agent_wise_performance_report', compact('data'));
    }

    public function agent_dashboard2(Request $request)
    {
        return view('porter_design.dashboards.agent_dashboard2');

    }
    public function agent_dashboard(Request $request)
    {
        // return $request;
        if (Auth::user()->parent_client) {

            $client_id = Auth::user()->parent_client;

            $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);

            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);

            $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);

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
            $month_first_data = date('Y-m-01');
            $today = date('Y-m-d H:i:s');
            $month = $audit_cyle_data->name;
        }

        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
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


        if (Auth::user()->parent_client) {
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
            if (Auth::user()->parent_client) {
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



        if (Auth::user()->parent_client) {

            if (Auth::user()->hasRole('qtl')) {

                $partner_list = Partner::where('client_id', $client_id)->with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])->get();

            } else {

                $partner_list = Partner::with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])

                    ->whereIn('id', $all_cluster_partners)

                    ->get();

            }



        } else {

            $partner_list = Partner::where('client_id', $client_id)->with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])->get();

        }

        $all_processes = RawData::whereIn('agent_id', $agents)->distinct('process_id')->pluck('process_id')->toArray();

        foreach ($all_processes as $pro) {
            $partner_process_list[$pro]['name'] = Process::where('id', $pro)->value('name');
        }





        $loop = 1;

        $final_score = 0;

        $final_scorable = 0;

        foreach ($partner_process_list as $key => $value) {

            $agents_string = implode(',', $agents);



            $process_audit_data['score_sum'] = Audit::where('client_id', $client_id)

                ->where('process_id', $key)
                ->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                })
                ->whereDate('audit_date', '>=', $month_first_data)
                ->where('internal_audit_status', '=', $audit_type)
                ->whereDate('audit_date', '<=', $today)

                ->sum('overall_score');



            $fatal_score_sum = DB::select("
                    select sum(p.with_fatal_score) as fatal_sum from audit_parameter_results p inner join audits a 
                    on p.audit_id = a.id 
                    inner join raw_data r on r.id = a.raw_data_id 
                    where a.process_id = " . $key . " and r.agent_id in (" . $agents_string . ") and a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "'
                    and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'  and a.is_critical = 0");



            $temp_wait_sum = DB::select("

                                        select sum(p.temp_weight) as temp_sum from audit_parameter_results p inner join audits a

                                        on p.audit_id = a.id 
                                        inner join raw_data r on r.id = a.raw_data_id 
                                        where a.process_id = " . $key . " and r.agent_id in (" . $agents_string . ") and  a.client_id = " . $client_id . " and a.internal_audit_status = '" . $audit_type . "'

                                        and a.audit_date >= '" . $month_first_data . "' and a.audit_date <= '" . $today . "'");


            $process_audit_data['audit_count'] = Audit::where('client_id', $client_id)

                ->where('process_id', $key)
                ->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                })
                ->whereDate('audit_date', '>=', $month_first_data)
                ->where('internal_audit_status', '=', $audit_type)
                ->whereDate('audit_date', '<=', $today)

                ->count();



            $process_audit_data['with_fatal'] = Audit::where('client_id', $client_id)

                ->where('process_id', $key)

                //->where('is_critical',1)
                ->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                })
                ->whereDate('audit_date', '>=', $month_first_data)
                ->where('internal_audit_status', '=', $audit_type)
                ->whereDate('audit_date', '<=', $today)

                ->sum('with_fatal_score_per');


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

            $plr_audits = Audit::where('client_id', $client_id)->whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today)->get();

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





                    $audits = Audit::where('client_id', $client_id)->where('process_id', $a['process_id'])->where('internal_audit_status', '=', $audit_type)->whereHas('raw_data', function (Builder $query) use ($agents) {
                        $query->whereIn('agent_id', $agents);
                    })->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today)->whereHas('raw_data', function (Builder $query) use ($a) {

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


        if (Auth::user()->hasRole('agent-tl')) {
            /* echo "h2";
            die; */
            $all_agents = User::where('reporting_user_id', Auth::user()->id)
                ->pluck('id')->toArray();

            $action_list = RawData::where('status', 1)
                ->whereIn('agent_id', $all_agents)
                ->whereHas('audit', function ($query) use ($month_first_data, $today) {
                    $query->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today)
                        ->whereDate('agent_tl_approval_tat', '>', date('Y-m-d H:i:s'))
                        ->where('rebuttal_status', '>', 0);
                })->with('audit')->orderby('updated_at', 'desc')
                ->get();

        } elseif (Auth::user()->hasRole('agent')) {

            $action_list = RawData::where('status', 1)
                ->where('agent_id', Auth::user()->id)
                ->whereHas('audit', function ($query) use ($month_first_data, $today) {
                    $query->whereDate('audit_date', '>=', $month_first_data)->whereDate('audit_date', '<=', $today)
                        ->whereDate('rebuttal_tat', '>', date('Y-m-d H:i:s'))
                        ->where('feedback_shared_status', 0);
                })->with('audit')->orderby('updated_at', 'desc')
                ->get();

        }

        return view('dashboards.agent_dashboard', compact('final_data', 'month_first_data', 'today', 'client_id', 'action_list'));

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



        echo "<script>console.log('" . $process_audit_data['raised'] . "')</script>";

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

    public function get_qrc_lob_wise_welcome_dashboard($process_id, $month_first_data, $today)
    {

        if (Auth::user()->parent_client) {
            $client_id = Auth::user()->parent_client;
            $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);
            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);
        }
        if (Auth::user()->hasRole('qtl')) {
            if (Auth::user()->parent_client) {
                $client_id = Auth::user()->parent_client;
            } else {
                return redirect('error')->with('warning', 'Please set client details');
            }
        } else {
            if (Auth::user()->hasRole('partner-quality-head') || Auth::user()->hasRole('partner-admin') || Auth::user()->hasRole('partner-operation-head') || Auth::user()->hasRole('partner-training-head')) {

                if (Auth::user()->parent_client) {
                    $client_id = Auth::user()->parent_client;
                } else {
                    $partner_details = PartnersProcessSpoc::where('user_id', Auth::user()->id)->first();

                    if (isset($partner_details->partner_id)) {
                        $user_details = Partner::where('id', $partner_details->partner_id)->first();
                        $partner_id = $user_details->id;
                        $client_id = $user_details->client_id;
                    } else {
                        $user_details = Partner::where('admin_user_id', Auth::user()->id)->first();
                        if (isset($user_details->client_id)) {
                            $partner_id = $user_details->id;
                            $client_id = $user_details->client_id;
                        } else {
                            return redirect('error')->with('warning', 'Your role is not set please contact to admin!');
                        }
                    }
                }
            } else {
                if (Auth::user()->client_detail) {
                    $client_id = Auth::user()->client_detail->client_id;
                } else {
                    if (Auth::user()->parent_client) {
                        $client_id = Auth::user()->parent_client;
                    } else {
                        return redirect('error')->with('warning', 'Your role is not set please contact to admin!');
                    }

                }
            }
        }

        if (Auth::user()->parent_client) {
            // echo $month_first_data;
            // die;
            $audit_data = Audit::with('raw_data')->where('client_id', $client_id)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->where('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->withCount(['audit_rebuttal', 'audit_rebuttal_accepted', 'audit_parameter_result', 'raw_data', 'audit_results'])
                ->get();

            $callType = RawData::select(DB::raw("distinct(call_type)"))
                ->where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->where('process_id', $process_id)
                ->get();
        } else if (Auth::user()->hasRole('partner-admin')) {
            $audit_data = Audit::with('raw_data')->where('client_id', $client_id)
                ->where('partner_id', $partner_id)
                ->where('process_id', $process_id)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->withCount(['audit_rebuttal', 'audit_rebuttal_accepted', 'audit_parameter_result', 'raw_data', 'audit_results'])->get();
            $callType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $client_id)->get();
        } else {
            $audit_data = Audit::with('raw_data')->where('client_id', $client_id)
                ->where('partner_id', $partner_id)
                ->where('process_id', $process_id)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->withCount(['audit_rebuttal', 'audit_rebuttal_accepted', 'audit_parameter_result', 'raw_data', 'audit_results'])->get();
            $callType = RawData::select(DB::raw("distinct(call_type)"))->where('client_id', $client_id)->get();

        }

        if (Auth::user()->parent_client) {
            $temp_qrc['query_count'] = Audit::where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->where('qrc_2', 'Query')
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->count();

            $temp_qrc['query_fatal_count'] = Audit::where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->where('qrc_2', 'Query')
                ->where('is_critical', 1)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->count();

            $temp_qrc['request_count'] = Audit::where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->where('qrc_2', 'Request')
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->count();

            $temp_qrc['request_fatal_count'] = Audit::where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->where('qrc_2', 'Request')
                ->where('is_critical', 1)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->count();

            $temp_qrc['complaint_count'] = Audit::where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->where('qrc_2', 'Complaint')
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->count();

            $temp_qrc['complaint_fatal_count'] = Audit::where('client_id', $client_id)
                ->whereIn('partner_id', $all_cluster_partners)
                ->whereIn('process_id', $all_cluster_processes)
                ->where('qrc_2', 'Complaint')
                ->where('is_critical', 1)
                ->whereDate('audit_date', '>=', $month_first_data)
                ->whereDate('audit_date', '<=', $today)
                ->count();
            // Add Rebuttal on welcome dashboard on PWS 

            $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id, $process_id, $partner_id, $month_first_data, $today, $temp_qrc['query_count'], ['Query', 'FTR']);

            $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
            $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
            $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
            $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
            $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
            $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];

            $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id, $process_id, $partner_id, $month_first_data, $today, $temp_qrc['request_count'], ['NFTR', 'Request']);
            $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
            $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
            $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
            $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
            $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
            $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];

            $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id, $process_id, $partner_id, $month_first_data, $today, $temp_qrc['complaint_count'], ['Complaint', 'DNA']);
            $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
            $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
            $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
            $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
            $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
            $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];

        } else {

            $temp_qrc['query_count'] =
                Audit::where('client_id', $client_id)->whereIn('qrc_2', ['Query', 'FTR', 'Enquiry'])
                    ->where('process_id', $process_id)
                    ->where('partner_id', $partner_id)
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->whereDate('audit_date', '<=', $today)
                    ->count();

            $temp_qrc['query_fatal_count'] =
                Audit::where('client_id', $client_id)
                    ->where('process_id', $process_id)
                    ->where('partner_id', $partner_id)
                    ->whereIn('qrc_2', ['Query', 'FTR', 'Enquiry'])
                    ->where('is_critical', 1)
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->whereDate('audit_date', '<=', $today)
                    ->count();

            $temp_qrc['request_count'] =
                Audit::where('client_id', $client_id)
                    ->where('process_id', $process_id)
                    ->where('partner_id', $partner_id)
                    ->whereIn('qrc_2', ['NFTR', 'Request'])
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->whereDate('audit_date', '<=', $today)
                    ->count();

            $temp_qrc['request_fatal_count'] =
                Audit::where('client_id', $client_id)
                    ->where('process_id', $process_id)
                    ->where('partner_id', $partner_id)
                    ->whereIn('qrc_2', ['NFTR', 'Request'])
                    ->where('is_critical', 1)
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->whereDate('audit_date', '<=', $today)
                    ->count();

            $temp_qrc['complaint_count'] =
                Audit::where('client_id', $client_id)
                    ->where('process_id', $process_id)
                    ->where('partner_id', $partner_id)
                    ->whereIn('qrc_2', ['Complaint', 'DNA'])
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->whereDate('audit_date', '<=', $today)
                    ->count();

            $temp_qrc['complaint_fatal_count'] =
                Audit::where('client_id', $client_id)
                    ->where('process_id', $process_id)
                    ->where('partner_id', $partner_id)
                    ->whereIn('qrc_2', ['Complaint', 'DNA'])
                    ->where('is_critical', 1)
                    ->whereDate('audit_date', '>=', $month_first_data)
                    ->whereDate('audit_date', '<=', $today)
                    ->count();

            // Add Rebuttal on welcome dashboard on PWS 

            $rebuttal_data_process_query = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id, $process_id, $partner_id, $month_first_data, $today, $temp_qrc['query_count'], ['Query', 'FTR', 'Enquiry']);
            $temp_qrc['query_rebuttal_per'] = $rebuttal_data_process_query['rebuttal_per'];
            $temp_qrc['query_accepted_per'] = $rebuttal_data_process_query['accepted_per'];
            $temp_qrc['query_rejected_per'] = $rebuttal_data_process_query['rejected_per'];
            $temp_qrc['query_raised_process'] = $rebuttal_data_process_query['raised'];
            $temp_qrc['query_accepted_process'] = $rebuttal_data_process_query['accepted'];
            $temp_qrc['query_rejected_process'] = $rebuttal_data_process_query['rejected'];

            $rebuttal_data_process_request = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id, $process_id, $partner_id, $month_first_data, $today, $temp_qrc['request_count'], ['NFTR', 'Request']);
            $temp_qrc['request_rebuttal_per'] = $rebuttal_data_process_request['rebuttal_per'];
            $temp_qrc['request_accepted_per'] = $rebuttal_data_process_request['accepted_per'];
            $temp_qrc['request_rejected_per'] = $rebuttal_data_process_request['rejected_per'];
            $temp_qrc['request_raised_process'] = $rebuttal_data_process_request['raised'];
            $temp_qrc['request_accepted_process'] = $rebuttal_data_process_request['accepted'];
            $temp_qrc['request_rejected_process'] = $rebuttal_data_process_request['rejected'];

            $rebuttal_data_process_complain = $this->Calculate_qrc_process_rebuttal_welcome_dashboard($client_id, $process_id, $partner_id, $month_first_data, $today, $temp_qrc['complaint_count'], ['Complaint', 'DNA']);
            $temp_qrc['complain_rebuttal_per'] = $rebuttal_data_process_complain['rebuttal_per'];
            $temp_qrc['complain_accepted_per'] = $rebuttal_data_process_complain['accepted_per'];
            $temp_qrc['complain_rejected_per'] = $rebuttal_data_process_complain['rejected_per'];
            $temp_qrc['complain_raised_process'] = $rebuttal_data_process_complain['raised'];
            $temp_qrc['complain_accepted_process'] = $rebuttal_data_process_complain['accepted'];
            $temp_qrc['complain_rejected_process'] = $rebuttal_data_process_complain['rejected'];

            // End Rebuttal on welcome dashboard on PWS
        }
        $final_data['qrc'] = $temp_qrc;


        // Guage chart data

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
        $final_data['fatal_dialer_data'] = $fatal_dialer_data;
        // Guage chart data end

        // Parameter Wise Fatal Count & Score
        //get latest QM Sheet

        $latest_qm_sheet = QmSheet::where('client_id', $client_id)->where('process_id', $process_id)->orderBy('version', 'desc')->first();
        $latest_qm_sheet_id = $latest_qm_sheet->id;
        $all_params = QmSheetParameter::where('qm_sheet_id', $latest_qm_sheet_id)->where('is_non_scoring', 0)->with('qm_sheet_sub_parameter')->get();
        $pwfcs = [];
        $pws;
        $params_list = [];

        $all_fatal_audits = $audit_data->where('is_critical', 1);

        $count = 1;
        foreach ($all_params as $key => $value) {
            $temp_params['counter'] = $count;
            $temp_params['fatal_count'] = 0;
            $temp_params['fail_count'] = 0;
            $temp_params['fatal_counted_score'] = 0;
            $temp_params['parameter'] = $value->parameter;
            $params_list[] = ucfirst($value->parameter);
            $paramSco = 0;
            $paramScoble = 0;
            foreach ($audit_data as $bkey => $bvalue) {
                $temp_params['fatal_count'] += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->where('is_critical', 1)->count();
                $temp_params['fail_count'] += $bvalue->audit_results->where('parameter_id', $value->id)->where('selected_option', 2)->count();
                // $temp_row = $bvalue->audit_parameter_result->where('parameter_id',$value->id)->where('is_critical',1)->first();
                $temp_params['fatal_counted_score'] += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score_per');
                $paramSco += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score');
                $paramScoble += $bvalue->audit_parameter_result->where('parameter_id', $value->id)->sum('temp_weight');

            }
            if ($paramScoble != 0)
                // $temp_params['fatal_score'] = round(($temp_params['fatal_counted_score']/$audit_data->count()));
                $temp_params['fatal_score'] = round(($paramSco / $paramScoble) * 100);
            else
                $temp_params['fatal_score'] = 0;
            $pwfcs[] = $temp_params;
            // Parameter score 2
            $paramScore = 0;
            $paramScorable = 0;
            $temp_params['param_score_total'] = 0;
            foreach ($audit_data as $keyc => $valuec) {
                $temp_params['param_score_total'] += $valuec->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score_per');
                $paramScore += $valuec->audit_parameter_result->where('parameter_id', $value->id)->sum('with_fatal_score');
                $paramScorable += $valuec->audit_parameter_result->where('parameter_id', $value->id)->sum('temp_weight');
            }
            if ($paramScorable != 0)
                // $temp_params['param_score'] = round(($temp_params['param_score_total']/$audit_data->count()));
                $temp_params['param_score'] = round(($paramScore / $paramScorable) * 100);
            else
                $temp_params['param_score'] = rand(65, 95);
            // Parameter score 2

            // $pws[] = $temp_params['param_score'];
            $pws[] = $temp_params['param_score'];
            $count++;
        }
        $final_data['pwfcs'] = $pwfcs;

        // Parameter Wise Fatal Count & Score
        // Process Score
        // Parameter score 2

        $final_data['parameter_list'] = $params_list;
        $final_data['parameter_wise_score'] = $pws;


        // Parameter Wise compliance & Score

        // pareto data
        $pareto_audit_result = AuditResult::whereHas('audit', function (Builder $query) use ($partner_id, $process_id, $month_first_data, $today) {
            $query->where('partner_id', 'like', $partner_id)
                ->where('process_id', $process_id)
                ->whereDate('audit_date', ">=", $month_first_data)
                ->whereDate('audit_date', "<=", $today)
            ;
        })->with('reason')->get();
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
        $final_data['pareto_data']['count'] = $all_uni_reaons_b;
        $final_data['pareto_data']['per'] = $all_uni_reaons_e;
        $final_data['pareto_data']['reasons'] = $all_uni_reaons_d;
        // pareto data

        if (Auth::user()->parent_client) {
            $partner_list = Partner::where('client_id', $client_id)
                ->whereIn('id', $all_cluster_partners)
                ->with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])->get();

        } else {
            if (Auth::user()->hasRole('partner-admin')) {
                $partner_list = Partner::where('id', Auth::user()->id)->with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])->get();
            } else {
                $partner_list = Partner::where('client_id', $client_id)->with(['partner_process', 'partner_process.process', 'partner_location', 'partner_location.location_detail'])->get();
            }
        }



        $partner_process_list = [];

        if (Auth::user()->parent_client) {

            foreach ($partner_list as $akey => $avalue) {

                foreach ($avalue->partner_process as $bkey => $bvalue) {

                    if (gettype(array_search($bvalue->process_id, $all_cluster_processes)) == "integer") {

                        $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;

                    }



                }

            }

        } else {

            foreach ($partner_list as $akey => $avalue) {

                foreach ($avalue->partner_process as $bkey => $bvalue) {

                    $partner_process_list[$bvalue->process_id]['name'] = $bvalue->process->name;

                }

            }

        }





        $loop = 1;

        foreach ($partner_process_list as $key => $value) {



            $process_audit_data['score_sum'] = Audit::where('client_id', $client_id)->where('process_id', $key)

                ->whereDate('audit_date', '>=', $month_first_data)

                ->whereDate('audit_date', '<=', $today)

                ->sum('overall_score');

            $process_audit_data['audit_count'] = Audit::where('client_id', $client_id)

                ->where('process_id', $key)

                ->whereDate('audit_date', '>=', $month_first_data)

                ->whereDate('audit_date', '<=', $today)

                ->count();

            $process_audit_data['score_with_fatal'] = Audit::where('client_id', $client_id)

                ->where('process_id', $key)

                ->whereDate('audit_date', '>=', $month_first_data)

                ->whereDate('audit_date', '<=', $today)

                ->sum('with_fatal_score_per');





            if ($process_audit_data['audit_count']) {

                $process_audit_data['score'] = round(($process_audit_data['score_sum'] / $process_audit_data['audit_count']));

                $process_audit_data['scored_with_fatal'] = round(($process_audit_data['score_with_fatal'] / $process_audit_data['audit_count']));

            } else {

                $process_audit_data['score'] = 0;

                $process_audit_data['scored_with_fatal'] = 0;

            }



            $partner_process_list[$key]['data'] = $process_audit_data;



            if ($loop == 1)

                $partner_process_list[$key]['class'] = true;
            else

                $partner_process_list[$key]['class'] = false;



            $loop++;



        }
        $final_data['pws'] = $partner_process_list;


        return $final_data;
    }



    public function Calculate_qrc_process_rebuttal_welcome_dashboard($client_id, $process_id, $partner_id, $month_first_data, $today, $process_audit_count, $qrc)
    {

        // Add Rebuttal on welcome dashboard on PWS 





        $process_audit_data = array();

        $temp_rebuttal_data_process = Audit::where('client_id', $client_id)->where('rebuttal_status', '>', 0)
            ->where('partner_id', $partner_id)
            ->where('process_id', $process_id)

            ->whereIn('qrc_2', $qrc)

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

    public function agent_feedback()
    {
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }
        $all_feedbacks = Audit::with('auditor', 'process', 'raw_data')->where('feedback_shared_status', 0)
            ->whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })
            ->whereDate('rebuttal_tat', '>', date('Y-m-d H:i:s'))
            ->orderBy('id', 'desc')->get();
        return view('porter_design.reports.feedback', compact('all_feedbacks'));
    }

    public function agent_overall_score()
    {
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }

        $final_data = array();
        $date = date('Y-m') . "%";
        $final_data['audit_count'] = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('audit_date', 'like', $date)->count();

        $final_data['new_feedback'] = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereDate('audit_date', '>', date('Y-m-d H:i:s'))->where('feedback_shared_status', 0)->count();

        $final_data['total_rebuttals'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->count();

        $final_data['rebuttal_accepted'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('status', 1)->count();

        $final_data['rebuttal_rejected'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('status', 2)->count();

        $final_data['rebuttal_wip'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('status', 0)->count();

        return $final_data;
    }

    public function get_agent_dashboard($date_range)
    {
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }
        dd($date_range);
        $final_data = array();
        $date = date('Y-m') . "%";
        $final_data['audit_count'] = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('audit_date', 'like', $date)->count();

        $final_data['new_feedback'] = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereDate('audit_date', '>', date('Y-m-d H:i:s'))->where('feedback_shared_status', 0)->count();

        $final_data['total_rebuttals'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->count();

        $final_data['rebuttal_accepted'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('status', 1)->count();

        $final_data['rebuttal_rejected'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('status', 2)->count();

        $final_data['rebuttal_wip'] = Rebuttal::whereHas('audit_data', function (Builder $query) use ($date) {
            $query->where('audit_date', 'like', $date);
        })->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->where('status', 0)->count();

        return $final_data;
    }

    public function agent_processes()
    {
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }

        $final_data = array();
        $date = date('Y-m') . "%";
        $process_ids = RawData::where('status', 1)->whereIn('agent_id', $agents)->groupby('process_id')->pluck('process_id')->toArray();
        $processes = Process::whereIn('id', $process_ids)->pluck('name', 'id');
        $html = '<option selected>Select Process</option>';
        foreach ($processes as $key => $value) {
            $html .= '<option value="' . $key . '">' . $value . '</option>';
        }

        return $html;
    }

    function agent_dashboard_lob($process_id, $start_date, $end_date)
    {
        if (Auth::user()->hasRole('agent-tl')) {
            $agent_id = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agent_id = [Auth::user()->id];
        }

        $process_id = $process_id;
        $start_date = $start_date;
        $end_date = $end_date;

        $process_data = array();

        $process_data['process'] = Process::find($process_id)->name;

        $process_data['audit_count'] = Audit::where('process_id', $process_id)
            ->whereDate('audit_date', '>=', $start_date)
            ->whereDate('audit_date', '<=', $end_date)
            ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                $query->whereIn('agent_id', $agent_id);
            })
            ->count();

        $audit_ids = Audit::where('process_id', $process_id)
            ->whereDate('audit_date', '>=', $start_date)
            ->whereDate('audit_date', '<=', $end_date)
            ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                $query->whereIn('agent_id', $agent_id);
            })
            ->pluck('id')->toArray();

        $process_data['with_fatal_score'] = Audit::where('process_id', $process_id)
            ->whereDate('audit_date', '>=', $start_date)
            ->whereDate('audit_date', '<=', $end_date)
            ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                $query->whereIn('agent_id', $agent_id);
            })
            ->avg('with_fatal_score_per');

        $process_data['without_fatal_score'] = Audit::where('process_id', $process_id)
            ->whereDate('audit_date', '>=', $start_date)
            ->whereDate('audit_date', '<=', $end_date)
            ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                $query->whereIn('agent_id', $agent_id);
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
                    $query->whereIn('agent_id', $agent_id);
                })
                ->count();

            $qrc_object['with_fatal_score_per'] = Audit::where('process_id', $process_id)
                ->where('qrc_2', $val)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                    $query->whereIn('agent_id', $agent_id);
                })
                ->avg('with_fatal_score_per');

            $qrc_object['without_fatal_score'] = Audit::where('process_id', $process_id)
                ->where('qrc_2', $val)
                ->whereDate('audit_date', '>=', $start_date)
                ->whereDate('audit_date', '<=', $end_date)
                ->whereHas('raw_data', function (Builder $query) use ($agent_id) {
                    $query->whereIn('agent_id', $agent_id);
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

        // // pareto data
        return response()->json(['status' => 0, 'message' => "success", 'final_data' => $process_data], 200);

    }

    

    public function score_summary($start_date, $end_date)
    {
        $final_data = array();
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }
        $audits = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereBetween('audit_date', [$start_date, $end_date]);

        $final_data['audit_count'] = $audits->count();

        $final_data['fatal_errors'] = $audits->where('is_critical', 1)->count();

        $final_data['quality_score'] = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereBetween('audit_date', [$start_date, $end_date])
            ->avg('with_fatal_score_per');
        
        
        $final_data['system_score'] = AuditParameterResult::whereHas('audit', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereBetween('audit_date', [$start_date, $end_date])->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                });
        })->where("parameter_id", 815)->groupBy('parameter_id')->avg('with_fatal_score_per');

        $final_data['process_score'] = AuditParameterResult::whereHas('audit', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereBetween('audit_date', [$start_date, $end_date])->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                });
        })->where("parameter_id", 816)->groupBy('parameter_id')->avg('with_fatal_score_per');

        $final_data['communication_score'] = AuditParameterResult::whereHas('audit', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereBetween('audit_date', [$start_date, $end_date])->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                });
        })->where("parameter_id", 817)->groupBy('parameter_id')->avg('with_fatal_score_per');


        /* $final_data['quality_score'] = Audit::whereHas('raw_data', function(Builder $query) use ($agents) {             
            $query->whereIn('agent_id',$agents);
        })->whereDate('audit_date',">=",$start_date)
        ->whereDate('audit_date',"<=",$end_date)
        ->avg('with_fatal_score_per'); */

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data], 200);
    }

    public function errorSummary($start_date, $end_date){
        $markdownProcess = $markdownSystem = $markdownCommunication = $fatalProcess = $fatalSystem = $fatalCommunication = '';
        $agents = [Auth::user()->id];
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        }
        $audits = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereBetween('audit_date', [$start_date, $end_date])->with(['audit_results'])->get();
        $totalAuditCount = count($audits);
        $markdown = $fatal = $code = [];
        $avg = 0;
        foreach ($audits as $key => $audit) {
            $errorcodes = explode(",", $audit->new_error_code);
            foreach ($errorcodes as $index => $errorcode) {
                $errorData = ErrorCode::where('error_codes', $errorcode)->first();
                if($errorData) {
                    if(!in_array($errorData->error_codes, $code)) {
                        $code[] = $errorData->error_codes;
                        $auditCount = Audit::whereHas('raw_data', function ($query) use ($agents) {
                            $query->whereIn('agent_id', $agents);
                         })->whereBetween('audit_date', [$start_date, $end_date])->whereRaw('find_in_set("'.$errorData->error_codes.'",new_error_code)')->count();
                        if($auditCount) {
                            $avg = round(($auditCount/$totalAuditCount)*100,2);
                        }
                        $tableRow = "<tr><td>{$errorData->error_reasons}</td><td>{$errorData->error_codes}</td><td>{$auditCount}</td><td>{$avg}%</td></tr>";
                        if ($errorData->markdown == "Markdown") {
                            if ($errorData->error_reason_types == "Process") {
                                $markdownProcess .= $tableRow;
                            } elseif ($errorData->error_reason_types == "System") {
                                $markdownSystem .= $tableRow;
                            } elseif ($errorData->error_reason_types == "Communication") {
                                $markdownCommunication .= $tableRow;
                            }
                        } elseif ($errorData->markdown == "Fatal") {
                            if ($errorData->error_reason_types == "Process") {
                                $fatalProcess .= $tableRow;
                            } elseif ($errorData->error_reason_types == "System") {
                                $fatalSystem .= $tableRow;
                            } elseif ($errorData->error_reason_types == "Communication") {
                                $fatalCommunication .= $tableRow;
                            }
                        }
                    }
                }
                
            }
        }    
        $final_data['fatalCommunication'] = $fatalCommunication;
        $final_data['fatalSystem'] = $fatalSystem;
        $final_data['fatalProcess'] = $fatalProcess;
        $final_data['markdownCommunication'] = $markdownCommunication;
        $final_data['markdownSystem'] = $markdownSystem;
        $final_data['markdownProcess'] = $markdownProcess;
        return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data], 200);    
    }

    public function fatal_summary($start_date, $end_date)
    {
        $final_data = array();
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }


        $final_data['system_score'] = AuditParameterResult::whereHas('audit', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)
                ->whereNotNull('error_code')
                ->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                });
        })->where("parameter_id", 815)->groupBy('parameter_id')->avg('with_fatal_score_per');

        $final_data['process_score'] = AuditParameterResult::whereHas('audit', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                });
        })->where("parameter_id", 816)->groupBy('parameter_id')->avg('with_fatal_score_per');

        $final_data['communication_score'] = AuditParameterResult::whereHas('audit', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                });
        })->where("parameter_id", 817)->groupBy('parameter_id')->avg('with_fatal_score_per');


        /* $final_data['quality_score'] = Audit::whereHas('raw_data', function(Builder $query) use ($agents) {             
            $query->whereIn('agent_id',$agents);
        })->whereDate('audit_date',">=",$start_date)
        ->whereDate('audit_date',"<=",$end_date)
        ->avg('with_fatal_score_per'); */

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data], 200);
    }

    public function demark_summary($start_date, $end_date)
    {
        $final_data = array();
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }


        $final_data['system_score'] = AuditParameterResult::whereHas('audit', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereDate('audit_date', ">=", $start_date)
                ->whereDate('audit_date', "<=", $end_date)
                ->whereNotNull('error_code')
                ->whereHas('raw_data', function (Builder $query) use ($agents) {
                    $query->whereIn('agent_id', $agents);
                });
        })->where("parameter_id", 815)->groupBy('parameter_id')->avg('with_fatal_score_per');


        //DB::select();

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data], 200);
    }

    public function rebuttal_score($start_date, $end_date)
    {
        $final_data = array();
        if (Auth::user()->hasRole('agent-tl')) {
            $agents = User::where('reporting_user_id', Auth::user()->id)->pluck('id')->toArray();
        } else {
            $agents = [Auth::user()->id];
        }

        $auditors = Audit::distinct('audited_by_id')->whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereBetween('audit_date', [$start_date, $end_date])
            ->pluck('audited_by_id')->toArray();
        $final_data = [];
        $auditor_string = implode(',', $auditors);

        $html = "";

        $overall_rebuttal = Rebuttal::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereHas('audit_data', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereBetween('audit_date', [$start_date, $end_date]);
        })->count();


        $overall_rebuttal_accepted = Rebuttal::whereHas('raw_data', function (Builder $query) use ($agents) {
            $query->whereIn('agent_id', $agents);
        })->whereHas('audit_data', function (Builder $query) use ($agents, $start_date, $end_date) {
            $query->whereBetween('audit_date', [$start_date, $end_date]);
        })->where('status', 1)->count();


        $final_data['overall_accept_per'] = round(divnum($overall_rebuttal_accepted, $overall_rebuttal) * 100, 2);


        foreach ($auditors as $qa) {
            $array = array();
            $html .= "<tr>";
            $auditor_name = User::find($qa)->email;

            $audit_count = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })->whereBetween('audit_date', [$start_date, $end_date])
                ->where('audited_by_id', $qa)->count();

            $rebuttal_count = Rebuttal::whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })->whereHas('audit_data', function (Builder $query) use ($agents, $qa, $start_date, $end_date) {
                $query->where('audited_by_id', $qa)
                ->whereBetween('audit_date', [$start_date, $end_date]);
            })->count();

            $rebuttal_valid = Rebuttal::whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })->whereHas('audit_data', function (Builder $query) use ($agents, $qa, $start_date, $end_date) {
                $query->where('audited_by_id', $qa)
                ->whereBetween('audit_date', [$start_date, $end_date]);
            })->where('valid_invalid', 1)->count();

            $rebuttal_auditor_error = Rebuttal::whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })->whereHas('audit_data', function (Builder $query) use ($agents, $qa, $start_date, $end_date) {
                $query->where('audited_by_id', $qa)
                ->whereBetween('audit_date', [$start_date, $end_date]);
            })->where('status', 1)->count();

            $audit_no_error_count = Audit::whereHas('raw_data', function (Builder $query) use ($agents) {
                $query->whereIn('agent_id', $agents);
            })->whereBetween('audit_date', [$start_date, $end_date])
                ->where('audited_by_id', $qa)
                ->where('rebuttal_status', 0)
                ->count();

            $html .= "<td>" . $auditor_name . "</td>";
            $html .= "<td>" . $audit_count . "</td>";
            $html .= "<td>" . $rebuttal_count . "</td>";
            $html .= "<td>" . $rebuttal_valid . "</td>";
            $html .= "<td>" . $rebuttal_auditor_error . "</td>";
            $html .= "<td>" . $audit_no_error_count . "</td>";
            $html .= "<td>WIP</td>";

            $accept_per = divnum($rebuttal_auditor_error, $rebuttal_count) * 100;
            $html .= "<td>" . round($accept_per, 2) . "%</td>";
            /* $array['rebuttal_valid_rebuttal'] = Rebuttal::whereHas('raw_data', function(Builder $query) use ($agents) {             
                $query->whereIn('agent_id',$agents);
            })->whereHas('audit_data', function(Builder $query) use ($agents) {             
                $query->where('audited_by_id',$qa);
            })->count(); */
            $html .= "</tr>";
            //$final_data[] = $array;
        }

        $final_data['html'] = $html;




        return response()->json(['status' => 200, 'message' => "Success", 'data' => $final_data], 200);
    }

    public function feedback_accept($id, $status)
    {

        $audit = Audit::find(Crypt::decrypt($id));
        $audit->feedback_shared_status = $status;
        $audit->save();

        return redirect()->back()->with('success', 'Feedback accepted successfully');

    }

}