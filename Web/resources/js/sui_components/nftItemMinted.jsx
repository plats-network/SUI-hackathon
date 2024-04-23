import React from 'react';

const NftItemMinted = ({nftData}) => {
    return (
        <div className="row mb-3">
            <div className="col-4">
                <label htmlFor="image-file">
                    <img className="img-preview img-preview-nft" src={nftData.image_file || "/imgs/no-image.png"}/>
                </label>
            </div>
            <div className="col-3">
                <div className="col-10 mt-25">
                    <p className="class-ticket">{nftData?.nft_name}</p>
                </div>
                <div className="col-10 mt-20">
                    <p className="class-ticket">{nftData.nft_symbol}</p>
                </div>
            </div>
            <div className="col-3" style={{marginTop: "50px"}}>
                <div className="col-10">
                    <p className="class-ticket">{nftData.nft_amount}</p>
                </div>
            </div>
            <div className="col-2" style={{marginTop: "50px"}}>
                <p className="class-ticket"><a
                    href={`https://suiscan.xyz/devnet/tx/${JSON.parse(nftData.res).digest}`}>TxHash</a></p>
            </div>
        </div>
    );
};

export default NftItemMinted;
