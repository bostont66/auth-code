<x-mail::message>
# Verify Your Email

Hi {{ $registration->name }},

Thanks for registering with {{ config('app.name') }}! Please verify your email by clicking the button below:

<x-mail::button :url="$verifyUrl">
Verify Email
</x-mail::button>

Or copy this verification token:

<x-mail::panel>
<div style="font-size: 14px; font-family: monospace; text-align: center; padding: 12px; color: #2f3a3f; word-break: break-all;">
{{ $registration->token }}
</div>
</x-mail::panel>

This link expires in **24 hours**.

If you didn't create an account, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
