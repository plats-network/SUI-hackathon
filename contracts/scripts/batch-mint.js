const { TransactionBlock } = require("@mysten/sui.js/transactions");
const { Ed25519Keypair } = require("@mysten/sui.js/keypairs/ed25519");
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
        url: getFullnodeUrl(process.env.NETWORK),
    });

    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.EVENT_OBJECT_ID;
    const tx = new TransactionBlock();
    tx.moveCall({
        target: `${packageId}::client::mint_batch_tickets`,
        arguments: [
            // ticket event id 
            tx.object(collectionId),
            // event_id
            tx.pure("8ba9148d4e85e4a6862e8fa613f6cf6b"),
            // name: vector<u8>,
            tx.pure.string("SUI Hackathon"),
            // description: vector<u8>,
            tx.pure.string("This is a ticket yo join SUI Hackathon"),
            // url: vector<u8>,
            tx.pure("https://sui-hackathon.infura-ipfs.io/ipfs/QmTdrqauAgYPk9uxZjFUyQCBfhHLkCgjZixx5ZHQFAJcos"),
            // catogory: vector<u8>,
            tx.pure.string("Standard"),
            // max_supply: u64,
            tx.pure(1),
        ],
        //typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
        options: {
            showObjectChanges: true,
            showEffects: true
        },
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
