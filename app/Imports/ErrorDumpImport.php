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
use App\ErrorCode;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ErrorDumpImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
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
        $this->error_code = ErrorCode::pluck('error_codes','id')->toArray();
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
            
            if($row[1] !== "Error Reason"){

                
                $codes = ErrorCode::where('error_codes',$row[2])->first();
                if($codes){
                    $new_rc = ErrorCode::find($codes->id);
            
                    $new_rc->upload_by_user_id = $this->data['upload_by_user_id'];         
                    $new_rc->error_reason_types = $row[0];
                 
                    $new_rc->error_reasons = $row[1];
                    
                    $new_rc->error_codes = $row[2];

                    $new_rc->markdown = $row[3];
                    $new_rc->experience_impacting = $row[4];
                   
                    $new_rc->save();
                }else{
                    $new_rc = new ErrorCode;
            
                    $new_rc->upload_by_user_id = $this->data['upload_by_user_id'];         
                    $new_rc->error_reason_types = $row[0];
                 
                    $new_rc->error_reasons = $row[1];
                    
                    $new_rc->error_codes = $row[2];

                    $new_rc->markdown = $row[3];
                    $new_rc->experience_impacting = $row[4];
                   
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
