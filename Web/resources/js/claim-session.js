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

$('#button-claim').click(async function () {
    //$('.loading').show();
    
   
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
        alert('Claim NFT is success. Please see on https://explorer.solana.com/')
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
