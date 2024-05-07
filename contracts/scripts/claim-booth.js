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
        target: `${packageId}::ticket_collection::claim_booth`,
        arguments: [
            tx.object(collectionId),
            // booth collection id 
            tx.object(process.env.BOOTH_COLLECTION_ID),
            // booth object id 
            tx.pure("0x04f009e7f56a56e73e4b00a8800c2f2e8d74ba46e8999d9e61d18fd2201434da")
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTBooth`]
    });
    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });


    console.log({ result });
}

claim();
