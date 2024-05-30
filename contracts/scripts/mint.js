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
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();

    tx.moveCall({
        target: `${process.env.PACKAGE_ID}::sui_nft::mint_to_sender`,
        arguments: [
            tx.pure("SUI Hackathon"),
            tx.pure("This is a ticket yo join SUI Hackathon"),
            tx.pure("https://picsum.photos/id/237/200/300"),
        ],
        //typeArguments: [`${packageId}::minter::NFT`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });

    console.log({ result });
}

mint();

