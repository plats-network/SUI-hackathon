module sui_nft::client {


    use sui::tx_context::{TxContext};
    
    use sui_nft::ticket_collection::{Self as collection, TicketCollection};
    
    // ===== Collection =====
    entry fun create_new_collection<T: key>(
        url: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        price: u64,
        ctx: &mut TxContext
    ) {

        collection::create<T>(url, name, description, price, ctx);

    }

    entry fun mint_nft<T>(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        category: vector<u8>,
        collection: &mut TicketCollection<T>,
        token_id: u64,
        ctx: &mut TxContext
    ) {

        collection::mint(collection, name, description, image_url, category, token_id, ctx);

    }


    public entry fun mint_batch<T>(
        collection:&mut TicketCollection<T>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        catogory: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext
    ){


        // let nfts: vector<NFTTicket> = collection::mint_batch(collection, name, description, url, category, max_supply, ctx);
        // let len = vector::length(&nfts);
        // let i = 0;

        // while (i < len) {
        //     let nft = *vector::borrow(&mut nfts, i);
        //     transfer::transfer(nft, tx_context::sender(ctx));
        //     i = i+1;
        // }
        collection::mint_batch(collection,name, description, url, catogory, max_supply, ctx );
    
    }

    entry fun mint_session<T>(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        collection: &TicketCollection<T>,
        ctx: &mut TxContext
    ) {
        collection::mint_session(collection, name, description, image_url, ctx);
    }

    entry fun mint_batch_sessions<T>(
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        image_urls: vector<vector<u8>>,
        collection: &mut TicketCollection<T>,
        ctx: &mut TxContext
    ) {
        collection::mint_batch_sessions(collection, names, descriptions, image_urls, ctx);
    }


    entry fun mint_booth<T>(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        collection: &mut TicketCollection<T>,
        ctx: &mut TxContext
    ) {
        collection::mint_booth(collection, name, description, image_url, ctx);
    }

    entry fun mint_batch_booths<T>(
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        image_urls: vector<vector<u8>>,
        collection: &mut TicketCollection<T>,
        ctx: &mut TxContext
    ) {
        collection::mint_batch_booths(collection, names, descriptions, image_urls, ctx);
    }


}

