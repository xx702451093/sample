<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller
{
    public function __construct(){
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    // 登录页
    public function create(){
        return view('sessions.create');
    }
    // 登录动作
    public function store(Request $request)
    {
      // 验证过后返回正确的数据数组
       $credentials = $this->validate($request, [
           'email' => 'required|email|max:255',
           'password' => 'required'
       ]);
      //  利用Auth类进行验证
       if (Auth::attempt($credentials,$request->has('remember'))) {
           // 登录成功后的相关操作
           if(Auth::user()->activated) {
               session()->flash('success', '欢迎回来！');
               return redirect()->intended(route('users.show', [Auth::user()]));
           } else {
               Auth::logout();
               session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
               return redirect('/');
           }
       } else {
           // 登录失败后的相关操作
           session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
           return redirect()->back();
       }
       return;
    }

    // 退出动作
    public function destroy(){
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
