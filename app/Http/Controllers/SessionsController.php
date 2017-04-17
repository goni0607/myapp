<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct()
    {
    		$this->middleware('guest', ['except' => 'destroy']);
    }


    public function create()
    {
    		return view('sessions.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required|min:6',
        ]);

        $token = is_api_domain()
            ? jwt()->attempt($request->only('email', 'password'))
            : auth()->attempt($request->only('email', 'password'), $request->has('remember'));

        if (! $token) {
            if (\App\User::socialUser($request->input('email'))->first()) {
                return $this->respondSocialUser();
            }

            return $this->respondLoginFailed();
        }

        /*
        if (!auth()->attempt($request->only('email', 'password'), $request->has('remember'))) {
            flash('이메일 또는 비밀번호가 맞지 않습니다.');

            return back()->withInput();
        }
        */

        if (! auth()->user()->activated) {
            auth()->logout();
            //flash('가입 확인해 주세요.');

            //return back()->withInput();
            return $this->respondNotConfirmed();
        }

        // flash(auth()->user()->name . '님, 환영합니다.');
        
        //return redirect()->intended('home');

        return $this->respondCreated($token);
    }


    /* 소셜 계정이 로그인 폼에서 하려 했을 경우 */
    protected function respondSocialUser()
    {
        flash()->error(
            trans('auth.sessions.error_social_user')
        );

        return back()->withInput();
    }


    /* 로그인에 실패했을 경우 */
    protected function respondLoginFailed()
    {
        flash()->error(
            trans('auth.sessions.error_incorrect_credentials')
        );

        return back()->withInput();
    }


    /* 가입 메일 승인처리 하지 않을 사용자 일 경우 */
    protected function respondNotConfirmed()
    {
        flash()->error(
            trans('auth.sessions.error_not_confirmed')
        );

        return back()->withInput();
    }


    /* 로그인 성공 */
    protected function respondCreated($token)
    {
        flash(
            trans('auth.sessions.info_welcome', ['name' => auth()->user()->name ])
        );

        return ($return = $request('return'))
            ? redirect(urldecode($return))
            : redirect()->intended(route('home'));
    }


    public function destroy()
    {
        auth()->logout();
        flash(
            trans('auth.sessions.info_bye')
        );

        return redirect('home');
    }
}
