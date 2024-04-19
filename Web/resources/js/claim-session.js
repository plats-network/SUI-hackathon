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
import {TransactionBlock} from "@mysten/sui.js/transactions";
import { getFullnodeUrl, SuiClient }  from '@mysten/sui.js/client';
import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";

import axios from "axios";

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

$('#button-claim').click(async function () {
    //$('.loading').show();
    //user login jdk
    const address_nft_min = $("#address_nft_min").val();
    console.log('address_nft_min',address_nft_min);
    const zkLoginUserAddress = localStorage.getItem("zkLoginUserAddress");
    if(!zkLoginUserAddress){
        alert('user can`t login')
        return;
    }

    const tx = new TransactionBlock();
    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    tx.transferObjects([tx.object(address_nft_min)] , zkLoginUserAddress);


    //console.log('signAndExecuteTransactionBlock',result);
    let mnemonic_client = 'genius exit shallow wealth boring layer rotate model calm behind immune maze';
    // let collection_id = '0x2587305d59dbcc09406e  1ef0147053fff3019a64aca312108adac2913785a6d0';
    // let package_id = '0x5ff08c4a46f0e68e9677f6be420b6adf9f0fc90355f978ea235173fffc061a5c';
    const keypair = Ed25519Keypair.deriveKeypair(mnemonic_client);
    try {
        
        const resultUserClaim = await client.signAndExecuteTransactionBlock({
            signer: keypair,
            transactionBlock: tx,
        });
        console.log(resultUserClaim);    
        console.log(`Sessions id :`,address_nft_min);
        console.log('resultUserClaim',resultUserClaim);
        $('#button-claim').hide()
        $('#button-claim-link').attr('href', 'https://suiscan.xyz/testnet/tx/'+resultUserClaim.digest);
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
