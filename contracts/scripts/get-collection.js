const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');

const getNfts = async () => {
    const client = new SuiClient({
        url: getFullnodeUrl('testnet'),
    });
    let address = "0xb9941d47ba2a5583b89d8399a646251cb9bc8ad0004ec70c5bb8088f6f5356b7";

    const allObjects = await client.getOwnedObjects({
      owner: address,
      options: {
        showType: true,
        showDisplay: true,
        showContent: true,
      }
    });

    allObjects.data.map((item) => console.log(item.data.type));
    //console.log("objectIDs", allObjects.data[0]);
    // const objectIDs = (allObjects?.data || [])
    //   .filter((item) => !Coin.isCoin(item))
    //   .map((anObj) => anObj.data.objectId);
    
    // const allObjRes = await suiProvider.multiGetObjects({
    //   ids: objectIDs,
    //   options: {
    //     showContent: true,
    //     showDisplay: true,
    //     showType: true,
    //   },
    // });
    // console.log("allObjRes",allObjRes);
    // const nftList = allObjRes.filter(obj => obj.data).map(obj => ({
    //   objectId: obj.data.objectId,
    //   image: (obj.data?.display?.data as any)?.image_url.replace(/^ipfs:\/\//, 'https://ipfs.io/ipfs/'),
    //   name: (obj.data?.display?.data as any).name
    // }))

}

getNfts();
