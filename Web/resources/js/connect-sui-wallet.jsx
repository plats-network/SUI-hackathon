import { createRoot } from "react-dom/client";
import { ConnectButton } from '@suiet/wallet-kit';
import { WalletProvider } from '@suiet/wallet-kit';
import { useWallet } from '@suiet/wallet-kit';
import '@suiet/wallet-kit/style.css';
import { useEffect, useState, useRef } from 'react';

export default function MyComponentConnectWallet() {
    const { connected, address } = useWallet(); // Access wallet information
    const [connectedChangeCount, setConnectedChangeCount] = useState(0);
    const isFirstRender = useRef(true);

    useEffect(() => {
        // Mark as first render and set initial count after a delay
        const timeoutId = setTimeout(() => {
            setConnectedChangeCount(1);
            isFirstRender.current = false; // Mark the first render as complete
        }, 1000);

        return () => clearTimeout(timeoutId); // Cleanup function to prevent leaks
    }, []);

    useEffect(() => {
        if (!isFirstRender.current) { // Skip this effect on first render
            console.log('Connected state changed:', connected);
            setConnectedChangeCount(prevCount => prevCount + 1); // Increment count on subsequent changes
            console.log('connectedChangeCount', connectedChangeCount);
            const dataCount = document.getElementById('data-count').getAttribute('data-count');
            console.log('dataCount', dataCount);
            if(dataCount >= 1){
                window.location.reload();
            }
        } else {
            console.log('This is the first render.');
        }
    }, [connected]);

    return (
        <div>
            <ConnectButton />
            <div id="data-count" data-count={connectedChangeCount}></div>
        </div>
    );
}

const root = createRoot(document.getElementById('button_connect_suit'));
root.render(
    <WalletProvider>
        <MyComponentConnectWallet />
    </WalletProvider>
);
