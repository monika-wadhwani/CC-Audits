<?php

namespace App\Imports;
ini_set('memory_limit', '-1');
set_time_limit(0);
use App\FailedRawDumpRow;

use App\Partner;

use App\User;
use App\ScenerioTree;
use Auth;
use Crypt;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ScenarioCodeImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow
{
    Public $data;
    
  
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

        if($row[0]!=null && $row[1]!=null && $row[2]!=null && $row[3]!=null && $row[4]!=null && $row[5]!=null && strpos($row[1], 'VLOOKUP') !== true)
        {   
     
            $scenario = ScenerioTree::where('scenerio_code',$row[5])->first();
            if($scenario){
                $new_rc = ScenerioTree::find($scenario->id);
        
                $new_rc->uploaded_by = $this->data['uploaded_by'];         
                $new_rc->caller = $row[0];
                $new_rc->order_stage = $row[1];
                $new_rc->issue = $row[2];
                $new_rc->sub_issues = $row[3];
                $new_rc->scenario = $row[4];
                $new_rc->scenerio_code = $row[5];
                
                $new_rc->save();
            }else{
                $new_rc = new ScenerioTree;
        
                $new_rc->uploaded_by = $this->data['uploaded_by'];         
                $new_rc->caller = $row[0];
                $new_rc->order_stage = $row[1];
                $new_rc->issue = $row[2];
                $new_rc->sub_issues = $row[3];
                $new_rc->scenario = $row[4];
                $new_rc->scenerio_code = $row[5];
                
                $new_rc->save();
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
