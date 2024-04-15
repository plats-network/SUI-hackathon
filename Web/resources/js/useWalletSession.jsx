import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect, useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";

export default function App() {
    const wallet = useWallet();
 
    const [sessionData, setSessionData] = useState([]);

    const handleClick = async () => {
        const newSessionData = [];
        
        $('.itemSessionDetailMint').each(function (index) {

            const nameSession = $(this).find('.name_session').val();
            const descriptionSession = $(this).find('.description_session').val();
            const fileSession = $(this).find('.image-file').attr('link-img') ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';
            const sessionObj = {
                nameSession: nameSession,
                descriptionSession: descriptionSession,
                fileSession: fileSession
            };  
            newSessionData.push(sessionObj);
        });
        console.log('newSessionData',newSessionData);
        setSessionData(newSessionData);
    }

    const appendNftSessionDetail = (details) => {
        console.log('details',details);
        // Khai báo một biến để chứa chuỗi HTML
        let html = '';

        // Lặp qua mỗi chi tiết trong mảng details
        details.forEach(detail => {
            // Tạo HTML cho mỗi chi tiết và thêm vào chuỗi html
            html += '<div class="row mb-3">\n';
            html += '    <div class="col-4">\n';
            html += '        <label for="image-file">\n';
            html += '            <img class="img-preview img-preview-nft" src="' + detail.fileSession + '">\n';
            html += '        </label>\n';
            html += '    </div>\n';
            html += '    <div class="col-6">\n';
            html += '        <div class="col-10 mt-25">\n';
            html += '            <p class="class-ticket">' + detail.nameSession + '</p>\n';
            html += '        </div>\n';
            html += '        <div class="col-10 mt-20">\n';
            html += '            <p class="class-ticket">' + detail.descriptionSession + '</p>\n';
            html += '        </div>\n';
            html += '    </div>\n';
            html += '    <div class="col-2" style="margin-top: 50px">\n';
            html += '        <p class="class-ticket"><a target="_blank" href="https://suiscan.xyz/testnet/tx/' + detail.txhash + '">txhash</a></p>\n';
            html += '    </div>\n';
            html += '</div>';
        });

        // Sau khi lặp qua mảng details, thêm chuỗi HTML vào '.append-nft-session-detail'
        $('.append-nft-session-detail').empty().append(html);

    };
    
    const mint = async (wallet,data) => {
        let newData = {
            nameSession: data.map(item => item.nameSession),
            descriptionSession: data.map(item => item.descriptionSession),
            fileSession: data.map(item => item.fileSession)
        };
        console.log('newData',newData);
        console.log('wallet',wallet);
        const tx = new TransactionBlock();
        let packageId = "0x4adab96560b3199dd3b46f2c906e87f49a0ac8029f5e6eb3bb7d9739ee69235d";
        tx.moveCall({
            target: `${packageId}::client::mint_batch_sessions`,
            arguments: [
                
                tx.pure(newData.nameSession),
                // description: vector<vector<u8>>,
                tx.pure(newData.descriptionSession),
                // url: vector<vector<u8>>,
                tx.pure(newData.fileSession),

                //tx.pure(data.nameSession),
                //tx.pure(data.descriptionSession),
                //tx.pure(data.fileSession),
                tx.object('0xde8cb6c56c178eb3c25cd1c979f9b6b251d65ad50f34b8b70ad8c43fad4ad96e'),
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
                alert('nft minted Session fails!');
                return;
            }

            // Lặp qua mỗi đối tượng trong mảng data
            data.forEach(obj => {
                // Thêm trường 'hash' với giá trị '123' vào mỗi đối tượng
                obj.txhash = result.digest;
            });

            appendNftSessionDetail(data);

            $('.loading').hide();

            alert('nft minted Session successfully!');
            
         } catch (error) {
            
            $('.loading').hide();

            alert('nft minted Session fails!');

        }
        
    }
    useEffect(() => {
        
        if(sessionData.length > 0){

            mint(wallet,sessionData);
        }
    }, [sessionData]);

    return (
        <div className="App">
            <ConnectButton />
            <section>
                {wallet.status === "connected" && (
                    <>
                        {wallet?.account && (
                            <>
                                <p>
                                    <button id="btnGenItemSession" onClick={handleClick} type="button" className="btn btn-primary btn-rounded waves-effect waves-light mb-2 mt-2 me-2">Generate Session</button>
                                </p>
                            </>
                        )}
                    </>
                )}
            </section>
        </div>
    );
}
