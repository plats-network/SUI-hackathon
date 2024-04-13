// import { SolanaConnect } from "solana-connect";
// import { Adapter } from "@solana/wallet-adapter-base";
import {AnchorProvider, BN, Program, setProvider, web3} from "@project-serum/anchor";
import {Connection, Keypair, PublicKey, TransactionMessage} from "@solana/web3.js";
import {TOKEN_PROGRAM_ID} from "@project-serum/anchor/dist/cjs/utils/token";
import idl from './abi/abi.json'
import { Buffer } from 'buffer';
import {
    createAssociatedTokenAccountInstruction,
    createInitializeMintInstruction,
    getAssociatedTokenAddress, getAssociatedTokenAddressSync,
    MINT_SIZE
} from "@solana/spl-token";
import { create } from 'ipfs-http-client'
import * as splToken from "@solana/spl-token";
import * as anchor from "@project-serum/anchor";
import bs58 from "bs58";
import NodeWallet from "@project-serum/anchor/dist/cjs/nodewallet";
import {randomBytes} from "ethers/lib/utils";
import axios from "axios";
import FormData from 'form-data'

const solConnect = new window.SolanaConnect();
var walletOr = '';
var pub = '';
var TOKEN_METADATA_PROGRAM_ID = new PublicKey( "metaqbxxUerdq28cj1RbAWkYQm3ybzjb6a8bt518x1s");
const connection = new Connection("https://api.devnet.solana.com");
const PROGRAM_ID = new PublicKey( "D5GK8Kye78gjuDMMjRnkWH5a6KfNEXzex5mekXL3HLR2");
let provider = new AnchorProvider(connection, solConnect.getWallet(), { commitment: "confirmed" })
let program = new Program(idl, PROGRAM_ID, provider);

// const input_file = document.getElementById('image-file');
// const input_label = document.getElementById('image-label')

const convert_to_base64 = file => new Promise((response) => {
    const file_reader = new FileReader();
    file_reader.readAsDataURL(file);
    file_reader.onload = () => response(file_reader.result);
});

$('.page-content').on("change", ".image-file", async function () {
    console.log(1);
    const input = $(this).parent().find('.image-label')
    let file = $(this).prop('files');
    const my_image = await convert_to_base64(file[0]);
    input.attr('src', my_image);
    // input.css('backgroundImage', `url(${my_image})`);
});

$('.page-content').on("click", ".btn-delete-nft-ticket", async function () {
    $(this).parents('.nft-ticket-div').remove()
})

$('.page-content').on("click", ".btn-delete-nft-ticket-session", async function () {
    $(this).parents('.itemSessionDetail').remove()
})

$('.page-content').on("click", ".btn-delete-nft-ticket-booth", async function () {
    $(this).parents('.itemBoothDetail').remove()
})


$('.page-content').on("click", "#btnAddItemNft", async function () {
    let random = Math.floor(Math.random() * 100);
    $('.append-nft-ticket').append(
        '<div class="row nft-ticket-div mb-3">\n' +
        '                                            <div class="col-4">\n' +
        '                                                <input type="file"\n' +
        '                                                       id="file-image-'+random+'" name="file-image-nft" class="image-file"\n'+
        '                                                       accept="image/x-png, image/jpeg"\n' +
        '                                                       style="display: none"\n' +
        '                                                />\n' +
        '                                                <label for="file-image-'+random+'">\n' +
        '                                                    <img class="image-label img-preview" src="https://static.vecteezy.com/system/resources/previews/007/567/154/original/select-image-icon-vector.jpg">\n' +
        '                                                </label>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-4">\n' +
        '                                                <div class="col-10 mt-20">\n' +
        '                                                    <input type="text" required\n' +
        '                                                           class="form-control nft_symbol"\n' +
        '                                                           placeholder="Ticket Class">\n' +
        '                                                </div>\n' +
        '                                                <div class="col-10 mt-20">\n' +
        '                                                    <input type="text" required\n' +
        '                                                           class="form-control nft_name"\n' +
        '                                                           placeholder="NFT Title">\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-2" style="margin-top: 50px">\n' +
        '                                                <input type="number" required\n' +
        '                                                       class="form-control nft_amount"\n' +
        '                                                       value="1"\n' +
        '                                                       >\n' +
        '                                            </div>\n' +
        '                                            <div class="col-2" style="margin-top: 50px">\n' +
        '                                                <button type="button" class="btn btn-danger btn-delete-nft-ticket">Delete</button>\n' +
        '                                            </div>\n' +
        '                                        </div>'
    );
});

