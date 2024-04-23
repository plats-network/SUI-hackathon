const {Ed25519Keypair} = require("@mysten/sui.js/keypairs/ed25519");
const {TransactionBlock}  = require("@mysten/sui.js/transactions");
const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');
const { execSync } = require("child_process");
const path = require("path");
const scriptsPath = path.resolve(__dirname);
const dotenv = require('dotenv');
dotenv.config();

if (!process.env.MNEMONIC_PUBLISHER) {
  console.log('Requires MNEMONIC; set with `export MNEMONIC="..."`');
  process.exit(1);
}
const moveDir = path.resolve(scriptsPath, '..');
const keypair = Ed25519Keypair.deriveKeypair(process.env.MNEMONIC_PUBLISHER);
const client = new SuiClient({
    url: getFullnodeUrl('devnet'),
});

const { modules, dependencies } = JSON.parse(
  execSync(
      `sui move build --dump-bytecode-as-base64 --path ${moveDir}`,
      { encoding: "utf-8" }
  )
);

(async () => {
  // publish the module
  let result = await (async function publish() {
    const tx = new TransactionBlock();
    const [upgradeCap] = tx.publish({
        modules,
        dependencies,
    });
    const account = keypair.toSuiAddress();
    tx.transferObjects([upgradeCap], tx.pure(account));

    let result;
    try {
        result = await client.signAndExecuteTransactionBlock(
        {
            signer: keypair, 
            transactionBlock: tx,
            options: {
            showEffects: true,
            showObjectChanges: true,
            showEvents: true,
            },
        },
        );
    } catch (e) {
        console.log("Error publishing: ", e);
        process.exit(1);
    }

    console.log("EXECUTE RESULT", JSON.stringify(result, null, 4));
  })();

  // console.log("RESULT", JSON.stringify(result, null, 4));
  // const [_pkg, publisher, item, itemType] = result;
  // get package ID 
  // const packageId = result.objectChanges.filter(
  //   (a) => a.type === 'published');
  // console.log("PACKAGE ID:",packageId);

  return result;
})();