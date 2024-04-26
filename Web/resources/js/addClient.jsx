import React, {useState} from 'react';
import ReactDOM from "react-dom";
import {WalletProvider} from '@suiet/wallet-kit';
import Add from './add';

function AddClient() {
    return (
        <>
            <div className="p-2">
                <WalletProvider>
                    <Add/>
                </WalletProvider>
            </div>
        </>
    );
}

export default AddClient;

ReactDOM.createRoot(document.getElementById('add_button')).render(
    <AddClient/>
);
