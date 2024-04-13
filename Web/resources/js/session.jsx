import React, { useEffect } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";
import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";

function createMintNftTxnSesion() {
  // define a programmable transaction block
  const txb = new TransactionBlock();

  // note that this is a devnet contract address
  const contractAddress =
      "0xd0b1403e3d2348ff55ae76b6926a8fbe20c807c0cd59df4b1d6815468f45162d";
  const contractModule = "sui_nft";
  const contractMethod = "mint_to_sender";

  const nftName = "Suiet NFT";
  const nftDescription = "Hello, Suiet NFT";
  const nftImgUrl =
      "https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4";
 
  txb.moveCall({
      target: `${contractAddress}::${contractModule}::${contractMethod}`,
      arguments: [
          txb.pure(nftName),
          txb.pure(nftDescription),
          txb.pure(nftImgUrl)
      ]
  });

  return txb;
}

function SessionComponent() {
  const wallet = useWallet();

  useEffect(() => {
    const btn = document.getElementById('btnGenItemSession');
    if (btn) {
      btn.addEventListener('click', () => {
        alert(11111);  
        const txb = createMintNftTxnSesion();
        console.log(txb);
      });
    }
  }, []);

  return null; // This component doesn't render anything
}

export default SessionComponent;