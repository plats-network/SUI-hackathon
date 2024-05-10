const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }

async function claim() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_USER);
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();
    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.EVENT_OBJECT_ID;

    // claim ticket by user
    tx.moveCall({
        target: `${packageId}::ticket_collection::claim_session`,
        arguments: [
            tx.object(collectionId),
            // session collection id 
            tx.object("0xa5626b2f554c0ec4f4e0b4efff337a84d0d1e0de1a6a1290176224190fd0bb3a"),
            // session object id 
            tx.pure("0x9b72adcf519c28c8d2009b6d1ef5eeb3ecee742a10a567cc7cac57a6d9e3a716")
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTSession`]
    });
    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });


    console.log({ result });
}

claim();
