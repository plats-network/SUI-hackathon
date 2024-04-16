import React, {useState} from 'react';
import ReactDOM from "react-dom";
import {WalletProvider} from '@suiet/wallet-kit';
import axios from 'axios';
import NftItem from './sui_components/nftItem';
import MintNft from './mintNft';
import NftItemMinted from "./sui_components/nftItemMinted";

function NftForm() {
    const [nftMinted, setNftMinted] = useState([]);
    const [nftData, setNftData] = useState([
        {
            nft_id: 0,
            nft_name: "",
            nft_symbol: "",
            image_file: "",
            nft_category: "Standard",
            nft_amount: 1
        }
    ]);
    const [items, setItems] = useState([0])

    const handleInputChange = (event, index) => {
        const {name, value} = event.target;
        const list = [...nftData];
        if (list[index]) {
            list[index][name] = value;
            setNftData(list);
        }
    };
    const handleAddMore = () => {
        setItems([...items, items.length]);
        setNftData([
            ...nftData,
            {
                nft_id: items.length,
                nft_name: "",
                nft_symbol: "",
                image_file: "",
                nft_category: "Standard",
                nft_amount: "1"
            }
        ]);
    };

    const handleDelete = (index) => {
        console.log('index:' + typeof index);
        setItems(items.filter((item, i) => item !== index));
        setNftData(nftData.filter((item, i) => item.nft_id !== index));
    };

    const handleFileChange = async (event, index) => {
        let file = event.target.files[0];
        if (file) {
            // Tạo một FileReader mới
            let reader = new FileReader();

            // Định nghĩa hàm onload cho FileReader
            reader.onloadend = () => {
                // Khi tệp đã được đọc xong, cập nhật state với URL của ảnh
                const list = [...nftData];
                if (list[index]) {
                    list[index]['image_file'] = reader.result;
                    setNftData(list);
                }
            };

            // Bắt đầu đọc tệp như URL
            reader.readAsDataURL(file);

            let data = new FormData();
            data.append('file', file, 'file-image-nft');

            try {
                const response = await axios.post('/upload-image-nft', data, {
                    headers: {
                        'accept': 'application/json',
                        'Accept-Language': 'en-US,en;q=0.8',
                        'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                    }
                });
                // Cập nhật state với URL của ảnh từ máy chủ
                const list = [...nftData];
                if (list[index]) {
                    list[index]['image_file'] = response.data.path;
                    setNftData(list);
                }
            } catch (error) {
                console.error('Error uploading file: ', error);
            }
        }
    };
    const _setMinted = (data, key) => {
        setNftMinted(data);
    }
    console.log('nftData:', nftData);
    console.log('nftMinted:', nftMinted);
    // React.useEffect(() => {
    //     setNftData(nftData.filter(item => !nftMinted.some(mintedItem => item.nft_id === mintedItem.nft_id)));
    // }, [nftMinted]);
    // console.log('nftMinted1:', nftData);

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
                        itemKey={item}
                        onDelete={() => handleDelete(item)}
                        onInputChange={(event) => handleInputChange(event, key)}
                        onFileChange={(event) => handleFileChange(event, key)}
                        nftData={nftData[key]}
                        id={`nft-item-${item}`}
                    />)}
                </div>
                <div className="col-6 append-nft-detail" id={"append-nft-detail"}>
                    {nftMinted.map((item, key) => <NftItemMinted
                        key={item}
                        nftData={item}
                    />)}
                </div>
            </div>
            <div className="row">
                <div className="col-6 d-flex flex-row-reverse"
                     style={{borderLeft: '1px', borderRight: '1px solid'}}>
                    <div className="p-2">
                        <button id="btnAddItemNftReact" type="button"
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
                            <MintNft nftData={nftData} _setMinted={_setMinted} setNftData={setNftData} setItems={setItems} items={items}/>
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
