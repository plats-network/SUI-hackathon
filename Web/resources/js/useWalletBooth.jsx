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

            const nameBooth = $(this).find('.name_booth').val();
            const descriptionBooth = $(this).find('.description_booth').val();
            const fileBooth = $(this).find('.image-file').attr('link-img') ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';

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
            html += '        <p class="class-ticket"><a target="_blank" href="https://suiscan.xyz/testnet/tx/' + detail.txhash + '">txhash</a></p>\n';
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
        let packageId = "0x3827b28d5f79b559cf7f9f545cbc99a2653e19d7c99173cec1a9428a478357f5";
        tx.moveCall({
            target: `${packageId}::client::mint_batch_booths`,
            arguments: [
                tx.pure(newData.nameBooth),
                // description: vector<vector<u8>>,
                tx.pure(newData.descriptionBooth),
                // url: vector<vector<u8>>,
                tx.pure(newData.fileBooth),

                tx.object('0x3b0b0833c020f964c09991796945efa46b4cd66af696df698ae9a41a75383819'),
            ],
            typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
        });
        $('.loading').show();
        try {
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
            $('.loading').hide();   
            alert('nft minted Booth successfully!');
        } catch (error) {
                
            $('.loading').hide();

            alert('nft minted Session fails!');

        }
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
