import {createRoot} from "react-dom/client";
import ReactDOM from 'react-dom';
import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import {WalletProvider} from '@suiet/wallet-kit';
import '@suiet/wallet-kit/style.css';
import App from './useWalletSession';
import React, { useEffect, useState } from 'react';
// import App from './mintNft';
import {TransactionBlock} from "@mysten/sui.js/transactions";


export default function StatusSession({payload}) {
    
    const wallet = useWallet();
    const [valueCheckBox, setValueCheckBox] = useState(payload.status)
    
    const lockSession = async (packageId,contract_event_id,nftsessionid,status) => {
    
        console.log('wallet',wallet);
        let tx = new TransactionBlock();
    
        tx.moveCall({
            target: `${packageId}::client::batch_lock_sessions`,
            arguments: [
            // ticket event id 
            tx.object(contract_event_id),
            // tập hợp session object id - collection session 
            tx.pure([nftsessionid]),
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
                url: '/event-job/' + payload.id,
                type: 'GET',
                dataType: 'json',
                data: {
                    event_id: payload.detailid,
                },
                success: function (data) {
                    if (data.message == 'OK') {

                        setValueCheckBox((prev) => !prev)
                        $.notify("Success.", "success");
                    } else {
                        $.notify("Success", "error");
                    }
                },
                error: function (data) {
                    $.notify("Errors.", "error");
                }
            });
            console.log("lock session  tx", txs);
            $('.loading').hide();

        } catch (error) {
            console.log(error);
            alert(error);
            $('.loading').hide();
        }
    }
    

    

    const handleChange = (e) => {

        setValueCheckBox((prev) => !prev)
        // const id_session = payload.nft_session_id;
       
    }

    const handleClick = async () => {
        console.log('payload',payload);
        const contract_event_id = payload.contract_event_id;
        let packageId = $('meta[name="package_id"]').attr('content');
        lockSession(packageId,contract_event_id,payload.nftsessionid,valueCheckBox);
        
    }
    
    return (
        <div>
            <input
                type="checkbox"
                class="jobSwitch"
                switch="none"
                checked={valueCheckBox}
                id={payload.sessionid}
                onChange={handleChange}
                value={valueCheckBox}
            />
            <label class="job"
                data-id={payload.id}
                data-detail-id={payload.detailid}
                for={payload.sessionid}
                onClick={handleClick}
                data-on-label="On"
                data-off-label="Off">
            </label>
        </div>
    );
}

// Lấy tất cả các phần tử có class name 'statusSession'
const statusSessionElements = document.getElementsByClassName('statusSession');

// Lặp qua từng phần tử và render React component vào đó
Array.from(statusSessionElements).forEach(element => {
    console.log(element);

    // Lấy giá trị của thuộc tính data từ phần tử HTML
    const dataId = element.dataset.id;
    const dataStatus = element.dataset.status;
    const dataSessionId = element.dataset.sessionid;
    const dataDetailSessionId = element.dataset.detailsessionid;
    const dataContractEventId = element.dataset.contracteventid;
    const dataNftsessionid = element.dataset.nftsessionid;
    const dataNftRes = element.dataset.nftres;
    
    let sessionIds =  JSON.parse(dataNftRes).objectChanges.filter((o) =>
        o.type === "created" &&
        o.objectType.includes("::ticket_collection::NFTSession")
    ).map(item => item.objectId);

    console.log('sessionIds',sessionIds);

    console.log('dataNftRes',JSON.parse(dataNftRes));
    
    const payload = {
        contract_event_id:dataContractEventId,
        sessionids:sessionIds,
        nftsessionid:dataNftsessionid,
        id: dataId,
        status: dataStatus == 'true' ? true : false,
        detailid:dataDetailSessionId,
    }

    console.log('payload',payload);
    createRoot(element).render(
        <WalletProvider>
            <StatusSession payload={payload} />
        </WalletProvider>
    );
});
