module sui_nft::ticket_collection {
    use std::string::{Self, utf8, String};
    use sui::object::{Self, UID, ID};
    use std::option::{Self, Option};
    use sui::transfer;
    use sui::tx_context::{Self, TxContext};
    use sui::url::{Self, Url};
    use sui::package::{Self, Publisher};
    use sui::display;
    use sui::balance::{Self, Balance};
    use sui::sui::SUI;
    use std::vector;
    use std::ascii;
    use std::type_name;
    use sui_nft::ticket_nft::{Self, NFTTicket, NFTBooth, NFTSession};
    use sui_nft::utils::{Self};
    friend sui_nft::client;
    friend sui_nft::admin;

    struct TicketCollection<phantom T> has key {
        id: UID,
        name: String,
        description: String,
        price: u64,
        balance: Balance<SUI>,
        url: Option<Url>,
        tickets:vector<NFTTicket>,
        sessions: vector<NFTSession>,
        booths: vector<NFTBooth>,
    }



    struct Clients has key {
        id: UID,
        clients: vector<ID>
    } 
    // Error code
    const ENotOwner: u64 = 0;
    const ENotInClients: u64 = 1;
    const ENotClient: u64 = 2;
    const ENFTNotExist: u64 = 3;

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

    // Provide accessibility to client for creating new collection 
    public(friend) fun create<T: key>(
        //pub: &Clients,
        url: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        price: u64,
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        //assert_client(pub, sender);
        let collection = create_<T>(ctx);
        set_url(&mut collection, url);
        set_name(&mut collection, name);
        set_description(&mut collection, description);
        set_price(&mut collection, price);

        let name_field = collection.name;
        string::append_utf8(&mut name_field, b": {name}");

        // let dp = display::new<T>(pub, ctx);
        // display::add(&mut dp, utf8(b"name"), name_field);
        // display::add(&mut dp, utf8(b"description"), utf8(b"{description}"));
        // display::add(&mut dp, utf8(b"url"), utf8(b"{url}"));
        // display::update_version(&mut dp);

        transfer::transfer(collection, sender);
        //transfer::public_transfer(dp, sender);

    }

    public(friend) fun set_url<T>(self: &mut TicketCollection<T>, url: vector<u8>) {
        self.url = option::some(url::new_unsafe_from_bytes(url));
    }

    public(friend) fun set_name<T>(self: &mut TicketCollection<T>, name: vector<u8>) {
        self.name = string::utf8(name);
    }

    public(friend) fun set_description<T>(self: &mut TicketCollection<T>, description: vector<u8>) {
        self.description = string::utf8(description);
    }

    public(friend) fun set_price<T>(self: &mut TicketCollection<T>, price: u64) {
        self.price = price;
    }

    // HELPER FUNCTION
    // ANYONE CAN CALL
    fun create_<T>(ctx: &mut TxContext): TicketCollection<T> {
        let nft_typename = type_name::get<T>();
        let module_len = ascii::length(&type_name::get_module(&nft_typename));
        
        let nft_name = string::from_ascii(type_name::into_string(nft_typename));
        let nft_name = string::sub_string(&nft_name, 68+module_len, ascii::length(&type_name::into_string(nft_typename)));

        let name= utf8(b"Ticket");
        string::append(&mut name, nft_name);
        string::append(&mut name, utf8(b"s"));

        TicketCollection<T> {
            id: object::new(ctx),
            name: name,
            description: utf8(b"This is description"),
            url: option::none(),
            price: 0,
            balance: balance::zero(),
            tickets:vector::empty(),
            sessions: vector::empty(),
            booths: vector::empty()
        }
    }

    public(friend) fun mint<T>(
        self: &mut TicketCollection<T>, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        catogory: vector<u8>,
        token_id: u64,
        ctx: &mut TxContext) {

        let sender = tx_context::sender(ctx);
        let collection_id = *object::uid_as_inner(&self.id);
        let nft = ticket_nft::mint_to_sender(name,  description, image_url, collection_id, catogory, token_id,  ctx);
        //vector::pus(&mut self.tickets, nft);
        vector::push_back(&mut self.tickets, nft);
        //nft
    
    }

    public(friend) fun mint_batch<T>(
        collection:&mut TicketCollection<T>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        catogory: vector<u8>,
        max_supply: u64,
        ctx: &mut TxContext
    ) {

        let i = 0; 
        let collection_id = *object::uid_as_inner(&collection.id);
        while (i < max_supply){
            let nft = ticket_nft::mint_to_sender(name,  description, url, collection_id, catogory, i,  ctx);
            vector::push_back(&mut collection.tickets,nft);
            i = i +1;
        };
    }

    public(friend) fun mint_batch_booths<T>(
        collection:&mut TicketCollection<T>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        total: u64,
        ctx: &mut TxContext
    ) {

        let i = 0; 
        let collection_id = *object::uid_as_inner(&collection.id);
        while (i < total){
            let booth = ticket_nft::mint_booth(name,  description, url, collection_id,  ctx);
            vector::push_back(&mut collection.booths, booth);
            i = i +1;
        };
    }

    public(friend) fun mint_booth<T>(
        self: &TicketCollection<T>, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        ctx: &mut TxContext) {

        let sender = tx_context::sender(ctx);
        let collection_id = *object::uid_as_inner(&self.id);
        let nft = ticket_nft::mint_booth(name,  description, image_url, collection_id, ctx);
        transfer::public_transfer(nft, sender);
    
    }

