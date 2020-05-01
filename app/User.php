<?php

namespace App;

use App\Mail\OTPMail;
use App\Mail\verifyEmail;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\File;
use App\Mail\SendEmailMailable;
use App\Notifications\TaskCompleted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\Models\Media;
use App\Notifications\OTPNotification;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyNotification;
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
        'name', 'email', 'password', 'verify_token', 'verified', 'phone'
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
        $user = auth()->user();
        // $user->addMedia($req->avatar)->toMediaCollection("avatar");

        $fileName = $req->avatar->store("avatars", 'public');
        Storage::disk('public')->delete($user->avatar);

        $user->avatar = $fileName;
        $user->save();
    }

    public static function updateAvatarByVue($base64Data)
    {
        list($type, $fileData) = explode(';', $base64Data);
        list(, $fileData) = explode(',', $fileData); 
        $imageName = 'avatars/' . str_random(10).'.'.'png';   
        Storage::disk('public')->put($imageName, base64_decode($fileData));

        $user = auth()->user();
        Storage::disk('public')->delete($user->avatar);
        $user->avatar = $imageName;
        $user->save();
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyNotification($this));
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

    public function OTP()
    {
        return Cache::get($this->OTPKey());
    }

    public function OTPKey()
    {
        return "OTP_for_{$this->id}";
    }

    public function cacheTheOTP()
    {
        $OTP = rand(100000, 999999);
        Cache::put([$this->OTPKey() => $OTP], now()->addMinutes(5));
        return $OTP;
    }

    public function sendOTP($via)
    {
        $OTP = $this->cacheTheOTP();
        $this->notify(new OTPNotification($via, $OTP));
    }

    public function routeNotificationForKarix()
    {
        // cái này dùng để pass 'to phone' vào trong OTPNotification::toKarix
        // nếu k có cái này ta phải add KarixMessage::create()->to(phone)
        return $this->phone;
    }

}
