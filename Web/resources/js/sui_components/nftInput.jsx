import React from 'react';

function NftInput({nftData}) {
    return (
        <>
            <input type="hidden" name={`nft-ticket-name-[]`} value={nftData.nft_name}/>
            <input type="hidden" name={`nft-ticket-symbol-[]`} value={nftData.nft_symbol}/>
            <input type="hidden" name={`nft-ticket-category-[]`} value={nftData.nft_category}/>
            <input type="hidden" name={`nft-ticket-uri-[]`} value={nftData.image_file}/>
            <input type="hidden" name={`nft-ticket-res-[]`} value={nftData.res}/>
            <input type="hidden" name={`nft-ticket-[]`} value={nftData.tickets}/>
            <input type="hidden" name={`nft-amount-[]`} value={nftData.tickets}/>
        </>
    )
}
export default NftInput;
