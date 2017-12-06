<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     * 包含在该属性中的字段才能够被正常更新：
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * 用户实例通过数组或 JSON 显示时进行隐藏
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 获取头像
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // boot 方法会在用户模型类完成初始化之后进行加载，因此我们对事件的监听需要放在该方法中
    public static function boot()
   {
       parent::boot();
      //  监听模型创建之前 发生事件
       static::creating(function ($user) {
           $user->activation_token = str_random(30);
       });
   }
}
