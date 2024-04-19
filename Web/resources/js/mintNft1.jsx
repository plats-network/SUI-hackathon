import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import NftInput from "./sui_components/nftInput";
import React, {useState} from 'react';
import ReactDOM from "react-dom";


export default function MintNft1({nftData, _setMinted, nftMinted, setNftData, setItems, items}) {
    const wallet = useWallet();
    const [nftInputs, setNftInputs] = useState([]);
    const mnemonic_client = $('#mnemonic_client').val();
    const collection_id = $('#collection_id').val();
    const [isLoading, setIsLoading] = useState(false);
    const datas = JSON.parse(JSON.stringify(nftData));
    const itemCopy = JSON.parse(JSON.stringify(items));
    const contractAddress =
        "0xc4ad98ab991591aebb1c72cfd6080d8706d5c819bda9572f009be3d35b799023";
    const contractModule = "client";
    const contractMethod = "mint_batch_sessions";
    // const collectionId = "0x2587305d59dbcc09406e1ef0147053fff3019a64aca312108adac2913785a6d0";
    async function mintNft(event) {
        event.preventDefault();
        let txs = [];
        const tx = new TransactionBlock();
        tx.moveCall({
            target: `${contractAddress}::${contractModule}::${contractMethod}`,
            arguments: [
                // collection object id
                //tx.object('0x0d6422b82f418e592546019b81585963300f2f29acb86a281e5add34f3388c7d'),
                // tx.object(collectionId),
                // event_id
                tx.pure("8ba9148d4e85e4a6862e8fa613f6cf6b"),
                // name: vector<u8>,
                tx.pure("SUI Hackathon"),
                // description: vector<u8>,
                tx.pure("This is a ticket yo join SUI Hackathon"),
                // url: vector<u8>,
                tx.pure("https://picsum.photos/id/237/200/300"),
                // catogory: vector<u8>,
                tx.pure("Standard"),
                // max_supply: u64,
                tx.pure(1),
            ],
            // typeArguments: [`${contractAddress}::ticket_collection::NFTTicket`]
        });
        txs.push(tx);
        const tx2 = new TransactionBlock();
        tx2.moveCall({
            target: `${contractAddress}::${contractModule}::${contractMethod}`,
            arguments: [
                // collection object id
                //tx.object('0x0d6422b82f418e592546019b81585963300f2f29acb86a281e5add34f3388c7d'),
                // tx2.object(collectionId),
                // event_id
                tx.pure("8ba9148d4e85e4a6862e8fa613f6cf6b"),
                // name: vector<u8>,
                tx2.pure("SUI Hackathon 1"),
                // description: vector<u8>,
                tx2.pure("This is a ticket VIP yo join SUI Hackathon"),
                // url: vector<u8>,
                tx2.pure("https://picsum.photos/id/237/200/300"),
                // catogory: vector<u8>,
                tx2.pure("VIP"),
                // max_supply: u64,
                tx2.pure(1),
            ],
            // typeArguments: [`${contractAddress}::ticket_collection::NFTTicket`]
        });

        txs.push(tx2);

        for (const tx of txs) {
            const result = await wallet.signAndExecuteTransactionBlock({
                transactionBlock: tx,
                options: {
                    showObjectChanges: true,
                },
                //requestTypes:'WaitForEffectsCert'
            });

            console.log({ result });
            const ticketIds =
                result.objectChanges.filter(
                    (o) =>
                        o.type === "created" &&
                        o.objectType.includes("::ticket_collection::NFTTicket")
                ).map(item => item.objectId);
            console.log(`ticket id : ${ticketIds}`);
        }


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
        </div>
    );
}
