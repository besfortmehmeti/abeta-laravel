<?php

namespace AbetaIO\Laravel\Http\Controllers;

use AbetaIO\Laravel\AbetaPunchOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController
{
    /**
     * Login customer and set session
     *
     * @param Request $request
     * @return Model
     */
    public function login(Request $request)
    {
        abort_unless($request->hasValidSignature(), 404);

        $user = AbetaPunchOut::getCustomerModel()::find($request->user_id);
        
        if ($user) {
            // Log in the user
            AbetaPunchOut::getAuth()::login($user);
    
            // Store return URL and user_id in the session
            Session::put('abeta_punchout', [
                'return_url' => $request->get('return_url'),
                'user_id' => $user->id,
            ]);

            // Redirect the user to the configured route after login
            return redirect()->intended(config('abeta.routes.redirectTo'));
        }

        throw new \Exception('User not found');
    }
}