import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, {useState} from 'react';
import axios from "axios";


export default function lock() {
    const wallet = useWallet();
    const [isLoading, setIsLoading] = useState(false);
    const [isStatus, setIsStatus] = useState(true);
    const packageId = import.meta.env.VITE_PACKAGE_ID;
    const publisherId = import.meta.env.VITE_PUBLISHER_ID;
    const event_object_id = document.getElementById('event_object_id').value;
    const status = document.getElementById('status_0').value;
    // if (status === 1) {
    //     setIsStatus(true);
    // }else {
    //     setIsStatus(false);
    // }

    async function lock(event) {
        event.preventDefault();
        setIsLoading(true);

        const tx = new TransactionBlock();

        console.log('packageId', packageId, 'publisherId', publisherId, 'event_object_id', event_object_id);

        tx.moveCall({
            target: `${packageId}::client::lock_event`,
            arguments: [
                // ticket event id
                tx.object(event_object_id),
                // bật on = false , off = true
                tx.pure(true),
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



            console.log("lock event  tx", JSON.stringify(txs, null, 2));
            // setIsStatus(!isStatus);
            alert("Off event!");
            // const body = {
            //     event_object_id: ticketCollectionId,
            //     wallet_address: wallet_address,
            //     white_list: txs.confirmedLocalExecution
            // };
            // const res = await axios.post("/update-whitelist", body, {
            //     headers: {
            //         'accept': 'application/json',
            //         'Accept-Language': 'en-US,en;q=0.8',
            //         'Content-Type': 'application/json',
            //     }
            // });
            // setIsCreate(true);

        } catch (e) {
            alert("Oops, create ticket failed");
            console.error("create ticket failed", e);
        }
        setIsLoading(false);
    }

    return (
        <div className="App">
            <ConnectButton label={'Connect Wallet'}/>
            <section style={{marginTop: '15px', textAlign: 'left'}}>
                {wallet.status === "connected" && (
                    <>
                        <button className="btn btn-primary" onClick={lock}
                                style={{marginRight: '15px'}}> Off Event !
                        </button>
                    </>
                )}
            </section>

        </div>
    );
}
