import React, {useState} from 'react';
import ReactDOM from "react-dom";
import {WalletProvider} from '@suiet/wallet-kit';
import NftItem from './sui_components/nftItem';
import MintNft from './mintNft';

function NftForm() {
    const [nftData, setNftData] = useState([
        {
            nft_name: "",
            nft_symbol: "",
            image_file: "",
            nft_amount: 1
        }
    ]);
    const [items, setItems] = useState([0])

    const handleInputChange = (event, index) => {
        const { name, value } = event.target;
        console.log(event.target.value);
        const list = [...nftData];
        if (list[index]) {
            list[index][name] = value;
            setNftData(list);
        }
    };
    console.log(nftData);
    const handleAddMore = () => {
        setItems([...items, items.length + 1]);
        setNftData([
            ...nftData,
            {
                nft_name: "",
                nft_symbol: "",
                image_file: "",
                nft_amount: "1"
            }
        ]);
    };

    const handleDelete = (index) => {
        setItems(items.filter((item, i) => item !== index));
        setNftData(nftData.filter((item, i) => item !== index));
    };

    return (
        <>
            <div className="text-center mb-4">
                <h5>NFT Ticket</h5>
            </div>

            <div className="row">
                <div className="col-6">
                </div>
                <div className="col-6">
                    <p>Generated NFT Ticket</p>
                </div>
            </div>
            <div className="row" style={{height: 'auto', minHeight: '400px'}}>
                <div className="col-6 append-nft-ticket" id="append-nft-ticket"
                     style={{borderLeft: '1px', borderRight: '1px solid'}}>
                    {items.map((item, key) => <NftItem
                        key={item}
                        onDelete={() => handleDelete(item)}
                        onInputChange={(event) => handleInputChange(event, key)}
                        nftData={nftData[key]}
                    />)}
                </div>
                <div className="col-6 append-nft-detail">
                </div>
            </div>
            <div className="row">
                <div className="col-6 d-flex flex-row-reverse"
                     style={{borderLeft: '1px', borderRight: '1px solid'}}>
                    <div className="p-2">
                        <button id="btnAddItemNft" type="button"
                                className="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"
                                onClick={handleAddMore}>
                            <i className="mdi mdi-plus me-1"></i> Add More
                        </button>
                    </div>
                </div>
            </div>
            <div className="row">
                <div className="col-6 d-flex flex-row-reverse"
                     style={{borderLeft: '1px', borderRight: '1px solid'}}>
                    <div className="p-2">
                        <WalletProvider>
                            <MintNft nftData={nftData} />
                        </WalletProvider>
                    </div>
                </div>
            </div>
        </>
    );
}

export default NftForm;

ReactDOM.createRoot(document.getElementById('nft_get')).render(
    <NftForm/>
);
