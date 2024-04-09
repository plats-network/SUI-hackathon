import {web3Accounts, web3Enable, web3FromSource} from '@polkadot/extension-dapp';
import BN from 'bn.js';
const {ApiPromise, WsProvider, Keyring} = require('@polkadot/api');
const wsHost = 'ws://' + process.env.BLC_WS_HOST + ':' + process.env.BLC_WS_PORT;

class Deposit {
    constructor() {
        const _this = this;
        ;(async function () {
            try {
                await _this._web3Enabled();
                await _this._connectWS();
            } catch (e) {
                console.log('err 18', e);
                return true;
            }
        }());
    }

    /**
     *
     * @returns {Promise<boolean>}
     * @private
     * @param apiPolkadot ApiPromise
     */
    _makeTransaction(apiPolkadot) {
        const _this = this;
        //Show message box
        $('#deposit_process').show();
        //Start deposit
        ;(async function () {
            try {
               const allAccounts = await web3Accounts().then((allAccounts) => {
                    if (allAccounts.length <= 0) {
                        _this._showErr('Wallet is empty', 'Please create a new wallet on Polkadot plugin');
                        throw Error('Loi');
                        //return false;
                    }

                    return allAccounts;
                });

               //CAMPAIGN_AMOUNT
               let depositAmount = new BN(CAMPAIGN_AMOUNT + '000000000000000000');
               let taskId = TASK_ID;

                //TODO: Change wallet. Default 0
                const account = allAccounts[0];

                await web3FromSource(account.meta.source).then((injector) => {
                    const transferExtrinsic = apiPolkadot.tx.task.createCampaign(taskId, depositAmount);
                    transferExtrinsic.signAndSend(account.address, {signer: injector.signer}, ({status}) => {
                        if (status.isInBlock) {
                            _this._showErr('Payment success!', 'Successful transaction. Transitioning state!');

                            setTimeout(() => {
                                location.href = REDIRECT_AFTER_DEPOSIT;
                            }, 2000);
                        }
                    }).catch((error) => {
                        _this._showErr('Transaction failed', error);
                    });
                });

                return true;
            } catch (e) {
                return true;
            }
        }());
    }

    /**
     *
     * @returns {Promise<*>}
     * @private
     */
    async _connectWS() {
        const provider = new WsProvider(wsHost);
        provider.on('connected', (r) => {
            new ApiPromise({provider}).isReady.then((api) => {
                this._makeTransaction(api);
            });
        });
        provider.on('error', () => {
            provider.disconnect();
            this._showErr('WS Provider Connection Error', 'Không thể kết nối tới socket');
        });
        provider.on('disconnected', () => {
            provider.disconnect();
        });

        return true;
    }

    /**
     *
     * @returns {Promise<boolean>}
     * @private
     */
    async _web3Enabled() {
        return await web3Enable('plats-action-hub').then(extensions => {
            if (extensions.length <= 0) {
                this._showErr('Plugin not found', 'Hãy cài đặt plugin');

                throw Error('');
            }

            return true;
        });
    }

    /**
     *
     * @private
     */
    _showErr(title, message) {
        $('.js-error-title').text(title);
        $('.js-error-message').text(message);
        $('#err_box').show();
        $('#deposit_process').hide();
    }
}

$(document).ready(function () {
   new Deposit();
   // main();
});
