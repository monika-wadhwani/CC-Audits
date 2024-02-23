<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
set_time_limit(0);
use App\FailedRawDumpRow;
use App\RawData;
use App\Region;
use App\User;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class RawDataImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    Public $data;
    Public $all_location;
    Public $all_qa;
    Public $all_agents;
    Public $failed_rows;

    public function startRow(): int
    {
        return 2;
    }

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        $this->data = $data;

        // get all company location
        $this->all_location = Region::where('company_id',$this->data['company_id'])->pluck('name','id')->toArray();
        $this->all_qa = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'qa');
        })->pluck('email','id')->toArray();

        $this->all_agents = User::where('company_id',Auth::user()->company_id)->whereHas('roles', function ($query) {
            $query->where('name', '=', 'agent');
        })->pluck('email','id')->toArray();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // print_r($this->all_location);
        // print_r($this->all_qa);
       
        if($row[0]!=null && $row[1]!=null && strpos($row[15], 'VLOOKUP') !== true)
        {   
            if(Auth::user()->id == 333){
                if(array_search($row[17],$this->all_qa)!=null)
                {
                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = array_search($row[5],$this->all_location);
                $new_rc->qa_id = array_search($row[17],$this->all_qa);
    
                $new_rc->agent_name = $row[0];
                if(array_search($row[0],$this->all_agents)){
                    $new_rc->agent_id = array_search($row[0],$this->all_agents);
                }
                $new_rc->qtl_id = Auth::user()->id;
                
                $new_rc->call_id = $row[1];
                $new_rc->call_time = $row[2];
                $new_rc->call_duration = $row[3];
                $new_rc->phone_number = $row[4];
                $new_rc->location = $row[5];
                $new_rc->language = $row[6];
                $new_rc->call_type = $row[7];
                $new_rc->call_sub_type = $row[8];
                $new_rc->disposition = $row[9];
                $new_rc->campaign_name = $row[10];
                $new_rc->hangup_details = $row[11];
                $new_rc->lob = $row[12];
                $new_rc->tl = $row[13];
                $new_rc->doj = $row[14];
                $new_rc->emp_id = $row[15];
                $new_rc->customer_name = $row[16];
                $new_rc->brand_name = $row[18];
                $new_rc->circle = $row[19];
                $new_rc->info_1 = $row[20];
                $new_rc->info_2 = $row[21];
                $new_rc->info_3 = $row[22];
                $new_rc->info_4 = $row[23];
                $new_rc->info_5 = $row[24];
                
    
                $new_rc->save();
    
                }else
                {
                    //$failed_rows[] = $row[1];
                    $fr = new FailedRawDumpRow;
                    $fr->failed_raw_dump_slot_id = $this->data['batch_id'];
                    $fr->call_id = $row[1];
                    $fr->save();
                }
            }
            else{
                if(array_search($row[12],$this->all_location)!=null && array_search($row[21],$this->all_qa)!=null)
                {

                $new_rc = new RawData;
                $new_rc->dump_date = $this->data['dump_date'];
                $new_rc->batch_id = $this->data['batch_id'];
                $new_rc->company_id = $this->data['company_id'];
                $new_rc->client_id = $this->data['client_id'];
                $new_rc->partner_id = $this->data['partner_id'];
                $new_rc->process_id = $this->data['process_id'];
                $new_rc->partner_location_id = array_search($row[12],$this->all_location);
                $new_rc->qa_id = array_search($row[21],$this->all_qa);

                $new_rc->phone_number = $row[0];
                $new_rc->qtl_id = User::find(array_search($row[21],$this->all_qa))->reporting_user_id;
                $new_rc->call_time = $row[1];
                $new_rc->campaign_name = $row[2];
                $new_rc->call_id = $row[3];
                $new_rc->caller_id = $row[3];
                /* $new_rc->crt_object_id = $row[4]; */
                $new_rc->call_type = $row[5];
                $new_rc->hangup_details = $row[6];
                $new_rc->agent_name = $row[7];
                $new_rc->agent_id = array_search($row[7],$this->all_agents);
                $new_rc->call_duration = $row[8];
                $new_rc->disposition = $row[9];
                $new_rc->language = $row[10];
                $new_rc->case_id = $row[11];
                $new_rc->location = $row[12];
                /* $new_rc->raised_by = $row[13]; */
                $new_rc->crn_no_order_id = $row[14];
                $new_rc->order_stage = $row[15];
                $new_rc->issues = $row[16];
                $new_rc->sub_issues = $row[17];
                // $new_rc->scenerio = $row[18];
                // $new_rc->scenerio_code = $row[19];
    /* 
                $new_rc->action_taken = $row[20];
                $new_rc->close_reason = $row[21];
    */

                $new_rc->vehicle_type = $row[20];


                $new_rc->call_sub_type = "-";
                $new_rc->lob = "-";
                $new_rc->tl = "-";
                $new_rc->doj = "-";
                $new_rc->emp_id = "-";
                $new_rc->customer_name = "-";
                $new_rc->brand_name = "-";
                $new_rc->circle = "-";
                $new_rc->info_1 = "-";
                $new_rc->info_2 = "-";
                $new_rc->info_3 = "-";
                $new_rc->info_4 = "-";
                $new_rc->info_5 = "-";
                

                $new_rc->save();

                }else
                {
                    //$failed_rows[] = $row[1];
                    $fr = new FailedRawDumpRow;
                    $fr->failed_raw_dump_slot_id = $this->data['batch_id'];
                    $fr->call_id = $row[3];
                    $fr->save();
                }
            }
        }
    }

    public function batchSize(): int
    {
        return 1000;
    }
    
    public function chunkSize(): int
    {
        return 250;
    }
}
