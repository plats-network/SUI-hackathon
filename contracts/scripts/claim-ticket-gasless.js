
const { Ed25519Keypair } = require("@mysten/sui.js/keypairs/ed25519");
const { TransactionBlock } = require("@mysten/sui.js/transactions");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const { fromB64, toB64 } = require('@mysten/sui.js/utils');
const fetch = require('node-fetch');
const axios = require('axios');
const dotenv = require('dotenv');
dotenv.config();

async function claimGasless() {
    
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
            tx.pure("0xddf2b46c379b7bfc103dbee47056e97d1aca101576b2a832efdbbd9d5b1f9f89")
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });

    const transactionBlockKindBytes = await tx.build({ client, onlyTransactionKind: true });

    const endpoint = 'https://api.enoki.mystenlabs.com/v1/transaction-blocks/sponsor';
    const data = {
        transactionBlockKindBytes: toB64(transactionBlockKindBytes), 
        network: 'testnet',

        sender: '0x6e5273e9a1e52c32d9256301253dba2a3bbcebf808829b1b117e2b8593dc9bb9', 
        allowedAddresses: ['0x6e5273e9a1e52c32d9256301253dba2a3bbcebf808829b1b117e2b8593dc9bb9'],
        allowedMoveCallTargets: [`${packageId}::ticket_collection::claim_ticket`]
    };
    const headers = {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${process.env.ENOKI_API}`,
        'zklogin-jwt': 'eyJhbGciOiJSUzI1NiIsImtpZCI6ImUxYjkzYzY0MDE0NGI4NGJkMDViZjI5NmQ2NzI2MmI2YmM2MWE0ODciLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIyOTA1NTQwNDEyODUtZzc3YXJzNTRtOXZjMmh2dWd2MW9la2h0ZDU0ZWxsOXAuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiIyOTA1NTQwNDEyODUtZzc3YXJzNTRtOXZjMmh2dWd2MW9la2h0ZDU0ZWxsOXAuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDQ0NTE5MDAwOTA3NDQ0ODU2ODkiLCJub25jZSI6Ik8wN3BMQ3dVMmg0UXlwZ19saGdkRDBJX3dmcyIsIm5iZiI6MTcxMzk3MjgyMSwiaWF0IjoxNzEzOTczMTIxLCJleHAiOjE3MTM5NzY3MjEsImp0aSI6IjljNDA0ZTBmNTBkNDQ0NjczNjM0NTc5OGEzM2VjMDIzMDlmMTM1YmQifQ.QR5WhM_TDPaki9QwCIIqn6I2Ph3qWVXvWkuQv5B4OugS2eiq195DzOh-dQXFHJfARNTWb_LKsDoQUBRyeWyUHqIpImZ_95axLS6exJywSRyf8rjk4yZRx3lEfGWf2GuzJPckaKCBZ8B5LWUukAC18FPbtWy0SW6td0wTFV6ddos9IGcz5T1g6EMchOsPhRlkz7phOatqDPNscF-adqo0gxQBJAMVUYSLKgo0Bth6IST539lc5olLWNSKkN3Y8Wpkz30KkKZ1WJ6BAkTlu9OXtjFWB0N64e4vu1-Ljt75JK4LMD3uxs1zV380s7_LMrMWmQoMq0HCn4eODk56etsYxw', 
    };
    console.log(headers);
    console.log(JSON.stringify(data));
    try {
        const response = await axios.post(endpoint, JSON.stringify(data), { headers });
        console.log('Transaction created successfully:', response.data);
    } catch (error) {
        console.error('Error creating sponsored transaction block:', error.response ? error.response.data : error.message);
    }
}
claimGasless();