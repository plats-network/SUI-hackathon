import { Magic } from 'magic-sdk';
import { SolanaExtension } from "@magic-ext/solana";
import {AnchorProvider, BN, Program, setProvider, web3} from "@project-serum/anchor";
import {Connection, Keypair, LAMPORTS_PER_SOL, PublicKey, TransactionMessage} from "@solana/web3.js";
import idl from './abi/abi.json'
import { Buffer } from 'buffer';
import {
    createAssociatedTokenAccountInstruction,
    createInitializeMintInstruction,
    getAssociatedTokenAddress, getAssociatedTokenAddressSync,
    MINT_SIZE
} from "@solana/spl-token";
import { generateNonce, generateRandomness,jwtToAddress } from '@mysten/zklogin';
import { TransactionBlock } from "@mysten/sui.js/transactions";
import { getFullnodeUrl, SuiClient }  from '@mysten/sui.js/client';
import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import { GasStationClient, createSuiClient, buildGaslessTransactionBytes } from "@shinami/clients";
import { genAddressSeed, getZkLoginSignature,getExtendedEphemeralPublicKey } from "@mysten/zklogin";
import { jwtDecode } from 'jwt-decode';
import axios from "axios";
import {toBigIntBE} from "bigint-buffer";
import {fromB64,toB64} from "@mysten/bcs";
import { log } from 'console';
import { SerializedSignature, decodeSuiPrivateKey } from '@mysten/sui.js/cryptography';

let type_network = $('meta[name="type_network"]').attr('content');
let packageId = $('meta[name="package_id"]').attr('content');
const contract_event_id = $('#contract_event_id').val();

