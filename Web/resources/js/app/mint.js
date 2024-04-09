// // import { SolanaConnect } from "solana-connect";
// // import { Adapter } from "@solana/wallet-adapter-base";
// import {AnchorProvider, BN, Program, setProvider, Wallet, web3} from "@project-serum/anchor";
// import {Connection, PublicKey} from "@solana/web3.js";
// import {TOKEN_PROGRAM_ID} from "@project-serum/anchor/dist/cjs/utils/token";
// import idl from './abi/abi.json'
// import { Buffer } from 'buffer';
// import {getAssociatedTokenAddress} from "@solana/spl-token";
// import {Wallet} from "@solana/wallet-adapter-react";

// const solConnect = new window.SolanaConnect();
// var walletOr = '';
// var TOKEN_METADATA_PROGRAM_ID = new PublicKey( "metaqbxxUerdq28cj1RbAWkYQm3ybzjb6a8bt518x1s");
// const connection = new Connection(web3.clusterApiUrl("devnet"), "confirmed");
// const PROGRAM_ID = new PublicKey( "D5GK8Kye78gjuDMMjRnkWH5a6KfNEXzex5mekXL3HLR2");
// let provider = new AnchorProvider(connection, solConnect.getWallet(), { commitment: "confirmed" })
// let program = new Program(idl, PROGRAM_ID, provider);

// $('#connect_wallet').click(function () {
//     console.log(1)
//     solConnect.openMenu({
//         top: 100
//     });

//     solConnect.onWalletChange((adapter) => {
//         walletOr = adapter.publicKey.toString();
//         console.log(adapter.publicKey.toString())
//             $('#connect_wallet').hide()
//             $('#mint_nft').show();
//         provider = new AnchorProvider(connection, solConnect.getWallet(), { commitment: "confirmed" })
//         program = new Program(idl, PROGRAM_ID, provider);
//         setProvider(provider)
//         console.log(program);
//     });
// })

// $('#mint_nft').click(async function () {
//     const mintKeypair = web3.Keypair.generate();
//     const ownerWallet = new PublicKey(walletOr);

//     const metadataAddress = (web3.PublicKey.findProgramAddressSync(
//         [
//             Buffer.from("metadata"),
//             TOKEN_METADATA_PROGRAM_ID.toBuffer(),
//             mintKeypair.publicKey.toBuffer(),
//         ],
//         TOKEN_METADATA_PROGRAM_ID
//     ))[0];

//     const masterEditionAddress = (web3.PublicKey.findProgramAddressSync(
//         [
//             Buffer.from("metadata"),
//             TOKEN_METADATA_PROGRAM_ID.toBuffer(),
//             mintKeypair.publicKey.toBuffer(),
//             Buffer.from("edition"),
//         ],
//         TOKEN_METADATA_PROGRAM_ID
//     ))[0];

//     const tokenAddress = await getAssociatedTokenAddress(
//         mintKeypair.publicKey,
//         ownerWallet
//     );


//     const mintTx = program.methods
//         .mint('tuanpa', '1', 'https://cms.plats.test/event-preview/9ba98057-484a-4128-8d87-29460ff30918?tab=0&preview=1')
//         .accounts(
//             {
//                 masterEdition: masterEditionAddress,
//                 metadata: metadataAddress,
//                 mint: mintKeypair.publicKey,
//                 tokenAccount: tokenAddress,
//                 mintAuthority: ownerWallet,
//                 tokenMetadataProgram: TOKEN_METADATA_PROGRAM_ID,
//                 systemProgram: web3.SystemProgram.programId
//             }
//         ).instruction();

//     // let airdropSignature = await connection.requestAirdrop(
//     //     walletOr,
//     //     web3.LAMPORTS_PER_SOL,
//     // );
//     console.log(program);

//     const { blockhash, lastValidBlockHeight } = await connection.getLatestBlockhash('confirmed');
//     let tx = new web3.Transaction();
//     // const setComputeUnitLimitInstruction = web3.ComputeBudgetProgram.setComputeUnitLimit(
//     //     { units: 300_000 }
//     // );
//     tx.recentBlockhash = blockhash;
//     tx.feePayer = walletOr;
//     tx.add(mintTx);
//     const signedTx = await Wallet.signTransaction(tx)

//     console.log(tx);
//     const signature = await connection.sendRawTransaction(signedTx.serialize(), {
//         skipPreflight: true,
//         preflightCommitment: 'confirmed',
//         maxRetries:50
//     });

//     await connection.confirmTransaction(
//         {
//             blockhash,
//             lastValidBlockHeight,
//             signature,
//         },
//         'confirmed',
//     );
//     // let signed = await provider.signTransaction(airdropSignature);

//     console.log(mintTx);
// })
// //
// // function getAssociatedTokenAddress(
// //     mint,
// //     owner,
// //     allowOwnerOffCurve = false,
// //     programId = TOKEN_PROGRAM_ID,
// // ) {
// //     // if (!allowOwnerOffCurve && !PublicKey.isOnCurve(owner.toBuffer())) throw new TokenOwnerOffCurveError();
// //     const [address] = PublicKey.findProgramAddress(
// //         [owner.toBuffer(), programId.toBuffer(), mint.toBuffer()],
// //         new PublicKey('ATokenGPvbdGVxr1b2hvZbsiqW5xWH25efTNsLJA8knL')
// //     );
// //
// //     return address;
// // }
