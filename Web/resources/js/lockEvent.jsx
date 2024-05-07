import React, {useState} from 'react';
import ReactDOM from "react-dom";
import {WalletProvider} from '@suiet/wallet-kit';
import Lock from './lock';

function LockEvent() {
    return (
        <>
            <div className="p-2">
                <WalletProvider>
                    <Lock/>
                </WalletProvider>
            </div>
        </>
    );
}

export default LockEvent;

ReactDOM.createRoot(document.getElementById('lock_event_0')).render(
    <LockEvent/>
);
