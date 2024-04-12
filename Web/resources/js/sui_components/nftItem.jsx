import React from 'react';

function NftItem({ onDelete, onInputChange, nftData }) {
    return (
        <div className="row mb-3 nft-ticket-div">
            <div className="col-4">
                <input type="file"
                       accept="image/x-png, image/jpeg"
                       style={{display: 'none'}}
                       className="image-file"
                       id="image-file"
                       name="file-image-nft"
                       onChange={onInputChange}
                />
                <label htmlFor="image-file">
                    <img className="image-label img-preview"
                         src="https://static.vecteezy.com/system/resources/previews/007/567/154/original/select-image-icon-vector.jpg"/>
                </label>
            </div>
            <div className="col-4">
                <div className="col-10 mt-20">
                    <input type="text" required
                           className="form-control nft_symbol"
                           placeholder="NFT description"
                           name={"nft_symbol"}
                           onChange={onInputChange}/>
                </div>
                <div className="col-10 mt-20">
                    <input type="text" required
                           className="form-control nft_name"
                           placeholder="NFT Title"
                           name="nft_name"
                           onChange={onInputChange}/>
                </div>
            </div>
            <div className="col-2" style={{marginTop: '50px'}}>
                <input type="number" required
                       className="form-control nft_amount"
                       name="nft_amount"
                       min="1"
                       defaultValue="1"
                       onChange={onInputChange}/>
            </div>
            <div className="col-2" style={{marginTop: '50px'}}>
                <button type="button" className="btn-delete-nft-ticket btn btn-danger" onClick={onDelete}>
                    Delete
                </button>
            </div>
        </div>
    );
}

export default NftItem;
