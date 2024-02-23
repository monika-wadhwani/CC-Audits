<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AgentFeedbackEmail;
use App\Client;
use App\Process;

class AgentFeedbackController extends Controller
{
    public function index(){
        
        return view('porter_design.agent_feedback_email.set_target');
    }
    public function getList(){

        $data = AgentFeedbackEmail:: select('agent_feedback_emails.id','processes.name as process_name','agent_feedback_emails.email','agent_feedback_emails.email_type','clients.name as client_name')
      
        ->join('processes', 'agent_feedback_emails.process_id', '=', 'processes.id')
        ->join('clients','agent_feedback_emails.client_id','=','clients.id')
        
       /*  ->join('partners', 'lobs.partner_id', '=', 'partners.id') */
       
        
        ->get();
        
        return view('porter_design.agent_feedback_email.list',compact('data'));
    }

    public function single_target($target_id){

        //echo Crypt::decrypt($target_id);

        $deleted_data = AgentFeedbackEmail::where('id',$target_id)->delete();
        /* echo Crypt::decrypt($target_id); */
       /*  echo '<pre>';
        print_r($data[0]->client_name);
        dd(); */
        $data = AgentFeedbackEmail:: select('agent_feedback_emails.id','processes.name as process_name','agent_feedback_emails.email','agent_feedback_emails.email_type','clients.name as client_name')
      
        ->join('processes', 'agent_feedback_emails.process_id', '=', 'processes.id')
        ->join('clients','agent_feedback_emails.client_id','=','clients.id')
        
       /*  ->join('partners', 'lobs.partner_id', '=', 'partners.id') */
       
        
        ->get();
        return view('porter_design.agent_feedback_email.list',compact('data'));
    }

    public function get_client($company_id){
   
            $data = Client::select('id','name')->where('company_id',$company_id)->get();
        
        
      
        $html='<option value="">Select Client</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_process($company_id){
        $data = Process::select('id','name')->where('company_id',$company_id)->get();
      
        $html='<option value="">Select Process</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_partners($client_id, $company_id){
        $data = Partner::select('id','name')->where('company_id',$company_id)->where('client_id',$client_id)->get();
      
        $html='<option value="0">Select Partner</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        $html.='<option value="all">All</option>';
        return $html; 
    }

    public function get_audit_cycle($client_id, $process_id){

        $data = AuditCycle::select('id','name')->where('client_id',$client_id)->where('process_id',$process_id)->get();
      
        $html='<option value="">Select Audit Cycle</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->id.'>'.$val->name.'</option>';
        }
        return $html; 
    }

    public function get_lob($partner_id){
      
        if($partner_id == 'all'){
            $data=RawData::select(DB::raw("distinct lob"))->get();
        } else {
            
            $data=RawData::select(DB::raw("distinct lob"))->where('partner_id',$partner_id)->get();
        }
        
        $html='<option value="">Select Lob</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->lob.'>'.$val->lob.'</option>';
        }
        $html.='<option value="%">All</option>';
        return $html; 
    }

    public function get_brand($partner_id){

        $data=RawData::select(DB::raw("distinct brand_name"))->where('partner_id',$partner_id)->get();
        $html='<option value="">Select Audit brand</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->brand_name.'>'.$val->brand_name.'</option>';
        }
        return $html; 
    }

    public function get_circle($partner_id){

        $data=RawData::select(DB::raw("distinct circle"))->where('partner_id',$partner_id)->get();
        $html='<option value="">Select Circle</option>';
        foreach($data as $val) {
            $html.='<option value='.$val->circle.'>'.$val->circle.'</option>';
        }
        return $html; 
    }
    
    public function save_target(Request $request)
    {
    //echo "fun in";
    /* print_r($request->target);
    dd(); */

    $check_email = AgentFeedbackEmail::where('email',$request->email)->count();
    
    // if($check_email){
       
    //     return redirect('agent_feedback_email/set')->with('warning', 'Please enter different name.');
    // }
    // else {
       
        $target = new AgentFeedbackEmail;
        $target->process_id = $request->process_id;;
        $target->client_id = $request->client_id;
        $target->email_type = $request->email_type;
        $target->email = $request->email;
        $target->save();
        

        return redirect('agent_feedback_email/set')->with('success', 'Email Set Successfully.'); 
    //}
   
}

    public function updae_target(Request $request)
    {
        //$data = Target::where('audit_cycle_id',$request->audit_cycle_id)->where('circle_name',$request->circle)->count();
        
        $update_data = Target::find($request->target_id);

        $check_cycle = AuditCycle::whereDate('end_date', '<=', date("Y-m-d"))->where('id',$update_data->audit_cycle_id)->count();


        if($check_cycle){
            $update_data->target = $request->target;
            $update_data->save();
            return redirect('target/list')->with('success', 'Target Set Successfully.'); 
           
        }
        else {
            return redirect('target/list')->with('warning', 'You can not update target after ending Audit cycle end date.');
           
        }
       
    }
}
