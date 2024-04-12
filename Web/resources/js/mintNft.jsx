import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet

function createMintNftTxnBlock() {
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
[
    {
        nftName:"Suiet NFT",
        nftDescription:"Hello, Suiet NFT",
        nftImgUrl:"https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4"
    },
    {
        nftName:"Suiet NFT",
        nftDescription:"Hello, Suiet NFT",
        nftImgUrl:"https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4"
    },
    {
        nftName:"Suiet NFT",
        nftDescription:"Hello, Suiet NFT",
        nftImgUrl:"https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4"
    }
]
export default function App() {
    const wallet = useWallet();

    async function mintNft() {
        if (!wallet.connected) return;

        const txb = createMintNftTxnBlock();
        try {
            // call the wallet to sign and execute the transaction
            const res = await wallet.signAndExecuteTransactionBlock({
                transactionBlock: txb
            });
            console.log("nft minted successfully!", res);
            alert("Congrats! your nft is minted!");
        } catch (e) {
            alert("Oops, nft minting failed");
            console.error("nft mint failed", e);
        }
    }

    return (
        <div className="App">
            <ConnectButton/>

            <section>
                {wallet.status === "connected" && (
                    <>
                        <button className="btn btn-primary" onClick={mintNft}> Mint Your NFT !</button>
                    </>
                )}
            </section>
        </div>
    );
}
