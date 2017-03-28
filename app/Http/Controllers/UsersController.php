<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
	public function __construct()
	{
		$this->middleware('guest');
	}


	/* 회원가입 양식 */
    public function create()
    {
    		return view('users.create');
    }


    /* 회원가입 정보 저장 */
    public function store(Request $request)
    {
        // $socialUser = \App\User::whereEmail($request->input('email'))->whereNull('password')->first();

        /* scode 사용 예제 */
        if ($socialUser = \App\User::socialUser($request->get('email'))->first()) {
            return $this->updateSocialAccount($request, $socialUser);
        }

        return $this->createNativeAccount($request);
    }



    public function updateSocialAccount(Request $request, \App\User $user)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed:min:6',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
        ]);

        $this->respondCreated($user);
    }


    public function createNativeAccount(Request $request)
    {
        $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|confirmed|min:6',
        ]);

        $confirmCode = str_random(60);

        $user = \App\User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'confirm_code' => $confirmCode,
        ]);

        event(new \App\Events\UserCreated($user));

        flash('가입하신 메일 계정으로 가입 확인 메일을 보내드렸습니다. 가입 확인하시고 로그인해 주세요.');

        $this->respondCreated($user);

        //auth()->login($user);
        //flash(auth()->user()->name . '님 환영합니다.');

        //return redirect('/');
    }

    /* 회원가입 활성화 */
    public function confirm($code)
    {
        $user = \App\User::whereConfirmCode($code)->first();

        if (!$user) {
            flash('URL이 정확하지 않습니다.');
            return redirect('/');
        }

        $user->activated = 1;
        $user->confirm_code = null;
        $user->save();

        $this->respondCreated($user);

        /*
    		auth()->login($user);
    		flash(auth()->user()->name . '님, 환영합니다. 가입 확인되었습니다.');

    		return redirect('home');
        */
    }


    protected function respondCreated($user)
    {
            auth()->login($user);

            flash($user()->name . '님, 환영합니다. 가입 확인되었습니다.');

            return redirect('home');
    }
}
