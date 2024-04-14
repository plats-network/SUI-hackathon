import { generateNonce, generateRandomness,jwtToAddress } from '@mysten/zklogin';
import { getFullnodeUrl, SuiClient }  from '@mysten/sui.js/client';
import { TransactionBlock }  from "@mysten/sui.js/transactions";
import { Ed25519Keypair } from "@mysten/sui.js/keypairs/ed25519";
import { jwtDecode } from 'jwt-decode';

// Assume SuiClient and Ed25519Keypair are imported or defined elsewhere.
const FULLNODE_URL = 'https://fullnode.devnet.sui.io';
const suiClient = new SuiClient({ url: FULLNODE_URL });

async function getSystemState() {
    const { epoch, epochDurationMs, epochStartTimestampMs } = await suiClient.getLatestSuiSystemState();
    return { epoch, epochDurationMs, epochStartTimestampMs };
}

function saltRandomString(length) {
    const characters = '0123456789';
    let result = '';
    const charactersLength = characters.length;
    for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

// đăng nhập google
$(".Google").click(async function(){

    //chuyển sang google để đăng nhập
    const { epoch, epochDurationMs, epochStartTimestampMs } = await getSystemState();
    const maxEpoch = Number(epoch) + 2;
    const ephemeralKeyPair = new Ed25519Keypair();
    console.log(ephemeralKeyPair);
    const randomness = generateRandomness();
    const nonce = generateNonce(ephemeralKeyPair.getPublicKey(), maxEpoch, randomness);
    // Tạo một đối tượng URLSearchParams với các tham số cần thiết
    var params = new URLSearchParams({
        client_id: '290554041285-g77ars54m9vc2hvugv1oekhtd54ell9p.apps.googleusercontent.com',
        nonce: nonce,
        //redirect_uri: 'http://localhost/SUI-hackathon/Web/public',
        redirect_uri: 'https://cws-suivent.plats.network/',
        response_type: 'id_token',
        scope: 'openid',
    });

    // Tạo URL đăng nhập
    var loginURL = 'https://accounts.google.com/o/oauth2/v2/auth?' + params.toString();

    // Lưu urlcallback vào localStorage
    localStorage.setItem("urlcallback", window.location.href);

    // Chuyển hướng đến loginURL
    window.location.href = loginURL;

    console.log(loginURL);

});

$(document).ready(function() {
    // Đặt các lệnh JavaScript của bạn ở đây
    getTokenSocial();
});

//check token login to social
async function getTokenSocial(){

    const urlFragment = window.location.hash.substring(1);
    const urlParams = new URLSearchParams(urlFragment);
    const jwt = urlParams.get('id_token');

    if(!jwt) return;
    //tạo salt ngẫu nhiên hiện tại đang fix cứng, phải đợi bên t2(zklogin) duyệt sal qua token
    const salt = saltRandomString(42);
    const zkLoginUserAddress =  jwtToAddress(jwt, salt);

    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    const accountBalances = await client.getBalance({owner: zkLoginUserAddress});
    console.log('accountBalances',accountBalances);

    const jwtPayload = jwtDecode(jwt);
    console.log(jwtPayload);
    
    $.ajax({
        type: "POST",
        url: "login_google",
        data: {
            email: jwtPayload.sub+"@gmail.com",
            name: jwtPayload.sub,
        },
        dataType: "dataType",
        success: function (response) {
            console.log(response);
            window.location.href = window.location.origin + window.location.pathname;
        },
        complete: function () {
            window.location.href = window.location.origin + window.location.pathname;
        }
    });

}
