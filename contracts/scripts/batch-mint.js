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
    //let packageId = "0x769941cd7b338429e9ada6f6e697e47461971c6bc2c8c45d8a1f3e412c4767ea";
    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.COLLECTION_ID;
    tx.moveCall({
        target: `${packageId}::client::mint_batch`,
        arguments: [
            // collection object id 
            //tx.object('0x0d6422b82f418e592546019b81585963300f2f29acb86a281e5add34f3388c7d'),
            tx.object(collectionId),
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
        typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });

    console.log({ result });

    const ticketIds = 
    result.objectChanges.filter(
        (o) =>
            o.type === "created" &&
            o.objectType.includes("::ticket_collection::NFTTicket")
    ).map(item => item.objectId);
    console.log(`ticket id : ${ticketIds}`);
}

mint();