async function autoClaim() {

    const claimDevNet = async () => {
        
            const user_claim = $("#user_claim").val();
            
            console.log('user_claim',user_claim);

            //user đã claim rồi thì thôi
            if(user_claim == 'true'){
                return;
            }
            $('.loading').show();
            //user login jdk
            const GAS_AND_NODE_TESTNET_ACCESS_KEY = "sui_testnet_7543d1af9a8d035b0de83f45907b0fe3";
            
            // 3. Set up your Gas Station and Node Service clients
            const nodeClient = createSuiClient(GAS_AND_NODE_TESTNET_ACCESS_KEY);
            const gasStationClient = new GasStationClient(GAS_AND_NODE_TESTNET_ACCESS_KEY);
        
            const address_nft_min = $("#address_nft_min").val();
            console.log('address_nft_min',address_nft_min);
            const zkLoginUserAddress = localStorage.getItem("zkLoginUserAddress");
            if(!zkLoginUserAddress){
                alert('user can`t login')
                return;
            }
        
            let event_object_id = $('meta[name="event_id"]').attr('content');
            let packageId = $('meta[name="package_id"]').attr('content');
        
            let keypair = JSON.parse(localStorage.getItem('ephemeralKeyPair'));
            let jwtUser = localStorage.getItem('jwtUser');
            let ranDomness = localStorage.getItem('randomness');
            let maxEpoch = localStorage.getItem('maxEpoch');
            let salt = localStorage.getItem('salt');
            let ephemeralPrivateKey = localStorage.getItem('ephemeraPrivateKey');
        
            
            console.log('keypair',keypair);
        
                
            const client = new SuiClient({
            
                url: getFullnodeUrl(type_network),
            });
        
            const txb = new TransactionBlock();
            
            //https://cws-suivent.plats.test/dE
            
            txb.setSender(zkLoginUserAddress);
            txb.setGasBudget(5000000);
            txb.moveCall({
                target: `${packageId}::ticket_collection::claim_session`,
                arguments: [
                    txb.object(event_object_id),
                    txb.pure(address_nft_min)
                ],
                typeArguments: [`${packageId}::ticket_collection::NFTSession`]
            });
            
            const ephemeralKeyPairs = keypairFromSecretKey(ephemeralPrivateKey);
        
            console.log('Pair',ephemeralKeyPairs);
        
            const ephemeralPublicKeyB64 = ephemeralKeyPairs.getPublicKey().toSuiPublicKey();
        
            console.log(ephemeralPublicKeyB64);
        
            const ephemeralKeyPair = ephemeralKeyPairs.toSuiAddress();
        
            console.log('ephemeralKeyPair',ephemeralKeyPair);
        
            const { bytes, signature } = await txb.sign({
                client,
                signer: ephemeralKeyPairs, // This must be the same ephemeral key pair used in the ZKP request
            });
        
            const jwtPayload = jwtDecode(jwtUser);
        
            const addressSeed  = genAddressSeed(BigInt(salt), "sub", jwtPayload.sub, jwtPayload.aud).toString();
            
            console.log('addressSeed',addressSeed);
        
            // #devnet
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
            
            // testnet
            // const zkpPayload = {
            //     "network":"testnet",
            //     "ephemeralPublicKey":ephemeralPublicKeyB64, 
            //     "randomness": ranDomness,
            //     "maxEpoch": Number(maxEpoch),
            //     "jwt":jwtUser
            // }
            
            console.log('zkpPayload',zkpPayload);
            
            //nếu là mạng testnet
            const proofResponse = await axios.post(`/zkp${type_network}/post`, zkpPayload);
        
            console.log('proofResponse',proofResponse.data);
        
            const zkLoginSignature  = getZkLoginSignature({
                inputs: {
                    ...proofResponse.data,
                    addressSeed
                },
                maxEpoch:maxEpoch,
                userSignature:signature,
            });
            
            console.log('signature',signature);
        
            console.log("zkLoginSignature",zkLoginSignature);
        
            console.log('bytes',bytes);
            try {
                
                const result = await client.executeTransactionBlock({
                    transactionBlock: bytes,
                    signature: zkLoginSignature,
                });
                let digest = result.digest;
                console.log('result :',result, 'digest :',digest);
                console.log('zkLoginSignature',zkLoginSignature);
        
                const task_id = $("#task_id").val();
        
                const session_id = $("#session_id").val();
                
                const booth_id = $("#booth_id").val();

                const nft_mint_id = $("#nft_id").val();

                const body = {
                    nft_mint_id: nft_mint_id,
                    session_id: session_id,
                    booth_id: booth_id,
                    task_id: task_id,
                    digest:digest
                }

                const resUpdate_nft_status = await axios.post("/update_session_booth_nft_status", body);
                
                console.log('resUpdate_nft_status',resUpdate_nft_status.data);
                
                alert(`Claim NFT is success. Please see on https://suiscan.xyz/${type_network}/tx/${digest}`);
                $('#button-claim').hide()
                $('#button-claim-link').attr('href', `https://suiscan.xyz/${type_network}/tx/${digest}`);
                $('#button-claim-link').show();
                $('.loading').hide();
                
                // lưu data rồi thì tải lại trang
                if(resUpdate_nft_status.data.status){
                    window.location.reload();
                }
            } catch (error) {
                console.log(error);
                alert(error.message);
                $('.loading').hide();
        
            }
    }

    const claimTestNet = async () => {
        
        const user_claim = $("#user_claim").val();
            
        console.log('user_claim',user_claim);

        //user đã claim rồi thì thôi
        if(user_claim == 'true'){
            return;
        }
        $('.loading').show();

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
        let address_nft_min = $("#address_nft_min").val();
        let contract_task_events_details_id = $("#contract_task_events_details_id").val();
        let address_ticket_id = $("#address_ticket_id").val();
        console.log('address_nft_min',address_nft_min);
        console.log('contract_task_events_details_id',contract_task_events_details_id);
        let digest;
        let transactionBlockBytes;
        
        // let ticket_id = tickets[0][indexNft];
        let ticket_id = $('#ticket_id').val();
        const txb = new TransactionBlock();
        // txb.setSender(zkLoginUserAddress);
        // txb.setGasBudget(5000000);
        txb.moveCall({
            target: `${packageId}::ticket_collection::claim_session`,
            arguments: [
                
                txb.object(event_object_id),

                txb.object(address_ticket_id),  

                txb.object(contract_task_events_details_id),

                //claim 1 địa chỉ address nft
                txb.pure(address_nft_min)
            ],
            typeArguments: [`${packageId}::ticket_collection::NFTSession`]
        });

        const ephemeralKeyPairs = keypairFromSecretKey(ephemeralPrivateKey);

        console.log('Pair',ephemeralKeyPairs);

        // const ephemeralPublicKeyB64 = ephemeralKeyPairs.getPublicKey().toSuiPublicKey();
        
        // console.log(ephemeralPublicKeyB64);
        
        const toSuiAddress = ephemeralKeyPairs.toSuiAddress();
        console.log('toSuiAddress',toSuiAddress);

        console.log('zkLoginUserAddress',zkLoginUserAddress);
        
        const client = new SuiClient({

            url: getFullnodeUrl(type_network),
        });

        const transactionBlockKindBytes = await txb.build({ client, onlyTransactionKind: true });
        const endpoint = 'https://api.enoki.mystenlabs.com/v1/transaction-blocks/sponsor';
        const data = {
            transactionBlockKindBytes: toB64(transactionBlockKindBytes),
            network: type_network,
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
        try {
            const response = await axios.post(endpoint, JSON.stringify(data), { headers });
            console.log('Response:', response.data);
            digest = response.data.data.digest;
            transactionBlockBytes = response.data.data.bytes;
        } catch (error) {
            alert('Can`t claim session');
            $('.loading').hide();
            console.log('Error:', error);
        }

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

        try {
            const response = await axios.post(executeEndpoint, JSON.stringify(dataExecuteSponsor), { headers });

            console.log('Response for executing sponsored:', response.data.data.digest);

            let digests = response.data.data.digest;
            let task_id = $("#task_id").val();
            let session_id = $("#session_id").val();
            let booth_id = $("#booth_id").val();
            let nft_mint_id = $("#nft_id").val();

            let body = {
                nft_mint_id: nft_mint_id,
                session_id: session_id,
                booth_id: booth_id,
                task_id: task_id,
                digest:digests
            }

            let bodyUserJoinEvent = {
                task_event_detail_id:session_id,
                task_event_id:$("#task_event_id").val(),
                type:"1",
                is_important:"0",
                task_id:task_id
            }

            const resUpdate_nft_status = await axios.post("/update_session_booth_nft_status", body);

            const resUserJoinEvent = await axios.post("/userjoinevent", bodyUserJoinEvent);

            console.log('resUpdate_nft_status',resUpdate_nft_status.data);
            
            alert(`Claim NFT is success. Please see on https://suiscan.xyz/${type_network}/tx/${digest}`);
            $('#button-claim').hide()
            $('#button-claim-link').attr('href', `https://suiscan.xyz/${type_network}/tx/${digest}`);
            $('#button-claim-link').show();
            $('.loading').hide();
            
            // lưu data rồi thì tải lại trang
            if(resUpdate_nft_status.data.status && resUserJoinEvent.data.status){
                 
                //Show toast
                Toast.fire({
                    icon: 'success',
                    title: 'Claim session success',
                });

                // lưu data rồi thì tải lại trang
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
            
        } catch (error) {

            alert(error);
            $('.loading').hide();
            console.error('Error executing sponsored transaction block:', error);
        }
    }
    const handleReward = async () => {
        
        if(type_network == 'devnet'){
            
            await claimDevNet();
        }

        if(type_network == 'testnet'){

            await claimTestNet();
        }
        
    };

    // Call the desired function directly
    await handleReward();
}

// Call the autoClaim function when the script is executed
autoClaim();

export default autoClaim;

function keypairFromSecretKey(privateKeyBase64){
    const keyPair = decodeSuiPrivateKey(privateKeyBase64);
    return Ed25519Keypair.fromSecretKey(keyPair.secretKey);
}
$('#claim').on('click',async function (e) {

    const payload = {
    
    };
    
    console.log('payloadclaim',payload);
    
    const proofResponse = await axios.post(`/createluckycode`, payload);

    console.log('proofResponse',proofResponse.data);
});
