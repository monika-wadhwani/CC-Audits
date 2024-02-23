<?php

namespace App\Console\Commands;

ini_set('memory_limit', '-1');
ini_set('max_execution_time', '10000');
use App\Audit;
use App\AuditParameterResult;
use App\AuditResult;
use App\Rebuttal;
use App\QmSheet;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\RawData;
use App\User;
use App\Mail\FeedbackToAgent;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\AgentCallFeedbackLog;
use Carbon\Carbon;
use Crypt;
use App\AgentFeedbackEmail;
use Illuminate\Support\Facades\Storage;
use Notification;
use App\Notifications\RebuttalReply;
class AutoAcceptedRebuttal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoaccepted:rebuttal';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $current_date = date("Y-m-d H:i:s");
      
        $all_todays_audits = Rebuttal::where('status',0)
        ->whereHas('audit_data', function ($query) use($current_date) {

            $query->whereDate('porter_tl_reply_rebuttal_time_tat','<',$current_date)
                ->whereNotNull('porter_tl_reply_rebuttal_time_tat');

        })
        ->pluck('audit_id')->toArray();
        $array = collect($all_todays_audits)->unique();
        

        foreach($array as $value){
            $all_rebuttals = Rebuttal::where('audit_id',$value)->get();
            $audit_data = Audit::find($value);
            foreach($all_rebuttals as $rebuttal){
              
                $audit_result = AuditResult::where('audit_id',$rebuttal->audit_id)
                                     ->where('parameter_id',$rebuttal->parameter_id)
                                     ->where('sub_parameter_id',$rebuttal->sub_parameter_id)
                                     ->first();
                $new_data['sub_parameter_id'] = $rebuttal->sub_parameter_id;
                $new_data['previous_observation'] = $audit_result->selected_option;
                $is_critical = 0;
                /* if($audit_result->is_critical == 1){
                    $is_critical = 0;
                } else {
                    $is_critical = 0;  
                } */
                $audit_result->is_critical = $is_critical;
                if($audit_result->is_non_scoring == 0){
                    $option = 1;
                    $scored = $audit_result->after_audit_weight;
                } else {
                    if($audit_result->selected_option == 3 || $audit_result->selected_option == 4 || $audit_result->selected_option == 5){
                        $option = 3;
                        $scored = $audit_result->after_audit_weight;
                    } else if($audit_result->selected_option == 6 || $audit_result->selected_option == 7 || $audit_result->selected_option == 8 
                    || $audit_result->selected_option == 9){
                        $option = 6;
                        $scored = $audit_result->after_audit_weight;
                    } else if($audit_result->selected_option == 10 || $audit_result->selected_option == 11 || $audit_result->selected_option == 12 
                    || $audit_result->selected_option == 13 || $audit_result->selected_option == 14 ){
                        $option = 10;
                        $scored = $audit_result->after_audit_weight;
                    }
                }
                $audit_result->selected_option = $option;
                $audit_result->score = $scored;
                $audit_result->after_audit_weight = $audit_result->after_audit_weight;
                
                $audit_result->reason_type_id = 0;
                $audit_result->reason_id = 0;
            
                $audit_result->save();

                // update Parameter score starts

                if($audit_result->is_non_scoring)
                {
                    $all_sub_parameter = AuditResult::where('audit_id',$rebuttal->audit_id)
                    ->where('parameter_id',$rebuttal->parameter_id)
                    ->get();
                    $parameter_revised_data=[];
                    $parameter_revised_data['score_sum']=0;
                    $parameter_revised_data['scorable_sum']=0;
                    $parameter_revised_data['is_critical']=0;
                    foreach ($all_sub_parameter as $key => $value) {
                        $parameter_revised_data['score_sum'] += $value->score;
                        $parameter_revised_data['scorable_sum'] += $value->after_audit_weight;

                        if($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                    }

                    if($parameter_revised_data['scorable_sum'] == 0){
                        $parameter_revised_data['final_score_per'] = 0.00;
                    }
                    else {
                        $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum']/$parameter_revised_data['scorable_sum'])*100,2);
                    }


                    $audit_parameter = AuditParameterResult::where('audit_id',$request->audit_id)
                                ->where('parameter_id',$request->parameter_id)
                                ->first();
                    $audit_parameter->is_critical = $parameter_revised_data['is_critical'];
                    $audit_parameter->temp_weight = $parameter_revised_data['scorable_sum'];

                    $audit_parameter->without_fatal_score = $parameter_revised_data['score_sum'];
                    $audit_parameter->without_fatal_score_pre = $parameter_revised_data['final_score_per'];

                    if($parameter_revised_data['is_critical'])
                    {
                        $audit_parameter->with_fatal_score = 0;
                        $audit_parameter->with_fatal_score_per = 0;
                    }else
                    {
                        $audit_parameter->with_fatal_score = $parameter_revised_data['score_sum'];
                        $audit_parameter->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                    }

                    $audit_parameter->save();
                    // update Parameter score ends

                    //update entire audit reised score starts
                    $all_parameter = AuditParameterResult::where('audit_id',$rebuttal->audit_id)->get();


                    $parameter_revised_data['score_sum']=0;
                    $parameter_revised_data['scorable_sum']=0;
                    $parameter_revised_data['is_critical']=0;
                    foreach ($all_parameter as $key => $value) {
                        $parameter_revised_data['score_sum'] += $value->without_fatal_score;
                        $parameter_revised_data['scorable_sum'] += $value->temp_weight;

                        if($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                    }

                    $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum']/$parameter_revised_data['scorable_sum'])*100,2);

                    $audit_data = Audit::find($rebuttal->audit_id);

                    $new_data['score_with_fatal'] = $audit_data->with_fatal_score_per;
                    $new_data['score_without_fatal'] = $audit_data->overall_score;

                    $audit_data->is_critical = $parameter_revised_data['is_critical'];
                    $audit_data->overall_score = $parameter_revised_data['final_score_per'];

                    if($parameter_revised_data['is_critical'])
                    {
                    $audit_data->with_fatal_score_per = 0;
                    }else
                    {
                    $audit_data->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                    }
                    $audit_data->save();
                    //update entire audit reised score ends
                }else{

                    $all_sub_parameter = AuditResult::where('audit_id',$rebuttal->audit_id)
                                                ->where('parameter_id',$rebuttal->parameter_id)
                                                ->get();
                    $parameter_revised_data=[];
                    $parameter_revised_data['score_sum']=0;
                    $parameter_revised_data['scorable_sum']=0;
                    $parameter_revised_data['is_critical']=0;
                    foreach ($all_sub_parameter as $key => $value) {
                        $parameter_revised_data['score_sum'] += $value->score;
                        $parameter_revised_data['scorable_sum'] += $value->after_audit_weight;

                        if($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                    }

                    if($parameter_revised_data['scorable_sum'] == 0){
                        $parameter_revised_data['final_score_per'] = 0.00;
                    }
                    else {
                        $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum']/$parameter_revised_data['scorable_sum'])*100,2);
                    }
            
            
                    $audit_parameter = AuditParameterResult::where('audit_id',$rebuttal->audit_id)
                                                ->where('parameter_id',$rebuttal->parameter_id)
                                                ->first();
                    $audit_parameter->is_critical = $parameter_revised_data['is_critical'];
                    $audit_parameter->temp_weight = $parameter_revised_data['scorable_sum'];

                    $audit_parameter->without_fatal_score = $parameter_revised_data['score_sum'];
                    $audit_parameter->without_fatal_score_pre = $parameter_revised_data['final_score_per'];

                    if($parameter_revised_data['is_critical'])
                    {
                            $audit_parameter->with_fatal_score = 0;
                            $audit_parameter->with_fatal_score_per = 0;
                    }else
                    {
                            $audit_parameter->with_fatal_score = $parameter_revised_data['score_sum'];
                            $audit_parameter->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                    }

                    $audit_parameter->save();
                    // update Parameter score ends

                    //update entire audit reised score starts
                    $all_parameter = AuditParameterResult::where('audit_id',$rebuttal->audit_id)->get();


                    $parameter_revised_data['score_sum']=0;
                    $parameter_revised_data['scorable_sum']=0;
                    $parameter_revised_data['is_critical']=0;
                    foreach ($all_parameter as $key => $value) {
                        $parameter_revised_data['score_sum'] += $value->without_fatal_score;
                        $parameter_revised_data['scorable_sum'] += $value->temp_weight;

                        if($value->is_critical)
                        $parameter_revised_data['is_critical'] = 1;
                    }

                    $parameter_revised_data['final_score_per'] = round(($parameter_revised_data['score_sum']/$parameter_revised_data['scorable_sum'])*100,2);

                    $audit_data = Audit::find($rebuttal->audit_id);

                    $new_data['score_with_fatal'] = $audit_data->with_fatal_score_per;
                    $new_data['score_without_fatal'] = $audit_data->overall_score;

                    $audit_data->is_critical = $parameter_revised_data['is_critical'];
                    $audit_data->overall_score = $parameter_revised_data['final_score_per'];

                    if($parameter_revised_data['is_critical'])
                    {
                        $audit_data->with_fatal_score_per = 0;
                    }else
                    {
                        $audit_data->with_fatal_score_per = $parameter_revised_data['final_score_per'];
                    }
                    $audit_data->save();
                    //update entire audit reised score ends
                }

                    //update qc/rebuttal status
                
                $rebuttal_data = Rebuttal::find($rebuttal->id);
                $rebuttal_data->revised_score_with_fatal = $new_data['score_with_fatal'];
                $rebuttal_data->revised_score_without_fatal = $new_data['score_without_fatal'];
                $rebuttal_data->status=1;
                $rebuttal_data->reply_remark = "Auto accepted by system";
                $rebuttal_data->save();

                Notification::send(User::find($rebuttal->raised_by_user_id), new RebuttalReply(['upper_text'=>"Rebuttal Accepted.",'audit_id'=>Crypt::encrypt($rebuttal->audit_id)]));
                
            }

            
            
        }

    }
}
