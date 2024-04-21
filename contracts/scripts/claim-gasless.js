// 1. Import everything we'll need for the rest of the tutorial
const { Ed25519Keypair } = require("@mysten/sui.js/keypairs/ed25519");
const { TransactionBlock } = require("@mysten/sui.js/transactions");
const { GasStationClient, createSuiClient, buildGaslessTransactionBytes } = require("@shinami/clients");

const dotenv = require('dotenv');
dotenv.config();

async function claimGasless() {
    // 2. Copy your Testnet Gas Station and Node Service key value
    const GAS_AND_NODE_TESTNET_ACCESS_KEY = process.env.ACCESS_KEY;
    let packageId = process.env.PACKAGE_ID;
    let collectionId = process.env.EVENT_ID;

    // 3. Set up your Gas Station and Node Service clients
    const nodeClient = createSuiClient(GAS_AND_NODE_TESTNET_ACCESS_KEY);
    const gasStationClient = new GasStationClient(GAS_AND_NODE_TESTNET_ACCESS_KEY);

    // 4. Generate a new KeyPair to represent the sender
    const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_USER_NO_FUND);
    let user = keypair.toSuiAddress();

    // 5. Generate the TransactionKind for sponsorship as a Base64 encoded string
    let gaslessPayloadBase64 = await buildGaslessTransactionBytes({
        sui: nodeClient,
        build: async (txb) => {
            txb.moveCall({
                target: `${packageId}::ticket_collection::claim_ticket`,
                arguments: [
                    txb.object(collectionId),
                    txb.pure("0x3d039c27da7b70d181b4599efc76f33b268ab692efbb5a7d939917eeda8f5737")
                ],
                typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
            });
        }
    });

    // 6. Send the TransactionKind to Shinami Gas Station for sponsorship.
    //     We are omitting the gasBudget parameter to take advantage of auto-budgeting.
    let sponsoredResponse = await gasStationClient.sponsorTransactionBlock(
        gaslessPayloadBase64,
        user
    );

    // 7. The transaction should be sponsored as long as there was SUI 
    //     in your fund, so its status will be "IN_FLIGHT"
    let sponsoredStatus = await gasStationClient.getSponsoredTransactionBlockStatus(
        sponsoredResponse.txDigest
    );
    console.log("Transaction Digest:", sponsoredResponse.txDigest);
    // For me this printed "Transaction Digest: GE6rWNfjVk7GiNSRHExaYnQB6TNKRpWBbQrAAK1Cqax5"
    // which we'll see in the image below. 
    console.log("Sponsorship Status:", sponsoredStatus);
    // Printed "Sponsorship Status: IN_FLIGHT"

}
claimGasless();