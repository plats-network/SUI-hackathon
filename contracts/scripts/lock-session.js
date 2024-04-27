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
        target: `${packageId}::client::batch_lock_sessions`,
        arguments: [
            // ticket event id 
            tx.object(collectionId),
            // tập hợp session object id - collection session 
            tx.pure(["0x7e01bb84713e7ce59cd1474b4c1b7accd1a31bb2ef59067be54efe07406a818e", "0x8db556fb8316205458a364bf2ba25c11bf69bb2781cb35997da89cb8908a3b48","0xcca077d60ea628b9d149740bd8aa8a7f72714d4620e8086720255084abb2c9df","0xd3fb47a45907bc3c82e6ddb32d3ebc99f2bf7c60e5906595c1589fab4dc5e556"]),
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