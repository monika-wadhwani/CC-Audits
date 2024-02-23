<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
use App\Audit;
use App\Client;
use App\Mail\UserCreated;
use App\Partner;
use App\PartnerLocation;
use App\PartnersProcess;
use App\PartnersProcessSpoc;
use App\Process;
use App\QmSheet;
use App\QmSheetParameter;
use App\QmSheetSubParameter;
use App\RawData;
use App\Rebuttal;
use App\Region;
use App\AuditResult;
use App\Role;
use App\User;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Mail;
use Notification;

use Illuminate\Support\Facades\Storage;
use Validator;
use DB;



class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($client_id)
    {
        $client_data = Client::find(Crypt::decrypt($client_id));
        $all_regions = Region::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $all_process = Process::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $all_partner_admin = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'partner-admin');
        })->pluck('name','id');
        return view('partners.create',compact('client_data','all_regions','all_process','all_partner_admin'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'client_id' => 'required',
            'partner_name' => 'required',
            'admin_user_id'=>'required',
            'partner_process_id'=>'required'
        ]);

        if($validator->fails())
        {
            return redirect('client/'.$request->client_id.'/create/partner')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
            // //create user
            // $temp_roles = '';
            // $new_rec = new User;
            // $new_rec->company_id = Auth::user()->company_id;
            // $new_rec->name = $request->user_name;
            // $new_rec->email = $request->email;
            // $new_rec->mobile = $request->mobile;
            // $new_rec->password = bcrypt($request->password);
            // $new_rec->status=1;
            // $new_rec->save();
            // // attach role
            //     $role  = Role::where('name','partner-admin')->first();
            //     $temp_roles .= $role->display_name;
            //     $new_rec->attachRole($role);
            //create user


            /* if(true) */
            $check_multiple = Partner::where('contact_email',$request->contact_email)->get();

            if(sizeof($check_multiple) == 0)
            {
                $new_rc = new Partner;
                $new_rc->company_id = Auth::user()->company_id;
                $new_rc->client_id = Crypt::decrypt($request->client_id);
                $new_rc->name = $request->partner_name;
                $new_rc->details = $request->details;
                $new_rc->admin_user_id = $request->admin_user_id;
                $new_rc->contact_email = $request->contact_email;

                if($request->logo) {
                $request->logo->store("company/_".Auth::user()->company_id.'/client');
                $new_rc->logo = $request->logo->hashName();
                }   

                $new_rc->save();

                if($new_rc->id)
                {
                    foreach ($request->partner_process_id as $key => $value) {
                        $new_rec_cq = new PartnersProcess;
                        $new_rec_cq->partner_id = $new_rc->id;
                        $new_rec_cq->process_id = $value;
                        $new_rec_cq->save();
                    }

                    foreach ($request->locations as $key => $value) {
                        $new_rec_cq = new PartnerLocation;
                        $new_rec_cq->partner_id = $new_rc->id;
                        $new_rec_cq->location_id = $value['location_id'];
                        $new_rec_cq->save();
                    }


                    // $mail_data = ['name'=>$request->user_name,'roles'=>"Partner",'url'=>url('/login'),'company_name'=>Auth::user()->company->company_name,'email'=>$request->email,'password'=>$request->password];
                    // Mail::to($new_rec)->send(new UserCreated($mail_data));
                }
            }
            else {
                return redirect('client/'.$request->client_id.'/partner')->with('warning', 'Email id already exist with different client, Please select different email id');    
            }
        }
        return redirect('client/'.$request->client_id.'/partner')->with('success', 'Partner created successfully');    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Partner::with(['partner_process','partner_location'])->find(Crypt::decrypt($id));
        $client_data = Client::find($data->client_id);
        $all_regions = Region::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $all_process = Process::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $all_partner_admin = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'partner-admin');
        })->pluck('name','id');

        //dd($data->partner_process);
        if(count($data->partner_process))
        $selected_partner_process = $data->partner_process->pluck('process_id');
        else
        $selected_partner_process = [];

        return view('partners.edit',compact('data','client_data','all_regions','all_process','selected_partner_process','all_partner_admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //dd($request->locations);
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'client_id' => 'required',
            'partner_name' => 'required',
            'admin_user_id' => 'required',
            'partner_process_id'=>'required'
        ]);

        if($validator->fails())
        {
            return redirect('partner/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }else
        {
                $new_rc = Partner::find(Crypt::decrypt($id));
                $new_rc->name = $request->partner_name;
                $new_rc->details = $request->details;
                $new_rc->admin_user_id = $request->admin_user_id;
                $new_rc->contact_email = $request->contact_email;

                if($request->logo) {
                Storage::delete("company/_".Auth::user()->company_id."/client/".$new_rc->logo);
                $request->logo->store("company/_".Auth::user()->company_id.'/client');
                $new_rc->logo = $request->logo->hashName();
                }

                $new_rc->save();

                PartnersProcess::where('partner_id',$new_rc->id)->delete();
                foreach ($request->partner_process_id as $key => $value) {
                        $new_rec_cq = new PartnersProcess;
                        $new_rec_cq->partner_id = $new_rc->id;
                        $new_rec_cq->process_id = $value;
                        $new_rec_cq->save();
                    }

                    PartnerLocation::where('partner_id',$new_rc->id)->delete();
                    foreach ($request->locations as $key => $value) {
                        if($value['location_id'])
                        {                       
                            $new_rec_cq = new PartnerLocation;
                            $new_rec_cq->partner_id = $new_rc->id;
                            $new_rec_cq->location_id = $value['location_id'];
                            $new_rec_cq->save();
                        }
                    }
        }
        return redirect('client/'.$request->client_id.'/partner')->with('success', 'Partner updated successfully');    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd(Crypt::decrypt($id));
        PartnersProcess::where('partner_id',Crypt::decrypt($id))->delete();
        PartnersProcessSpoc::where('partner_id',Crypt::decrypt($id))->delete();
        $data = Partner::find(Crypt::decrypt($id));
        $client_id = $data->client_id;
        Storage::delete("company/_".Auth::user()->company_id."/client/".$data->logo);
        $data->delete();
        return redirect('client/'.Crypt::encrypt($client_id).'/partner')->with('success', 'Partner deleted successfully');    
    }
    public function add_spocs($partner_id)
    {
        $partner_data = Partner::with(['partner_process','partner_process.process'])->find(Crypt::decrypt($partner_id));

        $selected_processes = [];
        foreach ($partner_data->partner_process as $key => $value) {
            $selected_processes[$value->id] = $value->process->name;
        }
        $all_partner_spocs = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'partner-training-head')->orWhere('name', '=', 'partner-operation-head')->orWhere('name', '=', 'partner-quality-head');
        })->pluck('name','id');

        $all_attached_spocs = PartnersProcessSpoc::with(['user','process'])->where('partner_id',$partner_data->id)->get();

        $all_partner_locations = [];
        $all_partner_locations_temp = PartnerLocation::where('partner_id',$partner_data->id)->with('location_detail')->get();
        foreach ($all_partner_locations_temp as $key => $value) {
            $all_partner_locations[$value->location_id] = $value->location_detail->name;
        }

        return view('partners.manage_spocs',compact('partner_data','selected_processes','all_partner_spocs','all_attached_spocs','all_partner_locations'));
    }
    public function store_spocs(Request $request)
    {
        $pp_data = PartnersProcess::find($request->pp_id);

        $new = new PartnersProcessSpoc;
        $new->pp_id = $request->pp_id;
        $new->partner_id = Crypt::decrypt($request->partner_id);
        $new->process_id = $pp_data->process_id;
        $new->user_id = $request->user_id;
        $new->partner_location_id = $request->partner_location_id;
        $new->save();

        return redirect('partner/'.$request->partner_id.'/add/spocs')->with('success', 'Partner spocs updated successfully');
    }
    public function partner_process_spoc_delete($id)
    {
        $data = PartnersProcessSpoc::find(Crypt::decrypt($id));
        $partner_id = $data->partner_id;
        $data->delete();
        return redirect('partner/'.Crypt::encrypt($partner_id).'/add/spocs')->with('success', 'Partner spocs deleted successfully');
    }
    public function audit_completed(Request $request)
    {
        $start_date = date_to_db($request->start_date);
        $end_date = date_to_db($request->end_date);
        
        if(Auth::user()->hasRole('agent-tl') || Auth::user()->hasRole('agent')) {
            if(!isset($request->start_date)){
                
                $start_date = date_to_db("2023-01-01 00:00:00");
                $end_date = date_to_db(date('Y-m-d H:i:s'));
            }
        }
        $partner_id=null;
        $process_id=null;
        $partner_location_id=null;
        if(Auth::user()->parent_client) {

            $client_id = Auth::user()->parent_client;

            $all_cluster_processes = get_helper_cluster_processes(Auth::user()->id);

            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);

            $all_cluster_locations = get_helper_cluster_locations(Auth::user()->id);

        // $all_cluster_lobs = get_helper_cluster_lobs(Auth::user()->id);

        }    
        if(Auth::user()->hasRole('partner-admin'))
        {
            $all_cluster_partners = get_helper_cluster_partners(Auth::user()->id);
            /* print_r($all_cluster_partners);
            die; */
            if(sizeof($all_cluster_partners)>0){
               
                $partner_id=$all_cluster_partners[0];
               
            }
             else {
                if(isset(Auth::user()->partner_admin_detail->id)){
                    $partner_id=Auth::user()->partner_admin_detail->id;
                } else {
                    return redirect('error')->with('warning','Please contact to admin partner detail not mapped.');
                }
                
            }           
            $process_id='%';
            $partner_location_id='%';
        }else
        {

            $spoc_data = Auth::user()->spoc_detail;
            if(gettype($spoc_data) == "NULL") {
                $partner_id='';
                $process_id="%";
                $partner_location_id='';
            } else {
                $partner_id=$spoc_data->partner_id;
                $process_id="%";
                $partner_location_id=$spoc_data->partner_location_id;
            }
           
        }

        $current_date_time = date('Y-m-d H:i:s');
       

       /*  $start_date = "2020-05-01";
        $end_date = "2020-06-04"; */
       /*  echo $partner_id;
        dd();  */ 
    
       if(Auth::user()->hasRole('agent-tl')){
             /* echo "h2";
             die; */
            $all_agents = User::where('reporting_user_id',Auth::user()->id)
            ->pluck('id')->toArray();

                $data = RawData::where('status',1)
                ->whereIn('agent_id',$all_agents)
                ->whereHas('audit', function ($query) use ($start_date,$end_date){
                $query->whereDate('audit_date','>=',$start_date)
                ->whereDate('audit_date','<=',$end_date)
                ->whereDate('agent_tl_approval_tat','>',date('Y-m-d H:i:s'))
                ->where('rebuttal_status','>',0);
                })->with('audit')->orderby('updated_at','desc')
                ->get();
           
        }
        elseif(Auth::user()->hasRole('agent') ){
            // echo "h2";
            // die;
                
                $data = RawData::where('status',1)
                ->where('agent_id',Auth::user()->id)
                ->whereHas('audit', function ($query) use ($start_date,$end_date){
                    $query->whereDate('audit_date','>=',$start_date)
                    ->whereDate('audit_date','<=',$end_date);
                    // ->whereDate('rebuttal_tat','>',date('Y-m-d H:i:s'));
                })->with('audit')->orderby('updated_at','desc')
                ->get();
        }
        elseif(Auth::user()->parent_client) {
                // echo "h1";
                // die;
                $data = RawData::where('status',1)
                ->whereIn('partner_id',$all_cluster_partners)
                ->whereIn('partner_location_id', $all_cluster_locations)
                ->whereIn('process_id', $all_cluster_processes)
                ->whereHas('audit', function ($query) use ($start_date,$end_date){
                    $query->whereDate('audit_date','>=',$start_date)->whereDate('audit_date','<=',$end_date);
                })->whereHas('audit', function ($query) use  ($current_date_time) {
                    $query
                          ->where('rebuttal_tat','>=',$current_date_time)
                          ->where('qc_tat','<',$current_date_time);
                         
                          
                })->with('audit')->orderby('updated_at','desc')
                ->get();
                
            }
            
            else{
                // echo "h3";
                // die;
            $data = RawData::where('status',1)
                ->where('partner_id',$partner_id)
                ->where('partner_location_id', 'like' ,$partner_location_id)
                ->whereHas('audit', function ($query) use ($start_date,$end_date){
                    $query->whereDate('audit_date','>=',$start_date)->whereDate('audit_date','<=',$end_date);
                })->whereHas('audit', function ($query) use  ($current_date_time) {
                    $query
                          ->where('rebuttal_tat','>=',$current_date_time)
                          ->where('qc_tat','<',$current_date_time);
                        
                         
                          
                })->with('audit')->orderby('updated_at','desc')
                ->get();
            }
        
         
            

        /* print_r($data);
        dd();   */      
        return view('partners.audit_completed',compact('data'));
    }
    public function single_audit_detail($audit_id){
        $audit_data = Audit::with(['audit_parameter_result','audit_results','audit_results.reason_type','audit_results.reason'])->find(Crypt::decrypt($audit_id));
        $raw_data = RawData::find($audit_data->raw_data_id); 
        $qm_sheet_para_data = QmSheetParameter::with(['qm_sheet_sub_parameter'])->where('qm_sheet_id',$audit_data->qm_sheet_id)->get();
        $audit_sp_results = $audit_data->audit_results;
        $audit_p_results = $audit_data->audit_parameter_result;
        $final_data = [];
        $all_sub_parameters = [];
        $all_questions = AuditResult::where('audit_id',Crypt::decrypt($audit_id))->pluck('sub_parameter_id')->toArray();
        $qc_to_qa_artifact = AuditResult::where('audit_id',Crypt::decrypt($audit_id))->get();
        foreach ($qm_sheet_para_data as $key => $value) {
            //all subparameters
            foreach ($value->qm_sheet_sub_parameter as $sskey => $ssvalue) {
                if(array_search($ssvalue->id,$all_questions)){
                $all_sub_parameters[] = ["key"=>$value->id."_".$ssvalue->id,"value"=>$ssvalue->sub_parameter];
                }
            }
            //all subparameters
            $final_data[$value->id]['name'] = $value->parameter;
            $final_data[$value->id]['qm_sheet_id'] = $value->qm_sheet_id;
            $final_data[$value->id]['score'] = $audit_p_results->where('parameter_id',$value->id)->first()->with_fatal_score;
            $pass_count = 0;
            $fail_count = 0;
            $critical_count = 0;
                if($value->is_non_scoring)
                { 
                    foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                            $t_1 = $audit_sp_results->where('parameter_id',$value->id)->where('sub_parameter_id',$svalue->id)->toArray();
                            $temp_result = $t_1[array_key_first($t_1)];
                            $final_data[$value->id]['sp'][] = ['id'=>$svalue->id,'qc_to_qa_artifact'=>$value->qc_to_qa_artifact,'name'=>$svalue->sub_parameter,'detail'=>$svalue->details,'selected_option'=>return_non_scoring_observation($temp_result['selected_option']),'scored'=>$temp_result['score'],'reason_type'=>"-",'reason'=>'-','remark'=>$temp_result['remark'],'screenshot'=>$temp_result['screenshot']];
                        }
                }else
                {
                    foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                        if((array_search($svalue->id,$all_questions)!=null && $value->qm_sheet_id == 137 ) || $value->qm_sheet_id != 137){
                            $para_detail = QmSheetSubParameter::find($value->id);
                            $t_1 = $audit_sp_results->where('parameter_id',$value->id)->where('sub_parameter_id',$svalue->id)->toArray();
                            $temp_result = $t_1[array_key_first($t_1)];
                            if(isset($temp_result['reason_type']['name'])){
                                    $temp_result['reason_type']['name'] = $temp_result['reason_type']['name'];
                                    $temp_result['reason']['name'] = $temp_result['reason']['name'];
                                    $temp_result['remark']=$temp_result['remark'];
                                } else {
                                    $temp_result['reason']['name'] = "-";
                                    $temp_result['reason_type']['name'] = "-";
                                    $temp_result['remark'] = "-";
                                }
                        $final_data[$value->id]['sp'][] = ['id'=>$svalue->id,'qc_to_qa_artifact'=>$value->qc_to_qa_artifact,'name'=>$svalue->sub_parameter,'detail'=>$svalue->details,'para_detail'=>$para_detail,'selected_option'=>auditDetails($temp_result['selected_option']),'scored'=>$temp_result['score'],'is_critical'=>$temp_result['is_critical'],'reason_type'=>$temp_result['reason_type']['name'],'reason'=>$temp_result['reason']['name'],'remark'=>$temp_result['remark'],'screenshot'=>$temp_result['screenshot']];
                        $pass_count += $audit_sp_results->where('parameter_id',$value->id)->where('is_critical',0)->where('score',">",0)->where('sub_parameter_id',$svalue->id)->count();
                        $fail_count += $audit_sp_results->where('parameter_id',$value->id)->where('is_critical',0)->where('score',0)->where('sub_parameter_id',$svalue->id)->count();
                        $critical_count += $audit_sp_results->where('parameter_id',$value->id)->where('is_critical',1)->where('sub_parameter_id',$svalue->id)->count();
                        }
                    }
                }
                $final_data[$value->id]['pass_count'] = $pass_count;
                $final_data[$value->id]['fail_count'] = $fail_count;
                $final_data[$value->id]['critical_count'] = $critical_count;

        }
        $rebuttal_data = Rebuttal::where('audit_id',$audit_data->id)->with(['parameter','sub_parameter'])->get();
        return view('porter_design.partners.single_audit_detail',compact(['raw_data','audit_data','final_data','all_sub_parameters','rebuttal_data']));
    }
    public function all_agent_audit_detail($audit_id) {
        //echo(Crypt::decrypt($audit_id)); die;
        $audit_data = Audit::with(['audit_parameter_result','audit_results','audit_results.reason_type','audit_results.reason','partner','client','qa_qtl_detail'])->find(Crypt::decrypt($audit_id));
        // if($audit_data->rebuttal_status!=2)
            // $audit_data->rebuttal_status = 1;

        
        // $audit_data->save();

        $raw_data = RawData::with('tl_details')->find($audit_data->raw_data_id); 

        $qm_sheet_para_data = QmSheetParameter::with(['qm_sheet_sub_parameter'])->where('qm_sheet_id',$audit_data->qm_sheet_id)->get();
        
        $audit_sp_results = $audit_data->audit_results;
        $audit_p_results = $audit_data->audit_parameter_result;
        // return $audit_p_results;
        $final_data = [];
        $all_sub_parameters = [];
        $all_questions = AuditResult::where('audit_id',Crypt::decrypt($audit_id))->pluck('sub_parameter_id')->toArray();
        foreach ($qm_sheet_para_data as $key => $value) {

            //all subparameters
            foreach ($value->qm_sheet_sub_parameter as $sskey => $ssvalue) {
                
              
                if(array_search($ssvalue->id,$all_questions)){
                $all_sub_parameters[] = ["key"=>$value->id."_".$ssvalue->id,"value"=>$ssvalue->sub_parameter];
                }
            }
            //all subparameters


            $final_data[$value->id]['name'] = $value->parameter;
            $final_data[$value->id]['qm_sheet_id'] = $value->qm_sheet_id;
            $final_data[$value->id]['score'] = $audit_p_results->where('parameter_id',$value->id)->first()->with_fatal_score_per;
          

            $pass_count = 0;
            $fail_count = 0;
            $critical_count = 0;

                if($value->is_non_scoring)
                {   
                    
                    foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                            $t_1 = $audit_sp_results->where('parameter_id',$value->id)->where('sub_parameter_id',$svalue->id)->toArray();
                            $temp_result = $t_1[array_key_first($t_1)];
                            $final_data[$value->id]['sp'][] = ['id'=>$svalue->id,'name'=>$svalue->sub_parameter,'detail'=>$svalue->details,'selected_option'=>return_non_scoring_observation($temp_result['selected_option']),'scored'=>$temp_result['score'],'reason_type'=>"-",'reason'=>'-','remark'=>$temp_result['remark'],'screenshot'=>$temp_result['screenshot']];
                    
                    
                        }
                }else
                {
                    foreach ($value->qm_sheet_sub_parameter as $skey => $svalue) {
                        if((array_search($svalue->id,$all_questions)!=null && $value->qm_sheet_id == 137 ) || $value->qm_sheet_id != 137){

                            $para_detail = QmSheetSubParameter::find($value->id);
                            
                            $t_1 = $audit_sp_results->where('parameter_id',$value->id)->where('sub_parameter_id',$svalue->id)->toArray();
                        //    if(!isset($t_1[0]['id'])){
                        //     print_r(($t_1)); die;
                        //    }
                        //print_r(($t_1)); die;
                        // if(!isset($t_1[array_key_first($t_1)])){
                        //     print_r(($t_1)); die;
                        // }
                                $temp_result = $t_1[array_key_first($t_1)];
                               
                              
                                if(isset($temp_result['reason_type']['name'])){

                                } else {
                                    $temp_result['reason']['name'] = "-";
                                    $temp_result['reason_type']['name'] = "-";
                               
                                }
                                // if(!isset($temp_result['selected_option'])){
                                //     print_r($temp_result[0]['id']); die;
                                // }
                        $final_data[$value->id]['sp'][] = ['id'=>$svalue->id,'name'=>$svalue->sub_parameter,'detail'=>$svalue->details,'para_detail'=>$para_detail,'selected_option'=>return_general_observation($temp_result['selected_option']),'scored'=>$temp_result['score'],'is_critical'=>$temp_result['is_critical'],'reason_type'=>$temp_result['reason_type']['name'],'reason'=>$temp_result['reason']['name'],'remark'=>$temp_result['remark'],'screenshot'=>$temp_result['screenshot']];
                        
                        $pass_count += $audit_sp_results->where('parameter_id',$value->id)->where('is_critical',0)->where('score',">",0)->where('sub_parameter_id',$svalue->id)->count();
                        $fail_count += $audit_sp_results->where('parameter_id',$value->id)->where('is_critical',0)->where('score',0)->where('sub_parameter_id',$svalue->id)->count();
                        $critical_count += $audit_sp_results->where('parameter_id',$value->id)->where('is_critical',1)->where('sub_parameter_id',$svalue->id)->count();
                        
                        }
                    }

                    // dd($pass_count); 
                    
                }
                $final_data[$value->id]['pass_count'] = $pass_count;
                $final_data[$value->id]['fail_count'] = $fail_count;
                $final_data[$value->id]['critical_count'] = $critical_count;

        }
     
        //   return response()->json(['status'=>200,'message'=>".",'data'=>$final_data], 200); die;
       
        $rebuttal_data = Rebuttal::where('audit_id',$audit_data->id)->with(['parameter','sub_parameter'])->get();
        // return view('partners.single_audit_detail',compact(['raw_data','audit_data','final_data','all_sub_parameters','rebuttal_data']));
        return view('porter_design.partners.all_agent_audit_detail',compact(['raw_data','audit_data','final_data','all_sub_parameters','rebuttal_data']));

    }
    public function store_feedback(Request $request)
    {
        $audit_data = Audit::find(Crypt::decrypt($request->audit_id));
        
        // $audit_data->feedback_status = $request->feedback_status;
        // $audit_data->feedback_date = $request->feedback_date;
        $audit_data->feedback_comment = $request->feedback_remarks;
        $audit_data->feedback_shared_status = $request->feedback;
        $audit_data->feedback_shared_date =  date('Y-m-d h:i:s'); 
        $audit_data->save();

        return redirect('partner/single_audit_detail/'.$request->audit_id)->with('success','Feedback saved successfully.');

    }
    public function error_page(){
        return view('error.error_page');
    }
    public function agent_tl_feedback(Request $request){

        $audit_data = Audit::with(['client','audit_rebuttal'])->find(Crypt::decrypt($request->audit_id));

        // print_r($request->sub_parameter_id);
        // print_r($request->agent_tl_feedback); die;
            for($i=0;$i<sizeof($request->sub_parameter_id);$i++){
                if($request->agent_tl_feedback[$i] == 2){
                    $status = 2;
                }
                else{
                    $status = 0;
                }

               $rebuttals = Rebuttal::where('audit_id',$audit_data->id)->where('sub_parameter_id',$request->sub_parameter_id[$i])
               ->update(['valid_invalid'=> $request->agent_tl_feedback[$i], 'valid_invalid_time'=> date('Y-m-d h:i:s'),
               'valid_invalid_user'=> Auth::user()->id,'status'=>$status,'invalid_remark'=>$request->invalid_remark]);
                
                //  $agent_tl_rebuttal_time = skipHoliday(strtotime($audit_data->agent_tl_approval_tat),$audit_data->client->holiday,$audit_data->client->agent_tl_approval,$audit_data->client->agent_tl_approval);      
            }
           // echo $request->invalid_remark; die;
            $porter_reply_rebuttal_time = skipHoliday(strtotime($audit_data->agent_tl_approval_tat),$audit_data->client->holiday,$audit_data->client->porter_tl_reply_rebuttal_time,$audit_data->client->porter_tl_reply_rebuttal_time);
            $audit_data->porter_tl_reply_rebuttal_time_tat = $porter_reply_rebuttal_time[0]; 
            $audit_data->save();

        return redirect('partner/single_audit_detail/'.$request->audit_id)->with('success','Feedback saved successfully.');
    }
    public function valid_invalid($audit_id, $status){
    
        //$audit_id = Crypt::decrypt($id);
        $audit_data = Audit::find(Crypt::decrypt($audit_id));
        if($status == 1){
            $rebuttal_status = 0;
        } else {
            $rebuttal_status = 2;
        }
          
        $rebuttals = Rebuttal::where('audit_id',$audit_data->id)
        ->update(['valid_invalid'=> $status, 'valid_invalid_time'=> date('Y-m-d H:i:s'),
        'valid_invalid_user'=> Auth::user()->id,'status'=>$rebuttal_status,'invalid_remark'=>""]);
        
        $porter_reply_rebuttal_time = skipHoliday(strtotime($audit_data->agent_tl_approval_tat),$audit_data->client->holiday,$audit_data->client->porter_tl_reply_rebuttal_time,$audit_data->client->porter_tl_reply_rebuttal_time);
        $audit_data->porter_tl_reply_rebuttal_time_tat = $porter_reply_rebuttal_time[0]; 
        $audit_data->save();

        return redirect()->back()->with('success','Validte successfully.');
    }
}
