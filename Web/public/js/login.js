import { Magic } from 'magic-sdk';
// import { OAuthExtension } from "@magic-ext/oauth";
import { Connection } from '@solana/web3.js';

let magic;

const connection = new Connection("<https://api.devnet.solana.com>");

console.log(123);

// Construct with an API key and testMode enabled:
magic = new Magic('pk_live_7AC27AA25AE25994', {
    testMode: true,
    extensions: [new OAuthExtension()],
});

const isLoggedIn = await magic.user.isLoggedIn();
console.log(isLoggedIn)
if (isLoggedIn) {
    const { email } = await magic.user.getMetadata();
    console.log(magic.user.getIdToken);
}


$('#btn-google-login').click(function () {
    magic.oauth.loginWithRedirect({
        provider: "google",
        redirectURI: new URL("/", window.location.origin).href,
    });
})


