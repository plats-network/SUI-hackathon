import React from 'react';

const NftItemMinted = ({ nftData }) => {
    return (
        <div className="row mb-3">
            <div className="col-4">
                <label htmlFor="image-file">
                    <img className="img-preview img-preview-nft" src={nftData.image_file} />
                </label>
            </div>
            <div className="col-4">
                <div className="col-10 mt-25">
                    <p className="class-ticket">{nftData.nft_name}</p>
                </div>
                <div className="col-10 mt-20">
                    <p className="class-ticket">{nftData.nft_symbol}</p>
                </div>
            </div>
            <div className="col-4">
                <div className="col-10 mt-25">
                    <p className="class-ticket">{nftData.nft_category}</p>
                </div>
                <div className="col-10 mt-20">
                    <p className="class-ticket">{nftData.nft_amount}</p>
                </div>
            </div>
        </div>
    );
};

export default NftItemMinted;
