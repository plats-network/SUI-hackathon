
import {Buffer} from 'buffer';
import {generateNonce, generateRandomness, jwtToAddress} from '@mysten/zklogin';
import {TransactionBlock} from "@mysten/sui.js/transactions";
import {getFullnodeUrl, SuiClient} from '@mysten/sui.js/client';
import {Ed25519Keypair} from "@mysten/sui.js/keypairs/ed25519";
import {genAddressSeed, getZkLoginSignature, getExtendedEphemeralPublicKey} from "@mysten/zklogin";
import {jwtDecode} from 'jwt-decode';
import axios from "axios";
import {toBigIntBE} from "bigint-buffer";
import {SerializedSignature, decodeSuiPrivateKey} from '@mysten/sui.js/cryptography';



const package_id = $('#package_id').val();
const collection_id = $('#collection_id').val();
let typenetwork = $('meta[name="type_network"]').attr('content');
console.log(typenetwork);
function keypairFromSecretKey(privateKeyBase64) {
    const keyPair = decodeSuiPrivateKey(privateKeyBase64);
    return Ed25519Keypair.fromSecretKey(keyPair.secretKey);
}

async function autoClaim() {
    const handelClaim = async () => {
        // $('.btn-claim-id').click(async function () {
        $('.loading').show();

        let packageId = package_id;
        let event_object_id = collection_id;
        const zkLoginUserAddress = localStorage.getItem("zkLoginUserAddress");
        if (!zkLoginUserAddress) {
            alert('user can`t login')
            return;
        }

        let keypair = JSON.parse(localStorage.getItem('ephemeralKeyPair'));
        let jwtUser = localStorage.getItem('jwtUser');
        let ranDomness = localStorage.getItem('randomness');
        let maxEpoch = localStorage.getItem('maxEpoch');
        let salt = localStorage.getItem('salt');
        let ephemeralPrivateKey = localStorage.getItem('ephemeraPrivateKey');

        // let ticket_id = tickets[0][indexNft];
        let ticket_id = $('#ticket_id').val();
        const txb = new TransactionBlock();
        txb.setSender(zkLoginUserAddress);
        txb.setGasBudget(5000000);
        txb.moveCall({
            target: `${packageId}::ticket_collection::claim_session`,
            arguments: [
                txb.object(event_object_id),
                txb.pure(ticket_id)
            ],
            typeArguments: [`${packageId}::ticket_collection::NFTTicket`]
        });

        const ephemeralKeyPairs = keypairFromSecretKey(ephemeralPrivateKey);

        console.log('Pair', ephemeralKeyPairs);
        const client = new SuiClient({

            url: getFullnodeUrl(typenetwork),
        });
        const {bytes, signature} = await txb.sign({
            client,
            signer: ephemeralKeyPairs, // This must be the same ephemeral key pair used in the ZKP request
        });

        const jwtPayload = jwtDecode(jwtUser);

        const addressSeed = genAddressSeed(BigInt(salt), "sub", jwtPayload.sub, jwtPayload.aud).toString();

        console.log(addressSeed);

        const zkpPayload = {
            jwt: jwtUser,
            extendedEphemeralPublicKey: toBigIntBE(
                Buffer.from(ephemeralKeyPairs.keypair.publicKey),
            ).toString(),
            jwtRandomness: ranDomness,
            maxEpoch: maxEpoch,
            salt: salt,
            keyClaimName: "sub"
        };
        console.log('zkpPayload', zkpPayload);

        const proofResponse = await axios.post("/zkp/post", zkpPayload);
        const zkLoginSignature = getZkLoginSignature({
            inputs: {
                ...proofResponse.data,
                addressSeed
            },
            maxEpoch: maxEpoch,
            userSignature: signature,
        });

        const nftId = $('#nft_id').val();
        //
        try {
            const result = await client.executeTransactionBlock({
                transactionBlock: bytes,
                signature: zkLoginSignature,
            });
            let digest = result.digest;
            console.log('result :', result, 'digest :', digest);

            const body = {
                nft_id: nftId,
                digest: digest,
            }

            const res = await axios.post("/update_nft_status", body);
            alert(`Claim NFT is success. Please see on https://suiscan.xyz/${typenetwork}/tx/${digest}`);
            $('.loading').hide();
            $('.claim-success').css('display', 'block');
            $('.sol-link').attr('href', `https://suiscan.xyz/${typenetwork}/tx/${digest}`);
            $('.sol-link').show();
            $('.btn-claim-id').hide();
            $('.claim-btn').hide();
            $('.showModal').hide();
        } catch (error) {
            alert(error.message);
        }

    };
    await handelClaim();
}

autoClaim();
