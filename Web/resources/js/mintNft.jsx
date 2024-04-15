import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import NftInput from "./sui_components/nftInput";
import React, {useState} from 'react';

import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';
import NftItemMinted from "./sui_components/nftItemMinted";
import ReactDOM from "react-dom";

function createMintNftTxnBlock(data) {
    // define a programmable transaction block
    const txb = new TransactionBlock();

    // note that this is a devnet contract address
    const contractAddress =
        "0x3827b28d5f79b559cf7f9f545cbc99a2653e19d7c99173cec1a9428a478357f5";
    const contractModule = "client";
    const contractMethod = "mint_batch";


    const nftName = data.nft_name;
    const nftAmount = data.nft_amount;
    const nftCategory = data.nft_category;
    const nftDescription = data.nft_symbol;
    const nftImgUrl = data.image_file ?? "https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4";
    const nftCollectionId = "0x3b0b0833c020f964c09991796945efa46b4cd66af696df698ae9a41a75383819"

    txb.moveCall({
        target: `${contractAddress}::${contractModule}::${contractMethod}`,
        arguments: [
            // collection object id
            txb.object(nftCollectionId),
            // name: vector<u8>,
            txb.pure(nftName),
            // description: vector<u8>,
            txb.pure(nftDescription),
            // url: vector<u8>,
            txb.pure(nftImgUrl),
            // catogory: vector<u8>,
            txb.pure(nftCategory),
            // max_supply: u64,
            txb.pure(nftAmount),
        ],
        typeArguments: [`${contractAddress}::ticket_collection::NFTTicket`]

    });

    return txb;
}

export default function MintNft({nftData, _setMinted}) {
    const wallet = useWallet();
    const [nftInputs, setNftInputs] = useState([]);
    const mnemonic_client = $('#mnemonic_client').val();
    const collection_id = $('#collection_id').val();
    const [isLoading, setIsLoading] = useState(false);
    console.log('------', nftInputs);

    async function mintNft(event) {
        event.preventDefault();
        if (!wallet.connected) return;
        setIsLoading(true);
        for (let i = 0; i < nftData.length; i++) {
            const txb = createMintNftTxnBlock(nftData[i]);
            try {
                const res = await wallet.signAndExecuteTransactionBlock({
                    transactionBlock: txb,
                    requestType: 'WaitForLocalExecution',
                });
                alert("Congrats! your nft is minted!");
                console.log("nft minted successfully!", res);

                const keypair = Ed25519Keypair.deriveKeypair(mnemonic_client);
                const client = new SuiClient({
                    url: getFullnodeUrl('testnet'),
                });
                let addressClient = keypair.getPublicKey().toSuiAddress();

                const allObjects = await client.getOwnedObjects({
                    owner: addressClient,
                    options: {
                        showType: true,
                        showDisplay: true,
                        showContent: true,
                    }
                });


                //console.log("objectIDs", allObjects.data[0]);
                const objectIDs = (allObjects?.data || [])
                    .filter((item) => item.data.objectId == collection_id)
                    .map((anObj) => anObj.data.objectId);

                const allObjRes = await client.multiGetObjects({
                    ids: objectIDs,
                    options: {
                        showContent: true,
                        showDisplay: true,
                        showType: true,
                    },
                });
                const nftList = allObjRes.filter(obj => obj.data).map(obj => ({
                    objectId: obj.data.objectId,
                    data: obj.data.content.fields,

                }));
                //get ticket

                const tickets = nftList.map((data) => data.data.tickets);
                console.log('tickets:',tickets);

                // Add a new NftInput for each successful mint
                for (let j = 0; j < Number(nftData[i].nft_amount); j++) {
                    // setNftInputs(prevInputs => [...prevInputs, nftData[i]]);
                    setNftInputs(prevInputs => [...prevInputs, {
                        ...nftData[i],
                        res: JSON.stringify(res),
                        tickets: JSON.stringify(tickets)
                    }]);
                }
                console.log(nftInputs);
                _setMinted({...nftData[i], res: JSON.stringify(res)});


            } catch (e) {
                alert("Oops, nft minting failed");
                console.error("nft mint failed", e);
            }
        }
        setIsLoading(false);
        document.getElementById("append-nft-ticket").innerHTML = "";

    }

    return (
        <div className="App">
            <ConnectButton/>
            <section style={{marginTop: '15px', textAlign: 'right'}}>
                {wallet.status === "connected" && (
                    <>
                        <button className="btn btn-primary" onClick={mintNft}> Mint Your NFT !</button>
                    </>
                )}
            </section>
            {isLoading && <div className={'loading'}></div>}
            {nftInputs.map((nftData, index) => <NftInput key={index} nftData={nftData}/>)}
        </div>
    );
}
