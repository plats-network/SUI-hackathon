module sui_nft::ticket_collection {
    use std::string::{Self, String};
    use sui::object::{Self, UID, ID};
    use sui::transfer;
    use sui::tx_context::{Self, TxContext};
    use sui::package::{Self, Publisher};
    use std::vector;
    use sui::event;
    use sui_nft::utils::{Self};
    use sui::bag::{Bag, Self};
    use sui::dynamic_object_field as ofield;
    friend sui_nft::client;
    friend sui_nft::admin;


    struct NFTTicket has key, store {
        id: UID,
        /// Name for the token
        name: String,
        /// Description of the token
        description: String,
        /// URL for the token
        image_url: String,
        /// Event ID
        event_id: String,
        //claimed: bool,
        catogory: String,
        // token ID 
        token_id: u64,

    }

    struct EventTicket has key {
        id: UID,
        tickets: Bag
    
    }

    struct EventTicketClaimed has key, store {
        id : UID,
    }


    struct NFTBooth has key {
        id: UID,
        /// Name for the token
        name: String,
        /// Description of the token
        description: String,
        /// URL for the token
        image_url: String,

        /// Event Id 
        event_id: String,
    }

    struct NFTSession has key {
        id: UID,
        /// Name for the token
        name: String,
        /// Description of the token
        description: String,
        /// URL for the token
        image_url: String,

        /// Event Id 
        event_id: String,

    }


    // ===== Events =====

    struct NFTMinted has copy, drop {
        // The Object ID of the NFT
        object_id: ID,
        // The creator of the NFT
        creator: address,
        creator_id: ID 
    }



    struct Clients has key {
        id: UID,
        clients: vector<ID>
    } 
    // Error code
    const ENotOwner: u64 = 0;
    const ENotInClients: u64 = 1;
    const ENotClient: u64 = 2;

    // OTW
    struct TICKET_COLLECTION has drop {}


    fun init(otw: TICKET_COLLECTION, ctx:&mut TxContext) {
        // 1. Create a publisher (OTW required). This grants root user privileges.
        let sender = tx_context::sender(ctx);
        let publisher: Publisher = package::claim(otw, ctx);

        let clients = Clients {
            id: object::new(ctx),
            clients: vector::empty()
        };
        transfer::transfer(clients, sender);
        // 2. Send publisher to sender
        transfer::public_transfer(publisher, sender);
    }

    // create a new shared EventTicket
    public fun create_tickets(ctx: &mut TxContext) {
        let id = object::new(ctx);
        let tickets = bag::new(ctx);
        transfer::share_object(
            EventTicket {
                id,
                tickets
            }
        )
    
    
    }

    public(friend) fun assert_authority<T>(pub: &Publisher) {
        assert!(package::from_package<T>(pub), ENotOwner);
    }

    public(friend) fun assert_client(self: &Clients, client: address) {
        let wl = &self.clients;
        let id = object::id_from_address(client); 
        assert!(utils::is_in_list(wl, &id), ENotClient);
    }

    fun drop_client<T>(self: &mut Clients, addr: &ID) {
        let wl = &self.clients;
        let (in_wl, i) = vector::index_of(wl, addr);
        if (in_wl) {
            vector::remove<ID>(
                &mut self.clients,
                i
            );
        } else {
            abort ENotInClients
        };
    }

    // publisher only function to add address to whitelist
    public(friend) fun add_client<T>(self: &mut Clients, addr: address, pub: &Publisher) {
        assert_authority<T>(pub);
        let wl = &self.clients;
        let id = object::id_from_address(addr);
        if (!utils::is_in_list(wl, &id)) {
            vector::push_back<ID>(
                &mut self.clients, 
                id
            );
        } else {
            abort ENotInClients
        };
    }

    public(friend) fun remove_client<T>(self: &mut Clients, addr: address, pub: &Publisher) {
        assert_authority<T>(pub);
        let id = &object::id_from_address(addr);
        drop_client<T>(self, id);
    }


    public(friend) fun mint_ticket(
        event_ticket: &mut EventTicket,
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        category: vector<u8>,
        event_id: vector<u8>,
        token_id: u64,
        ctx: &mut TxContext
    
    ): ID {        
        let nft = mint_to_sender(name,  description, image_url, event_id, category, token_id,  ctx);
        let nft_id = *object::uid_as_inner(&nft.id);
        //transfer::transfer(nft, tx_context::sender(ctx));
        let claimed = EventTicketClaimed {id: object::new(ctx)};
        ofield::add(&mut claimed.id, true,  nft);
        bag::add(&mut event_ticket.tickets, nft_id, claimed);
        nft_id

    }
    // Internal claim function 
    fun internal_claim<T: key + store>(
        event_ticket: &mut EventTicket,
        ticket_id: ID
    ): T {
        let EventTicketClaimed {id} = bag::remove(&mut event_ticket.tickets, ticket_id);
        // todo with payment

        let ticket = ofield::remove(&mut id, true);
        object::delete(id);
        ticket
    }

    // Claim by user
    public fun claim<T: key + store>(
        event_ticket: &mut EventTicket,
        ticket_id: ID,
        ctx: &mut TxContext
    ) {
        transfer::public_transfer(
            internal_claim<T>(event_ticket, ticket_id),
            tx_context::sender(ctx)
        )
    }

    public(friend) fun mint_tickets(
        event_id: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        catogory: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext  
    ): vector<ID>{
        let tickets = vector::empty();
        let i = 0; 
        while (i < max_supply){
            let nft = mint_to_sender(name,  description, url, event_id, catogory, i,  ctx);
            let nft_id = *object::uid_as_inner(&nft.id);
            vector::push_back(&mut tickets, nft_id);
            transfer::transfer(nft, tx_context::sender(ctx));
            i = i +1;
        };  
        tickets
    }

    public(friend) fun mint_session(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        ctx: &mut TxContext  
    
    ): ID{
        let sender = tx_context::sender(ctx);
        let session = mint_session_single(name,  description, image_url, event_id, ctx);
        let session_id = *object::uid_as_inner(&session.id);
        transfer::transfer(session, sender);
        session_id
    }
    public(friend) fun mint_sessions(
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        event_id: vector<u8>,
        ctx: &mut TxContext    
    ): vector<ID>{
        let sender = tx_context::sender(ctx);
        let sessions = vector::empty();
        let i = 0; 
        // todo
        let len = vector::length(&names);

        while (i < len){
            let name = vector::borrow(&names, i );
            let description = vector::borrow(&descriptions, i);
            let url = vector::borrow(&urls, i);
            let session = mint_session_single(*name,  *description, *url, event_id,  ctx);
            let session_id = *object::uid_as_inner(&session.id);
            vector::push_back(&mut sessions, session_id);
            transfer::transfer(session, sender);
            i = i +1;
        };

        sessions
    }
    public(friend) fun mint_booth(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        ctx: &mut TxContext    
    ): ID{
        let sender = tx_context::sender(ctx);
        let booth = mint_booth_single(name,  description, image_url, event_id, ctx);
        let booth_id = *object::uid_as_inner(&booth.id);
        transfer::transfer(booth, sender);
        booth_id

    }    
    public(friend) fun mint_booths(
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        event_id: vector<u8>,
        ctx: &mut TxContext
    ):vector<ID>{
        let i = 0; 
        let booths = vector::empty();

        let len = vector::length(&names);
        while (i < len){
            let name = vector::borrow(&names, i );
            let description = vector::borrow(&descriptions, i);
            let url = vector::borrow(&urls, i);
            let booth = mint_booth_single(*name,  *description, *url, event_id,  ctx);
            let booth_id = *object::uid_as_inner(&booth.id);
            vector::push_back(&mut booths, booth_id);
            transfer::transfer(booth, tx_context::sender(ctx));
            i = i +1;
        };

        booths    
    }
    public fun mint_to_sender(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        catogory: vector<u8>,
        token_id: u64,
        ctx: &mut TxContext
    ): NFTTicket {
        let nft = NFTTicket {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url),
            event_id: string::utf8(event_id),
            catogory: string::utf8(catogory),
            token_id
        };


        let nft_id = object::uid_to_bytes(&nft.id);
        let recipient = tx_context::sender(ctx);
        let recipient_id = object::id_from_address(*&recipient);
        event::emit(NFTMinted {
            object_id: object::id_from_bytes(*&nft_id), 
            creator: recipient,
            creator_id: recipient_id
        });
        nft
    }

    

    public fun mint_booth_single(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        ctx: &mut TxContext
    ): NFTBooth {
        let nft = NFTBooth {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url),
            event_id: string::utf8(event_id),
        };

        // let nft_id = object::uid_to_bytes(&nft.id);
        // let recipient = tx_context::sender(ctx);
        // let recipient_id = object::id_from_address(*&recipient);
        nft
    }

    public fun mint_session_single(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        ctx: &mut TxContext
    ): NFTSession {
        let nft = NFTSession {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url),
            event_id: string::utf8(event_id),
        };

        nft
    }

    #[test_only] public fun init_for_testing(ctx: &mut TxContext) { init(TICKET_COLLECTION{}, ctx) }


    #[test]
    fun test_clients() {
        use sui::test_scenario::{Self, ctx};
        use sui::display::{Display};
        use std::type_name;
        use std::ascii;
        use std::debug;
        use sui::package::{Self, Publisher};
        use sui::object::{Self, ID};
        use std::vector;
        use std::string::utf8;
        let client1 = @0xABCD;
        let client2 = @0xABCDE;
        let admin = @0xABCDEF;
        let user1 = @0xAB;
        let user2 = @0xABC;

        let ticket_created_id;

        //let user = @0xAB;
        let event_id_1 = b"8ba9148d4e85e4a6862e8fa613f6cf6b";
        let event_id_2 = b"8ba9148d4e85e4a6862e8fa613f6cf6a";

        let scenario_val = test_scenario::begin(admin);
        let scenario = &mut scenario_val;
        {
            init_for_testing(ctx(scenario));
        };

        test_scenario::next_tx(scenario, admin);
        {
            // check Publisher
            let pub = test_scenario::take_from_sender<Publisher>(scenario);
            let pub_tn = type_name::get<Publisher>();
            
            let pub_module = package::published_module(&pub);
            debug::print(&pub_tn);
            assert!(pub_module == &ascii::string(b"ticket_collection"), 0);
            test_scenario::return_to_sender(scenario, pub);
        };

        // Create ticket events  
        test_scenario::next_tx(scenario, admin);

        {
            create_tickets(ctx(scenario));

        };

        test_scenario::next_tx(scenario, admin);

        {
            let event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            debug::print(&utf8(b"EVENT TICKET CREATED "));
            debug::print(&event_ticket);
            test_scenario::return_shared<EventTicket>(event_ticket);

        };


        test_scenario::next_tx(scenario, client1);

        {
            let event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            let ticket_id = mint_ticket(&mut event_ticket, b"This is sui hackthon VN", b"This is description", b"abc.xyz", b"Standard",  event_id_1, 3, ctx(scenario));
            debug::print(&utf8(b"TICKET NFT CREATED "));
            debug::print(&ticket_id);
            // assign ticket created id to serve for claim 
            ticket_created_id = ticket_id;

            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        test_scenario::next_tx(scenario, client1);
        {
            let event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            debug::print(&utf8(b"EVENT TICKET AFTER CREATING 1 NFT TICKET"));
            
            debug::print(&event_ticket);
            test_scenario::return_shared<EventTicket>(event_ticket);

        };
        // claim by user
        test_scenario::next_tx(scenario, user1);
        {
            let event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            claim<NFTTicket>(&mut event_ticket, ticket_created_id, ctx(scenario));

            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        test_scenario::next_tx(scenario, user1);
        {
            let event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            let ticket_by_user = test_scenario::take_from_sender<NFTTicket>(scenario);

            debug::print(&utf8(b"EVENT TICKET AFTER CLAIMING 1 NFT TICKET"));
            debug::print(&event_ticket);

            debug::print(&utf8(b"TICKET CLAIMED BY USER"));
            debug::print(&ticket_by_user);

            test_scenario::return_to_sender(scenario, ticket_by_user);
            test_scenario::return_shared<EventTicket>(event_ticket);

        };


        // mint batch tickets with client 1 
        test_scenario::next_tx(scenario, client1);

        {

            let tickets: vector<ID> = mint_tickets(event_id_1, b"This is sui hackthon VN", b"This is description", b"abc.xyz", b"Standard",3, ctx(scenario));
            debug::print(&utf8(b"CHECK MINT BATCH TICKETS WITH CLIENT 1"));
            debug::print(&tickets);
            //test_scenario::return_to_sender(scenario, tickets);
        };


        // check batch tickets with client 2
        test_scenario::next_tx(scenario, client2);

        {

            let tickets: vector<ID> = mint_tickets(event_id_2, b"This is sui hackathon Global", b"This is description", b"abc.xyz", b"Standard",3, ctx(scenario));

            debug::print(&utf8(b"CHECK MINT BATCH TICKETS WITH CLIENT 2"));
            debug::print(&tickets);
            //test_scenario::return_to_sender(scenario, tickets);
        };


        test_scenario::next_tx(scenario, client1);

        {

            let names = vector::empty();
            let descriptions = vector::empty();
            let urls = vector::empty();
            vector::push_back(&mut names ,b"Session1");
            vector::push_back(&mut descriptions ,b"This is session 1");
            vector::push_back(&mut urls ,b"session1.xyz");

            vector::push_back(&mut names ,b"Session2");
            vector::push_back(&mut descriptions ,b"This is session 2");
            vector::push_back(&mut urls ,b"session2.xyz");



            let sessions: vector<ID> = mint_sessions(names, descriptions, urls, event_id_1,  ctx(scenario));
            debug::print(&utf8(b"CHECK MINT BATCH SESSIONS"));
            debug::print(&sessions);
            //test_scenario::return_to_sender(scenario, sessions);
        };

        test_scenario::next_tx(scenario, client1);

        {

            let names = vector::empty();
            let descriptions = vector::empty();
            let urls = vector::empty();
            vector::push_back(&mut names ,b"Booth 1");
            vector::push_back(&mut descriptions ,b"This is booth 1");
            vector::push_back(&mut urls ,b"booth1.xyz");

            vector::push_back(&mut names ,b"Booth 2");
            vector::push_back(&mut descriptions ,b"This is booth 2");
            vector::push_back(&mut urls ,b"booth2.xyz");

            let booths: vector<ID> = mint_booths(names, descriptions, urls, event_id_1, ctx(scenario));
            debug::print(&utf8(b"CHECK MINT BATCH BOOTHS"));
            debug::print(&booths);

            //test_scenario::return_to_sender(scenario, booths);
        };

        // end test
        test_scenario::end(scenario_val);
    }


}