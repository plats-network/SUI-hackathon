import { Buffer } from 'buffer';

import { TransactionBlock } from "@mysten/sui.js/transactions";
import { getFullnodeUrl, SuiClient }  from '@mysten/sui.js/client';
import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import { genAddressSeed, getZkLoginSignature,getExtendedEphemeralPublicKey } from "@mysten/zklogin";
import { jwtDecode } from 'jwt-decode';
import axios from "axios";
import {toBigIntBE} from "bigint-buffer";
import { SerializedSignature, decodeSuiPrivateKey } from '@mysten/sui.js/cryptography';
import { fromB64, toB64 } from '@mysten/sui.js/utils';

let typenetwork = $('meta[name="type_network"]').attr('content');

const package_id = $('#package_id').val();
const contract_event_id = $('#contract_event_id').val();
const collection_id = $('#collection_id').val();

let keypair = JSON.parse(localStorage.getItem('ephemeralKeyPair'));
let jwtUser = localStorage.getItem('jwtUser');
let ranDomness = localStorage.getItem('randomness');
let maxEpoch = localStorage.getItem('maxEpoch');
let salt = localStorage.getItem('salt');
let ephemeralPrivateKey = localStorage.getItem('ephemeraPrivateKey');
const zkLoginUserAddress = localStorage.getItem("zkLoginUserAddress");

