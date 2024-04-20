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

async function createTicket() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_PUBLISHER);
    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    const tx = new TransactionBlock();


    tx.moveCall({
        target: `${packageId}::ticket_collection::create_tickets`,
        arguments: [],

        //typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
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


    console.log("create ticket tx", JSON.stringify(txs, null, 2));

    const ticketCollectionId = (
        txs.objectChanges.filter(
          (o) =>
            o.type === "created" &&
            o.objectType.includes("::ticket_collection::EventTicket")
        )[0]
      ).objectId;
      console.log(`ticket  id : ${ticketCollectionId}`);
    // get collection object id 

}

createTicket();