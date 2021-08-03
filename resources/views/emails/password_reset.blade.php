@component('mail::message')
# Password Reset
You are receiving this email because you requested for password reset from your account.

@component('mail::button', ['url' => 'https:blood-hq.netlify.app/password/reset?token=' . $data['token']])
Reset Password
@endcomponent
If you did not request for password reset, kindly ignore this message.
Thanks,<br>
{{ config('app.name') }}
@endcomponent