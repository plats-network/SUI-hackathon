@extends('web.layouts.event_app')

@section('content')
{{--    @viteReactRefresh--}}
    @vite('resources/js/claim.js')

    <section class="home-top bg-contact pb-110">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12 col-md-6">
                    <div class="contact-content">
                        <h3 class="pb-40">NFT detail</h3>
                        <p >address_nft: {{ $nft->address_nft }}</p>
                        <p id="nft_title">nft_title: {{ $nft->nft_title }}</p>
                        <p id="nft_symbol">nft_symbol: {{ $nft->nft_symbol }}</p>
                        <p id="nft_uri">nft_uri: {{ $nft->nft_uri }}</p>
                        <p id="secret_key">secret_key: {{ $nft->secret_key }}</p>
                        <p >seed: {{ $nft->seed }}</p>
                        <p id="private_organizer">private_organizer: {{ $nft->private_organizer }}</p>
                        <p >address_organizer: {{ $nft->address_organizer }}</p>
                        <input id="address_organizer" value="{{ $nft->address_organizer }}" type="hidden">
                        <input id="address_nft" value="{{ $nft->address_nft }}" type="hidden">
                        <input id="seed" value="{{ $nft->seed }}" type="hidden">
                        <input id="user_address" value="{{ auth()->user()->wallet_address }}" type="hidden">
                    </div>

                    <button
                        type="button"
                        class="btn w-sm ms-auto"
                        id="connect_wallet">Claim
                    </button>
                </div>
                <div class="col-12 col-md-6">
                    <div class="about-thumb mb-80" >
                        <img src="{{url('events/icon/contact-icon.svg')}}" alt="Contact us for more support">
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
@push('custom-scripts')
<script src="{{ url('js/index.umd.js') }}"></script>
@endpush
