import { ComputeBudgetProgram, Connection, Keypair, PublicKey, SystemProgram, Transaction, clusterApiUrl, sendAndConfirmTransaction } from "@solana/web3.js";
import { Metaplex, keypairIdentity, bundlrStorage, toMetaplexFile, toBigNumber } from "@metaplex-foundation/js";
import { getAssociatedTokenAddress } from "@solana/spl-token";
import * as fs from 'fs';
import { AnchorProvider, BN, Idl, Program, Wallet, setProvider, web3 } from "@coral-xyz/anchor";
import dotenv from "dotenv";
import base58, * as bs58 from "bs58";
import { Nft} from "../target/types/nft";
import idl from "../target/idl/nft.json";
import { set } from "@coral-xyz/anchor/dist/cjs/utils/features";
dotenv.config(); 

const privateKey = bs58.decode(process.env.PRIVATE_KEY ?? '')
const payer = Keypair.fromSecretKey(privateKey);
const wallet = new Wallet(payer)

const privateKey0 = bs58.decode(process.env.PRIVATE_KEY_TWO ?? '')
const payer0 = Keypair.fromSecretKey(privateKey0);
const wallet0 = new Wallet(payer0)

const connection = new Connection(clusterApiUrl("devnet"), "confirmed");
const PROGRAM_ID = new PublicKey(process.env.MINT_NFT_PROGRAM ?? "");

  
async function mintNft() {    
  let provider: AnchorProvider = new AnchorProvider(connection, wallet, { commitment: "confirmed" })
  setProvider(provider)
  const program = new Program(idl as Idl, PROGRAM_ID)
  const actionNftTitle = "Action NFT";
  const actionNftSymbol = "Action";
  const actionNftUri = "https://raw.githubusercontent.com/Coding-and-Crypto/Solana-NFT-Marketplace/master/assets/example.json"; 

  const freeTicketNftTitle = "Free ticket NFT";
  const freeTicketNftSymbol = "FTICKET";
  const freeTicketNftUri = "https://raw.githubusercontent.com/Coding-and-Crypto/Solana-NFT-Marketplace/master/assets/example.json"; 

  const ticketNftTitle = "Ticket NFT";
  const ticketNftSymbol = "TICKET";
  const ticketNftUri = "https://raw.githubusercontent.com/Coding-and-Crypto/Solana-NFT-Marketplace/master/assets/example.json"; 
  const TOKEN_METADATA_PROGRAM_ID = new PublicKey(
    "metaqbxxUerdq28cj1RbAWkYQm3ybzjb6a8bt518x1s"
  );

  const mintKeypair = web3.Keypair.generate();
  console.log(`mint: ${mintKeypair.publicKey}`)

  const metadataAddress = (await web3.PublicKey.findProgramAddressSync(
    [
      Buffer.from("metadata"),
      TOKEN_METADATA_PROGRAM_ID.toBuffer(),
      mintKeypair.publicKey.toBuffer(),
    ],
    TOKEN_METADATA_PROGRAM_ID
  ))[0];
  console.log("Metadata initialized");
  const masterEditionAddress = (await web3.PublicKey.findProgramAddressSync(
    [
      Buffer.from("metadata"),
      TOKEN_METADATA_PROGRAM_ID.toBuffer(),
      mintKeypair.publicKey.toBuffer(),
      Buffer.from("edition"),
    ],
    TOKEN_METADATA_PROGRAM_ID
  ))[0];

  const tokenAddress = await getAssociatedTokenAddress(
    mintKeypair.publicKey,
    wallet.publicKey
  );

  const buyTokenAddress = await getAssociatedTokenAddress(
    mintKeypair.publicKey,
    wallet0.publicKey
  );

  // console.log(`metadata: ${metadataAddress}`)
  // console.log(`mintAuth: ${wallet.publicKey}`)
  // console.log(`ownerAuth: ${wallet0.publicKey}`)

  try{
    let tx = new Transaction();
    const setComputeUnitLimitInstruction = ComputeBudgetProgram.setComputeUnitLimit(
      { units: 400_000 }
    );
    // // action
    // const mintTx = await program.methods
    // .mint(actionNftTitle, actionNftSymbol, actionNftUri)
    // .accounts(
    //   {
    //     masterEdition: masterEditionAddress,
    //     metadata: metadataAddress,
    //     mint: mintKeypair.publicKey,
    //     tokenAccount: tokenAddress,
    //     mintAuthority: wallet.publicKey,
    //     tokenMetadataProgram: TOKEN_METADATA_PROGRAM_ID,
    //   }
    // ).instruction();

    // const transferTx = await program.methods
    // .claim()
    // .accounts(
    //   {
    //     mint: mintKeypair.publicKey,
    //     ownerTokenAccount: tokenAddress,
    //     ownerAuthority: wallet.publicKey,
    //     buyerTokenAccount: buyTokenAddress,
    //     buyerAuthority: wallet0.publicKey,
    //   }
    // ).instruction();

    // free ticket
    const mintTx = await program.methods
    .mint(freeTicketNftTitle, freeTicketNftSymbol, freeTicketNftUri)
    .accounts(
      {
        masterEdition: masterEditionAddress,
        metadata: metadataAddress,
        mint: mintKeypair.publicKey,
        tokenAccount: tokenAddress,
        mintAuthority: wallet.publicKey,
        tokenMetadataProgram: TOKEN_METADATA_PROGRAM_ID,
      }
    ).instruction();

    const transferTx = await program.methods
    .claim()
    .accounts(
      {
        mint: mintKeypair.publicKey,
        ownerTokenAccount: tokenAddress,
        ownerAuthority: wallet.publicKey,
        buyerTokenAccount: buyTokenAddress,
        buyerAuthority: wallet0.publicKey,
      }
    ).instruction();

    // // ticket
    // const mintTx = await program.methods
    // .mint(ticketNftTitle, ticketNftSymbol, ticketNftUri)
    // .accounts(
    //   {
    //     masterEdition: masterEditionAddress,
    //     metadata: metadataAddress,
    //     mint: mintKeypair.publicKey,
    //     tokenAccount: tokenAddress,
    //     mintAuthority: wallet.publicKey,
    //     tokenMetadataProgram: TOKEN_METADATA_PROGRAM_ID,
    //   }
    // ).instruction();

    // const transferTx = await program.methods
    // .sell()
    // .accounts(
    //   {
    //     mint: mintKeypair.publicKey,
    //     ownerTokenAccount: tokenAddress,
    //     ownerAuthority: wallet.publicKey,
    //     buyerTokenAccount: buyTokenAddress,
    //     buyerAuthority: wallet0.publicKey,
    //   }
    // ).instruction();
    tx.add(setComputeUnitLimitInstruction, mintTx, transferTx);
    const createTx = await sendAndConfirmTransaction(
      connection,
      tx,
      [payer, payer0, mintKeypair]
    );
    console.log(tx)

  }catch (err) {
    console.log("Transaction error: ", err);
  }
  
}

function generateExplorerTxUrl(txId: string) {
  return `https://explorer.solana.com/tx/${txId}?cluster=devnet`;
}

async function main() {
  await mintNft();
}

main()