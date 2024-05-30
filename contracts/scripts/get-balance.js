

const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');


async function getBalance() {

    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
    });
    try {
        const accountBalances = await client.getBalance({owner: "0x6b79f4d42a607d30b2a6a5f92d8cb8a0272eddf8d59e5bad7f289b0e41ef3838"});


        const suiBalance = accountBalances.totalBalance;
        
        console.log(`SUI Balance for :`, Number(suiBalance)/Number(1e6));
    } catch (error) {
        console.error('Failed to fetch SUI balance:', error);
    }
}

getBalance();
