import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import NftInput from "./sui_components/nftInput";
import React, {useState} from 'react';

function createMintNftTxnBlock(data) {
    // define a programmable transaction block
    const txb = new TransactionBlock();

    // note that this is a devnet contract address
    const contractAddress =
        "0xe83d5c6059f09a1c98d73603c0ec7ef9c148fdd4983f90837426cc2cbf55cb94";
    const contractModule = "client";
    const contractMethod = "mint_batch";


    const nftName = data.nft_name;
    const nftAmount = data.nft_amount;
    const nftCategory = data.nft_category;
    const nftDescription = data.nft_symbol;
    const nftImgUrl = data.image_file ?? "https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4";
    const nftCollectionId = "0x5682fb257218baf7e9f4d0cac8c41875b8870ca2a2463cad4d0d4cdd37cee989"

    txb.moveCall({
        target: `${contractAddress}::${contractModule}::${contractMethod}`,
        arguments: [
            // collection object id
            txb.object(nftCollectionId),
            // name: vector<u8>,
            txb.pure(nftName),
            // description: vector<u8>,
            txb.pure(nftDescription),
            // url: vector<u8>,
            txb.pure(nftImgUrl),
            // catogory: vector<u8>,
            txb.pure(nftCategory),
            // max_supply: u64,
            txb.pure(nftAmount),
        ],
        typeArguments: [`${contractAddress}::ticket_nft::NFTTicket`]

    });

    return txb;
}

export default function MintNft({nftData}) {
    const wallet = useWallet();
    const [nftInputs, setNftInputs] = useState([]);

    async function mintNft(event) {
        event.preventDefault();
        if (!wallet.connected) return;

        for (let i = 0; i < nftData.length; i++) {
            const txb = createMintNftTxnBlock(nftData[i]);
            try {
                const res = await wallet.signAndExecuteTransactionBlock({
                    transactionBlock: txb
                });
                alert("Congrats! your nft is minted!");
                console.log("nft minted successfully!", res);

                // Add a new NftInput for each successful mint
                for (let j = 0; j < Number(nftData[i].nft_amount); j++) {
                    // setNftInputs(prevInputs => [...prevInputs, nftData[i]]);
                    setNftInputs(prevInputs => [...prevInputs, {...nftData[i], res: JSON.stringify(res)}]);
                }

            } catch (e) {
                alert("Oops, nft minting failed");
                console.error("nft mint failed", e);
            }
        }

    }

    return (
        <div className="App">
            <ConnectButton/>
            <section style={{marginTop: '15px', textAlign: 'right'}}>
                {wallet.status === "connected" && (
                    <>
                        <button className="btn btn-primary" onClick={mintNft}> Mint Your NFT !</button>
                    </>
                )}
            </section>
            {nftInputs.map((nftData, index) => <NftInput key={index} nftData={nftData}/>)}
        </div>
    );
}
