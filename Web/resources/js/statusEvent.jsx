import { createRoot } from "react-dom/client";
import ReactDOM from 'react-dom';
import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import { WalletProvider } from '@suiet/wallet-kit';
import '@suiet/wallet-kit/style.css';
import App from './useWalletSession';
import React, { useEffect, useState } from 'react';
// import App from './mintNft';
import { TransactionBlock } from "@mysten/sui.js/transactions";


export default function StatusEvent({ payload }) {

    const wallet = useWallet();
    const [valueCheckBox, setValueCheckBox] = useState(payload.status)

    const lockEvent = async (packageId, contract_event_id, status) => {

        console.log('wallet', wallet);
        let tx = new TransactionBlock();

        tx.moveCall({
            target: `${packageId}::client::lock_event`,
            arguments: [
                // ticket event id 
                tx.object(contract_event_id),
                // bật on = false , off = true 

                tx.pure(status),
            ],

        });

        try {
            $('.loading').show();

            const txs = await wallet.signAndExecuteTransactionBlock({
                transactionBlock: tx,
                options: {
                    showInput: true,
                    showEffects: true,
                    showEvents: true,
                    showObjectChanges: true,
                },
            });
            $.ajax({
                url: '/event-status/'+ payload.eventid,
                type: 'GET',
                dataType: 'json',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    setValueCheckBox((prev) => !prev)
                    console.log(data);
                    $.notify("Success.", "success");
                    location.reload();
                },
                error: function (data) {
                    console.log(data);
                    $.notify("Success", "error");
                    location.reload();
                }
            });
            console.log("lock event  tx", txs);
            $('.loading').hide();

        } catch (error) {
            console.log('error', error);
            alert(error);
            $('.loading').hide();

        }
    }


    const handleChange = (e) => {

        // setValueCheckBox((prev) => !prev)
        // const id_session = payload.nft_session_id;

    }

    const handleClick = async () => {
        console.log('payload', payload);
        const contract_event_id = payload.contracteventid;
        const packageId = $('meta[name="package_id"]').attr('content');
        lockEvent(packageId, contract_event_id, valueCheckBox);
    }

    return (
        <div>
            <input
                type="checkbox"
                className="jobSwitch"
                switch="none"
                checked={valueCheckBox}
                id={payload.switchid}
                onChange={handleChange}
                value={valueCheckBox}
            />
            <label className="event"
                htmlFor={payload.switchid}
                onClick={handleClick}
                data-on-label="On"
                data-off-label="Off">
            </label>
        </div>
    );
}

// Lấy tất cả các phần tử có class name 'statusEvent'
const statusEventElements = document.getElementsByClassName('statusEvent');

// Lặp qua từng phần tử và render React component vào đó
Array.from(statusEventElements).forEach(element => {
    console.log(element);

    // Lấy giá trị của thuộc tính data từ phần tử HTML
    const eventid = element.dataset.eventid;
    const contracteventid = element.dataset.contracteventid;
    const status = element.dataset.status;
    const switchid = element.dataset.switchid;

    const payload = {
        eventid: eventid,
        contracteventid: contracteventid,
        status: status === '1' ? true : false,
        switchid: switchid
    }

    console.log('payload',payload);
    createRoot(element).render(
        <WalletProvider>
            <StatusEvent payload={payload} />
        </WalletProvider>
    );
});
