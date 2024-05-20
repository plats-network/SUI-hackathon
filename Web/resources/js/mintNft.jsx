import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import NftInput from "./sui_components/nftInput";
import React, {useState} from 'react';
import ReactDOM from "react-dom";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';
import { atom,useSetRecoilState,useRecoilState  } from 'recoil';

function createMintNftTxnBlock(data) {
    // define a programmable transaction block
    const txb = new TransactionBlock();

    // note that this is a devnet contract address
    const contractAddress = import.meta.env.VITE_PACKAGE_ID;
    const contractModule = "client";
    const contractMethod = "mint_batch_tickets";

    const nftName = data.nft_name;
    const nftAmount = data.nft_amount;
    const nftCategory = data.nft_category;
    const nftDescription = data.nft_symbol;
    const nftImgUrl = data.image_file ?? window.location.origin+'/imgs/defaulticket.png';
    // const nftCollectionId = import.meta.env.VITE_COLLECTION_ID;
    const nftCollectionId = $('#event_object_id').val();
    const event_hash_id = $('meta[name="nft_hash_id"]').attr('content');
    const contract_event_id = localStorage.getItem("contract_event_id");

    txb.moveCall({
        target: `${contractAddress}::${contractModule}::${contractMethod}`,
        arguments: [
            // collection object id
            txb.pure(contract_event_id),
            // event_id
            txb.pure(event_hash_id),
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
        // typeArguments: [`${contractAddress}::ticket_collection::NFTTicket`]

    });

    return txb;
}

function hasDuplicateNFTName(data) {

    // Create a set to store unique NFT names encountered
    const uniqueNames = new Set();

    // Iterate through the data array
    for (const nft of data) {

        const nftName = nft.nft_name;

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

function hasDuplicateNFTDescription(data) {

    // Create a set to store unique NFT names encountered
    const uniqueNames = new Set();

    // Iterate through the data array
    for (const nft of data) {

        const nftName = nft.nft_symbol;

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

export default function mintNft({nftData, _setMinted, nftMinted, setNftData, setItems, items}) {

    const wallet = useWallet();

    const mnemonic_client = import.meta.env.VITE_MNEMONIC_CLIENT;
    const [nftInputs, setNftInputs] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    let typenetwork = $('meta[name="type_network"]').attr('content');
    const datas = JSON.parse(JSON.stringify(nftData));
    const itemCopy = JSON.parse(JSON.stringify(items));
    const [totalMin, setTotalMin] = useState(0);
    

    async function mintNftTicket(event) {
       

        console.log('datas',datas);
        console.log('wallet file mint.js',wallet);

        const resultDuplicateNftName = hasDuplicateNFTName(datas);
        const resultDuplicateNftDescription = hasDuplicateNFTDescription(datas);

        if (resultDuplicateNftName) {
            setIsLoading(false);
            alert("Data NftName is duplicated, please check again!");
            return;
        }

        if (resultDuplicateNftDescription) {
            setIsLoading(false);
            alert("Data NftDescription is duplicated, please check again!");
            return;
        }

        event.preventDefault();
        setIsLoading(true);
        if (!wallet.connected){
            alert("Please connect your wallet first");
            setIsLoading(false);
            return;
        
        };
        const nftMints = [];
        const newItems = [];
        const newDatas = [];
        if(datas.length === 0) {
            alert('No NFT to mint');
            setIsLoading(false);
            return;
        }


        //nếu tootal min = 0 là click mint lần đầu tiên thì sẽ tạo ticket_collectionId
        if(totalMin == 0){
            let tx = new TransactionBlock();

            let packageId = $('meta[name="package_id"]').attr('content');

            tx.moveCall({
                target: `${packageId}::ticket_collection::create_event`,
                arguments: [
                    //tx.pure(process.env.PUBLISHER_ID),
                    // địa chỉ của organizer để có thể tạo nft ticket, lock event, session
                    tx.pure(wallet.address)
                ],
            });

            const txs  = await wallet.signAndExecuteTransactionBlock({
                transactionBlock: tx,
                options: {
                    showInput: true,
                    showEffects: true,
                    showEvents: true,
                    showObjectChanges: true,
                },
            });

            console.log("create ticket tx", txs);

            const ticketCollectionId = (
                txs.objectChanges.filter(
                    (o) =>
                        o.type === "created" &&
                        o.objectType.includes("::ticket_collection::EventTicket")
                )[0]
            ).objectId;
            console.log(`ticket  id : `,ticketCollectionId);
  

            if(!ticketCollectionId || ticketCollectionId == "" || ticketCollectionId == undefined){
                alert('Failed to create ticket collection');
                setIsLoading(false);
                return;
            }
            localStorage.setItem('contract_event_id',ticketCollectionId);

            setTotalMin(1);
        }

        for (let i = 0; i < nftData.length; i++) {
            try {
                const txb = createMintNftTxnBlock(nftData[i]);

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
                for (let j = 0; j < Number(nftData[i].nft_amount); j++) {
                    setNftInputs(prevInputs => [...prevInputs, {
                        ...nftData[i],
                        res: res.digest,
                        tickets: ticketIds[j]
                    }]);
                }
                console.log('nftData line 152',nftData);
                nftMints.push({...nftData[i], res: res.digest, address_nft:JSON.stringify(ticketIds)});
                newItems.push(nftData[i].nft_id);
                newDatas.push({...nftData[i]});
            } catch (e) {
                alert("Oops, nft minting failed");
                console.error("nft mint failed", e);
                setIsLoading(false);
            }
        }
        console.log('nftMints line 161',nftMints);
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
            {/* <ConnectButton label={'Connect Wallet'}/> */}
            <section style={{marginTop: '15px', textAlign: 'right'}}>
                {wallet.status === "connected" ? (
                    <button className="btn btn-primary" onClick={mintNftTicket}> Mint Your NFT !</button>
                ) : (
                    <p>Please login to mint your NFT.</p>
                )}
            </section>
            {isLoading && <div className={'loading'}></div>}
            {nftInputs.map((nftData, index) => <NftInput key={index} nftData={nftData}/>)}
        </div>
    );
}
