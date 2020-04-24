<?php

namespace App;

use Illuminate\Http\Request;
use App\Mail\SendEmailMailable;
use App\Notifications\TaskCompleted;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'verify_token', 'verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function updateAvatar(Request $req)
    {
        if($req->hasFile("avatar")){
            $user = auth()->user();

            $fileName = $req->avatar->store("avatars", 'public');
            Storage::disk('public')->delete($user->avatar);

            $user->avatar = $fileName;
            $user->save();
        }
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new TaskCompleted);
    }

}
