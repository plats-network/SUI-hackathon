
const { Ed25519Keypair } = require("@mysten/sui.js/keypairs/ed25519");
const { TransactionBlock } = require("@mysten/sui.js/transactions");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const { decodeSuiPrivateKey } = require("@mysten/sui.js/cryptography");
const { fromB64, toB64 } = require('@mysten/sui.js/utils');
const fetch = require('node-fetch');
const axios = require('axios');
const dotenv = require('dotenv');
dotenv.config();

async function claimGasless() {
    let digest;
    let transactionBlockBytes;

    //with keypair
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_USER_NO_FUND);
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    const tx = new TransactionBlock();

    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.EVENT_OBJECT_ID;

    // claim ticket by user
    tx.moveCall({
        target: `${packageId}::ticket_collection::claim_ticket`,
        arguments: [
            // event object id 
            tx.object(collectionId),
            // ticket id 
            tx.pure("0x9a9692dc452774340bda2007d4cdaf8934b2cac13a5f2757bc42dda121b91caf")
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });

    const transactionBlockKindBytes = await tx.build({ client, onlyTransactionKind: true });

    const endpoint = 'https://api.enoki.mystenlabs.com/v1/transaction-blocks/sponsor';
    const data = {
        transactionBlockKindBytes: toB64(transactionBlockKindBytes),
        network: 'testnet',
        // with keypair 
        sender: '0x6e5273e9a1e52c32d9256301253dba2a3bbcebf808829b1b117e2b8593dc9bb9',
        allowedMoveCallTargets: [`${packageId}::ticket_collection::claim_ticket`]
    };
    const headers = {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${process.env.ENOKI_API}`,
    };
    console.log(headers);
    console.log(JSON.stringify(data));
    try {
        const response = await axios.post(endpoint, JSON.stringify(data), { headers });
        console.log('Response:', response.data);
        digest = response.data.data.digest;
        transactionBlockBytes = response.data.data.bytes;
    } catch (error) {
        console.error('Error creating sponsored transaction block:', error.response ? error.response.data : error.message);
    }

    console.log('Digest:', digest);
    console.log('transactionBlockBytes:', transactionBlockBytes);

    // sign by user with keypair
    const signature = await keypair.signTransactionBlock(fromB64(transactionBlockBytes));


    console.log('Signature:', signature);

    //
    const executeEndpoint = `https://api.enoki.mystenlabs.com/v1/transaction-blocks/sponsor/${digest}`;

    console.log("Execute Endpoint:", executeEndpoint);
    let dataExecuteSponsor = {
        signature: signature.signature
    };

    console.log('Data executeSponsor:', dataExecuteSponsor);

    try {
        const response = await axios.post(executeEndpoint, JSON.stringify(dataExecuteSponsor), { headers });
        console.log('Response for executing sponsored:', response.data);

    } catch (error) {
        console.error('Error executing sponsored transaction block:', error.response ? error.response.data : error.message);
    }


}
claimGasless();