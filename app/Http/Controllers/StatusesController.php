<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Status;
class StatusesController extends Controller
{
  // 中间件过滤 必须登录
  public function __construct()
  {
      $this->middleware('auth');
  }
  // 发布微博
  public function store(Request $request)
   {
       $this->validate($request, [
           'content' => 'required|max:140'
       ]);

       Auth::user()->statuses()->create([
           'content' => $request->content
       ]);
       return redirect()->back();
   }

  //  删除微博
  public function destroy(Status $status)
    {
        $this->authorize('destroy', $status);
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}
