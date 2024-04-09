<div id="nftC" class="modal fade" data-backdrop="static" data-keyboard="false" data-nft="{{$nft}}">
    <style type="text/css">
        .text-danger, .error {
            color: red;
        }

        .btn--order {
            padding: 10px 30px;
            background: #3EA2FF;
            color: #fff;
            text-align: right;
        }

        .btn-nft {
            background-color: #007bff;
            padding: 10px 20px;
            border-radius: 10px;
            margin-top: 20px;
            display: inline-block;
            color: #fff;
        }
    </style>

    <div class="modal-dialog" style="width: 380px; margin: 20% auto;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size: 23px; text-align: center;">Claim NFT To Complete Task</h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <a id="reset-NFT"
                        class="btn-nft"
                        href="#"
                        data-url="{{$url}}"
                        data-reset="{{route('nft.remove')}}">NEXT</a>
                </div>
            </div>
        </div>
    </div>
</div>