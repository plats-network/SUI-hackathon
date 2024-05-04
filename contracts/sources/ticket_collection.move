module sui_nft::ticket_collection {
    use std::string::{Self, String};
    use sui::package::{Self, Publisher};
    use sui::event;
    use sui_nft::utils::{Self};
    use sui::bag::{Bag, Self};
    use sui::dynamic_object_field as ofield;

    public struct NFTTicket has key, store {
        id: UID,
        /// Name for the token
        name: String,
        /// Description of the token
        description: String,
        /// URL for the token
        image_url: String,
        /// Event ID
        event_id: String,
        catogory: String,
        // token ID 
        token_id: u64,
        claimed: bool,

    }

    public struct EventTicket has key {
        id: UID,
        tickets: Bag,
        sessions: Bag,
        clients: vector<ID>,
        booths: Bag,
        locked: bool,
        owner: address,
        
    }

    public struct EventTicketClaimed has key, store {
        id : UID,
    }

    public struct EventSessionClaimed has key, store {
        id : UID,
    }

    public struct EventBoothClaimed has key, store {
        id : UID,
    }

    public struct SessionCollection has key, store {
        id: UID, 
        sessions: vector<ID>,
        locked: bool,
        event: ID
    }

    public struct BoothCollection has key, store {
        id: UID, 
        booths: vector<ID>,
        locked: bool,
        event: ID
    }


    public struct NFTBooth has key, store {
        id: UID,
        /// Name for the token
        name: String,
        /// Description of the token
        description: String,
        /// URL for the token
        image_url: String,
        /// Event Id 
        event_id: String,
        token_id: u64
    }

    public struct NFTSession has key, store {
        id: UID,
        /// Name for the token
        name: String,
        /// Description of the token
        description: String,
        /// URL for the token
        image_url: String,
        /// Event Id 
        event_id: String,

        token_id: u64,
    }


    // ===== Events for NFT Ticket =====

    public struct NFTMinted has copy, drop {
        // The Object ID of the NFT
        object_id: ID,
        // The creator of the NFT
        creator: address,
        creator_id: ID 
    }


    // Error code
    const ENotOwner: u64 = 0;
    const ENotInClients: u64 = 1;
    const ENotClient: u64 = 2;
    const EEventOver: u64 = 3;
    const EBoothOver: u64 = 4;
    const ESessionOver: u64 = 5;
    const ENotEventOwner: u64 = 6;
    const ENotClaimTicket: u64 = 7;

    // OTW
    public struct TICKET_COLLECTION has drop {}


    fun init(otw: TICKET_COLLECTION, ctx:&mut TxContext) {
        // 1. Create a publisher (OTW required). This grants root user privileges.
        let sender = tx_context::sender(ctx);
        let publisher: Publisher = package::claim(otw, ctx);

        // 2. Send publisher to sender
        transfer::public_transfer(publisher, sender);
    }

    // create a new shared EventTicket
    #[allow(lint(self_transfer))]
    public fun create_event(client:address, ctx: &mut TxContext) {
        let id = object::new(ctx);
        let event_id = *object::uid_as_inner(&id);
        let tickets = bag::new(ctx);
        let sessions  = bag::new(ctx);
        let booths  = bag::new(ctx);
        let mut clients = vector::empty();
        let client_id = object::id_from_address(client); 
        vector::push_back(&mut clients, client_id);
        transfer::share_object(
            EventTicket {
                id,
                tickets,
                sessions,
                booths,
                clients,
                locked: false,
                owner: client 
            }
        );
        // sessions
        transfer::share_object(
            SessionCollection {
                id: object::new(ctx),
                sessions: vector::empty(),
                locked: false,
                event: event_id
            });
        // booths 
        transfer::share_object(
            BoothCollection {
                id: object::new(ctx),
                booths: vector::empty(),
                locked: false,
                event: event_id
            }
        );

    }

    public(package) fun assert_authority(pub: &Publisher) {
        assert!(package::from_package<EventTicket>(pub), ENotOwner);
    }

    public(package) fun assert_client(self: &EventTicket, client: address) {
        let wl = &self.clients;
        let id = object::id_from_address(client); 
        assert!(utils::is_in_list(wl, &id), ENotClient);
    }

    fun drop_client(self: &mut EventTicket, addr: &ID) {
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
    public(package) fun add_client(self: &mut EventTicket, addr: address, pub: &Publisher) {
        assert_authority(pub);
        let wl = &self.clients;
        let id = object::id_from_address(addr);
        if (!utils::is_in_list(wl, &id)) {
            vector::push_back<ID>(
                &mut self.clients, 
                id
            );
        } else {
            //abort ENotInClients
            return
        };
    }

    public(package) fun remove_client(self: &mut EventTicket, addr: address, pub: &Publisher) {
        assert_authority(pub);
        let id = &object::id_from_address(addr);
        drop_client(self, id);
    }

    public(package) fun lock_event(event: &mut EventTicket, locked: bool, ctx: &TxContext) {
        let sender = tx_context::sender(ctx);
        assert!(event.owner == sender, ENotEventOwner);
        event.locked = locked; 
    }
    public(package) fun lock_session(event_ticket: &mut EventTicket, session_collection: &mut SessionCollection, locked: bool, ctx: &TxContext) {
        let sender = tx_context::sender(ctx);
        assert!(event_ticket.owner == sender, ENotEventOwner);
        session_collection.locked = locked;
    }

    public(package) fun lock_booth(event_ticket: &mut EventTicket, booth_collection: &mut BoothCollection, locked: bool, ctx: &TxContext) {
        let sender = tx_context::sender(ctx);
        assert!(event_ticket.owner == sender, ENotEventOwner);

        booth_collection.locked = locked;
    }

    public(package) fun mint_ticket(
        event_ticket: &mut EventTicket,
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        category: vector<u8>,
        event_id: vector<u8>,
        token_id: u64,
        ctx: &mut TxContext
    
    ): ID {
        let sender = tx_context::sender(ctx);
        assert_client(event_ticket, sender);
        let nft = mint_to_sender(name,  description, image_url, event_id, category, token_id,  ctx);
        let nft_id = *object::uid_as_inner(&nft.id);
        //transfer::transfer(nft, tx_context::sender(ctx));
        let mut claimed = EventTicketClaimed {id: object::new(ctx)};
        ofield::add(&mut claimed.id, true,  nft);
        bag::add(&mut event_ticket.tickets, nft_id, claimed);
        nft_id

    }
    // Internal claim ticket function 
    fun internal_claim_ticket<T: key + store>(
        event_ticket: &mut EventTicket,
        ticket_id: ID
    ): T {
        //let tickets = event_ticket.tickets;
        //let ticket = bag::borrow(&tickets, )
        assert!(event_ticket.locked == false, EEventOver );
        let EventTicketClaimed {mut id} = bag::remove(&mut event_ticket.tickets, ticket_id);
        // todo with payment

        // update claimed to true
        let claimed_ticket = ofield::borrow_mut<bool, NFTTicket>(&mut id, true);
        claimed_ticket.claimed = true;
        let removed_ticket = ofield::remove(&mut id, true);
        object::delete(id);
        removed_ticket
    }

    // Claim ticket by user
    public entry fun claim_ticket<T: key + store>(
        event_ticket: &mut EventTicket,
        ticket_id: ID,
        ctx: &mut TxContext
    ) {
        transfer::public_transfer(
            internal_claim_ticket<T>(event_ticket, ticket_id),
            tx_context::sender(ctx)
        )
    }

    public(package) fun mint_tickets(
        event_ticket: &mut EventTicket,
        event_id: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        catogory: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext  
    ): vector<ID>{
        let sender = tx_context::sender(ctx);
        assert_client(event_ticket, sender);
        let mut tickets = vector::empty();
        let mut i = 0; 
        
        while (i < max_supply){
            let nft = mint_to_sender(name,  description, url, event_id, catogory, i,  ctx);
            let nft_id = *object::uid_as_inner(&nft.id);
            let mut claimed = EventTicketClaimed {id: object::new(ctx)};
            ofield::add(&mut claimed.id, true,  nft);
            bag::add(&mut event_ticket.tickets, nft_id, claimed);
            vector::push_back(&mut tickets, nft_id);
            i = i +1;
        };  
        tickets
    }

    public(package) fun mint_session(
        event_ticket: &mut EventTicket,
        session_collection: &mut SessionCollection, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        ctx: &mut TxContext  
    
    ): ID{
        let sender = tx_context::sender(ctx);
        assert_client(event_ticket, sender);
        let session = mint_session_single(name,  description, image_url, event_id, 0, ctx);
        let session_id = *object::uid_as_inner(&session.id);
        let mut claimed = EventSessionClaimed {id: object::new(ctx)};
        ofield::add(&mut claimed.id, true,  session);
        bag::add(&mut event_ticket.sessions, session_id, claimed);
        vector::push_back(&mut session_collection.sessions, session_id);

        session_id
    }
    public(package) fun mint_sessions(
        event_ticket: &mut EventTicket,
        session_collection: &mut SessionCollection,
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        event_id: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext    
    ): vector<ID>{
        let sender = tx_context::sender(ctx);
        assert_client(event_ticket, sender);
        let mut sessions = vector::empty();
        let mut i = 0; 

        // todo
        let len = vector::length(&names);

        while (i < len){
            let mut j = 0;
            let name = vector::borrow(&names, i );
            let description = vector::borrow(&descriptions, i);
            let url = vector::borrow(&urls, i);
            while (j < max_supply) {
                let session = mint_session_single(*name,  *description, *url, event_id,j,  ctx);
                let session_id = *object::uid_as_inner(&session.id);
                let mut claimed = EventSessionClaimed {id: object::new(ctx)};
                ofield::add(&mut claimed.id, true,  session);
                bag::add(&mut event_ticket.sessions, session_id, claimed);
                vector::push_back(&mut sessions, session_id);   
                j = j+1;
            };

            i = i +1;
        };
        vector::append(&mut session_collection.sessions, sessions);
        sessions
    }

    // Internal claim session function 
    fun internal_claim_session<T: key + store>(
        event_ticket: &mut EventTicket,
        session_collection: &SessionCollection,
        session_id: ID
    ): T {

        assert!(event_ticket.locked == false, EEventOver );

        assert!(session_collection.locked == &false, ESessionOver);

        //let session_id = *object::uid_as_inner(&session.id);
        let EventSessionClaimed {mut id} = bag::remove(&mut event_ticket.sessions, session_id);
        // todo with payment
        //session.claimed = true;
        let claimed_session = ofield::remove(&mut id, true);
        object::delete(id);
        claimed_session
    }

    // Claim ticket by user
    public entry fun claim_session<T: key + store>(
        event_ticket: &mut EventTicket,
        session_collection: &SessionCollection,
        session_id: ID,
        ctx: &mut TxContext
    ) {
        transfer::public_transfer(
            internal_claim_session<T>(event_ticket,session_collection,  session_id),
            tx_context::sender(ctx)
        )
    }

    public(package) fun mint_booth(
        event_ticket: &mut EventTicket,
        booth_collection: &mut BoothCollection, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        ctx: &mut TxContext    
    ): ID{

        let sender = tx_context::sender(ctx);
        assert_client(event_ticket, sender);
        let booth = mint_booth_single(name,  description, image_url, event_id, 1,  ctx);
        let booth_id = *object::uid_as_inner(&booth.id);
        let mut claimed = EventBoothClaimed {id: object::new(ctx)};
        ofield::add(&mut claimed.id, true,  booth);
        bag::add(&mut event_ticket.booths, booth_id, claimed);
        vector::push_back(&mut booth_collection.booths, booth_id);
        booth_id

    }    
    public(package) fun mint_booths(
        event_ticket: &mut EventTicket,
        booth_collection: &mut BoothCollection,
        names: vector<vector<u8>>,
        descriptions: vector<vector<u8>>,
        urls: vector<vector<u8>>,
        event_id: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext
    ):vector<ID>{

        let sender = tx_context::sender(ctx);
        assert_client(event_ticket, sender);

        let mut i = 0; 
        let mut booths = vector::empty();

        let len = vector::length(&names);
        while (i < len){
            let mut j = 0;

            let name = vector::borrow(&names, i );
            let description = vector::borrow(&descriptions, i);
            let url = vector::borrow(&urls, i);
            while (j < max_supply) {
                let booth = mint_booth_single(*name,  *description, *url, event_id, j ,  ctx);
                let booth_id = *object::uid_as_inner(&booth.id);
                let mut claimed = EventBoothClaimed {id: object::new(ctx)};
                ofield::add(&mut claimed.id, true,  booth);
                bag::add(&mut event_ticket.booths, booth_id, claimed);
                vector::push_back(&mut booths, booth_id);   
                j = j+1;
            };

            i = i +1;
        };
        vector::append(&mut booth_collection.booths, booths);

        booths    
    }

    // Internal claim ticket function 
    fun internal_claim_booth<T: key + store>(
        event_ticket: &mut EventTicket,
        booth_collection: &BoothCollection, 
        booth_id: ID
    ): T {

        assert!(event_ticket.locked == false, EEventOver );
        assert!(booth_collection.locked == &false, EBoothOver);

        let EventBoothClaimed {mut id} = bag::remove(&mut event_ticket.booths, booth_id);
        // todo with payment


        let claimed_booth = ofield::remove(&mut id, true);
        object::delete(id);
        claimed_booth
    }

    // Claim ticket by user
    public entry fun claim_booth<T: key + store>(
        event_ticket: &mut EventTicket,
        booth_collection: &BoothCollection, 
        booth_id: ID,
        ctx: &mut TxContext
    ) {
        transfer::public_transfer(
            internal_claim_booth<T>(event_ticket,booth_collection,  booth_id),
            tx_context::sender(ctx)
        )
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
            token_id,
            claimed: false
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
        token_id: u64,
        ctx: &mut TxContext
    ): NFTBooth {
        let nft = NFTBooth {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url),
            event_id: string::utf8(event_id),
            token_id
        };

        nft
    }

    public fun mint_session_single(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        event_id: vector<u8>,
        token_id: u64,
        ctx: &mut TxContext
    ): NFTSession {
        let nft = NFTSession {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url),
            event_id: string::utf8(event_id),
            token_id,
        };

        nft
    }

    public fun check_claimed_ticket(
        event_ticket: &EventTicket,
        ticket_id: ID,
        ctx: &mut TxContext
    ) : bool {
        let EventTicketClaimed {id} = bag::borrow(&event_ticket.tickets, ticket_id);

        let claimed_ticket = ofield::borrow<bool, NFTTicket>(id, true);
        claimed_ticket.claimed
    }

    #[test_only] public fun init_for_testing(ctx: &mut TxContext) { init(TICKET_COLLECTION{}, ctx) }


    #[test]
    fun test_clients() {
        use sui::test_scenario::{Self, ctx};
        use std::type_name;
        use std::ascii;
        use std::debug;
        use std::string::utf8;
        let client1 = @0xABCD;
        let client2 = @0xABCDE;
        let admin = @0xABCDEF;
        let user1 = @0xAB;
        let user2 = @0xABC;

        let ticket_created_id;
        let ticket_created_ids;
        let session_created_ids;
        let booth_created_ids;

        //let user = @0xAB;
        let event_id_1 = b"8ba9148d4e85e4a6862e8fa613f6cf6b";
        let event_id_2 = b"8ba9148d4e85e4a6862e8fa613f6cf6a";

        let mut scenario_val = test_scenario::begin(admin);
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
            //let pub = test_scenario::take_from_sender<Publisher>(scenario);
            create_event(client1,  ctx(scenario));
            //test_scenario::return_to_sender(scenario, pub);

        };

        // Add whitelist client 1 
        test_scenario::next_tx(scenario, admin);

        {
            let pub = test_scenario::take_from_sender<Publisher>(scenario);
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            add_client(&mut event_ticket,client1 ,&pub);
            test_scenario::return_to_sender(scenario, pub);
            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        // Add whitelist client 2
        test_scenario::next_tx(scenario, admin);

        {
            let pub = test_scenario::take_from_sender<Publisher>(scenario);
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            add_client(&mut event_ticket,client2 ,&pub);
            test_scenario::return_to_sender(scenario, pub);
            test_scenario::return_shared<EventTicket>(event_ticket);

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
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
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

        test_scenario::next_tx(scenario, client1);
        {
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            lock_event(&mut event_ticket, false, ctx(scenario));
            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        // claim by user
        test_scenario::next_tx(scenario, user1);
        {
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            claim_ticket<NFTTicket>(&mut event_ticket, ticket_created_id, ctx(scenario));

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
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            let tickets: vector<ID> = mint_tickets(&mut event_ticket, event_id_1 , b"This is sui hackthon VN", b"This is description", b"abc.xyz", b"Standard",3, ctx(scenario));
            debug::print(&utf8(b"CHECK MINT BATCH TICKETS WITH CLIENT 1"));
            debug::print(&tickets);
            ticket_created_ids = tickets;
            test_scenario::return_shared<EventTicket>(event_ticket);
        };


        // mint batch tickets with client 2
        test_scenario::next_tx(scenario, client2);

        {
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            let tickets: vector<ID> = mint_tickets(&mut event_ticket, event_id_2, b"This is sui hackathon Global", b"This is description", b"abc.xyz", b"Standard",3, ctx(scenario));

            debug::print(&utf8(b"CHECK MINT BATCH TICKETS WITH CLIENT 2"));
            debug::print(&tickets);

            test_scenario::return_shared<EventTicket>(event_ticket);
        };

        test_scenario::next_tx(scenario, admin);
        {
            let event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            debug::print(&utf8(b"EVENT TICKET AFTER MINTING BATCH BY USER1 AND USER2"));
            
            debug::print(&event_ticket);
            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        // user1 claim ticket from client 1 
        test_scenario::next_tx(scenario, user1);
        {
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);

            let ticket_id_1 = *vector::borrow(&ticket_created_ids, 0);

            claim_ticket<NFTTicket>(&mut event_ticket, ticket_id_1, ctx(scenario));

            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        // user2 claim ticket from client 1
        test_scenario::next_tx(scenario, user2);
        {
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);

            let ticket_id_2 = *vector::borrow(&ticket_created_ids, 1);
            claim_ticket<NFTTicket>(&mut event_ticket, ticket_id_2, ctx(scenario));

            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        // check ticket claimed by user1 and user 2 
        test_scenario::next_tx(scenario, admin);
        {
            let ticket_by_user1 = test_scenario::take_from_address<NFTTicket>(scenario, user1);
            let ticket_by_user2 = test_scenario::take_from_address<NFTTicket>(scenario, user2);


            debug::print(&utf8(b"TICKET CLAIMED BY USER1"));
            debug::print(&ticket_by_user1);

            debug::print(&utf8(b"TICKET CLAIMED BY USER2"));
            debug::print(&ticket_by_user2);

            test_scenario::return_to_address(user1, ticket_by_user1);
            test_scenario::return_to_address(user2, ticket_by_user2);

        };

        test_scenario::next_tx(scenario, client1);

        {

            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);

            let mut names = vector::empty();
            let mut descriptions = vector::empty();
            let mut urls = vector::empty();
            vector::push_back(&mut names ,b"Session1");
            vector::push_back(&mut descriptions ,b"This is session 1");
            vector::push_back(&mut urls ,b"session1.xyz");

            vector::push_back(&mut names ,b"Session2");
            vector::push_back(&mut descriptions ,b"This is session 2");
            vector::push_back(&mut urls ,b"session2.xyz");



            let sessions: vector<ID> = mint_sessions(&mut event_ticket, names, descriptions, urls, event_id_1, 2,   ctx(scenario));
            session_created_ids = sessions;
            debug::print(&utf8(b"CHECK MINT BATCH SESSIONS"));
            debug::print(&sessions);
            test_scenario::return_shared<EventTicket>(event_ticket);
            //test_scenario::return_to_sender(scenario, sessions);
        };

        // lock session 

        test_scenario::next_tx(scenario, client1);
        {
            let session_id_1 = *vector::borrow(&session_created_ids, 0);
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);
            lock_session(&mut event_ticket, session_id_1, false, ctx(scenario));
            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        // user 2 claim session 
        test_scenario::next_tx(scenario, user2);
        {
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);

            let session_id_1 = *vector::borrow(&session_created_ids, 0);
            claim_session<NFTSession>(&mut event_ticket, session_id_1, ctx(scenario));

            test_scenario::return_shared<EventTicket>(event_ticket);

        };

        test_scenario::next_tx(scenario, client1);

        {
            let mut event_ticket = test_scenario::take_shared<EventTicket>(scenario);

            let mut names = vector::empty();
            let mut descriptions = vector::empty();
            let mut urls = vector::empty();
            vector::push_back(&mut names ,b"Booth 1");
            vector::push_back(&mut descriptions ,b"This is booth 1");
            vector::push_back(&mut urls ,b"booth1.xyz");

            vector::push_back(&mut names ,b"Booth 2");
            vector::push_back(&mut descriptions ,b"This is booth 2");
            vector::push_back(&mut urls ,b"booth2.xyz");

            let booths: vector<ID> = mint_booths(&mut event_ticket, names, descriptions, urls, event_id_1,1,  ctx(scenario));
            debug::print(&utf8(b"CHECK MINT BATCH BOOTHS"));
            debug::print(&booths);
            booth_created_ids = booths;
            test_scenario::return_shared<EventTicket>(event_ticket);
            //test_scenario::return_to_sender(scenario, booths);
        };

        // lock booth
        // test_scenario::next_tx(scenario, client1);
        // {
        //     let booth_id_1 = *vector::borrow(&booth_created_ids, 0);
        //     let event_ticket = test_scenario::take_shared<EventTicket>(scenario);
        //     lock_booth(&mut event_ticket, booth_id_1, false, ctx(scenario));
        //     test_scenario::return_shared<EventTicket>(event_ticket);

        // };

        // end test
        test_scenario::end(scenario_val);
    }


}