@component('mail::message')

Hello, {{ $form['name'] }}

You are receiving this email because we received a registration request for your account.

Your 6-digit verification code - {{ $form['code'] }}

@component('mail::button', ['url' => route('email.verify', $form['token'])])
Verify Email
@endcomponent

If you didn't request this, you can ignore this email or let us know. Your password won't change until you create a new password.

{{ config('mail.signature') }}

@component('mail::subcopy')
This is a system-generated notification. If you need help, please contact [ivs_feedback@ivs.com](mailto:ivs_feedback@ivs.com).
@endcomponent
@endcomponent