function appendNftDetail(style, symbol, title, amount, txhash) {
    $('.append-nft-detail').append(
        '<div class="row mb-3">\n' +
        '                                            <div class="col-4">\n' +
        '                                                <label for="image-file">\n' +
        '                                                    <img class="img-preview img-preview-nft" src="'+style+'">\n' +
        '                                                </label>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-4">\n' +
        '                                                <div class="col-10 mt-25">\n' +
        '                                                    <p class="class-ticket">'+symbol+'</p>\n' +
        '                                                </div>\n' +
        '                                                <div class="col-10 mt-20">\n' +
        '                                                    <p class="class-ticket">'+title+'</p>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-2" style="margin-top: 50px">\n' +
        '                                                <p class="class-ticket">'+amount+'</p>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-2" style="margin-top: 50px">\n' +
        '                                                <p class="class-ticket"><a href="'+txhash+'">txhash</a></p>\n' +
        '                                            </div>\n' +
        '                                        </div>'
    )
}

function appendNftSessionDetail(style, name, des, txhash) {
    $('.append-nft-session-detail').append(
        '<div class="row mb-3">\n' +
        '                                            <div class="col-4">\n' +
        '                                                <label for="image-file">\n' +
        '                                                    <img class="img-preview img-preview-nft" src="'+style+'">\n' +
        '                                                </label>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-6">\n' +
        '                                                <div class="col-10 mt-25">\n' +
        '                                                    <p class="class-ticket">'+name+'</p>\n' +
        '                                                </div>\n' +
        '                                                <div class="col-10 mt-20">\n' +
        '                                                    <p class="class-ticket">'+des+'</p>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-2" style="margin-top: 50px">\n' +
        '                                                <p class="class-ticket"><a href="'+txhash+'">txhash</a></p>\n' +
        '                                            </div>\n' +
        '                                        </div>'
    )
}

function appendNftBoothDetail(style, name, des, txhash) {
    $('.append-nft-booth-detail').append(
        '<div class="row mb-3">\n' +
        '                                            <div class="col-4">\n' +
        '                                                <label for="image-file">\n' +
        '                                                    <img class="img-preview img-preview-nft" src="'+style+'">\n' +
        '                                                </label>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-6">\n' +
        '                                                <div class="col-10 mt-25">\n' +
        '                                                    <p class="class-ticket">'+name+'</p>\n' +
        '                                                </div>\n' +
        '                                                <div class="col-10 mt-20">\n' +
        '                                                    <p class="class-ticket">'+des+'</p>\n' +
        '                                                </div>\n' +
        '                                            </div>\n' +
        '                                            <div class="col-2" style="margin-top: 50px">\n' +
        '                                                <p class="class-ticket"><a href="'+txhash+'">txhash</a></p>\n' +
        '                                            </div>\n' +
        '                                        </div>'
    )
}

async function getMetadata(mint) {
    return (await web3.PublicKey.findProgramAddress(
        [
            Buffer.from("metadata"),
            TOKEN_METADATA_PROGRAM_ID.toBuffer(),
            mint.toBuffer(),
        ],
        TOKEN_METADATA_PROGRAM_ID
    ))[0];
};

