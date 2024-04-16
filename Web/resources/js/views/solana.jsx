'use client';

import { useAnchorProvider } from '../app/hook';
import { web3, Program } from '@project-serum/anchor';
import { getAssociatedTokenAddress } from '@solana/spl-token';
import {ConnectionProvider, useWallet, WalletProvider} from '@solana/wallet-adapter-react';
import {
    useWalletModal, WalletConnectButton, WalletDisconnectButton, WalletModalButton,
    WalletModalContext,
    WalletModalProvider,
    WalletMultiButton
} from '@solana/wallet-adapter-react-ui';
import {PublicKey, ComputeBudgetProgram, clusterApiUrl} from '@solana/web3.js';
import idl from './../app/abi/abi.json';
import RootLayout from "../app/layout";
import { createRoot } from "react-dom/client";
import {SolanaProvider} from "../app/provider";
import {WalletAdapterNetwork} from "@solana/wallet-adapter-base";
import {
    LedgerWalletAdapter,
    PhantomWalletAdapter,
    SolflareWalletAdapter,
    TorusWalletAdapter, UnsafeBurnerWalletAdapter
} from "@solana/wallet-adapter-wallets";
import {useContext, useMemo} from "react";

export default function HomeView() {

    // The network can be set to 'devnet', 'testnet', or 'mainnet-beta'.
    const network = WalletAdapterNetwork.Devnet;

    // You can also provide a custom RPC endpoint.
    const endpoint = useMemo(() => clusterApiUrl(network), [network]);

    const wallets = useMemo(
        () => [
            /**
             * Wallets that implement either of these standards will be available automatically.
             *
             *   - Solana Mobile Stack Mobile Wallet Adapter Protocol
             *     (https://github.com/solana-mobile/mobile-wallet-adapter)
             *   - Solana Wallet Standard
             *     (https://github.com/anza-xyz/wallet-standard)
             *
             * If you wish to support a wallet that supports neither of those standards,
             * instantiate its legacy wallet adapter here. Common legacy adapters can be found
             * in the npm package `@solana/wallet-adapter-wallets`.
             */
            new UnsafeBurnerWalletAdapter(),
        ],
        // eslint-disable-next-line react-hooks/exhaustive-deps
        [network]
    );

    return (
        <ConnectionProvider endpoint={endpoint}>
            <WalletProvider wallets={wallets} autoConnect>
                <WalletModalProvider>
                    <WalletModalButton />
                    { /* Your app's components go here, nested within the context providers. */ }
                </WalletModalProvider>
            </WalletProvider>
        </ConnectionProvider>
    );
}

const loginButton = createRoot(document.getElementById("example"));
if (loginButton !== null) {
    console.log(123);
    loginButton.render(<HomeView/>);
}
