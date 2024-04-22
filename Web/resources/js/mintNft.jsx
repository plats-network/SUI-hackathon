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
    const contractAddress = import.meta.env.VITE_PACKAGE_ID;
    const contractModule = "client";
    const contractMethod = "mint_batch";


    const nftName = data.nft_name;
    const nftAmount = data.nft_amount;
    const nftCategory = data.nft_category;
    const nftDescription = data.nft_symbol;
    const nftImgUrl = data.image_file ?? "https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4";
    const nftCollectionId = import.meta.env.VITE_COLLECTION_ID;
    console.log('contractAddress :', contractAddress, 'nftCollectionId :', nftCollectionId);

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

export default function mintNft({nftData, _setMinted, nftMinted, setNftData, setItems, items}) {
    const wallet = useWallet();
    const [nftInputs, setNftInputs] = useState([]);
    const mnemonic_client = $('#mnemonic_client').val();
    const collection_id = $('#collection_id').val();
    const [isLoading, setIsLoading] = useState(false);
    const datas = JSON.parse(JSON.stringify(nftData));
    const itemCopy = JSON.parse(JSON.stringify(items));


    async function mintNft(event) {
        event.preventDefault();
        if (!wallet.connected) return;
        setIsLoading(true);
        const nftMints = [];
        const newItems = [];
        const newDatas = [];
        for (let i = 0; i < nftData.length; i++) {
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



                // Add a new NftInput for each successful mint
                // let res = [];
                // let ticketIds = ['0x5432c747a8b536adbc2152958833e50d4aa8727fdfbd531be21960be1b06d565'];
                for (let j = 0; j < Number(nftData[i].nft_amount); j++) {
                    setNftInputs(prevInputs => [...prevInputs, {
                        ...nftData[i],
                        res: JSON.stringify(res),
                        tickets: ticketIds[j]
                    }]);
                }
                nftMints.push({...nftData[i], res: JSON.stringify(res)});
                newItems.push(nftData[i].nft_id);
                newDatas.push({...nftData[i]});
            } catch (e) {
                alert("Oops, nft minting failed");
                console.error("nft mint failed", e);
            }
        }
        _setMinted([...nftMinted, ...nftMints]);
        let difference = itemCopy.filter(x => !newItems.includes(x));
        const differenceData = datas.filter(nftItem =>
            !newDatas.some(dataItem => dataItem.nft_id === nftItem.nft_id)
        );
        setNftData([...differenceData]);
        setItems([...difference]);
        setNftData([...differenceData]);

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
