import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect, useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";

import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';

export default function App() {
    const wallet = useWallet();
 
    const [sessionData, setSessionData] = useState([]);

    const handleClick = async () => {
        const newSessionData = [];
        
        $('.itemSessionDetailMint').each(function (index) {

            const nameSession = $(this).find('.name_session').val() ?? 'name_session';
            const descriptionSession = $(this).find('.description_session').val() ?? 'description_session';
            const fileSession = $(this).find('.image-file').attr('link-img') ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';
            const mintftSession = $(this).find('.mintft_session').val();
            const sessionObj = {
                nameSession: nameSession,
                descriptionSession: descriptionSession,
                fileSession: fileSession,
                mintftSession:mintftSession
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
        let packageId = $('meta[name="package_id"]').attr('content');
        let collection_id = $('meta[name="collection_id"]').attr('content');
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
                tx.object(collection_id),
            ],
            typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
        });
        $('.loading').show();
        try {
            const result = await wallet.signAndExecuteTransactionBlock({
                transactionBlock: tx,
                options: {
                    showObjectChanges: true,
                },
            });
            
            // // console.log('signAndExecuteTransactionBlock',result);
            let mnemonic_client = 'genius exit shallow wealth boring layer rotate model calm behind immune maze';
            // let collection_id = '0x2587305d59dbcc09406e  1ef0147053fff3019a64aca312108adac2913785a6d0';
            // let package_id = '0x5ff08c4a46f0e68e9677f6be420b6adf9f0fc90355f978ea235173fffc061a5c';
            const keypair = Ed25519Keypair.deriveKeypair(mnemonic_client);
            // const client = new SuiClient({
            //     url: getFullnodeUrl('testnet'),
            // });
            // let addressClient = keypair.getPublicKey().toSuiAddress();
            
            // const allObjects = await client.getOwnedObjects({
            //     owner: addressClient,
            //     options: {
            //         showType: true,
            //         showDisplay: true,
            //         showContent: true,
            //     }
            // });
            
            // //console.log("objectIDs", allObjects.data[0]);
            // const objectIDs = (allObjects?.data || [])
            //     .filter((item) => item.data.objectId == collection_id)
            //     .map((anObj) => anObj.data.objectId);
            
            // const allObjRes = await client.multiGetObjects({
            //     ids: objectIDs,
            //     options: {
            //         showContent: true,
            //         showDisplay: true,
            //         showType: true,
            //     },
            // });
            // console.log('allObjRes',allObjRes);
            // const nftList = allObjRes.filter(obj => obj.data).map(obj => ({
            //     objectId: obj.data.objectId,
            //     data: obj.data.content.fields,
            
            // }));
            // //get ticket
            
            // const tickets = nftList.map((data) => data.data.tickets);
            // console.log('tickets:', tickets);

            if(!result.confirmedLocalExecution){
                alert('nft minted Session fails!');
                return;
            }
            console.log(result);
            

            // đoạn này là user claim
            let sessionIds =  result.objectChanges.filter((o) =>
                    o.type === "created" &&
                    o.objectType.includes("::ticket_collection::NFTSession")
            ).map(item => item.objectId);
            console.log('sessionIds',sessionIds);
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
                $('.itemSessionDetailMint').eq(index).find('.nft_uri_session').val(`https://suiscan.xyz/testnet/tx/${result.digest}`);
                $('.itemSessionDetailMint').eq(index).find('.nft_res_session').val(result);
                $('.itemSessionDetailMint').eq(index).find('.image-file').val();
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
            <ConnectButton label={'Connect Wallet'} />
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
