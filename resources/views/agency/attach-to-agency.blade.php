<x-mail::message>
    <h1 style="text-align: center">{{ __('Attach to agency') }}</h1>
    <p>{{ __('User :email invites you to his agency. You can complete the process by clicking the button below', ['email' => $email]) }}</p>
    <x-mail::button :url="$url">
        {{ __('Confirm') }}
    </x-mail::button>
    {{ __('Regards') }},<br> {{ config('app.name') }}
</x-mail::message>
