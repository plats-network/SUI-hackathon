import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect,useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";

export default function App() {
    const wallet = useWallet();
    const [boothData, setBoothData] = useState([]);

    const handleClick = async () => {
        const newBoothData = [];
        
        $('.itemBoothDetailMint').each(function (index) {

            const imgElement = $(this).find('img'); // Tìm thẻ img trong phần tử hiện tại
            const src = imgElement.attr('src'); // Lấy src từ thẻ img

            const nameBooth = $(this).find('.name_booth').val();
            const descriptionBooth = $(this).find('.description_booth').val();
            const fileBooth = src ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';

            const boothObj = {
                nameBooth: nameBooth,
                descriptionBooth: descriptionBooth,
                fileBooth: fileBooth
            };

            newBoothData.push(boothObj);
        });

        setBoothData(newBoothData);
    }

    const appendNftBoothDetail = (details) => {
        console.log('details',details);
        // Khai báo một biến để chứa chuỗi HTML
        let html = '';

        // Lặp qua mỗi chi tiết trong mảng details
        details.forEach(detail => {
            // Tạo HTML cho mỗi chi tiết và thêm vào chuỗi html
            html += '<div class="row mb-3">\n';
            html += '    <div class="col-4">\n';
            html += '        <label for="image-file">\n';
            html += '            <img class="img-preview img-preview-nft" src="' + detail.fileBooth + '">\n';
            html += '        </label>\n';
            html += '    </div>\n';
            html += '    <div class="col-6">\n';
            html += '        <div class="col-10 mt-25">\n';
            html += '            <p class="class-ticket">' + detail.nameBooth + '</p>\n';
            html += '        </div>\n';
            html += '        <div class="col-10 mt-20">\n';
            html += '            <p class="class-ticket">' + detail.descriptionBooth + '</p>\n';
            html += '        </div>\n';
            html += '    </div>\n';
            html += '    <div class="col-2" style="margin-top: 50px">\n';
            html += '        <p class="class-ticket"><a href="https://suiscan.xyz/testnet/tx/' + detail.txhash + '">txhash</a></p>\n';
            html += '    </div>\n';
            html += '</div>';
        });

        // Sau khi lặp qua mảng details, thêm chuỗi HTML vào '.append-nft-session-detail'
        $('.append-nft-booth-detail').empty().append(html);

    };
    
    const mint = async (wallet,data) => {
        console.log(data);
        let newData = {
            nameBooth: data.map(item => item.nameBooth),
            descriptionBooth: data.map(item => item.descriptionBooth),
            fileBooth: data.map(item => item.fileBooth)
        };
        const tx = new TransactionBlock();
        let packageId = "0x4adab96560b3199dd3b46f2c906e87f49a0ac8029f5e6eb3bb7d9739ee69235d";
        tx.moveCall({
            target: `${packageId}::client::mint_batch_booths`,
            arguments: [
                tx.pure(newData.nameBooth),
                // description: vector<vector<u8>>,
                tx.pure(newData.descriptionBooth),
                // url: vector<vector<u8>>,
                tx.pure(newData.fileBooth),

                tx.object('0xde8cb6c56c178eb3c25cd1c979f9b6b251d65ad50f34b8b70ad8c43fad4ad96e'),
            ],
            typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
        });

  
        const result = await wallet.signAndExecuteTransactionBlock({
            transactionBlock: tx,
        });

        console.log(result);
        if(!result.confirmedLocalExecution){
            alert('nft minted Booth fail!');
            return;
        }
        // Lặp qua mỗi đối tượng trong mảng data
        data.forEach(obj => {
            // Thêm trường 'hash' với giá trị '123' vào mỗi đối tượng
            obj.txhash = result.digest;
        });

        appendNftBoothDetail(data);
        alert('nft minted Booth successfully!');
    }
    useEffect(() => {

        if(boothData.length > 0){

            mint(wallet,boothData);

            console.log(wallet);
            console.log(boothData);
        }

    }, [boothData]);

    return (
        <div className="App">
            {/* <h1 className="title gradient">Hello, Suiet Wallet Kit</h1> */}
            <ConnectButton />

            <section>
                {/* <p>
                    <span class="gradient">Wallet status:</span> {wallet.status}
                </p> */}
                {wallet.status === "connected" && (
                    <>
                        {wallet?.account && (
                            <>
                                <p>
                                <button id="btnGenItemBooth" onClick={handleClick}  type="button" class="btn btn-primary btn-rounded waves-effect waves-light mb-2  mt-2 me-2">Generate Booth</button>
                                    {/* <span class="gradient">Connected Account: </span>
                                    {wallet.account.address}
                                </p>
                                <p>
                  <span class="gradient">
                    Connected Account (with ellipsis):{" "}
                  </span>
                                    {addressEllipsis(wallet.account.address)} */}
                                </p>
                            </>
                        )}
                        {/* <p>
                            <span class="gradient">Current chain of wallet: </span>
                            {wallet.chain.name}
                        </p> */}
                    </>
                )}
            </section>
        </div>
    );
}
