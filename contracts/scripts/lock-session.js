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
            tx.pure(["0x03556a53bd1b4e674cbc9e4921dcc0688c79f5b981bde91f5a5b31f6315a9b21", "0x17f4ebd7bdcaed24399e3793940e5274e18c45c0ec88c0c2718b0f04a0f46e50","0x3e028c8dbbe1a3942ef9f1772cbb2f7d42d0ab93019a69b88022165122b1aba5","0x74d8325bb8ae3424dc1faaf38874e3d5d7c03f4d5ff958987f1564d4f066191f"]),
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