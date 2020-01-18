@component('mail::message')

Hello, {{ $first_name . ' ' . $last_name }}

You are receiving this email because we received a password reset request for your account.

@php
$user = App\Models\User::where('email', request()->email)->first();
@endphp
@if ($user->isCustomer())
@component('mail::button', ['url' => route('password.reset', $token)])
@else 
@component('mail::button', ['url' => route('nova.password.reset', $token)])
@endif
Reset password
@endcomponent

If you didn't request this, you can ignore this email or let us know. Your password won't change until you create a new password.

{{ config('mail.signature') }}

@component('mail::subcopy')
This is a system-generated notification. If you need help, please contact [ivs_feedback@ivs.com](mailto:ivs_feedback@ivs.com).
@endcomponent
@endcomponent