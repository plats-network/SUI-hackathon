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
            const fileBooth = $(this).find('.img-preview').val() ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';

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
        const tx = new TransactionBlock();
        let packageId = "0xe83d5c6059f09a1c98d73603c0ec7ef9c148fdd4983f90837426cc2cbf55cb94";
        tx.moveCall({
            target: `${packageId}::client::mint_booth`,
            arguments: [
                tx.pure(data.nameBooth),
                tx.pure(data.descriptionBooth),
                tx.pure(data.fileBooth),
                tx.object('0x5682fb257218baf7e9f4d0cac8c41875b8870ca2a2463cad4d0d4cdd37cee989'),
            ],
            typeArguments: [`${packageId}::ticket_nft::NFTTicket`]
        });

  
        const result = await wallet.signAndExecuteTransactionBlock({
            transactionBlock: tx,
        });

        console.log(result);
        if(result.confirmedLocalExecution){
            alert('nft minted Booth successfully!');
            return;
        }
    }
    useEffect(() => {

        if(boothData.length > 0){

            mint(wallet,boothData[0] ?? []);

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
