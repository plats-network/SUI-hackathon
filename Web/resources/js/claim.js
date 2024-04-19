import {TransactionBlock} from "@mysten/sui.js/transactions";
import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';

const mnemonic_client = $('#mnemonic_client').val();
const package_id = $('#package_id').val();
const collection_id = $('#collection_id').val();
const keypair = Ed25519Keypair.deriveKeypair(mnemonic_client);
const client = new SuiClient({
    url: getFullnodeUrl('testnet'),
});


import {Magic} from 'magic-sdk';
import {SolanaExtension} from "@magic-ext/solana";
import {AnchorProvider, BN, Program, setProvider, web3} from "@project-serum/anchor";
import {Connection, Keypair, LAMPORTS_PER_SOL, PublicKey, TransactionMessage} from "@solana/web3.js";
import idl from './abi/abi.json'
import {Buffer} from 'buffer';
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

// $('.claim-btn').click(async function () {
//     const email = $('#email').val();
//
//     if (!email) {
//         alert('Please input to email !!!')
//     } else {
//         $('#myModal').modal('hide');
//         $('.loading').show();
//         const didToken = await magic?.auth.loginWithEmailOTP({email: email});
//         const metadata = await magic.user.getMetadata();
//         const adapter = new PublicKey(metadata.publicAddress);
//         walletOr = metadata.publicAddress;
//         let balance = await connection.getBalance(new PublicKey(adapter));
//         console.log(balance)
//         const mintKeypairId = $('#address_nft').val();
//         const feeW = $('#address_organizer').val();
//         const seedP = $('#seed').val();
//         const nftId = $('#nft_id').val();
//         console.log(mintKeypairId);
//         console.log(feeW)
//         console.log(seedP)
//         const mintKeypair = new PublicKey(mintKeypairId);
//         const wallet0 = adapter
//         console.log(mintKeypair);
//
//         // check wallet
//         if (balance/LAMPORTS_PER_SOL <= 0) {
//             $('.loading').hide();
//             alert('Balance is empty, please deposit Sol to claim nft !!!')
//         } else {
//             const buyTokenAddress = await getAssociatedTokenAddress(
//                 mintKeypair,
//                 adapter
//             );
//
//             const seed = new BN(seedP); // TODO: enter seed
//             const pool = web3.PublicKey.findProgramAddressSync(
//                 [
//                     Buffer.from("state"),
//                     seed.toArrayLike(Buffer, "le", 8)
//                 ],
//                 program.programId
//             )[0];
//
//             const poolTokenAddress = await getAssociatedTokenAddressSync(
//                 mintKeypair,
//                 pool,
//                 true
//             );
//
//             const feeWallet = new PublicKey(feeW);
//
//             const claimTx = await program.methods
//                 .claim()
//                 .accounts(
//                     {
//                         mint: mintKeypair,
//                         poolTokenAccount: poolTokenAddress,
//                         pool: pool,
//                         buyerTokenAccount: buyTokenAddress,
//                         buyerAuthority: adapter,
//                         feeWallet: feeWallet,
//                     }
//                 ).instruction();
//
//             await confirmSign(adapter, claimTx, mintKeypair, feeWallet);
//
//             try {
//                 const body = {
//                     nft_id: nftId,
//                     email: email,
//                     address: wallet0
//                 }
//                 console.log(body);
//
//                 const res = await axios.post("/update_nft_status", body);
//                 alert('Claim NFT is success. Please see on https://explorer.solana.com/')
//                 $('.loading').hide();
//                 $('.claim-success').show();
//                 $('.sol-link').show();
//                 $('.btn-claim-id').hide();
//                 $('.claim-btn').hide();
//             } catch (error) {
//                 alert(error.message);
//             }
//         }
//     }
// });
$('.claim-btn').click(async function () {
    const email = $('#email').val();

    if (!email) {
        alert('Please input to email !!!')
    } else {
        $('#myModal').modal('hide');
        $('.loading').show();
        // const didToken = await magic?.auth.loginWithEmailOTP({email: email});
        // const metadata = await magic.user.getMetadata();
        // const adapter = new PublicKey(metadata.publicAddress);
        // walletOr = metadata.publicAddress;
        // let balance = await connection.getBalance(new PublicKey(adapter));
        // console.log(balance)
        // const mintKeypairId = $('#address_nft').val();
        // const feeW = $('#address_organizer').val();
        // const seedP = $('#seed').val();
        const nftId = $('#nft_id').val();
        // console.log(mintKeypairId);
        // console.log(feeW)
        // console.log(seedP)
        // const mintKeypair = new PublicKey(mintKeypairId);
        // const wallet0 = adapter
        // console.log(mintKeypair);
        //
        // // check wallet
        // if (balance/LAMPORTS_PER_SOL <= 0) {
        //     $('.loading').hide();
        //     alert('Balance is empty, please deposit Sol to claim nft !!!')
        // } else {
        //     const buyTokenAddress = await getAssociatedTokenAddress(
        //         mintKeypair,
        //         adapter
        //     );
        //
        //     const seed = new BN(seedP); // TODO: enter seed
        //     const pool = web3.PublicKey.findProgramAddressSync(
        //         [
        //             Buffer.from("state"),
        //             seed.toArrayLike(Buffer, "le", 8)
        //         ],
        //         program.programId
        //     )[0];
        //
        //     const poolTokenAddress = await getAssociatedTokenAddressSync(
        //         mintKeypair,
        //         pool,
        //         true
        //     );
        //
        //     const feeWallet = new PublicKey(feeW);
        //
        //     const claimTx = await program.methods
        //         .claim()
        //         .accounts(
        //             {
        //                 mint: mintKeypair,
        //                 poolTokenAccount: poolTokenAddress,
        //                 pool: pool,
        //                 buyerTokenAccount: buyTokenAddress,
        //                 buyerAuthority: adapter,
        //                 feeWallet: feeWallet,
        //             }
        //         ).instruction();
        //
        //     await confirmSign(adapter, claimTx, mintKeypair, feeWallet);

        try {
            const body = {
                nft_id: nftId,
                email: email,
                // address: wallet0
            }
            console.log(body);

            const res = await axios.post("/update_nft_status", body);
            alert('Claim NFT is success. Please see on https://suiscan.xyz/')
            $('.loading').hide();
            $('.claim-success').show();
            $('.sol-link').show();
            $('.btn-claim-id').hide();
            $('.claim-btn').hide();
            $('.showModal').hide();
        } catch (error) {
            alert(error.message);
        }
    }
    // }
});

