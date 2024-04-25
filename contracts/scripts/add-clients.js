const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }

const packageId = process.env.PACKAGE_ID;
const ticketCollectionType = `${packageId}::ticket_collection::TicketCollection`;
const NFTTicketType = `${packageId}::ticket_nft::NFTTicket`;
const NFTSessionType = `${packageId}::ticket_nft::NFTSession`;
const NFTBoothType = `${packageId}::ticket_nft::NFTBooth`;
const CoinType = "0x2::sui::SUI";

async function addClient() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_PUBLISHER);
    const client = new SuiClient({
        url: getFullnodeUrl('devnet'),
    });
    const tx = new TransactionBlock();
    let packageId = process.env.PACKAGE_ID;
    const collectionId= process.env.EVENT_OBJECT_ID;


    tx.moveCall({
        target: `${packageId}::admin::batch_add_client`,
        arguments: [
            // ticket event id 
            tx.object(collectionId),
            // clients address 
            tx.pure(["0xb9941d47ba2a5583b89d8399a646251cb9bc8ad0004ec70c5bb8088f6f5356b7", "0xcdeec99b1786a614d9ddcf016222fdc30c17ead921d80a0dcbead5c6e6a616b3"]),  
            // publisher id -> hiện tại giữ nguyên hey
            tx.pure("0x8e8b2bf816ab77cb6985a0f7963aa9b7f9ec13638effe24aab78778902e5defb")
        ],

    });

    const txs = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
        options: {
            showInput: true,
            showEffects: true,
            showEvents: true,
            showObjectChanges: true,
        },
    });

    console.log("add client  tx", JSON.stringify(txs, null, 2));


}

addClient();