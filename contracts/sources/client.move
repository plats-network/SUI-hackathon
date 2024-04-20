module sui_nft::client {


    use sui::tx_context::{TxContext};
    use sui_nft::ticket_collection::{Self as collection, EventTicket};
    use sui::object::ID;


    public entry fun mint_ticket(
        event_ticket: &mut EventTicket,
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        category: vector<u8>,
        event_id: vector<u8>,
        token_id: u64,
        ctx: &mut TxContext
    ): ID {

        let nft_id = collection::mint_ticket(event_ticket, name, description, image_url, category, event_id, token_id, ctx);
        nft_id
    }


    public entry fun mint_batch_tickets(
        event_id: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        catogory: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext
    ): vector<ID>{

        let tickets = collection::mint_tickets(event_id, name, description, url, catogory, max_supply, ctx );

        tickets
    }

    public entry fun mint_session(
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        event_id: vector<u8>,
        ctx: &mut TxContext
    ): ID {
        
        let session_id = collection::mint_session(name, description, url, event_id, ctx);

        session_id

    }

    public entry fun mint_batch_sessions(
        event_id: vector<u8>,
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        ctx: &mut TxContext
    ): vector<ID> {
        
        let sessions = collection::mint_sessions(names, descriptions, urls, event_id,  ctx);
        sessions

    }


    public entry fun mint_booth(
        event_id: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        ctx: &mut TxContext
    ): ID {
        let booth_id = collection::mint_booth( name, description, url, event_id,  ctx);
        booth_id

    }

    public entry fun mint_batch_booths(
        event_id: vector<u8>,
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        ctx: &mut TxContext
    ): vector<ID> {
        let booths = collection::mint_booths(names, descriptions, urls, event_id, ctx);
        booths

    }


}

