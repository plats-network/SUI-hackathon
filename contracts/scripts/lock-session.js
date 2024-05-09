const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }


async function lockSession() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_CLIENT);
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();
    let packageId = process.env.PACKAGE_ID;
    const collectionId= process.env.EVENT_OBJECT_ID;


    tx.moveCall({
        target: `${packageId}::client::lock_session`,
        arguments: [
            // ticket event id 
            tx.object(collectionId),
            // session collection id 
            tx.pure("0xa5626b2f554c0ec4f4e0b4efff337a84d0d1e0de1a6a1290176224190fd0bb3a"),
            // bật on = false , off = true        
            tx.pure(true),  
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

    console.log("lock session  tx", JSON.stringify(txs, null, 2));


}

lockSession();