import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect, useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";
// import TimerComponent from 'timerComponent';

import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';

// Hàm để cắt mảng thành các mảng con
function chunkArray(array, numChunks){
    let result = [];
    const chunkSize = Math.ceil(array.length / numChunks);
    for (let i = 0; i < array.length; i += chunkSize) {
        result.push(array.slice(i, i + chunkSize));
    }
    return result;
}

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
            const fileSession = $(this).find('.image-file').attr('link-img') ?? window.location.origin+'/imgs/defaultsession.png';
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

        let typenetwork = $('meta[name="type_network"]').attr('content');

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
            html += '        <p class="class-ticket"><a target="_blank" href="https://suiscan.xyz/'+typenetwork+'/tx/' + detail.txhash + '">txhash</a></p>\n';
            html += '    </div>\n';
            html += '</div>';
        });

        // Sau khi lặp qua mảng details, thêm chuỗi HTML vào '.append-nft-session-detail'
        $('.append-nft-session-detail').empty().append(html);
    };
    
    const hasDuplicateNFTDescriptionSession = async(data) =>{

        // Create a set to store unique NFT names encountered
        const uniqueNames = new Set();
    
        // Iterate through the data array
        for (const nft of data) {
    
            const nftName = nft.descriptionSession;
    
            // Check if the name already exists in the set
            if (uniqueNames.has(nftName)) {
                uniqueNames.clear();
                return true; // Duplicate found, return true
            }
    
            // Add the name to the set if not found
            uniqueNames.add(nftName);
        }
    
        // No duplicates found, return false
        return false;
    }

    const hasDuplicateNFTNameSession = async(data) =>{

        // Create a set to store unique NFT names encountered
        const uniqueNames = new Set();
    
        // Iterate through the data array
        for (const nft of data) {
    
            const nftName = nft.nameSession;
    
            // Check if the name already exists in the set
            if (uniqueNames.has(nftName)) {
                uniqueNames.clear();
                return true; // Duplicate found, return true
            }
    
            // Add the name to the set if not found
            uniqueNames.add(nftName);
        }
    
        // No duplicates found, return false
        return false;
    }


    const mint = async (wallet,data) => {

  
        // const hasDuplicateNFTName = hasDuplicateNFTNameSession(data);
        
        // if (hasDuplicateNFTName) {
        //     alert("Data NFTNameSession is duplicated, please check again!");
        //     return;
        // }

        // const hasDuplicateNFTDescription = hasDuplicateNFTDescriptionSession(data);

        // if (hasDuplicateNFTDescription) {
        //     alert("Data NFTDescriptionSession is duplicated, please check again!");
        //     return;
        // }

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

                tx.pure(totalNftTicket),
              
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
            console.log('result',result);
            console.log('data',data);
            const sessionCollectionIds = result.objectChanges.filter(
                    (o) =>
                        o.type === "created" &&
                        o.objectType.includes("::ticket_collection::SessionCollection")
                ).map(item => item.objectId);
    
            console.log(`Sessions collection id :`,sessionCollectionIds);

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

            // Số mảng con bạn muốn tạo
            const numChunks = data.length;

            // Gọi hàm để cắt mảng
            let newArray = chunkArray(sessionIds, numChunks);

            let newArraySessionCollectionIds = chunkArray(sessionCollectionIds, numChunks);

            console.log('newArray',newArray);
            console.log('newArraySessionCollectionIds',newArraySessionCollectionIds);
            // Lặp qua mỗi đối tượng trong mảng data
            data.forEach((obj, index) => {
                console.log('line 164',obj);
                console.log('index line: 165',index);
                // Thêm trường 'hash' với giá trị '123' vào mỗi đối tượng
                obj.txhash = result.digest;

                //mint lại data
                $('.itemSessionDetailMint').eq(index).find('.nft_address_session').val(sessionIds[index]);
                $('.itemSessionDetailMint').eq(index).find('.nft_uri_session').val(obj.fileSession);
                $('.itemSessionDetailMint').eq(index).find('.nft_amount_session').val(totalNftTicket);
                $('.itemSessionDetailMint').eq(index).find('.nft_digest_session').val(result.digest);
                $('.itemSessionDetailMint').eq(index).find('.nft_res_session').val(JSON.stringify(newArray[index]));
                $('.itemSessionDetailMint').eq(index).find('.image-file').val();
                $('.itemSessionDetailMint').eq(index).find('.nft_contract_task_events_details_id').val(newArraySessionCollectionIds[index]);

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
        // <div className="App">
            /* <ConnectButton label={'Connect Wallet'} /> */
            <section>
                {wallet.status === "connected" ? (
                    <button id="btnGenItemSession" onClick={handleClick} type="button" className="btn btn-primary btn-rounded waves-effect waves-light mb-2 mt-2 me-2">Generate Session</button>
                ) : (
                    <p>Please login to mint your Session.</p>
                )}
                {/* {wallet.status === "connected" && (
                    <>
                        <p>
                            <button id="btnGenItemSession" onClick={handleClick} type="button" className="btn btn-primary btn-rounded waves-effect waves-light mb-2 mt-2 me-2">Generate Session</button>
                        </p>
                    </>
                )} */}
            </section>
        // </div>
    );
}
