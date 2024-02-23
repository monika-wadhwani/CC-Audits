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


class UsersImport implements ToModel, WithBatchInserts, WithChunkReading
{
    Public $data;
    Public $email;
  

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Array $data)
    {
        $this->data = $data;

        // get all company location
        //$this->all_location = Region::where('company_id',$this->data['company_id'])->pluck('name','id')->toArray();
        $this->email = User::pluck('email','id')->toArray();

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
            if(array_search($row[1],$this->email)==null && $row[1] !== "Primary Email"){
                $email_id = User::where('email',$row[1])->first();
                if($email_id){
                    $new_rc = User::find($email_id->id);
                    $new_rc->company_id = $this->data['company_id'];
                    //$new_rc->client_id = $this->data['client_id'];     
                    // $new_rc->process_id = $this->data['process_id'];         
                    $new_rc->name = $row[0];
                    $new_rc->email = trim($row[1]);
                    $new_rc->mobile = $row[2];
                    $new_rc->password = bcrypt($row[3]);
                    $new_rc->status=1;
                    $new_rc->is_first_time_user=1;
              
                    $new_rc->parent_client = $this->data['parent_client'];
                    $new_rc->save();
    
    
                    if($this->data['mapping']['role'] == 5){
                        $user_role = new RoleUser;
                        $user_role->role_id = 5;
                        $user_role->user_id = $new_rc->id;
                        $user_role->save();
    
                    }
                    if($this->data['mapping']['role'] == 14){
                        $user_role = new RoleUser;
                        $user_role->role_id = 14;
                        $user_role->user_id = $new_rc->id;
                        $user_role->save();
    
                    }
                    if($this->data['mapping']['role'] == 15){
                        $user_role = new RoleUser;
                        $user_role->role_id = 15;
                        $user_role->user_id = $new_rc->id;
                        $user_role->save();
                    }
                }else{
                $new_rc = new User;
            
                $new_rc->company_id = $this->data['company_id'];
                //$new_rc->client_id = $this->data['client_id'];     
                // $new_rc->process_id = $this->data['process_id'];         
                $new_rc->name = $row[0];
                $new_rc->email = trim($row[1]);
                $new_rc->mobile = $row[2];
                $new_rc->password = bcrypt($row[3]);
                $new_rc->status=1;
                $new_rc->is_first_time_user=1;
          
                $new_rc->parent_client = $this->data['parent_client'];
                $new_rc->save();


                if($this->data['mapping']['role'] == 5){
                    $user_role = new RoleUser;
                    $user_role->role_id = 5;
                    $user_role->user_id = $new_rc->id;
                    $user_role->save();

                 
                }
                if($this->data['mapping']['role'] == 14){
                    $user_role = new RoleUser;
                    $user_role->role_id = 14;
                    $user_role->user_id = $new_rc->id;
                    $user_role->save();

                 
                }
                if($this->data['mapping']['role'] == 15){
                    $user_role = new RoleUser;
                    $user_role->role_id = 15;
                    $user_role->user_id = $new_rc->id;
                    $user_role->save();
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