$('.page-content').on("click", "#btnGenItemNft", async function () {
    $('.loading').show();
    const btnClick = $(this);
    if ($('.nft-ticket-div').length > 0) {
        if (!solConnect.activeWallet) {
            solConnect.openMenu({
                top: 100
            });

            solConnect.onWalletChange( async (adapter) => {
                walletOr = await adapter.publicKey.toString();
                pub = adapter;
                provider = new AnchorProvider(connection, adapter, {commitment: "confirmed"})
                program = new Program(idl, PROGRAM_ID, provider);
                setProvider(provider);
                btnClick.click();
            });
        }

        if (walletOr) {
            const {blockhash, lastValidBlockHeight} =
                await connection.getLatestBlockhash('confirmed');
            var ownerWallet = new PublicKey(walletOr);
            let txs = [];
            var mintAccount = [];
            var deposits = [];
            var seeds = [];
            var names = [];
            var symbols = [];
            var uris = [];
            var pools = [];

            console.log($('.nft-ticket-div').length)
            $('.nft-ticket-div').each(async function (index) {
                let data = new FormData();
                let file = $(this).find('.image-file').prop('files');
                let uri = '';
                if (file) {
                    data.append('file', file[0], 'file-image-nft');

                    const response1 = await axios.post('/upload-image-nft', data, {
                        headers: {
                            'accept': 'application/json',
                            'Accept-Language': 'en-US,en;q=0.8',
                            'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                        }
                    }).then(async response1 => {
                        let amount = $(this).find('.nft_amount').val()

                        let nftName = $(this).find('.nft_name').val();
                        let nftSymbol = $(this).find('.nft_symbol').val();
                        for (let i = 0; i < amount; i++) {
                            txs.push(await createNftTx(nftName, nftSymbol, response1.data.path, blockhash, ownerWallet, lastValidBlockHeight, mintAccount, names, symbols, uris))
                        }
                        console.log(amount);
                        // appendNftDetail
                        appendNftDetail(await convert_to_base64(file ? file[0] : ''), nftSymbol, nftName, amount, 'https://explorer.solana.com/address/SfmKb6KG6MdXeqWz4o6kLj7hmVsvczAftDgGiToxxh1' + walletOr + '?cluster=devnet');
                        // empty
                        $(this).empty();
                    })
                    // uri = response1.data.path;
                }
            })

            console.log(txs);


            setTimeout(async () => {
                const signedTxs = await pub.signAllTransactions(txs);
                for (const signedTx in signedTxs) {
                    signedTxs[signedTx].partialSign(mintAccount[signedTx]);
                    const signature = await connection.sendRawTransaction(
                        signedTxs[signedTx].serialize(),
                        {
                            skipPreflight: true,
                            preflightCommitment: 'confirmed',
                            maxRetries: 50,
                        }
                    );
                    await connection.confirmTransaction(
                        {
                            blockhash,
                            lastValidBlockHeight,
                            signature,
                        },
                        'confirmed'
                    );

                    deposits.push(await addToPool(mintAccount[signedTx], ownerWallet, blockhash, seeds, pools));
                }

                const signedDepositTxs = await pub.signAllTransactions(deposits);

                for (const signedTx of signedDepositTxs) {
                    const signature = await connection.sendRawTransaction(
                        signedTx.serialize(),
                        {
                            skipPreflight: true,
                            preflightCommitment: 'confirmed',
                            maxRetries: 50,
                        }
                    );
                }

                // append
                for (const index in names) {
                    const classNe = $('.nft-div-append');
                    appendValueNft(names[index], symbols[index], uris[index], seeds[index], pools[index],
                        mintAccount[index].publicKey.toString(), provider.wallet.publicKey.toString(), classNe, 1)
                }

                alert('Mint NFT is success. Please see on https://explorer.solana.com/');
                $('#navItemTab2').parent().removeClass('tab-disabled')
                $('#navItemTab3').parent().removeClass('tab-disabled')
                $('.loading').hide();
                $('#btnAddItemSession').click();
            }, 4000);
        }
    } else {
        alert('Please add nft to mint!!!')
        $('.loading').hide();
    }
});

$('.page-content').on("click", "#btnGenItemSession", async function () {
    
    //$('.loading').show();
    // $('.loading').hide();
    $('#btnAddItemBooth').click();
   
    if ($('.itemSessionDetailMint').length > 0) {
        const {blockhash, lastValidBlockHeight} =
        await connection.getLatestBlockhash('confirmed');
        // var ownerWallet = new PublicKey(walletOr);
        var ownerWallet = "ownerWallet";
        let txs = [];
        var mintAccount = [];
        var deposits = [];
        var seeds = [];
        var names = [];
        var symbols = [];
        var uris = [];
        var pools = [];

        await $('.itemSessionDetailMint').each(async function (index) {
            let data = new FormData();
            let file = $(this).find('.image-file').prop('files');
            let sessionName = $(this).find('.name_session').val();
            let description = $(this).find('.description_session').val();
         
            let uri = '';
            if (file) {
                data.append('file', file[0], file[0].name);

                const response1 = await axios.post('/upload-image-nft', data, {
                    headers: {
                        'accept': 'application/json',
                        'Accept-Language': 'en-US,en;q=0.8',
                        'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                    }
                }).then(async response1 => {
                    let sessionName = $(this).find('.name_session').val();
                    let description = $(this).find('.description_session').val();
                    console.log(sessionName, description, response1.data.path);
                    txs.push(await createNftTx(sessionName, description, response1.data.path, blockhash, ownerWallet, lastValidBlockHeight, mintAccount, names, symbols, uris))
                    // appendNftDetail
                    appendNftSessionDetail(await convert_to_base64(file ? file[0] : ''), sessionName, description, 'https://explorer.solana.com/address/SfmKb6KG6MdXeqWz4o6kLj7hmVsvczAftDgGiToxxh1' + walletOr + '?cluster=devnet');
                    // empty
                    $(this).hide();
                    $(this).removeClass('itemSessionDetailMint');
                });
                // uri = response1.data.path;
            }
        });

        setTimeout(async () => {
            const signedTxs = await pub.signAllTransactions(txs);
            for (const signedTx in signedTxs) {
                signedTxs[signedTx].partialSign(mintAccount[signedTx]);
                const signature = await connection.sendRawTransaction(
                    signedTxs[signedTx].serialize(),
                    {
                        skipPreflight: true,
                        preflightCommitment: 'confirmed',
                        maxRetries: 50,
                    }
                );
                await connection.confirmTransaction(
                    {
                        blockhash,
                        lastValidBlockHeight,
                        signature,
                    },
                    'confirmed'
                );

                deposits.push(await addToPool(mintAccount[signedTx], ownerWallet, blockhash, seeds, pools));
            }

            const signedDepositTxs = await pub.signAllTransactions(deposits);

            for (const signedTx of signedDepositTxs) {
                const signature = await connection.sendRawTransaction(
                    signedTx.serialize(),
                    {
                        skipPreflight: true,
                        preflightCommitment: 'confirmed',
                        maxRetries: 50,
                    }
                );
            }

            // append
            for (const index in names) {
                const classNe = $('.nft-div-append');
                appendValueNft(names[index], symbols[index], uris[index], seeds[index], pools[index],
                    mintAccount[index].publicKey.toString(), provider.wallet.publicKey.toString(), classNe, 2)
            }

            alert('Mint NFT is success. Please see on https://explorer.solana.com/');
            $('.loading').hide();
            $('#btnAddItemBooth').click();
        }, 4000);
    } else {
        alert('Please add nft to mint session!!!')
        $('.loading').hide();
    }
});

$('.page-content').on("click", "#btnGenItemBooth", async function () {
    //$('.loading').show();
    if ($('.itemBoothDetailMint').length > 0) {
        const {blockhash, lastValidBlockHeight} =
            await connection.getLatestBlockhash('confirmed');
        //var ownerWallet = new PublicKey(walletOr);
        var ownerWallet = 'walletOr';

        let txs = [];
        var mintAccount = [];
        var deposits = [];
        var seeds = [];
        var names = [];
        var symbols = [];
        var uris = [];
        var pools = [];

        await $('.itemBoothDetailMint').each(async function (index) {
            let data = new FormData();
            let file = $(this).find('.image-file').prop('files');
            let uri = '';
            if (file) {
                data.append('file', file[0], file[0].name);

                const response1 = await axios.post('/upload-image-nft', data, {
                    headers: {
                        'accept': 'application/json',
                        'Accept-Language': 'en-US,en;q=0.8',
                        'Content-Type': `multipart/form-data; boundary=${data._boundary}`,
                    }
                }).then(async response1 => {
                    let sessionName = $(this).find('.name_booth').val();
                    let description = $(this).find('.description_booth').val();
                    txs.push(await createNftTx(sessionName, description, response1.data.path, blockhash, ownerWallet, lastValidBlockHeight, mintAccount, names, symbols, uris))
                    // appendNftDetail
                    appendNftBoothDetail(await convert_to_base64(file ? file[0] : ''), sessionName, description, 'https://explorer.solana.com/address/SfmKb6KG6MdXeqWz4o6kLj7hmVsvczAftDgGiToxxh1' + walletOr + '?cluster=devnet');
                    // empty
                    $(this).hide();
                    $(this).removeClass('itemBoothDetailMint');
                });
                // uri = response1.data.path;
            }
        });

        setTimeout(async () => {
            const signedTxs = await pub.signAllTransactions(txs);
            for (const signedTx in signedTxs) {
                signedTxs[signedTx].partialSign(mintAccount[signedTx]);
                const signature = await connection.sendRawTransaction(
                    signedTxs[signedTx].serialize(),
                    {
                        skipPreflight: true,
                        preflightCommitment: 'confirmed',
                        maxRetries: 50,
                    }
                );
                await connection.confirmTransaction(
                    {
                        blockhash,
                        lastValidBlockHeight,
                        signature,
                    },
                    'confirmed'
                );

                deposits.push(await addToPool(mintAccount[signedTx], ownerWallet, blockhash, seeds, pools));
            }

            const signedDepositTxs = await pub.signAllTransactions(deposits);

            for (const signedTx of signedDepositTxs) {
                const signature = await connection.sendRawTransaction(
                    signedTx.serialize(),
                    {
                        skipPreflight: true,
                        preflightCommitment: 'confirmed',
                        maxRetries: 50,
                    }
                );
            }

            // append
            for (const index in names) {
                const classNe = $('.nft-div-append');
                appendValueNft(names[index], symbols[index], uris[index], seeds[index], pools[index],
                    mintAccount[index].publicKey.toString(), provider.wallet.publicKey.toString(), classNe, 3)
            }

            alert('Mint NFT is success. Please see on https://explorer.solana.com/');
            $('.loading').hide();
            $('.min-save-btn').show();

        }, 4000);
    } else {
        alert('Please add nft to mint!!!')
        $('.loading').hide();
    }
});

function appendValueNft(name, symbol, uri, seed, pool, addressNft, secret, classAppend, type) {
    let classA = '';
    if (type == 2) classA = 'session';
    if (type == 3) classA = 'booth';
    classAppend.append('<input type="hidden" name="nft-ticket-name-'+classA+'[]" value="'+name+'"/>')
    classAppend.append('<input type="hidden" name="nft-ticket-symbol-'+classA+'[]" value="'+symbol+'"/>')
    classAppend.append('<input type="hidden" name="nft-ticket-uri-'+classA+'[]" value="'+uri+'"/>')
    classAppend.append('<input type="hidden" name="nft-ticket-seed-'+classA+'[]" value="'+seed+'"/>')
    classAppend.append('<input type="hidden" name="nft-ticket-pool-'+classA+'[]" value="'+pool+'"/>')
    classAppend.append('<input type="hidden" name="nft-ticket-address-nft-'+classA+'[]" value="'+addressNft+'"/>')
    classAppend.append('<input type="hidden" name="nft-ticket-secret-key-'+classA+'[]" value="'+secret+'"/>')
    classAppend.append('<input type="hidden" name="nft-ticket-address-organizer-'+classA+'[]" value="'+provider.wallet.publicKey.toString()+'"/>')
}

$('.mint-nft-append').click(function () {
    if (confirm("Are you sure to mint NFT Ticket") == true) {
        $('.amount-nft-ticket').text($('#nft_amount').val());
    }
})

async function addToPool(mintNftKey, ownerWallet, blockhash, seeds, pools) {
    const seed = new BN(randomBytes(8));
    seeds.push(seed);
    const nftPrice = 0;
    console.log(`seed: ${seed}`);
    const nftTokenAccount = await getAssociatedTokenAddress(
        mintNftKey.publicKey,
        provider.wallet.publicKey
    );
    const pool = web3.PublicKey.findProgramAddressSync(
        [
            Buffer.from("state"),
            seed.toArrayLike(Buffer, "le", 8)
        ],
        program.programId
    )[0];

    pools.push(pool);

    const poolTokenAddress = await getAssociatedTokenAddressSync(
        mintNftKey.publicKey,
        pool,
        true
    );
    //
    const depositTx = await program.methods
        .deposit(seed, new BN(nftPrice))
        .accounts(
            {
                mint: mintNftKey.publicKey,
                tokenAccount: nftTokenAccount,
                mintAuthority: ownerWallet,
                pool: pool,
                poolTokenAccount: poolTokenAddress,
            }
        ).instruction();

    let tx = new web3.Transaction();
    tx.recentBlockhash = blockhash;
    tx.feePayer = ownerWallet;
    const setComputeUnitLimitInstruction = web3.ComputeBudgetProgram.setComputeUnitLimit(
        {units: 500_000}
    );
    tx.add(setComputeUnitLimitInstruction, depositTx);

    return tx;
}

async function createNftTx(nftName, nftSymbol, nftUri, blockhash, ownerWallet, lastValidBlockHeight, mintAccount, names, symbols, uris) {
    const mintKeypair = await web3.Keypair.generate();
    mintAccount.push(mintKeypair);
    const nftTokenAccount = await getAssociatedTokenAddress(
        mintKeypair.publicKey,
        provider.wallet.publicKey
    );

    console.log("Mint key: ", mintKeypair.publicKey.toString());
    console.log("User: ", provider.wallet.publicKey.toString());
    console.log("nftName: ", nftName);

    const metadataAddress = await getMetadata(mintKeypair.publicKey);
    console.log("Metadata address: ", metadataAddress);
    const masterEditionAddress = await (web3.PublicKey.findProgramAddressSync(
        [
            Buffer.from("metadata"),
            TOKEN_METADATA_PROGRAM_ID.toBuffer(),
            mintKeypair.publicKey.toBuffer(),
            Buffer.from("edition"),
        ],
        TOKEN_METADATA_PROGRAM_ID
    ))[0];

    const name = nftName;
    const symbol = nftSymbol;
    const uri = nftUri;

    names.push(name);
    symbols.push(symbol);
    uris.push(uri);

    const mintTx = await program.methods
        .mint(name,
            symbol,
            uri)
        .accounts(
            {
                masterEdition: masterEditionAddress,
                mintAuthority: provider.wallet.publicKey,
                mint: mintKeypair.publicKey,
                tokenAccount: nftTokenAccount,
                tokenProgram: TOKEN_PROGRAM_ID,
                metadata: metadataAddress,
                tokenMetadataProgram: TOKEN_METADATA_PROGRAM_ID,
                payer: provider.wallet.publicKey,
                systemProgram: web3.SystemProgram.programId,
            }
        ).instruction()

    let tx = await new web3.Transaction();
    tx.recentBlockhash = blockhash;
    tx.feePayer = ownerWallet;
    const setComputeUnitLimitInstruction = web3.ComputeBudgetProgram.setComputeUnitLimit(
        { units: 500_000 }
    );
    tx.add(setComputeUnitLimitInstruction, mintTx);

    return tx;
}
