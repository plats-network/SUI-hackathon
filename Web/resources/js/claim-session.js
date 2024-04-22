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

const rpcUrl = 'https://api.devnet.solana.com';

const magic = new Magic("pk_live_F223EA517482BAF8", {
    extensions: {
        solana: new SolanaExtension({
            rpcUrl
        })
    }
});
const solConnect = new window.SolanaConnect();
var walletOr = '';
var pub = '';
var TOKEN_METADATA_PROGRAM_ID = new PublicKey("metaqbxxUerdq28cj1RbAWkYQm3ybzjb6a8bt518x1s");
const connection = new Connection(web3.clusterApiUrl("devnet"));
const PROGRAM_ID = new PublicKey("D5GK8Kye78gjuDMMjRnkWH5a6KfNEXzex5mekXL3HLR2");
let provider = new AnchorProvider(connection, solConnect.getWallet(), {commitment: "confirmed"})
let program = new Program(idl, PROGRAM_ID, provider);

$('#button-claim-test').click(async function(){
    
    let jwt = 'eyJhbGciOiJSUzI1NiIsImtpZCI6IjkzYjQ5NTE2MmFmMGM4N2NjN2E1MTY4NjI5NDA5NzA0MGRhZjNiNDMiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiIyOTA1NTQwNDEyODUtZzc3YXJzNTRtOXZjMmh2dWd2MW9la2h0ZDU0ZWxsOXAuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiIyOTA1NTQwNDEyODUtZzc3YXJzNTRtOXZjMmh2dWd2MW9la2h0ZDU0ZWxsOXAuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDQ0NTE5MDAwOTA3NDQ0ODU2ODkiLCJub25jZSI6IjFTRmxYd05Kd09CenFqcExDN1p3RXZ6clkxNCIsIm5iZiI6MTcxMzA3ODAyOCwiaWF0IjoxNzEzMDc4MzI4LCJleHAiOjE3MTMwODE5MjgsImp0aSI6IjdhNThkOWJlZDZmYzZlN2RiYzAxNjUzY2MwZjVmYWMyYmQzMjQ5MGEifQ.RPI0hfE70IN6f87y98CKW7ZGic0spRBcKlmB1K-5IrnkORK4hOzXiAbQTPfwWirreChOPvY6ogSgR8txsahDvT0AJovJjJIs9ZGyaryqKCzrwV6b9x8HgWhz0uwFaykN8O29dwG8kmeIdQ4SOjwNtBuvRi4ECQL_xK6VXsUAqtqxW44bzX5N3M_2xZCmxsgcvCv4dQ9Xh88w1H4JDYHci50ZuHCFH1ZlsGvpCFWZPEBIebiOZjGUyJ-D1eHhN04SOiTH3HVnpT38f36s7CeGX91DFTD6QAVT8r-QiKMY0y1dLCARIxyBPow26vGU-O0D62M7-g8HHLi3AbJ_8QelGA';
    
    let salt = '625269969902316412932473140542886827510878';
   
    const zkLoginUserAddress =  jwtToAddress(jwt, salt);

    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });

    const accountBalances = await client.getBalance({owner: zkLoginUserAddress});
});

function keypairFromSecretKey(privateKeyBase64){
    const keyPair = decodeSuiPrivateKey(privateKeyBase64);
    return Ed25519Keypair.fromSecretKey(keyPair.secretKey);
}

$('#button-claim').click(async function () {
    //$('.loading').show();
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
    
        url: getFullnodeUrl('devnet'),
    });

    const txb = new TransactionBlock();
    
    //https://cws-suivent.plats.test/dE
    const object_id   = '0x2f50f9643f52174a339568fe829c83909abdb21c66f29340a7cf2d55719761d3';
    
    txb.setSender(zkLoginUserAddress);
    txb.setGasBudget(5000000);
    txb.moveCall({
        target: `${packageId}::ticket_collection::claim_session`,
        arguments: [
            txb.object(event_object_id),
            txb.pure('0x2f50f9643f52174a339568fe829c83909abdb21c66f29340a7cf2d55719761d3')
        ],
        typeArguments: [`${packageId}::ticket_collection::NFTSession`]
    });
    
    const ephemeralKeyPairs = keypairFromSecretKey(ephemeralPrivateKey);

    console.log('Pair',ephemeralKeyPairs);

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
    
    const proofResponse = await axios.post("/zkp/post", zkpPayload);
    const zkLoginSignature  = getZkLoginSignature({
        inputs: {
            ...proofResponse.data,
            addressSeed
        },
        maxEpoch:maxEpoch,
        userSignature:signature,
    });

    const result = await client.executeTransactionBlock({
        transactionBlock: bytes,
        signature: zkLoginSignature,
    });

    console.log('result',result);
    console.log('zkLoginSignature',zkLoginSignature);
   
    return;

    try {
        
    
      
     

        $('#button-claim').hide()
        // $('#button-claim-link').attr('href', 'https://suiscan.xyz/testnet/tx/'+resultUserClaim.digest);
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

})

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
