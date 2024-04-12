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
    use sui_nft::ticket_nft::{Self, NFTTicket};
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

    // Provide accessibility to client for creating new collection 
    public(friend) fun create<T: key>(
        pub: &Publisher,
        url: vector<u8>,
        name: vector<u8>,
        description: vector<u8>,
        price: u64,
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        let collection = create_<T>(pub, ctx);
        set_url(&mut collection, url, pub);
        set_name(&mut collection, name, pub);
        set_description(&mut collection, description, pub);
        set_price(&mut collection, price, pub);

        let name_field = collection.name;
        string::append_utf8(&mut name_field, b": {name}");

        let dp = display::new<T>(pub, ctx);
        display::add(&mut dp, utf8(b"name"), name_field);
        display::add(&mut dp, utf8(b"description"), utf8(b"{description}"));
        display::add(&mut dp, utf8(b"url"), utf8(b"{url}"));
        display::update_version(&mut dp);

        transfer::transfer(collection, sender);
        transfer::public_transfer(dp, sender);

    }

    public(friend) fun set_url<T>(self: &mut TicketCollection<T>, url: vector<u8>, pub: &Publisher) {
        assert_authority<T>(pub);
        self.url = option::some(url::new_unsafe_from_bytes(url));
    }

    public(friend) fun set_name<T>(self: &mut TicketCollection<T>, name: vector<u8>, pub: &Publisher) {
        assert_authority<T>(pub);
        self.name = string::utf8(name);
    }

    public(friend) fun set_description<T>(self: &mut TicketCollection<T>, description: vector<u8>, pub: &Publisher) {
        assert_authority<T>(pub);
        self.description = string::utf8(description);
    }

    public(friend) fun set_price<T>(self: &mut TicketCollection<T>, price: u64, pub: &Publisher) {
        assert_authority<T>(pub);
        self.price = price;
    }

    // HELPER FUNCTION
    // ANYONE CAN CALL
    fun create_<T>(pub: &Publisher,  ctx: &mut TxContext): TicketCollection<T> {
        assert_authority<T>(pub);
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
            balance: balance::zero()
        }
    }

    public(friend) fun mint<T>(
        pub: &Publisher,
        self: &TicketCollection<T>, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        catogory: vector<u8>,
        token_id: u64,
        ctx: &mut TxContext) {

        assert_authority<T>(pub);
        let sender = tx_context::sender(ctx);
        let collection_id = *object::uid_as_inner(&self.id);
        let nft = ticket_nft::mint_to_sender(name,  description, image_url, collection_id, catogory, token_id,  ctx);
        transfer::public_transfer(nft, sender);
        //nft
    
    }

    public(friend) fun mint_booth<T>(
        pub: &Publisher,
        self: &TicketCollection<T>, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        ctx: &mut TxContext) {

        assert_authority<T>(pub);
        let sender = tx_context::sender(ctx);
        let collection_id = *object::uid_as_inner(&self.id);
        let nft = ticket_nft::mint_booth(name,  description, image_url, collection_id, ctx);
        transfer::public_transfer(nft, sender);
    
    }

    public(friend) fun mint_session<T>(
        pub: &Publisher,
        self: &TicketCollection<T>, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        ctx: &mut TxContext) {

        assert_authority<T>(pub);
        let sender = tx_context::sender(ctx);
        let collection_id = *object::uid_as_inner(&self.id);
        let nft = ticket_nft::mint_session(name,  description, image_url, collection_id, ctx);
        transfer::public_transfer(nft, sender);
    
    }


    public(friend) fun set_price_ticket<T>(self: &mut TicketCollection<T>, price: u64, pub: &Publisher) {
        assert_authority<T>(pub);
        self.price = price;

    }

    public(friend) fun assert_authority<T>(pub: &Publisher) {
        assert!(package::from_package<T>(pub), ENotOwner);
    }

    public(friend) fun assert_client<T>(self: &Clients, client: address) {
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


        let scenario_val = test_scenario::begin(client);
        let scenario = &mut scenario_val;
        {
            init_for_testing(ctx(scenario));
        };

        test_scenario::next_tx(scenario, client);
        {
            // check Publisher
            let pub = test_scenario::take_from_sender<Publisher>(scenario);
            let pub_tn = type_name::get<Publisher>();
            
            let pub_module = package::published_module(&pub);
            debug::print(&pub_tn);
            assert!(pub_module == &ascii::string(b"ticket_collection"), 0);
            test_scenario::return_to_sender(scenario, pub);
        };


        test_scenario::next_tx(scenario, client); 
        {
            let pub = test_scenario::take_from_sender<Publisher>(scenario);
            create<NFTTicket>(&pub, b"abc.xyz", b"Sui Hackathon",b"This is sui global hackathon", 0,  ctx(scenario) );
            test_scenario::return_to_sender(scenario, pub);
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
            let pub = test_scenario::take_from_sender<Publisher>(scenario);

            let collection = test_scenario::take_from_sender<TicketCollection<NFTTicket>>(scenario);

            mint(&pub, &collection,  b"tester #1", b"I am tester!", b"abc.xyz" ,b"Standard", 0, ctx(scenario));


            test_scenario::return_to_sender(scenario, pub);
            test_scenario::return_to_sender(scenario, collection);

            debug::print(&utf8(b"tx_3: nft minted to minter address."));
        };

        test_scenario::next_tx(scenario, client);
        {   
            // check Publisher
            let pub = test_scenario::take_from_sender<Publisher>(scenario);

            let collection = test_scenario::take_from_sender<TicketCollection<NFTTicket>>(scenario);

            mint(&pub, &collection,  b"tester #2", b"I am tester!", b"abc.xyz" ,b"VIP", 1, ctx(scenario));


            test_scenario::return_to_sender(scenario, pub);
            test_scenario::return_to_sender(scenario, collection);

            debug::print(&utf8(b"tx_4: nft minted to minter address."));
        };

        test_scenario::next_tx(scenario, client);
        {   

            let ids = test_scenario::ids_for_sender<NFTTicket>(scenario);
            debug::print(&utf8(b"Length of ids"));
            debug::print(&ids);
            let nft1: NFTTicket = test_scenario::take_from_sender_by_id(scenario, *vector::borrow(&ids, 0));
            debug::print(&nft1);

            test_scenario::return_to_sender(scenario, nft1);

            let nft2: NFTTicket = test_scenario::take_from_sender_by_id(scenario, *vector::borrow(&ids, 1));
            debug::print(&nft2);

            test_scenario::return_to_sender(scenario, nft2);
            

            debug::print(&utf8(b"check nft is created"));
        };


        // end test
        test_scenario::end(scenario_val);
    }


}