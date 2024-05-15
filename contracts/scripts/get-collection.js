const { getFullnodeUrl, SuiClient } = require('@mysten/sui.js/client');

const getNfts = async () => {
    const client = new SuiClient({
        url: getFullnodeUrl(process.env.NETWORK),
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
    console.log(allObjects);

    //console.log("objectIDs", allObjects.data[0]);
    const objectIDs = (allObjects?.data || [])
      .filter((item) => item.data.objectId =="0x2587305d59dbcc09406e1ef0147053fff3019a64aca312108adac2913785a6d0")
      .map((anObj) => anObj.data.objectId);
    
    const allObjRes = await client.multiGetObjects({
      ids: objectIDs,
      options: {
        showContent: true,
        showDisplay: true,
        showType: true,
      },
    });
    const nftList = allObjRes.filter(obj => obj.data).map(obj => ({
      objectId: obj.data.objectId,
      data: obj.data.content.fields,

    }));

    // get booths 
    const booths = nftList.map((data) => data.data.booths);
    console.log(booths);
    // get sessions 
    const sessions = nftList.map((data) => data.data.sessions);
    console.log(sessions);

    const tickets = nftList.map((data) => data.data.tickets);
    console.log(tickets);
}

getNfts();
