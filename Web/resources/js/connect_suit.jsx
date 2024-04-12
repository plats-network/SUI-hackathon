import {createRoot} from "react-dom/client";
import ReactDOM from 'react-dom';
import {ConnectButton} from '@suiet/wallet-kit';
import {WalletProvider} from '@suiet/wallet-kit';
import {useWallet} from '@suiet/wallet-kit';
import '@suiet/wallet-kit/style.css';
// import App from './useWallet';
import App from './mintNft';
import {TransactionBlock} from "@mysten/sui.js";
function ConnectedAccount() {
    const wallet = useWallet();
    return (
        <span className="gradient">
            Connected Account: {wallet.account ? wallet.account.address : 'Not connected'}
        </span>
    );
}
ReactDOM.createRoot(document.getElementById('button_connect_suit')).render(
    <WalletProvider>
        <App/>
    </WalletProvider>
);
