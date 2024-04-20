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
        url: getFullnodeUrl('testnet'),
    });
    const tx = new TransactionBlock();
    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.EVENT_ID;

    // claim ticket by user
    tx.moveCall({
        target: `${packageId}::ticket_collection::claim_ticket`,
        arguments: [
            tx.object(collectionId),
            tx.pure("0x0a437d5408a3dc2007b4329370a2ae1c1a0acf668fb9827cdd5dd8464780861f")
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });
    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });


    

    console.log({ result });
}

claim();
