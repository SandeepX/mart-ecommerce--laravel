<?php

namespace App\Modules\User\Notifications;

use App\Mail\VerifyUserEmailMail;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;


class VerifyUserEmail extends VerifyEmail
{
//    use Queueable;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var Closure|null
     */
    public static $toMailCallback;

    public $userId = '';
    public $hash = '';




    /**
     * Create a new notification instance.
     *
     * @return void
     */




    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function setIdAndHash($notifiable){
        $this->userId = $notifiable->getKey();
        $this->hash = sha1($notifiable->getEmailForVerification());
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $sendEmailUrl = $this->sendEmailUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $sendEmailUrl);
        }

//        return (new MailMessage)
//            ->subject(Lang::get('Verify Email Address for Allpasal Account'))
//            ->line(Lang::get('Please click the button below to verify your email address.'))
//            ->action(Lang::get('Verify Email Address'), $sendEmailUrl)
//            ->line(Lang::get('If you did not create an account in Allpasal, no further action is required.'));

        $details=[];
        $details['name'] =$notifiable->name;
        $details['url'] = $this->sendEmailUrl($notifiable);
        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address for Allpasal Account'))
            ->view('User::email',$details);
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {

          $this->setIdAndHash($notifiable);
//        return route('user.verify');
//        return URL::temporarySignedRoute('user.verify',Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),);
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id'=>$this->userId,
                'hash' =>$this->hash
            ]

        );

    }

    private function getParsedSignedUrlQuery($url){
        $parsedUrl = parse_url($url);
        return $parsedUrl['query'];
    }

    public function sendEmailUrl($notifiable)
    {
         $signedurl = $this->verificationUrl($notifiable);


        $queryString = $this->getParsedSignedUrlQuery($signedurl); // this contains expires and signature

//        $emailUrl = config('site_urls.ecommerce_site')."/verify/email"."?".$queryString.'&id='.$this->userId.'&hash='.$this->hash;
        $emailUrl = config('site_urls.ecommerce_site')."/store-registration/verify/email-address"."?".$queryString.'&id='.$this->userId.'&hash='.$this->hash;
        return $emailUrl;

//        $this->notify(new ResetPasswordMailNotification($resetUrl));
    }

    /**
     * Set a callback that should be used when building the notification mail message.
     *
     * @param  Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }


}
