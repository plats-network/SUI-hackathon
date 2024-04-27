const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }


async function lockBooth() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_CLIENT);
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();
    let packageId = process.env.PACKAGE_ID;
    const collectionId= process.env.EVENT_OBJECT_ID;


    tx.moveCall({
        target: `${packageId}::client::batch_lock_booths`,
        arguments: [
            // ticket event id 
            tx.object(collectionId),
            // booths collection (tập hợp các nft booth object id  của 1 booth)
            tx.pure(["0xda305a353cc351c7c13362a70331787b2e08f67af49975af4df8fd7865ce56ad","0xda305a353cc351c7c13362a70331787b2e08f67af49975af4df8fd7865ce56ad"]),
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

    console.log("lock booth  tx", JSON.stringify(txs, null, 2));


}

lockBooth();