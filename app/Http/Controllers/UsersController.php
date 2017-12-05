<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class UsersController extends Controller
{
    public function create(){
      return view('users.create');
    }
    // 个人中心
    public function show(User $user){
      return view('users.show', compact('user'));
    }
    // 注册
    public function store(Request $request){
        // 验证
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required'
        ]);
        // 数据创建至数据库
        $user = User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => bcrypt($request->password),
       ]);
      //  调用登录方法 直接登录
       Auth::login($user);
      //  使用sessoin 赋值方法为flash，键和值
       session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
      //  重定向至个人中心 并传值
        return redirect()->route('users.show', [$user]);
    }
}
