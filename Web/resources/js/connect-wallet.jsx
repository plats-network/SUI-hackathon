import { createRoot } from "react-dom/client";
import { useState } from "react";
import { ModalWallet } from "./ModalWallet";
import { UseInkProvider, useTx } from "useink";
import { AlephTestnet, PhalaTestnet } from "useink/chains";

function ConnectButton() {
    const [isModal, setIsModal] = useState(false);
    const [account, setAccount] = useState();
    const buttonAlert = () => {
        setIsModal(true);
    };

    return (
        <UseInkProvider
            config={{
                dappName: "hkt_plats",
                chains: [AlephTestnet, PhalaTestnet],
            }}
        >
            <a
                href="#"
                onClick={buttonAlert}
                className="btn btn-primary btn-sm"
            >
                <i className="fa fa-bars"></i>
                <span className="mx-1">
                    {account ? account : "Connect Wallet"}
                </span>
            </a>
            {isModal && (
                <ModalWallet setIsModal={setIsModal} setAccount={setAccount} />
            )}
        </UseInkProvider>
    );
}

export default ConnectButton;

const loginButton = createRoot(document.getElementById("login_button"));
if (loginButton !== null) {
    loginButton.render(<ConnectButton />);
}
