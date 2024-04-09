<!-- resources/views/emails/nft_notification.blade.php -->

@component('mail::message')
    # Thông Báo Nhận NFT

    Hi {{ $userName }},

    Congratulations! You just received an NFT gift from {{ $senderName }}. Below are the details about your NFT:

    **Name NFT:** {{ $nftName }}
    **Describe:** {{ $nftDescription }}
    **Link NFT:** [{{$nftUrl}}]

    Check it out now and experience your unique NFT!

    @component('mail::button', ['url' => $nftUrl])
        View NFT
    @endcomponent

    If you have any questions or need assistance, don't hesitate to contact us.

    Thank you for joining our community and have fun with your NFT.

    Best regards,
    Platform Team
@endcomponent
