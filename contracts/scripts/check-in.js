const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }

async function mint() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_USER);
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();
    //let packageId = "0x769941cd7b338429e9ada6f6e697e47461971c6bc2c8c45d8a1f3e412c4767ea";
    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.EVENT_OBJECT_ID;
    tx.moveCall({
        target: `${packageId}::ticket_collection::check_in`,
        arguments: [
            //  event object id 
            tx.object(collectionId),
            // ticket id mà user đã claim để checkin
            tx.object("0x6e96fc2d67a8a5b8509efcd9394a5d207ec315f26ce2f42a2ea266441374c8f1"),

        ],
        //typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });
    console.log({ result });

}

mint();