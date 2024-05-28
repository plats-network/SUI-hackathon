import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect, useState, useCallback } from 'react';
import { TransactionBlock } from "@mysten/sui.js/transactions";
import { Ed25519Keypair } from "@mysten/sui.js/keypairs/ed25519";
import { getFullnodeUrl, SuiClient } from '@mysten/sui.js/client';

function chunkArray(array, numChunks) {
    let result = [];
    const chunkSize = Math.ceil(array.length / numChunks);
    for (let i = 0; i < array.length; i += chunkSize) {
        result.push(array.slice(i, i + chunkSize));
    }
    return result;
}

function createMintTxnBlock(data, totalNftTicket, contract_event_id, event_id) {
    let packageId = $('meta[name="package_id"]').attr('content');
    let tx = new TransactionBlock();
    tx.moveCall({
        target: `${packageId}::client::mint_batch_sessions`,
        arguments: [
            tx.pure(contract_event_id),
            tx.pure(event_id),
            tx.pure([data.nameSession]),
            tx.pure([data.descriptionSession]),
            tx.pure([data.fileSession]),
            tx.pure(totalNftTicket),
        ],
    });
    return tx;
}

function hasDuplicateNFTDescriptionSession(data) {
    const uniqueNames = new Set();
    for (const nft of data) {
        const nftName = nft.descriptionSession;
        if (uniqueNames.has(nftName)) {
            return true;
        }
        uniqueNames.add(nftName);
    }
    return false;
}

function hasDuplicateNFTNameSession(data) {
    const uniqueNames = new Set();
    for (const nft of data) {
        const nftName = nft.nameSession;
        if (uniqueNames.has(nftName)) {
            return true;
        }
        uniqueNames.add(nftName);
    }
    return false;
}

export default function App() {
    const wallet = useWallet();
    const [sessionData, setSessionData] = useState([]);

    const handleClick = async () => {
        const totalNftTicket = $('input[name^="nft-ticket-name-"]').length;
        if (totalNftTicket <= 0) {
            alert('Please mint NFT ticket first!');
            return;
        }

        const newSessionData = [];
        $('.itemSessionDetailMint').each(function (detail, index) {
            const nameSession = $(this).find('.name_session').val();
            const descriptionSession = $(this).find('.description_session').val();
            const fileSession = $(this).find('.image-file').attr('link-img') || `${window.location.origin}/imgs/defaultsession.png`;
            const mintftSession = $(this).find('.mintft_session').val();

            if(!nameSession){
                alert(`Error: nameSession is required for item`);
                return;
            }

            if(!descriptionSession){
                alert(`Error: description_session is required for item `);
                return;
            }
            if(nameSession && descriptionSession){
               
                newSessionData.push({ nameSession, descriptionSession, fileSession, mintftSession, totalNftTicket });
            }
        });
        
        if (newSessionData.length > 0) {
            setSessionData(newSessionData);
        }
    };

    const appendNftSessionDetail = async (details, listDigest) => {
        const typenetwork = $('meta[name="type_network"]').attr('content');
        let html = '';
        details.forEach((detail, index) => {
            html += `
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="image-file">
                            <img class="img-preview img-preview-nft" src="${detail.fileSession}">
                        </label>
                    </div>
                    <div class="col-6">
                        <div class="col-10 mt-25">
                            <p class="class-ticket">${detail.nameSession}</p>
                        </div>
                        <div class="col-10 mt-20">
                            <p class="class-ticket">${detail.descriptionSession}</p>
                        </div>
                    </div>
                    <div class="col-2" style="margin-top: 50px">
                        <p class="class-ticket"><a target="_blank" href="https://suiscan.xyz/${typenetwork}/tx/${listDigest[index]}">txhash</a></p>
                    </div>
                </div>`;
        });
        $('.append-nft-session-detail').empty().append(html);
    };

    const mint = async (wallet, data) => {
        for (const [index, nft] of data.entries()) {
            if (!nft.nameSession || nft.nameSession.trim() === "") {
                alert(`Error: nameSession is required for item at index ${index + 1}`);
                return;
            }
            if (!nft.descriptionSession || nft.descriptionSession.trim() === "") {
                alert(`Error: nft_symbol is required for item at index ${index + 1}`);
                return;
            }
        }

        if (hasDuplicateNFTNameSession(data)) {
            alert("Data NFTNameSession is duplicated, please check again!");
            return;
        }

        if (hasDuplicateNFTDescriptionSession(data)) {
            alert("Data NFTDescriptionSession is duplicated, please check again!");
            return;
        }

        const contract_event_id = localStorage.getItem("contract_event_id");
        const event_id = $('meta[name="nft_hash_id"]').attr('content');
        const totalNftTicket = $('input[name^="nft-ticket-name-"]').length;

        $('.loading').show();
        let sessionsCollectionIds = [];
        let listSessionIds = [];
        let listDigest = [];

        for (const nft of data) {
            try {
                const tx = createMintTxnBlock(nft, totalNftTicket, contract_event_id, event_id);
                const result = await wallet.signAndExecuteTransactionBlock({
                    transactionBlock: tx,
                    options: { showObjectChanges: true },
                });

                if (!result.confirmedLocalExecution) {
                    alert('NFT minting session failed!');
                    return;
                }

                listDigest.push(result.digest);

                const sessionCollectionIds = result.objectChanges.filter(
                    o => o.type === "created" && o.objectType.includes("::ticket_collection::SessionCollection")
                ).map(item => item.objectId);

                const sessionIds = result.objectChanges.filter(
                    o => o.type === "created" && o.objectType.includes("::ticket_collection::NFTSession")
                ).map(item => item.objectId);

                listSessionIds.push(sessionIds);
                sessionsCollectionIds.push(sessionCollectionIds[0]);

            } catch (error) {
                $('.loading').hide();
                console.error('Error:', error);
                alert('NFT minting session failed!');
            }
        }

        data.forEach((obj, index) => {
            const element = $('.itemSessionDetailMint').eq(index);
            element.find('.nft_address_session').val(sessionsCollectionIds[index]);
            element.find('.nft_uri_session').val(obj.fileSession);
            element.find('.nft_amount_session').val(totalNftTicket);
            element.find('.nft_digest_session').val(listDigest[index]);
            element.find('.nft_res_session').val(JSON.stringify(listSessionIds[index]));
            element.find('.nft_contract_task_events_details_id').val(sessionsCollectionIds[index]);
        });

        appendNftSessionDetail(data, listDigest);
        $('.loading').hide();
        alert('NFT minting session successfully!');
    };

    useEffect(() => {
        if (sessionData.length > 0) {
            mint(wallet, sessionData);
        }
    }, [sessionData]);

    return (
        <div className="App">
            {wallet.status === "connected" ? (
                <button id="btnGenItemSession" onClick={handleClick} type="button" className="btn btn-primary btn-rounded waves-effect waves-light mb-2 mt-2 me-2">
                    Generate Session
                </button>
            ) : (
                <p>Please login to mint your session.</p>
            )}
        </div>
    );
}