$('.btn-claim-id').click(async function () {
    $('.loading').show();
    let addressClient = keypair.getPublicKey().toSuiAddress();
    const tx = new TransactionBlock();
    let packageId = package_id;
    let collectionId = collection_id;
    let zkLoginUserAddress = localStorage.getItem('zkLoginUserAddress');

    // let ticket_id = tickets[0][indexNft];
    let ticket_id = $('#ticket_id').val();


    tx.transferObjects([tx.object(ticket_id)], zkLoginUserAddress);

    const nftId = $('#nft_id').val();
    //
    try {
        console.log(zkLoginUserAddress);
        const result = await client.signAndExecuteTransactionBlock({
            signer: keypair,
            transactionBlock: tx,
        });
        console.log(result);
        let digest = result.digest;
        const body = {
            nft_id: nftId,
            digest: digest,
        }
        // console.log(body);

        const res = await axios.post("/update_nft_status", body);
        alert(`Claim NFT is success. Please see on https://suiscan.xyz/${digest}`);
        $('.loading').hide();
        // $('.claim-success').show();
        $('.claim-success').css('display', 'block');
        $('.sol-link').attr('href', `https://suiscan.xyz/${digest}`);
        $('.sol-link').show();
        $('.btn-claim-id').hide();
        $('.claim-btn').hide();
        $('.showModal').hide();
        // window.location.reload();
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
