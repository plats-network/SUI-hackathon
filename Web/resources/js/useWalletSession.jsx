import { ConnectButton, useWallet, addressEllipsis } from "@suiet/wallet-kit";
import "@suiet/wallet-kit/style.css"; // don't forget to import default stylesheet
import React, { useEffect, useState } from 'react';
import {TransactionBlock} from "@mysten/sui.js/transactions";

export default function App() {
    const wallet = useWallet();
 
    const [sessionData, setSessionData] = useState([]);

    const handleClick = async () => {
        const newSessionData = [];
        
        $('.itemSessionDetailMint').each(function (index) {
            const nameSession = $(this).find('.name_session').val();
            const descriptionSession = $(this).find('.description_session').val();
            const fileSession = $(this).attr('src') ?? 'https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4';
            const sessionObj = {
                nameSession: nameSession,
                descriptionSession: descriptionSession,
                fileSession: fileSession
            };
            console.log('fileSession',fileSession);
            newSessionData.push(sessionObj);
        });

        setSessionData(newSessionData);
    }

    const mint = async (wallet,data) => {
        console.log(data);
        let newData = {
            nameSession: data.map(item => item.nameSession),
            descriptionSession: data.map(item => item.descriptionSession),
            fileSession: data.map(item => item.fileSession)
        };
        const tx = new TransactionBlock();
        let packageId = "0x769941cd7b338429e9ada6f6e697e47461971c6bc2c8c45d8a1f3e412c4767ea";
        tx.moveCall({
            target: `${packageId}::client::mint_batch_sessions`,
            arguments: [
                
                tx.pure(newData.nameSession),
                // description: vector<vector<u8>>,
                tx.pure(newData.descriptionSession),
                // url: vector<vector<u8>>,
                tx.pure(newData.fileSession),

                //tx.pure(data.nameSession),
                //tx.pure(data.descriptionSession),
                //tx.pure(data.fileSession),
                tx.object('0x0d6422b82f418e592546019b81585963300f2f29acb86a281e5add34f3388c7d'),
            ],
            typeArguments: [`${packageId}::ticket_nft::NFTTicket`]
        });
         const result = await wallet.signAndExecuteTransactionBlock({
            transactionBlock: tx,
        });
        
        console.log(result);
        if(!result.confirmedLocalExecution){
            alert('nft minted Session fails!');
            return;
        }
        alert('nft minted Session successfully!');

        
    }
    useEffect(() => {
        
        if(sessionData.length > 0){

            mint(wallet,sessionData);
        }
    }, [sessionData]);

    return (
        <div className="App">
            <ConnectButton />
            <section>
                {wallet.status === "connected" && (
                    <>
                        {wallet?.account && (
                            <>
                                <p>
                                    <button id="btnGenItemSession" onClick={handleClick} type="button" className="btn btn-primary btn-rounded waves-effect waves-light mb-2 mt-2 me-2">Generate Session</button>
                                </p>
                            </>
                        )}
                    </>
                )}
            </section>
        </div>
    );
}
