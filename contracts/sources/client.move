module sui_nft::client {


    use sui::tx_context::{TxContext, Self};
    use sui::transfer;
    use sui::package::Publisher;
    
    use sui_nft::ticket_collection::{Self as collection, TicketCollection};
    // ===== Collection =====
    entry fun create_new_collection<T: key>(
        pub: &Publisher, 
        url: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        price: u64,
        ctx: &mut TxContext
    ) {

        collection::create<T>(pub, url, name, description, price, ctx);

    }

    entry fun mint<T>(
        pub: &Publisher, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        category: vector<u8>,
        collection: &TicketCollection<T>,
        ctx: &mut TxContext
    ) {
        collection::mint(pub, collection, name, description, image_url, category, ctx);
    }

    entry fun set_url<T>(self: &mut TicketCollection<T>, url: vector<u8>, pub: &Publisher) {
        collection::set_url(self, url, pub);
    }

}

