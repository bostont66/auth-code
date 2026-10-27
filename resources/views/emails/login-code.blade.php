<x-mail::message>
# Your Login Code

Hi {{ $user->name }},

Your login code for {{ config('app.name') }} is:

<x-mail::panel>
<!-- impeccable-disable-next-line design-system-font-size -->
<div style="font-size: 32px; font-weight: bold; letter-spacing: 8px; text-align: center; padding: 16px; color: #2597d4;">
{{ $code }}
</div>
</x-mail::panel>

This code expires in **10 minutes**.

If you didn't request this code, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