function keypairFromSecretKey(privateKeyBase64){
    const keyPair = decodeSuiPrivateKey(privateKeyBase64);
    return Ed25519Keypair.fromSecretKey(keyPair.secretKey);
}
const claimDevNet = async () => {
    $('.loading').show();

    let packageId = package_id;
    let event_object_id = collection_id;
    const zkLoginUserAddress = localStorage.getItem("zkLoginUserAddress");
    if(!zkLoginUserAddress){
        alert('user can`t login')
        return;
    }

    let keypair = JSON.parse(localStorage.getItem('ephemeralKeyPair'));
    let jwtUser = localStorage.getItem('jwtUser');
    let ranDomness = localStorage.getItem('randomness');
    let maxEpoch = localStorage.getItem('maxEpoch');
    let salt = localStorage.getItem('salt');
    let ephemeralPrivateKey = localStorage.getItem('ephemeraPrivateKey');

    // let ticket_id = tickets[0][indexNft];
    let ticket_id = $('#ticket_id').val();
    const txb = new TransactionBlock();
    txb.setSender(zkLoginUserAddress);
    txb.setGasBudget(5000000);
    txb.moveCall({
        target: `${packageId}::ticket_collection::claim_session`,
        arguments: [
            txb.object(event_object_id),
            txb.pure(ticket_id)
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });

    const ephemeralKeyPairs = keypairFromSecretKey(ephemeralPrivateKey);

    console.log('Pair',ephemeralKeyPairs);
    const client = new SuiClient({

        url: getFullnodeUrl(typenetwork),
    });
    const { bytes, signature } = await txb.sign({
        client,
        signer: ephemeralKeyPairs, // This must be the same ephemeral key pair used in the ZKP request
    });

    const jwtPayload = jwtDecode(jwtUser);

    const addressSeed  = genAddressSeed(BigInt(salt), "sub", jwtPayload.sub, jwtPayload.aud).toString();

    console.log(addressSeed);

    const zkpPayload = {
        jwt: jwtUser,
        extendedEphemeralPublicKey: toBigIntBE(
            Buffer.from(ephemeralKeyPairs.keypair.publicKey),
        ).toString(),
        jwtRandomness: ranDomness,
        maxEpoch: maxEpoch,
        salt: salt,
        keyClaimName: "sub"
    };
    console.log('zkpPayload',zkpPayload);

    const proofResponse = await axios.post(`/zkp${typenetwork}/post`, zkpPayload);
    const zkLoginSignature  = getZkLoginSignature({
        inputs: {
            ...proofResponse.data,
            addressSeed
        },
        maxEpoch:maxEpoch,
        userSignature:signature,
    });

    const nftId = $('#nft_id').val();
    //
    try {
        const result = await client.executeTransactionBlock({
            transactionBlock: bytes,
            signature: zkLoginSignature,
        });
        let digest = result.digest;
        console.log('result :',result, 'digest :',digest);

        const body = {
            nft_id: nftId,
            digest: digest,
        }

        const res = await axios.post("/update_nft_status", body);
        alert(`Claim NFT is success. Please see on https://suiscan.xyz/${typenetwork}/tx/${digest}`);
        $('.loading').hide();
        $('.claim-success').css('display', 'block');
        $('.sol-link').attr('href', `https://suiscan.xyz/${typenetwork}/tx/${digest}`);
        $('.sol-link').show();
        $('.btn-claim-id').hide();
        $('.claim-btn').hide();
        $('.showModal').hide();
    } catch (error) {
        alert(error.message);
    }
}

const claimTestNet = async () => {
    $('.loading').show();

    let packageId = package_id;
    let event_object_id = contract_event_id;
    const zkLoginUserAddress = localStorage.getItem("zkLoginUserAddress");
    if(!zkLoginUserAddress){
        alert('user can`t login')
        return;
    }

    let keypair = JSON.parse(localStorage.getItem('ephemeralKeyPair'));
    let jwtUser = localStorage.getItem('jwtUser');
    let ranDomness = localStorage.getItem('randomness');
    let maxEpoch = localStorage.getItem('maxEpoch');
    let salt = localStorage.getItem('salt');
    let ephemeralPrivateKey = localStorage.getItem('ephemeraPrivateKey');
        
    let digest;
    let transactionBlockBytes;
    
    // let ticket_id = tickets[0][indexNft];
    let ticket_id = $('#ticket_id').val();
    const txb = new TransactionBlock();
    // txb.setSender(zkLoginUserAddress);
    // txb.setGasBudget(5000000);
    txb.moveCall({
        target: `${packageId}::ticket_collection::claim_ticket`,
        arguments: [
            txb.object(event_object_id),
            txb.pure(ticket_id)
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
    });

    const ephemeralKeyPairs = keypairFromSecretKey(ephemeralPrivateKey);

    console.log('Pair',ephemeralKeyPairs);

    // const ephemeralPublicKeyB64 = ephemeralKeyPairs.getPublicKey().toSuiPublicKey();
    
    // console.log(ephemeralPublicKeyB64);
    
    const toSuiAddress = ephemeralKeyPairs.toSuiAddress();
    console.log('toSuiAddress',toSuiAddress);

    console.log('zkLoginUserAddress',zkLoginUserAddress);
    try {
        const client = new SuiClient({

            url: getFullnodeUrl(typenetwork),
        });

        const transactionBlockKindBytes = await txb.build({ client, onlyTransactionKind: true });
        const endpoint = 'https://api.enoki.mystenlabs.com/v1/transaction-blocks/sponsor';
        const data = {
            transactionBlockKindBytes: toB64(transactionBlockKindBytes),
            network: typenetwork,
            // with keypair 
            sender: toSuiAddress,
            allowedMoveCallTargets: [`${packageId}::ticket_collection::claim_ticket`]
        };
        const headers = {
            'Content-Type': 'application/json',
            'Authorization': `Bearer enoki_private_dadb3ac2919df99e92dbe6eac8b8d534`,
        };
        console.log(headers);
        console.log(JSON.stringify(data));
    
        const response = await axios.post(endpoint, JSON.stringify(data), { headers });
        console.log('Response:', response.data);
        digest = response.data.data.digest;
        transactionBlockBytes = response.data.data.bytes;

        console.log('Digest:', digest);
        console.log('transactionBlockBytes:', transactionBlockBytes);

        // sign by user with keypair
        const signature = await ephemeralKeyPairs.signTransactionBlock(fromB64(transactionBlockBytes));


        console.log('Signature:', signature);

        //
        const executeEndpoint = `https://api.enoki.mystenlabs.com/v1/transaction-blocks/sponsor/${digest}`;

        console.log("Execute Endpoint:", executeEndpoint);
        let dataExecuteSponsor = {
            signature: signature.signature
        };

        console.log('Data executeSponsor:', dataExecuteSponsor);

        const responseExecuteSponsor = await axios.post(executeEndpoint, JSON.stringify(dataExecuteSponsor), { headers });

        console.log('Response for executing sponsored:', responseExecuteSponsor.data.data.digest);

        let digests = responseExecuteSponsor.data.data.digest;
        const nftId = $('#nft_id').val();
        const task_id = $('#task_id').val();

        const body = {
            nft_mint_id: nftId,
            digest: digests,
            task_id:task_id
        }

        const res = await axios.post("/update_nft_status", body);

        alert(`Claim NFT is success. Please see on https://suiscan.xyz/${typenetwork}/tx/${digests}`);
        
        $('.loading').hide();
        $('.claim-success').css('display', 'block');
        $('.sol-link').attr('href', `https://suiscan.xyz/${typenetwork}/tx/${digests}`);
        $('.sol-link').show();
        $('.btn-claim-id').hide();
        $('.claim-btn').hide();
        $('.showModal').hide();
        
    } catch (error) {
        alert(error);
        $('.loading').hide();
        console.error('Error executing sponsored transaction block:', error);
    }
}
$('.btn-claim-id').click(async function () {
     
    if(typenetwork == 'devnet'){
            
        await claimDevNet();
    }

    if(typenetwork == 'testnet'){

        await claimTestNet();
    }
    return;
    

    // const { bytes, signature } = await txb.sign({
    //     client,
    //     signer: ephemeralKeyPairs, // This must be the same ephemeral key pair used in the ZKP request
    // });

    // const jwtPayload = jwtDecode(jwtUser);

    // const addressSeed  = genAddressSeed(BigInt(salt), "sub", jwtPayload.sub, jwtPayload.aud).toString();

    // console.log(addressSeed);
    //  // testnet
    // const zkpPayload = {
    //     "network":"testnet",
    //     "ephemeralPublicKey":ephemeralPublicKeyB64, 
    //     "randomness": ranDomness,
    //     "maxEpoch": Number(maxEpoch),
    //     "jwt":jwtUser
    // }

    // // const zkpPayload = {
    // //     jwt: jwtUser,
    // //     extendedEphemeralPublicKey: toBigIntBE(
    // //         Buffer.from(ephemeralKeyPairs.keypair.publicKey),
    // //     ).toString(),
    // //     jwtRandomness: ranDomness,
    // //     maxEpoch: maxEpoch,
    // //     salt: salt,
    // //     keyClaimName: "sub"
    // // };

    // console.log('zkpPayload',zkpPayload);

    // const proofResponse = await axios.post(`/zkp${typenetwork}/post`, zkpPayload);
    // const zkLoginSignature  = getZkLoginSignature({
    //     inputs: {
    //         ...proofResponse.data,
    //         addressSeed
    //     },
    //     maxEpoch:maxEpoch,
    //     userSignature:signature,
    // });

    // const nftId = $('#nft_id').val();
    // //
    // try {
    //     const result = await client.executeTransactionBlock({
    //         transactionBlock: bytes,
    //         signature: zkLoginSignature,
    //     });
    //     let digest = result.digest;
    //     console.log('result :',result, 'digest :',digest);

    //     const body = {
    //         nft_id: nftId,
    //         digest: digest,
    //     }

    //     const res = await axios.post("/update_nft_status", body);
    //     alert(`Claim NFT is success. Please see on https://suiscan.xyz/${typenetwork}/tx/${digest}`);
    //     $('.loading').hide();
    //     $('.claim-success').css('display', 'block');
    //     $('.sol-link').attr('href', `https://suiscan.xyz/${typenetwork}/tx/${digest}`);
    //     $('.sol-link').show();
    //     $('.btn-claim-id').hide();
    //     $('.claim-btn').hide();
    //     $('.showModal').hide();
    // } catch (error) {
    //     console.log(error);
    //     alert(error.message);
    // }

})
