module sui_nft::client {


    use sui_nft::ticket_collection::{Self as collection, EventTicket, SessionCollection, BoothCollection};

    public entry fun lock_event(
        event_ticket: &mut EventTicket,
        locked: bool, 
        ctx: &mut TxContext
    
    ) {
        collection::lock_event(event_ticket, locked, ctx);
    }
    public entry fun lock_session(
        event_ticket: &mut EventTicket,
        session_collection: &mut SessionCollection, 
        locked: bool, 
        ctx: &mut TxContext
    
    ) {
        collection::lock_session(event_ticket,session_collection, locked, ctx);
    }

    public entry fun lock_booth(
        event_ticket: &mut EventTicket,
        booth_collection: &mut BoothCollection, 
        locked: bool, 
        ctx: &mut TxContext
    
    ) {
        collection::lock_booth(event_ticket, booth_collection,  locked, ctx);
    }

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
        event_ticket: &mut EventTicket,
        event_id: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        catogory: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext
    ): vector<ID>{

        let tickets = collection::mint_tickets(event_ticket, event_id, name, description, url, catogory, max_supply, ctx );

        tickets
    }


    public entry fun mint_batch_sessions(
        event_ticket: &mut EventTicket,
        event_id: vector<u8>,
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        max_supply: u64,
        ctx: &mut TxContext
    ): vector<ID> {
        
        let sessions = collection::mint_sessions(event_ticket, names, descriptions, urls, event_id, max_supply,  ctx);
        sessions

    }

    public entry fun mint_batch_booths(
        event_ticket: &mut EventTicket,
        event_id: vector<u8>,
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        max_supply: u64,
        ctx: &mut TxContext
    ): vector<ID> {
        let booths = collection::mint_booths(event_ticket, names, descriptions, urls, event_id, max_supply, ctx);
        booths

    }


}

