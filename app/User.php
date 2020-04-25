<?php

namespace App;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\File;
use App\Mail\SendEmailMailable;
use App\Notifications\TaskCompleted;
use Spatie\MediaLibrary\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, HasMedia 
{
    use Notifiable, HasRoles, HasMediaTrait;

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
            $user->addMedia($req->avatar)->toMediaCollection("avatar");

            // $fileName = $req->avatar->store("avatars", 'public');
            // Storage::disk('public')->delete($user->avatar);

            // $user->avatar = $fileName;
            // $user->save();
        }
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new TaskCompleted);
    }

    public function registerMediaCollections()
    {
        $this
            ->addMediaCollection('avatar')
            ->acceptsFile(function (File $file) {
                return $file->mimeType === 'image/png';
            })
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);
                $this
                    ->addMediaConversion('card')
                    ->width(368)
                    ->height(232);
            });
    }

    public function avatar()
    {
        return $this->hasOne(Media::class, 'id', 'avatar');
    }

    public function getAvatarUrlAttribute()
    {
        return $this->avatar->getUrl();
    }

}
