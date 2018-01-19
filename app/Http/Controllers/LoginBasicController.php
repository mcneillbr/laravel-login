<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginBasicController extends Controller
{

  /**
   * Handle a login request to the application.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
   */
    public function login(Request $request)
    {
        $result = ['state' => false, 'csrf-token' => $request->session()->token(), 'fields' => $request->all()];
        if ( $this->guard()->attempt($request->only('email', 'password'), $request->has('remember')))
        {
            $result['state'] = true;
            $result['user'] = $this->guard()->user()->name;
        }
        return response()->json($result, 200);
    }
    /**
       * Log the user out of the application.
       *
       * @param  \Illuminate\Http\Request  $request
       * @return \Illuminate\Http\Response
       */
    public function logout(Request $request)
    {
        //$request->user()->token()->revoke();
        $this->guard()->logout();
        $request->session()->flush();
        $request->session()->regenerate(true);
        $request->session()->regenerateToken();
        return response()->json(['csrf_token' => $request->session()->token()], 200);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }
}
