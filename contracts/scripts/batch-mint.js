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
    let packageId = "0xe83d5c6059f09a1c98d73603c0ec7ef9c148fdd4983f90837426cc2cbf55cb94";
    tx.moveCall({
        target: `${packageId}::client::mint_batch`,
        arguments: [
            // collection object id 
            tx.object('0x0d6422b82f418e592546019b81585963300f2f29acb86a281e5add34f3388c7d'),
            // name: vector<u8>,
            tx.pure("SUI Hackathon"),
            // description: vector<u8>,
            tx.pure("This is a ticket yo join SUI Hackathon"),
            // url: vector<u8>,
            tx.pure("https://picsum.photos/id/237/200/300"),
            // catogory: vector<u8>,
            tx.pure("Standard"),
            // max_supply: u64,
            tx.pure(2),
        ],
        typeArguments: [`${packageId}::ticket_nft::NFTTicket`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });

    console.log({ result });
}

mint();
