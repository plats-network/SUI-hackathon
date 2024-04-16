import './admin/libs/bootstrap/js/bootstrap.bundle.min.js';
import './admin/libs/metismenujs/metismenujs.min.js';
import './admin/libs/simplebar/simplebar.min.js';
import './admin/libs/eva-icons/eva.min.js';
import './admin/app.js';
import EditorJS from '@editorjs/editorjs';


$(document).ready(function () {
    GLOBAL_CONFIG.init();
});
// NEAR
import { Wallet } from './near-wallet.js';
const wallet = new Wallet({ createAccessKeyFor: "nft1.actionhup.testnet" });

window.GLOBAL_CONFIG = function () {
    return {
        toggleBuilder : () => {
            $('[data-init-plugin="select2"]').each(function () {
                let config = {
                    theme: 'bootstrap4',
                    minimumResultsForSearch : -1,
                    disabled                : !!($(this).attr('readonly')),
                };

                $(this).select2(config);
            });
        },
        async connectWallet(){
            $('#connectWallet').on('click', () => {
                wallet.signIn();
            })
            let isSignedIn = await wallet.startUp();
            console.log("walllet: ", wallet)
            if (isSignedIn) {
                $('#connectWallet span').text(wallet.accountId)
            } else {
                $('#connectWallet span').text('Connect wallet')
            }

        },
        async unConnectWallet(){
            $('#unConnectWallet').on('click', () => {
                wallet.signOut();
            })
            let isSignedIn = await wallet.startUp();
            if (isSignedIn) {
                $('#connectWallet span').text(wallet.accountId)
            } else {
                $('#connectWallet span').text('Connect wallet')
            }

        },

        async getContractToken(){
            if(this.walletInstalled()){
                return window.tronWeb.contract().at(tokenContractAddress);
            }
        },

        walletInstalled : () => {
            if (window.tronWeb && window.tronWeb.defaultAddress.base58) {
                return true;
            } else {
                return false;
            }
        },

        init : function () {
            this.toggleBuilder();
            this.walletInstalled();
            this.getContractToken();
            this.connectWallet();
            this.unConnectWallet();
        }
    };
}();
