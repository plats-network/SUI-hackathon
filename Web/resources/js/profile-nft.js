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


$('.wallet-user').click(async function () {
    const rpcUrl = 'https://api.devnet.solana.com';
    const nftEmail = $('#email_nft').val()
    $('.loading').show();
    if (nftEmail) {
        const magic = new Magic("pk_live_F223EA517482BAF8", {
            extensions: {
                solana: new SolanaExtension({
                    rpcUrl
                })
            }
        });
        const didToken = await magic?.auth.loginWithEmailOTP({email: nftEmail});
        magic.wallet.showNFTs()
    }
    $('.loading').hide();
})
