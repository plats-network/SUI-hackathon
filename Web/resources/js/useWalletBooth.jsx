import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect,useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";

export default function App() {
    const wallet = useWallet();
    const [boothData, setBoothData] = useState([]);

    const handleClick = async () => {
        const newBoothData = [];
        
        $('.itemBoothDetailMint').each(function (index) {
            const nameBooth = $(this).find('.name_booth').val();
            const descriptionBooth = $(this).find('.description_booth').val();
            const fileBooth = $(this).attr('src') ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';

            const boothObj = {
                nameBooth: nameBooth,
                descriptionBooth: descriptionBooth,
                fileBooth: fileBooth
            };

            newBoothData.push(boothObj);
        });

        setBoothData(newBoothData);
    }
    const mint = async (wallet,data) => {
        console.log(data);
        let newData = {
            nameBooth: data.map(item => item.nameBooth),
            descriptionBooth: data.map(item => item.descriptionBooth),
            fileBooth: data.map(item => item.fileBooth)
        };
        const tx = new TransactionBlock();
        let packageId = "0x769941cd7b338429e9ada6f6e697e47461971c6bc2c8c45d8a1f3e412c4767ea";
        tx.moveCall({
            target: `${packageId}::client::mint_batch_booths`,
            arguments: [
                tx.pure(newData.nameBooth),
                // description: vector<vector<u8>>,
                tx.pure(newData.descriptionBooth),
                // url: vector<vector<u8>>,
                tx.pure(newData.fileBooth),

                tx.object('0x0d6422b82f418e592546019b81585963300f2f29acb86a281e5add34f3388c7d'),
            ],
            typeArguments: [`${packageId}::ticket_nft::NFTTicket`]
        });

  
        const result = await wallet.signAndExecuteTransactionBlock({
            transactionBlock: tx,
        });

        console.log(result);
        if(!result.confirmedLocalExecution){
            alert('nft minted Booth fail!');
            return;
        }
        alert('nft minted Booth successfully!');
    }
    useEffect(() => {

        if(boothData.length > 0){

            mint(wallet,boothData);

            console.log(wallet);
            console.log(boothData);
        }

    }, [boothData]);

    return (
        <div className="App">
            {/* <h1 className="title gradient">Hello, Suiet Wallet Kit</h1> */}
            <ConnectButton />

            <section>
                {/* <p>
                    <span class="gradient">Wallet status:</span> {wallet.status}
                </p> */}
                {wallet.status === "connected" && (
                    <>
                        {wallet?.account && (
                            <>
                                <p>
                                <button id="btnGenItemBooth" onClick={handleClick}  type="button" class="btn btn-primary btn-rounded waves-effect waves-light mb-2  mt-2 me-2">Generate Booth</button>
                                    {/* <span class="gradient">Connected Account: </span>
                                    {wallet.account.address}
                                </p>
                                <p>
                  <span class="gradient">
                    Connected Account (with ellipsis):{" "}
                  </span>
                                    {addressEllipsis(wallet.account.address)} */}
                                </p>
                            </>
                        )}
                        {/* <p>
                            <span class="gradient">Current chain of wallet: </span>
                            {wallet.chain.name}
                        </p> */}
                    </>
                )}
            </section>
        </div>
    );
}
