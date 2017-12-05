<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
class UsersController extends Controller
{
  // 中间件 利用auth验证 除了create store方法不需要登录 其他方法必须经过登录
    public function __construct(){
       $this->middleware('auth', [
           'except' => ['create', 'store','index']
       ]);
      //  访客 （未登录用户才能访问）
       $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 所有用户列表
    public function index(){
      //  $users = User::all();
       $users = User::paginate(10);
       return view('users.index', compact('users'));
     }

    // 注册页面
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

    // 编辑页面 利用了 Laravel 的『隐性路由模型绑定』功能，直接读取对应 ID 的用户实例 $user，未找到则报错；
    public function edit(User $user){
      $this->authorize('update', $user);
      return view('users.edit', compact('user'));
    }

    // 更新数据
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        // 权限验证 当前修改的信息是否为当前登录的用户
        $this->authorize('update', $user);
        // 转存提交的数据 当密码不为空时修改密码  为空时不修改密码
        $data = [];
       $data['name'] = $request->name;
       if ($request->password) {
           $data['password'] = bcrypt($request->password);
       }
       $user->update($data);
       session()->flash('success', '个人资料更新成功！');
       return redirect()->route('users.show', $user->id);
    }

    // 删除用户
    public function destroy(User $user){
      $this->authorize('destroy', $user);
       $user->delete();
       session()->flash('success', '成功删除用户！');
       return back();
   }
}
