<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\LoggedUser;
use App\Token;
use App\User;
use Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
    /**
     * Handle a login request to the application.
     *
     * overided 
     */
    
    public function login(\Illuminate\Http\Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        // This section is the only change
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();
            if ($user->two_auth==1 && $user->status==1) {
                $otp = rand(100000, 999999);
                $result = json_decode(sendOtp($user->mobile,$otp),true);
                if ($result['type']=="success") {
                    $token = new Token;
                    $token->user_id = $user->id;
                    $token->token = $otp;
                    $token->save();
                }

                return response()->json(['status' => 201], 201);
            } else {
                // Make sure the user is active
                $user_details = User::where('email',$request->email)->first();
                if($user_details->already_logged_in == 1){
                    //return response()->json([ 'status' => 423, 'message' => 'User is already login in Deffernet window, Please logout First...'], 423);
                    return redirect('login/')->with('warning', 'User is already login in Deffernet window, Please logout First...');
                
                
                } else {
                    if ($user->status==1 && $this->attemptLogin($request)) {
                        // Send the normal successful login response
                        
                        
                            /* $user_update = User::find($user_details->id);
                            $user_update->already_logged_in = 1;
                            $user_update->save(); */
                            return $this->sendLoginResponse($request);
                       
                        
    
                    } else {
                        // Increment the failed login attempts and redirect back to the
                        // login form with an error message.
                        $this->incrementLoginAttempts($request);
                        // return redirect()
                        //     ->back()
                        //     ->withInput($request->only($this->username(), 'remember'))
                        //     ->withErrors(['active' => 'You must be active to login.']);
    
                        if($user->status==0)
                        return response()->json([ 'status' => 423, 'message' => 'Email not verified, please verify your email...'], 423);
    
                        if($user->status==99)
                        return response()->json([ 'status' => 424, 'message' => 'Your account has been deactivated by admin, please contact QuoteNow Admin.'], 424);
    
                        
                    }
                }
                
            }
            
            
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    protected function authenticated(Request $request, $user)
    {
        $loggeduser = new LoggedUser;
        $loggeduser->user_id = Auth::user()->id;
        $loggeduser->logged_ip = $request->getClientIp();
        $loggeduser->save();
    }

    public function verify_otp(Request $request)
    {
        $user = Token::where('token',$request->otp)->with('user')->get();
        if (is_array($user)!=true && count($user)===0) {
            return response()->json(['massage' => 'We Did not Verify You'], 200);
        } else {
            $data = $user[0];
            Token::where('token',$request->otp)->delete();
            Auth::loginUsingId($data->user_id, TRUE);
            return response()->json(['status' => 201], 201);
        }
        
    }


}
