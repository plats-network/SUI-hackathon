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
            // ticket id mà user đó đã claim và đã check in 
            tx.object("0x6e96fc2d67a8a5b8509efcd9394a5d207ec315f26ce2f42a2ea266441374c8f1"),
            // session collection id 
            tx.object("0x6550ce0b06508af0b205e39c14dd5d6247aa26459020b2b868975fb5dfbe1748"),
            // session object id 
            tx.pure("0x1cbdb4109d9fd438cb273cc8aefa3279680cd7e7c9e0f67736c2e7f2f3ee3e0e")
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
