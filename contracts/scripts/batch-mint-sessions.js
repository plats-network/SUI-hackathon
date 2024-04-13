const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }

async function mintSessions() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_CLIENT);
    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    const tx = new TransactionBlock();
    let packageId = "0xe83d5c6059f09a1c98d73603c0ec7ef9c148fdd4983f90837426cc2cbf55cb94";
    tx.moveCall({
        target: `${packageId}::client::mint_batch_sessions`,
        arguments: [
            // name: vector<u8>,
            tx.pure("SUI Hackathon"),
            // description: vector<u8>,
            tx.pure("This is a ticket yo join SUI Hackathon"),
            // url: vector<u8>,
            tx.pure("https://picsum.photos/id/237/200/300"),
            // total: u64
            tx.pure(2),
            // collection object id 
            tx.object('0x5682fb257218baf7e9f4d0cac8c41875b8870ca2a2463cad4d0d4cdd37cee989'),
        ],
        typeArguments: [`${packageId}::ticket_nft::NFTSession`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });

    console.log({ result });
}

mintSessions();
