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
import {fromB64} from "@mysten/bcs";
import { log } from 'console';
import { SerializedSignature, decodeSuiPrivateKey } from '@mysten/sui.js/cryptography';

let rpcUrl = 'https://api.devnet.solana.com';

let magic = new Magic("pk_live_F223EA517482BAF8", {
    extensions: {
        solana: new SolanaExtension({
            rpcUrl
        })
    }
});
let solConnect = new window.SolanaConnect();
var walletOr = '';
var pub = '';
var TOKEN_METADATA_PROGRAM_ID = new PublicKey("metaqbxxUerdq28cj1RbAWkYQm3ybzjb6a8bt518x1s");
let type_network = $('meta[name="type_network"]').attr('content');

let connection = new Connection(web3.clusterApiUrl(type_network));
let PROGRAM_ID = new PublicKey("D5GK8Kye78gjuDMMjRnkWH5a6KfNEXzex5mekXL3HLR2");
let provider = new AnchorProvider(connection, solConnect.getWallet(), {commitment: "confirmed"})
let program = new Program(idl, PROGRAM_ID, provider);

async function autoClaim() {

    const handleDeposit = async () => {

        console.log(1111);
        // Your code for handling deposit goes here
    };

    const handleReward = async () => {
        
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
    
        // const convertKeypair = {
        //     "keypair": {
        //         "publicKey": Object.values(keypair.keypair.publicKey),
        //         "secretKey": Object.values(keypair.keypair.secretKey),
        //     }
        // };
    
        // console.log('convertKeypair',convertKeypair);
        
        // const ephemeralKeyPair = new Ed25519Keypair();
        // console.log('ephemeralKeyPair',ephemeralKeyPair);
        // return;
        // // ==================================
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
        const proofResponse = await axios.post(type_network == 'testnet' ? "/zkptestnet/post" : "/zkpdevnet/post", zkpPayload);
    
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
    };

    // Call the desired function directly
    await handleReward();
}

// Call the autoClaim function when the script is executed
autoClaim();

export default autoClaim;

$('#button-claim-test').click(async function(){
    
    
    
});

function keypairFromSecretKey(privateKeyBase64){
    const keyPair = decodeSuiPrivateKey(privateKeyBase64);
    return Ed25519Keypair.fromSecretKey(keyPair.secretKey);
}

async function autoClaims() {
   
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

    // const convertKeypair = {
    //     "keypair": {
    //         "publicKey": Object.values(keypair.keypair.publicKey),
    //         "secretKey": Object.values(keypair.keypair.secretKey),
    //     }
    // };

    // console.log('convertKeypair',convertKeypair);
    
    // const ephemeralKeyPair = new Ed25519Keypair();
    // console.log('ephemeralKeyPair',ephemeralKeyPair);
    // return;
    // // ==================================
    const { bytes, signature } = await txb.sign({
        client,
        signer: ephemeralKeyPairs, // This must be the same ephemeral key pair used in the ZKP request
    });

    const jwtPayload = jwtDecode(jwtUser);

    const addressSeed  = genAddressSeed(BigInt(salt), "sub", jwtPayload.sub, jwtPayload.aud).toString();
    
    console.log('addressSeed',addressSeed);

    // #devnet
    // const zkpPayload = {
    //     jwt: jwtUser,
    //     extendedEphemeralPublicKey: toBigIntBE(
    //         Buffer.from(ephemeralKeyPairs.keypair.publicKey),
    //     ).toString(),
    //     jwtRandomness: ranDomness,
    //     maxEpoch: maxEpoch,
    //     salt: salt,
    //     keyClaimName: "sub"
    // };
    
    // testnet
    const zkpPayload = {
        "network":"testnet",
        "ephemeralPublicKey":ephemeralPublicKeyB64, 
        "randomness": ranDomness,
        "maxEpoch": Number(maxEpoch),
        "jwt":jwtUser
    }
    
    console.log('zkpPayload',zkpPayload);
    
    //nếu là mạng testnet
    const proofResponse = await axios.post(type_network == 'testnet' ? "/zkptestnet/post" : "/zkpdevnet/post", zkpPayload);

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

        const resUpdate_nft_status = await axios.post("/update_nft_status", body);
        
        console.log('resUpdate_nft_status',resUpdate_nft_status);

        alert(`Claim NFT is success. Please see on https://suiscan.xyz/${type_network}/tx/${digest}`);
        $('#button-claim').hide()
        $('#button-claim-link').attr('href', `https://suiscan.xyz/${type_network}/tx/${digest}`);
        $('#button-claim-link').show();
        $('.loading').hide();

    } catch (error) {
        console.log(error);
        alert(error.message);
        $('.loading').hide();

    }
   
    return;

    try {
        
    
      
     

        $('#button-claim').hide()
        // $('#button-claim-link').attr('href', 'https://suiscan.xyz/devnet/tx/'+resultUserClaim.digest);
        $('#button-claim-link').show();
        alert('user clainm success');
        
    } catch (error) {
        console.log('claim sesisons error',error);
        alert('user clainm fail');
    }


    
    return;
    const emailLogin = $('#email_login').val();
    console.log(emailLogin);
    //const didToken = await magic?.auth.loginWithEmailOTP({email: emailLogin})
