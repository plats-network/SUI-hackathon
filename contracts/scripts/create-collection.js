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
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_CLIENT);
    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    const tx = new TransactionBlock();
    let packageID = "0xe83d5c6059f09a1c98d73603c0ec7ef9c148fdd4983f90837426cc2cbf55cb94";

    tx.moveCall({
        target: `${packageID}::client::create_new_collection`,
        arguments: [
            tx.pure("https://picsum.photos/id/237/200/300"),
            tx.pure("SUI Hackathon"),
            tx.pure("This is a ticket to join SUI Hackathon"),
            tx.pure("0"),
        ],

        typeArguments: [`${packageID}::ticket_nft::NFTTicket`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });


    console.log({ result });
}

mint();