@component('mail::message')
    # NFT Received
    Your NFT has been received!
    @component('mail::button', ['url' => $url])
        Receive NFT
    @endcomponent
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
