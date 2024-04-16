import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import NftInput from "./sui_components/nftInput";
import React, {useState} from 'react';

import ReactDOM from "react-dom";

function createMintNftTxnBlock(data) {
    // define a programmable transaction block
    const txb = new TransactionBlock();

    // note that this is a devnet contract address
    const contractAddress =
        "0x5ff08c4a46f0e68e9677f6be420b6adf9f0fc90355f978ea235173fffc061a5c";
    const contractModule = "client";
    const contractMethod = "mint_batch";


    const nftName = data.nft_name;
    const nftAmount = data.nft_amount;
    const nftCategory = data.nft_category;
    const nftDescription = data.nft_symbol;
    const nftImgUrl = data.image_file ?? "https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4";
    const nftCollectionId = "0x2587305d59dbcc09406e1ef0147053fff3019a64aca312108adac2913785a6d0"

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

export default function MintNft({nftData, _setMinted, setNftData, setItems, items}) {
    const wallet = useWallet();
    const [nftInputs, setNftInputs] = useState([]);
    const mnemonic_client = $('#mnemonic_client').val();
    const collection_id = $('#collection_id').val();
    const [isLoading, setIsLoading] = useState(false);
    const datas = JSON.parse(JSON.stringify(nftData));
    const itemCopy = JSON.parse(JSON.stringify(items));
    console.log('datas:', datas);

    console.log('nftInputs', nftInputs);

    async function mintNft(event) {
        event.preventDefault();
        if (!wallet.connected) return;
        setIsLoading(true);
        const nftMints = [];
        for (let i = 0; i < datas.length; i++) {
            const txb = createMintNftTxnBlock(nftData[i]);
            try {
                const res = await wallet.signAndExecuteTransactionBlock({
                    transactionBlock: txb,
                    options: {
                        showObjectChanges: true,
                    },
                });
                alert("Congrats! your nft is minted!");
                console.log("nft minted successfully!", res);

                const ticketIds =
                    res.objectChanges.filter(
                        (o) =>
                            o.type === "created" &&
                            o.objectType.includes("::ticket_collection::NFTTicket")
                    ).map(item => item.objectId);
                console.log('ticketIds :', ticketIds);
                // let res = [];
                // let tickets = [];
                // Add a new NftInput for each successful mint
                for (let j = 0; j < Number(nftData[i].nft_amount); j++) {
                    // setNftInputs(prevInputs => [...prevInputs, nftData[i]]);
                    setNftInputs(prevInputs => [...prevInputs, {
                        ...nftData[i],
                        res: JSON.stringify(res),
                        tickets: ticketIds[i]
                    }]);
                }
                nftMints.push({...nftData[i], res: JSON.stringify(res)});
                // setItems(items.filter((item, j) => item !== nftData[i].nft_id));
                // setNftData(nftData.filter(item => item.nft_id !== nftData[i].nft_id));
                // console.log('nftData after', nftData);
                // _setMinted({...nftData[i], res: JSON.stringify(res)});
                // datas.splice(i, 1);
                // itemCopy.splice(i, 1);
                // if (datas.length === 0) {
                //     break;
                // }
                // i --;
                // alert('success');
            } catch (e) {
                alert("Oops, nft minting failed");
                console.error("nft mint failed", e);
            }
        }
        _setMinted([...nftMints]);
        // setItems([...itemCopy]);
        // setNftData([...datas]);

        setIsLoading(false);

    }

    return (
        <div className="App">
            <ConnectButton label={'Connect Wallet'}/>
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