    public(friend) fun mint_batch_sessions<T>(
        collection:&mut TicketCollection<T>,
        name: vector<u8>,
        description: vector<u8>,
        url: vector<u8>,
        total: u64,
        ctx: &mut TxContext
    ) {

        let i = 0; 
        let collection_id = *object::uid_as_inner(&collection.id);
        while (i < total){
            let session = ticket_nft::mint_session(name,  description, url, collection_id,  ctx);
            vector::push_back(&mut collection.sessions, session);
            i = i +1;
        };
    }


    public(friend) fun mint_session<T>(
        self: &TicketCollection<T>, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        ctx: &mut TxContext) {

        let sender = tx_context::sender(ctx);
        let collection_id = *object::uid_as_inner(&self.id);
        let nft = ticket_nft::mint_session(name,  description, image_url, collection_id, ctx);
        transfer::public_transfer(nft, sender);
    
    }


    /// Transfer `nft` to `recipient`
    public fun transfer_nft_ticket<T>(collection: &mut TicketCollection<T>,
        nft: &NFTTicket, recipient: address, _: &mut TxContext
    ) {
        let tickets = &collection.tickets;
        let (found, id) = vector::index_of(tickets, nft);
        if (found) {
            let nft = vector::remove<NFTTicket>(
                &mut collection.tickets, 
                id
            );
            transfer::public_transfer(nft, recipient);
        }

        else {
            abort ENFTNotExist
        }

    }



    public(friend) fun set_price_ticket<T>(self: &mut TicketCollection<T>, price: u64) {
        self.price = price;

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

    #[test_only] public fun init_for_testing(ctx: &mut TxContext) { init(TICKET_COLLECTION{}, ctx) }


    #[test]
    fun test_clients() {
        use sui::test_scenario::{Self, ctx};
        use sui::display::{Display};
        use std::type_name;
        use std::ascii;
        use std::debug;
        use sui_nft::ticket_nft::NFTTicket;
        use sui::package::{Self, Publisher};
        use sui::object::{Self, ID};
        use std::vector;
        let client = @0xABCD;
        let admin = @0xABCDEF;

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

        // test_scenario::next_tx(scenario, admin);
        // {
        //     // check Publisher
        //     let pub = test_scenario::take_from_sender<Publisher>(scenario);
        //     debug::print(&pub);
        //     let clients = test_scenario::take_from_sender<Clients>(scenario);
        //     debug::print(&clients);
        //     add_client<ID>(&mut clients,  client, &pub );
        //     test_scenario::return_to_sender(scenario, pub);
        //     test_scenario::return_to_sender(scenario, clients);
        // };

        test_scenario::next_tx(scenario, client); 
        {
            //let pub = test_scenario::take_from_sender<Publisher>(scenario);
            create<NFTTicket>(b"abc.xyz", b"Sui Hackathon",b"This is sui global hackathon", 0,  ctx(scenario) );
            //test_scenario::return_to_sender(scenario, pub);
        };

        test_scenario::next_tx(scenario, client); 
        {

            let collection = test_scenario::take_from_sender<TicketCollection<NFTTicket>>(scenario);
            debug::print(&utf8(b"tx_2: client create collection of event"));
            debug::print(&collection);
            test_scenario::return_to_sender(scenario, collection);
        };

        test_scenario::next_tx(scenario, client);
        {   
            // check Publisher
            //let pub = test_scenario::take_from_sender<Publisher>(scenario);

            let collection = test_scenario::take_from_sender<TicketCollection<NFTTicket>>(scenario);

            mint(&mut collection,  b"tester #1", b"I am tester!", b"abc.xyz" ,b"Standard", 0, ctx(scenario));


            //test_scenario::return_to_sender(scenario, pub);
            test_scenario::return_to_sender(scenario, collection);

            debug::print(&utf8(b"tx_3: nft minted to minter address."));
        };


        test_scenario::next_tx(scenario, client);

        {
            let collection = test_scenario::take_from_sender<TicketCollection<NFTTicket>>(scenario);


            mint_batch<NFTTicket>(&mut collection, b"This is sui", b"This is description", b"abc.xyz", b"Standard",3, ctx(scenario));

            test_scenario::return_to_sender(scenario, collection);
        };

        test_scenario::next_tx(scenario, client);
        {   
            let collection = test_scenario::take_from_sender<TicketCollection<NFTTicket>>(scenario);
            debug::print(&utf8(b"Collection created"));
            debug::print(&collection);
            // let ids = test_scenario::ids_for_sender<NFTTicket>(scenario);
            // debug::print(&utf8(b"Length of ids"));
            // debug::print(&ids);
            // let nft1: NFTTicket = test_scenario::take_from_sender_by_id(scenario, *vector::borrow(&ids, 0));
            // debug::print(&nft1);

            test_scenario::return_to_sender(scenario, collection);

            // let nft2: NFTTicket = test_scenario::take_from_sender_by_id(scenario, *vector::borrow(&ids, 1));
            // debug::print(&nft2);

            // test_scenario::return_to_sender(scenario, nft2);
            
            // let nft3: NFTTicket = test_scenario::take_from_sender_by_id(scenario, *vector::borrow(&ids, 2));
            // debug::print(&nft3);

            // test_scenario::return_to_sender(scenario, nft3);

            // let nft4: NFTTicket = test_scenario::take_from_sender_by_id(scenario, *vector::borrow(&ids, 3));
            // debug::print(&nft4);

            // test_scenario::return_to_sender(scenario, nft4);
            debug::print(&utf8(b"check nft is created"));
        };

        // end test
        test_scenario::end(scenario_val);
    }


}