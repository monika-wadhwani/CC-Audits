<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
set_time_limit(0);
use App\FailedRawDumpRow;
use App\RawData;
use App\Region;
use App\QmSheet;
use App\Partner;
use App\Role;
use App\RoleUser;
use App\User;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;


class AgentsTlAssignmentImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    Public $data;
    Public $email;
  
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



        //$this->agents = Partner::where('client_id',1)->pluck('contact_email','id')->toArray();
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

        if($row[0]!=null && $row[1]!=null && strpos($row[1], 'VLOOKUP') !== true)
        {   
            
            if($row[0] !== "Agent TL Email"){

                $agent_tl_email = User::where('email',$row[0])->first();
                // if(!isset($agent_tl_email)){
                //    print_r($agent_tl_email->parent_client); die;
                // }
                $agent_tl_id = $agent_tl_email->id;
                $agent_id = User::where('email',$row[1])->first(); 
             
                if($agent_tl_id){                    
                    $new_rc = User::find($agent_id->id);
                    $new_rc->reporting_user_id = $agent_tl_id;
                    $new_rc->save();
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
