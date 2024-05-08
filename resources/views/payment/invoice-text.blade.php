<x-mail::message>
    <h1 style="text-align: center">{{ __('An invoice for payment') }}</h1>
    <p>{{ __('You can view your invoice by clicking on the link below.') }}</p>
    <x-mail::button :url="$paymentInvoice->pdf_url">
        {{ __('View invoice') }}
    </x-mail::button>
    {{ __('Regards') }},<br> {{ config('app.name') }}
</x-mail::message>
