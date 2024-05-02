import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, {useState} from 'react';
import axios from "axios";


export default function add() {
    const wallet = useWallet();
    const [isLoading, setIsLoading] = useState(false);
    const [isCreate, setIsCreate] = useState(false);
    const packageId = import.meta.env.VITE_PACKAGE_ID;
    const publisherId = import.meta.env.VITE_PUBLISHER_ID;
    const event_object_id = document.getElementById('event_object_id').value;
    const user_id = document.getElementById('user_id').value;

    // setIsCreate(event_object_id !== '');

    async function createTicket(event) {
        event.preventDefault();
        setIsLoading(true);
        const wallet_address = document.getElementById('wallet_address').value;

        const tx = new TransactionBlock();

        console.log('packageId', packageId, 'publisherId', publisherId, 'wallet_address', wallet_address);

        tx.moveCall({
            target: `${packageId}::ticket_collection::create_event`,
            arguments: [
                tx.pure(publisherId),
                // địa chỉ của organizer để có thể tạo nft ticket, lock event, session
                tx.pure(wallet_address)
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


            console.log("create ticket tx", JSON.stringify(txs, null, 2));

            const ticketCollectionId = (
                txs.objectChanges.filter(
                    (o) =>
                        o.type === "created" &&
                        o.objectType.includes("::ticket_collection::EventTicket")
                )[0]
            ).objectId;
            console.log(`ticket  id : ${ticketCollectionId}`);
            alert("Create ticket success!");
            const body = {
                id: user_id,
                event_object_id: ticketCollectionId,
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
                        <button className="btn btn-primary" onClick={createTicket}
                                style={{marginRight: '15px'}}> Create
                            Ticket !
                        </button>
                    </>
                )}
            </section>
            {isLoading && <div className={'loading'}></div>}

        </div>
    );
}
