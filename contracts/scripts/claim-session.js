const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }

async function claim(indexSession) {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_CLIENT);
    const user = "0x70f94573c6cd732304f2c0fd9d80cf7d6206e4609c5c4b259972e90885fc3acb";
    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    let addressClient = keypair.getPublicKey().toSuiAddress();
    const tx = new TransactionBlock();
    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.COLLECTION_ID;

    // get specific nft 
    // let nft = tx.moveCall({
    //     target: `${packageId}::ticket_collection::get_nft`,
    //     arguments: [
    //         tx.object('0xfec87ac18d66ee69144ffb58fe99c0522b89eaa4f0e2382cc09eee285d366a10')
    //     ],
    //     typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    // });
    const allObjects = await client.getOwnedObjects({
        owner: addressClient,
        options: {
          showType: true,
          showDisplay: true,
          showContent: true,
        }
      });
  
  
      const objectIDs = (allObjects?.data || [])
        .filter((item) => item.data.objectId == collectionId)
        .map((anObj) => anObj.data.objectId);
      
      const allObjRes = await client.multiGetObjects({
        ids: objectIDs,
        options: {
          showContent: true,
          showDisplay: true,
          showType: true,
        },
      });
      const nftList = allObjRes.filter(obj => obj.data).map(obj => ({
        objectId: obj.data.objectId,
        data: obj.data.content.fields,
  
      }));
      //get ticket 

    // get sessions 
    const sessions = nftList.map((data) => data.data.sessions);
    console.log(sessions);
    let session_id = sessions[0][indexSession];


    tx.transferObjects([tx.object(session_id)] , user);
    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });


    

    console.log({ result });
}

claim(0);
