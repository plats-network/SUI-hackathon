import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, {useState} from 'react';
import axios from "axios";


export default function add() {
    const wallet = useWallet();
    const [isLoading, setIsLoading] = useState(false);

    async function add(event) {
        event.preventDefault();
        const tx = new TransactionBlock();
        let packageId = import.meta.env.VITE_PACKAGE_ID;
        const collectionId = import.meta.env.VITE_COLLECTION_ID;
        const publisherId = import.meta.env.VITE_PUBLISHER_ID;
        const wallet_address = document.getElementById('wallet_address').value;
        const user_id = document.getElementById('user_id').value;
        setIsLoading(true);

        tx.moveCall({
            target: `${packageId}::admin::batch_add_client`,
            arguments: [
                // ticket event id
                tx.object(collectionId),
                // clients address
                tx.pure([wallet_address]),
                // publisher id -> hiện tại giữ nguyên hey
                tx.pure(publisherId)
            ],

        });
        try {
            const txs = await wallet.signAndExecuteTransactionBlock({
                transactionBlock: tx,
                options: {
                    showInput: true,
                    showEffects: true,
                    showEvents: true,
                    showObjectChanges: true,
                },
            });

            console.log("add client  tx", JSON.stringify(txs, null, 2));
            alert("Add white list success!");


            const body = {
                id: user_id,
                wallet_address: wallet_address,
                white_list: txs.confirmedLocalExecution
            };
            const res = await axios.post("/update-whitelist", body, {
                headers: {
                    'accept': 'application/json',
                    'Accept-Language': 'en-US,en;q=0.8',
                    'Content-Type': 'application/json',
                }
            });
        } catch (e) {
            alert("Oops, add white list failed");
            console.error("add white list failed", e);
        }
        setIsLoading(false);


    }

    return (
        <div className="App">
            <ConnectButton label={'Connect Wallet'}/>
            <section style={{marginTop: '15px', textAlign: 'left'}}>
                {wallet.status === "connected" && (
                    <>
                        <button className="btn btn-primary" onClick={add}> Add Client !</button>
                    </>
                )}
            </section>
            {isLoading && <div className={'loading'}></div>}

        </div>
    );
}
