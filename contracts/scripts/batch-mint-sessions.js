const { TransactionBlock } = require("@mysten/sui.js/transactions");
const { Ed25519Keypair } = require("@mysten/sui.js/keypairs/ed25519");
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
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();
    const collectionId = process.env.EVENT_OBJECT_ID;
    tx.moveCall({
        target: `${process.env.PACKAGE_ID}::client::mint_batch_sessions`,
        arguments: [
            // ticket event id 
            tx.object(collectionId),
            // event_id
            tx.pure("8ba9148d4e85e4a6862e8fa613f6cf6b"),
            // name: vector<vector<u8>>,
            tx.pure(["SUI session 1", "SUI session 2"]),
            // description: vector<vector<u8>>,
            tx.pure(["This is session 1", "This is session 2"]),
            // url: vector<vector<u8>>,
            tx.pure(["https://sui-hackathon.infura-ipfs.io/ipfs/QmTdrqauAgYPk9uxZjFUyQCBfhHLkCgjZixx5ZHQFAJcos", "https://sui-hackathon.infura-ipfs.io/ipfs/QmTdrqauAgYPk9uxZjFUyQCBfhHLkCgjZixx5ZHQFAJcos"]),
            // max supply 
            tx.pure(1),
        ],
        //typeArguments: [`${process.env.PACKAGE_ID}::ticket_collection::NFTTicket`]
    });

    const txs = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
        options: {
            showObjectChanges: true,
        },
    });

    const sessionCollectionIds = 
        txs.objectChanges.filter(
            (o) =>
                o.type === "created" &&
                o.objectType.includes("::ticket_collection::SessionCollection")
        ).map(item => item.objectId);

    console.log(`Sessions collection id : ${sessionCollectionIds}`);
    const sessionIds = 
        txs.objectChanges.filter(
            (o) =>
                o.type === "created" &&
                o.objectType.includes("::ticket_collection::NFTSession")
        ).map(item => item.objectId);
    console.log(`Sessions id : ${sessionIds}`);

}

mintSessions();
