<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
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
        $this->middleware('auth')->except(['verify','resend']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function verify(Request $request)
    {
       $user = User::find($request->route('id'));

        if($user)
        {
            if ($user->hasVerifiedEmail()) {
                return redirect($this->redirectPath());
            }
    
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
            $user->status=1;
            $user->save();
            return redirect($this->redirectPath())->with('verified', true);
        }else
        {
            return abort(404);
        }
    }
    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        $user = User::where('email',$request->email)->get();

        if($user)
        {
            $user = $user[0];
            if ($user->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
            }
            $user->sendEmailVerificationNotification();

            return response()->json([ 'status' => 200, 'message' => 'Link sent to your email.' ], 200);

        }else{
            return response()->json([ 'status' => 404, 'message' => 'User not found.' ], 404);
        }   
        
    }
}
