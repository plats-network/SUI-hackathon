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
        const tx = new TransactionBlock();
        let packageId = "0xe83d5c6059f09a1c98d73603c0ec7ef9c148fdd4983f90837426cc2cbf55cb94";
        tx.moveCall({
            target: `${packageId}::client::mint_session`,
            arguments: [
                tx.pure(data.nameSession),
                tx.pure(data.descriptionSession),
                tx.pure(data.fileSession),
                tx.object('0x5682fb257218baf7e9f4d0cac8c41875b8870ca2a2463cad4d0d4cdd37cee989'),
            ],
            typeArguments: [`${packageId}::ticket_nft::NFTTicket`]
        });

  
        const result = await wallet.signAndExecuteTransactionBlock({
            transactionBlock: tx,
        });

        console.log(result);
        if(result.confirmedLocalExecution){
            alert('nft minted Session successfully!');
            return;
        }
    }
    useEffect(() => {
        
        if(sessionData.length > 0){

            mint(wallet,sessionData[0] ?? []);
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
