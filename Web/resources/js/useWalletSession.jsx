import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect, useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";
// import TimerComponent from 'timerComponent';

import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';

export default function App() {

    const wallet = useWallet();
 
    const [sessionData, setSessionData] = useState([]);

    const handleClick = async () => {

        const totalNftTicket = $('input[name^="nft-ticket-name-"]').length;
        
        console.log('totalNftTicket',totalNftTicket);
        
        if(totalNftTicket <= 0){
            alert('please mint nft ticket first!')
            return;
        }

        const newSessionData = [];
        
        $('.itemSessionDetailMint').each(function (index) {

            const nameSession = $(this).find('.name_session').val() ?? 'name_session';
            const descriptionSession = $(this).find('.description_session').val() ?? 'description_session';
            const fileSession = $(this).find('.image-file').attr('link-img') ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';
            const mintftSession = $(this).find('.mintft_session').val() ?? 'mintft_session';
            const sessionObj = {
                nameSession: nameSession,
                descriptionSession: descriptionSession,
                fileSession: fileSession,
                mintftSession: mintftSession,
                totalNftTicket: totalNftTicket
            };  
            newSessionData.push(sessionObj);
        });
        console.log('newSessionData',newSessionData);
        setSessionData(newSessionData);
    }

    const appendNftSessionDetail = async (details) => {
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
            html += '        <p class="class-ticket"><a target="_blank" href="https://suiscan.xyz/devnet/tx/' + detail.txhash + '">txhash</a></p>\n';
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
            fileSession: data.map(item => item.fileSession),
            totalNftTicket: data.map(item => item.totalNftTicket)
        };
        console.log('newData',newData);
        console.log('wallet',wallet);

        let tx = new TransactionBlock();
        
        let typenetwork = $('meta[name="type_network"]').attr('content');

        console.log(typenetwork);

        let packageId = $('meta[name="package_id"]').attr('content');

        // let collection_id = $('meta[name="collection_id"]').attr('content');
        const contract_event_id = localStorage.getItem("contract_event_id");

        let event_id = $('meta[name="nft_hash_id"]').attr('content');

        let totalNftTicket = $('input[name^="nft-ticket-name-"]').length;
        
        console.log('totalNftTickets',totalNftTicket);

        tx.moveCall({
            target: `${packageId}::client::mint_batch_sessions`,
            arguments: [

                tx.pure(contract_event_id),

                tx.pure(event_id),

                tx.pure(newData.nameSession),
                
                tx.pure(newData.descriptionSession),
                
                tx.pure(newData.fileSession),

                tx.pure(newData.totalNftTicket[0] ?? 1),
              
            ],
        });
        $('.loading').show();
        try {
            const result = await wallet.signAndExecuteTransactionBlock({
                transactionBlock: tx,
                options: {
                    showObjectChanges: true,
                },
            });
            
            if(result.confirmedLocalExecution != true){
                alert('nft minted Session fails!');
                return;
            }
            console.log(result);
            console.log(data);

            // đoạn này là user claim
            let sessionIds =  result.objectChanges.filter((o) =>
                    o.type === "created" &&
                    o.objectType.includes("::ticket_collection::NFTSession")
            ).map(item => item.objectId);
            
            console.log('sessionIds',sessionIds);
            //user login jdk
            // const user = "0x70f94573c6cd732304f2c0fd9d80cf7d6206e4609c5c4b259972e90885fc3acb";
            // tx.transferObjects([tx.object(sessionIds)] , user);

            // const resultUserClaim = await client.signAndExecuteTransactionBlock({
            //     signer: keypair,
            //     transactionBlock: tx,
            // });
            // console.log(`Sessions id :`,$sessionIds);
            // console.log('resultUserClaim',resultUserClaim);
            // hết đoạn này là user claim

            // Lặp qua mỗi đối tượng trong mảng data
            data.forEach((obj, index) => {

                // Thêm trường 'hash' với giá trị '123' vào mỗi đối tượng
                obj.txhash = result.digest;

                //mint lại data
                $('.itemSessionDetailMint').eq(index).find('.nft_address_session').val(sessionIds[index]);
                $('.itemSessionDetailMint').eq(index).find('.nft_uri_session').val(`https://suiscan.xyz/${typenetwork}/tx/${result.digest}`);
                $('.itemSessionDetailMint').eq(index).find('.nft_res_session').val(JSON.stringify(result));
                $('.itemSessionDetailMint').eq(index).find('.image-file').val();
            });

            appendNftSessionDetail(data);

            $('.loading').hide();

            alert('nft minted Session successfully!');
            
         } catch (error) {

            $('.loading').hide();
            console.log('error',error);
            alert('nft minted Session fails!');

        }
        
    }
    useEffect(() => {
        
        if(sessionData.length > 0){

            return mint(wallet,sessionData);
        }
    }, [sessionData]);

    return (
        <div className="App">
            <ConnectButton label={'Connect Wallet'} />
            <section>
            
                {wallet.status === "connected" && (
                    <>
                        <p>
                            <button id="btnGenItemSession" onClick={handleClick} type="button" className="btn btn-primary btn-rounded waves-effect waves-light mb-2 mt-2 me-2">Generate Session</button>
                        </p>
                    </>
                )}
            </section>
        </div>
    );
}
