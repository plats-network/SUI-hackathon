import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect, useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";
// import TimerComponent from 'timerComponent';
import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';
import dotenv from "dotenv";

const packageId =  $('meta[name="package_id"]').attr('content');
const ticketCollectionType = `${packageId}::ticket_collection::TicketCollection`;
const NFTTicketType = `${packageId}::ticket_nft::NFTTicket`;
const NFTSessionType = `${packageId}::ticket_nft::NFTSession`;
const NFTBoothType = `${packageId}::ticket_nft::NFTBooth`;
const CoinType = "0x2::sui::SUI";
let typenetwork = $('meta[name="type_network"]').attr('content');

export default function App() {

    const wallet = useWallet();
    const client = new SuiClient({

        url: getFullnodeUrl(typenetwork),
    });
    const [sessionData, setSessionData] = useState([]);

    const handleClick = async () => {
        console.log('packageId',packageId)
        console.log('wallet',wallet);
        createEvent();
    }
   
    const createEvent = async () =>{
        
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

        console.log("create ticket tx", JSON.stringify(txs, null, 2));

        const ticketCollectionId = (
            txs.objectChanges.filter(
              (o) =>
                o.type === "created" &&
                o.objectType.includes("::ticket_collection::EventTicket")
            )[0]
          ).objectId;
        localStorage.setItem('contract_event_id',ticketCollectionId);
        console.log(`ticket  id : ${ticketCollectionId}`);
        window.location.href = '/event-create';
    }
    const mint = async (wallet,data) => {
        
    }
    useEffect(() => {
        
        if(sessionData.length > 0){

            return mint(wallet,sessionData);
        }
    }, [sessionData]);

    return (
        <div className="App">
            <ConnectButton label={'Connect Wallet'} />
            <section>
            
                {wallet.status === "connected" && (
                    <>
                        <button id="btnCreateEvent" onClick={handleClick} type="button" className="btn btn-primary btn-rounded waves-effect waves-light mb-2 mt-2 me-2">Create Event</button>
                    </>
                )}
            </section>
        </div>
    );
}
