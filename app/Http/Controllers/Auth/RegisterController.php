<?php

namespace App\Http\Controllers\Auth;

use App\Company;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'],
            'mobile' => ['required', 'digits:10'],
            'company_name' => ['required', 'string'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data, int $new_registered_company_id)
    {
        return User::create([
            'company_id' => $new_registered_company_id,
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
        ]);
    }
    /**
     * overide
     */
    public function register(\Illuminate\Http\Request $request)
    {   
        $this->validator($request->all())->validate();

        //register company here
        $new_crec = new Company;
        $new_crec->company_name = $request->company_name;
        $new_crec->contact_phone = $request->contact_phone;
        $new_crec->contact_email = $request->contact_email;
        $new_crec->save();
        $new_registered_company_id = $new_crec->id;
        //register company here

        event(new Registered($user = $this->create($request->all(),$new_registered_company_id)));
        
        
        return $this->registered($request, $user)
            ?: response()->json([ 'status' => 200, 'message' => 'Welcome to QM Tool, Please check your email for activate your account, Thanks.' ], 200);
    }
    /**
     * overide
     */
    protected function registered(\Illuminate\Http\Request $request, $user)
    {
        $role  = Role::where('name','admin')->first();
        $user->attachRole($role);
    }
}
