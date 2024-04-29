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
            tx.pure(["0x025bab49660c5c0a29eefcdec2d35df7201c79a297cf284129d4639285b4197e", "0x27600763e36900ea399db6245d90bd7a34f43e26d5a507099c4eb509599baaea","0x2eda4c3dfc2d37bf14ddde64b7c7a57cedc176785fb0e17e734e81349c08c52b","0x4b834a09667808fd32e0a3786f69c59abc31d4246fbbecb70ee3c5ab22604911"]),
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