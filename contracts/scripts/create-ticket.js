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

async function createTicket() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_CLIENT);
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();


    tx.moveCall({
        target: `${packageId}::ticket_collection::create_event`,
        arguments: [
            //tx.pure(process.env.PUBLISHER_ID),
            // địa chỉ của organizer để có thể tạo nft ticket, lock event, session 
            tx.pure("0xb9941d47ba2a5583b89d8399a646251cb9bc8ad0004ec70c5bb8088f6f5356b7")
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


    console.log("create event shared tx", JSON.stringify(txs, null, 2));

    const ticketCollectionId = (
        txs.objectChanges.filter(
          (o) =>
            o.type === "created" &&
            o.objectType.includes("::ticket_collection::EventTicket")
        )[0]
      ).objectId;
      console.log(`event shared  id : ${ticketCollectionId}`);
    
      const sessionCollectionId = (
        txs.objectChanges.filter(
          (o) =>
            o.type === "created" &&
            o.objectType.includes("::ticket_collection::SessionCollection")
        )[0]
      ).objectId;
      console.log(`session Collection id : ${sessionCollectionId}`);

      const boothCollectionId = (
        txs.objectChanges.filter(
          (o) =>
            o.type === "created" &&
            o.objectType.includes("::ticket_collection::BoothCollection")
        )[0]
      ).objectId;
      console.log(`booth Collection id : ${boothCollectionId}`);
}

createTicket();