// const userAddress = '0xef2fB5192536d336c47681CBe861381D44A83DF2';
// const userPublicKey = new web3.PublicKey(userAddress)
    //const metadata = await magic.user.getMetadata();
    //console.log(metadata);
   // const adapter = new PublicKey(metadata.publicAddress);
    //console.log(adapter)
// const adapter = userPublicKey;
    //walletOr = metadata.publicAddress;
    const mintKeypairId = $('#address_nft').val();
    const feeW = $('#address_organizer').val();
    const seedP = $('#seed').val();
    const nftId = $('#nft_id').val();

    try {
        const body = {
            nft_id: nftId
        }
        console.log(body);

        const res = await axios.post("/update_nft_status", body);
        alert('Claim NFT is success')
        $('.loading').hide();
        $('#button-claim').hide()
        $('#button-claim-link').show();
    } catch (error) {
        alert(error.message);
    }
    return;

    console.log(mintKeypairId);
    console.log(feeW)
    console.log(seedP)
    const mintKeypair = new PublicKey(mintKeypairId);
    const wallet0 = adapter
    console.log(mintKeypair);

    const buyTokenAddress = await getAssociatedTokenAddress(
        mintKeypair,
        adapter
    );

    const seed = new BN(seedP); // TODO: enter seed
    const pool = web3.PublicKey.findProgramAddressSync(
        [
            Buffer.from("state"),
            seed.toArrayLike(Buffer, "le", 8)
        ],
        program.programId
    )[0];

    const poolTokenAddress = await getAssociatedTokenAddressSync(
        mintKeypair,
        pool,
        true
    );

    const feeWallet = new PublicKey(feeW);

    const claimTx = await program.methods
        .claim()
        .accounts(
            {
                mint: mintKeypair,
                poolTokenAccount: poolTokenAddress,
                pool: pool,
                buyerTokenAccount: buyTokenAddress,
                buyerAuthority: adapter,
                feeWallet: feeWallet,
            }
        ).instruction();

    await confirmSign(adapter, claimTx, mintKeypair, feeWallet);

    try {
        const body = {
            nft_id: nftId
        }
        console.log(body);

        const res = await axios.post("/update_nft_status", body);
        alert('Claim NFT is success. Please see on https://explorer.solana.com/')
        $('.loading').hide();
        $('#button-claim').hide()
        $('#button-claim-link').show();
    } catch (error) {
        alert(error.message);
    }

}

async function confirmSign(ownerWallet, mintTx, mintKeypair, feeWallet) {
    const blockhash = await connection?.getLatestBlockhash()
    const tx = new web3.Transaction({
        ...blockhash,
        feePayer: ownerWallet,
    })

    const setComputeUnitLimitInstruction = web3.ComputeBudgetProgram.setComputeUnitLimit(
        {units: 500_000}
    );
    tx.add(setComputeUnitLimitInstruction, mintTx);
    const signedTransaction = await magic?.solana.signTransaction(
        tx,
        {
            requireAllSignatures: false,
            verifySignatures: true,
        }
    )
    console.log(tx);

    // const signedTx = await ownerWallet.signTransaction(tx);
    const signature = await connection?.sendRawTransaction(
        Buffer.from(signedTransaction?.rawTransaction, "base64")
    )
}
