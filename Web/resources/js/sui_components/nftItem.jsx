import React from 'react';
import {ConnectButton, useWallet, addressEllipsis} from "@suiet/wallet-kit";

function NftItem({onDelete, onInputChange, onFileChange, nftData, itemKey, upload}) {
    const wallet = useWallet();
    return (
        <div className="row mb-3 nft-ticket-div">
            <div className="col-4">
                <input type="file"
                       accept="image/x-png, image/jpeg"
                       style={{display: 'none'}}
                       className="image-file-react"
                       id={`image-file-${itemKey}`}
                       name="file-image-nft"
                       onChange={onFileChange}/>
                <label htmlFor={`image-file-${itemKey}`}>
                    {upload === itemKey ?
                        <img src="/imgs/loading/loading-upfile.webp" alt="loading"/> :
                        <img className="image-label img-preview" src={nftData.image_file || window.location.origin+"/imgs/defaulticket.png"}/>
                    }
                </label>
            </div>
            <div className="col-5">
                <div className="mt-20">
                    <input type="text"
                           className="form-control nft_name"
                           placeholder="NFT Title"
                           name="nft_name"
                           required={true}
                           onChange={onInputChange}/>
                </div>
                <div className="mt-20">
                    <input type="text"
                           className="form-control nft_symbol"
                           placeholder="NFT description"
                           name={"nft_symbol"}
                           onChange={onInputChange}/>
                </div>
            </div>
            <div className="col-3">
                <div className="mt-20">
                    <input type="hidden"
                           className="form-control nft_category"
                           placeholder="NFT Category"
                           name="nft_category"
                           defaultValue="Standard"
                           onChange={onInputChange}/>
                </div>
                <div className={'mt-20'}>
                    <input type="number"
                           className="form-control nft_amount"
                           name="nft_amount"
                           min="1"
                           defaultValue="1"
                           onChange={onInputChange}/>
                </div>
                <div className={'mt-20 d-flex justify-end'}>
                    <button type="button" className="btn btn-danger" onClick={onDelete} style={{width: "100%"}}>
                        Delete
                    </button>
                </div>
            </div>


        </div>
    );
}

export default NftItem;
