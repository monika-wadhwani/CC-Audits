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
use App\ScenerioTree;
use Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ScanerioQuestionsImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
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

            if($row[1] !== "Questions"){
                $response = array();
               
                if($row[1] == 'True'){
                    $scanerio_code = ScenerioTree::where('scenerio_code',$row[0])->first();
                    $id = 2463;
                  
                    if($scanerio_code){
                    $new_rc = ScenerioTree::find($scanerio_code->id);
                        if($new_rc->questions_answers){
                            $json_string = $new_rc->questions_answers;
                            $response = json_decode($json_string,true);
                            $response[$id] = $row[2];
                            $updated_response = json_encode($response);
                            $new_rc->questions_answers = $updated_response;
                            $new_rc->save();
                        }else{
                            $response[$id] = $row[2];
                            $new_rc->questions_answers = json_encode($response);
                            $new_rc->save();
                        }
                    }
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
