import { 
  Connection, 
  Keypair, 
  PublicKey,
  Transaction,
  SystemProgram,
  LAMPORTS_PER_SOL,
 } from "@solana/web3.js";
import { Metaplex, keypairIdentity, bundlrStorage, toMetaplexFile, toBigNumber } from "@metaplex-foundation/js";
import * as fs from 'fs';
import dotenv from "dotenv";
import base58, * as bs58 from "bs58";
import * as anchor from "@coral-xyz/anchor";
import { Nft} from "../target/types/nft";
dotenv.config(); 

const QUICKNODE_RPC = 'https://example.solana-devnet.quiknode.pro/0123456/';
const SOLANA_CONNECTION = new Connection(QUICKNODE_RPC);
const privateKey = bs58.decode(process.env.PRIVATE_KEY ?? '')
const WALLET = Keypair.fromSecretKey(new Uint8Array(privateKey));
console.log(WALLET.publicKey)
const METAPLEX = Metaplex.make(SOLANA_CONNECTION)    
  .use(keypairIdentity(WALLET))    
  .use(bundlrStorage({        
    address: 'https://devnet.bundlr.network',        
    providerUrl: QUICKNODE_RPC,        
    timeout: 60000,    
}));

const CONFIG = {    
  uploadPath: 'uploads/',    
  imgFileName: 'image.jpg',    
  imgType: 'image/jpg',    
  imgName: 'name',    
  description: 'name',    
  attributes: [        
    {trait_type: 'Speed', value: 'Quick'},        
    {trait_type: 'Type', value: 'Pixelated'},        
    {trait_type: 'Background', value: 'QuickNode Blue'}    
  ],    
  sellerFeeBasisPoints: 500,//500 bp = 5%    
  symbol: 'ANH',    
  creators: [        
    {address: WALLET.publicKey, share: 100}    
  ]
};

async function main() {    
  // console.log(`Minting ${CONFIG.imgName} to an NFT in Wallet ${WALLET.publicKey.toBase58()}.`);
  // //Step 1 - Upload Image    
  // const imgUri = await uploadImage(CONFIG.uploadPath, CONFIG.imgFileName);
  // //Step 2 - Upload Metadata    
  // const metadataUri = await uploadMetadata(imgUri, CONFIG.imgType, CONFIG.imgName, CONFIG.description, CONFIG.attributes); 
  //Step 3 - Mint NFT
  await mintNft();

}
main();

async function uploadImage(filePath: string,fileName: string) {    
  console.log(`Step 1 - Uploading Image`);
//   const filePath = "/home/anh/rust/nft/app/";
//   const fileName = "image.jpg"
  const imgBuffer = fs.readFileSync(filePath+fileName);
  const imgMetaplexFile = toMetaplexFile(imgBuffer,fileName);
  const imgUri = await METAPLEX.storage().upload(imgMetaplexFile);    
  console.log(`   Image URI:`,imgUri);    
  return imgUri;
}

async function uploadMetadata(
  imgUri: string, 
  imgType: string, 
  nftName: string, 
  description: string, 
  attributes: {trait_type: string, value: string}[]
) {    
  console.log(`Step 2 - Uploading Metadata`);  
  const { uri } = await METAPLEX    
  .nfts()    
  .uploadMetadata({        
    name: nftName,        
    description: description,        
    image: imgUri,        
    attributes: attributes,        
    properties: {            
      files: [                
        {                    
          type: imgType,                    
          uri: imgUri,                
        },            
      ]        
    }    
  });    
  console.log('   Metadata URI:',uri);    
  return uri;  
}

async function mintNft() {    
  console.log(`Step 3 - Minting NFT`);
  
}