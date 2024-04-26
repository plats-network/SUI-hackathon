const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }


async function stopEvent() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_PUBLISHER);
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();
    let packageId = process.env.PACKAGE_ID;
    const collectionId= process.env.EVENT_OBJECT_ID;


    tx.moveCall({
        target: `${packageId}::admin::stop_event`,
        arguments: [
            // ticket event id 
            tx.object(collectionId),
            // bật on = false , off = true 
            
            tx.pure(true),  
            // publisher id -> hiện tại giữ nguyên hey
            tx.pure(process.env.PUBLISHER_ID)
        ],

    });

    const txs = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
        options: {
            showInput: true,
            showEffects: true,
            showEvents: true,
            showObjectChanges: true,
        },
    });

    console.log("stop event  tx", JSON.stringify(txs, null, 2));


}

stopEvent();