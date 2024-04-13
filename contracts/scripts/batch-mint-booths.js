const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.PACKAGE_ID) {
    console.log('Requires PACKAGE_ID; set with `export PACKAGE_ID="..."`');
    process.exit(1);
  }

async function mintBooths() {
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_CLIENT);
    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    const tx = new TransactionBlock();
    tx.moveCall({
        target: `${process.env.PACKAGE_ID}::client::mint_batch_booths`,
        arguments: [
            // name: vector<vector<u8>>,
            tx.pure(["SUI booth 1","SUI booth 2"]),
            // description: vector<vector<u8>>,
            tx.pure(["This is booth 1","This is booth 2"]),
            // url: vector<vector<u8>>,
            tx.pure(["https://picsum.photos/id/237/200/300","https://picsum.photos/id/237/200/300"]),
            // collection object id 
            tx.object('0x0d6422b82f418e592546019b81585963300f2f29acb86a281e5add34f3388c7d'),
        ],
        typeArguments: [`${process.env.PACKAGE_ID}::ticket_nft::NFTTicket`]
    });

    const result = await client.signAndExecuteTransactionBlock({
        signer: keypair,
        transactionBlock: tx,
    });

    console.log({ result });
}

mintBooths();
