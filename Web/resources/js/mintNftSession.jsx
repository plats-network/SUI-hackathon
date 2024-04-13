import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";
import {TransactionBlock} from "@mysten/sui.js/transactions";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import {WalletProvider} from '@suiet/wallet-kit';

function createMintNftTxnBlock(data) {
    // define a programmable transaction block
    const txb = new TransactionBlock();

    // note that this is a devnet contract address
    const contractAddress =
        "0xd0b1403e3d2348ff55ae76b6926a8fbe20c807c0cd59df4b1d6815468f45162d";
    const contractModule = "sui_nft";
    const contractMethod = "mint_to_sender";

    const nftName = data.nft_name;
    const nftDescription = data.nft_symbol;
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

export default function MintNft({nftData}) {

    const wallet = useWallet();

    async function mintNft() {
        if (!wallet.connected) return;

        for (const txb of nftData.map(item => createMintNftTxnBlock(item))) {
            try {
                const res = await wallet.signAndExecuteTransactionBlock({
                    transactionBlock: txb
                });
                console.log("nft minted successfully!", res);
            } catch (e) {
                console.error("nft mint failed", e);
            }
        }

    }

    return (
        <div className="App">
            <ConnectButton/>
            <section>
                <p>
                    <span className="gradient">Wallet status:</span> {wallet.status}
                </p>
                {wallet.status === "connected" && (
                    <>
                        <button className="btn btn-primary" onClick={mintNft}> Mint Your NFT !</button>
                    </>
                )}
            </section>
        </div>
    );
}


ReactDOM.createRoot(document.getElementById('btnGenItemSession')).render(
    <NftForm/>
);
