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
const connection = new Connection(web3.clusterApiUrl(typenetwork));
let typenetwork = $('meta[name="type_network"]').attr('content');
const PROGRAM_ID = new PublicKey("D5GK8Kye78gjuDMMjRnkWH5a6KfNEXzex5mekXL3HLR2");
let provider = new AnchorProvider(connection, solConnect.getWallet(), {commitment: "confirmed"})
let program = new Program(idl, PROGRAM_ID, provider);


const package_id = $('#package_id').val();
const collection_id = $('#collection_id').val();

function keypairFromSecretKey(privateKeyBase64){
    const keyPair = decodeSuiPrivateKey(privateKeyBase64);
    return Ed25519Keypair.fromSecretKey(keyPair.secretKey);
}

$('.btn-claim-id').click(async function () {
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

    const proofResponse = await axios.post("/zkp/post", zkpPayload);
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

})
