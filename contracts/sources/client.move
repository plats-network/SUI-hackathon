module sui_nft::client {


    use sui::tx_context::{TxContext, Self};
    use sui::transfer;
    use sui::package::Publisher;
    
    use sui_nft::ticket_collection::{Self as collection, TicketCollection};
    // ===== Collection =====
    entry fun create_new_collection<T: key>(
        pub: &Publisher, 
        url: vector<u8>,
        ctx: &mut TxContext
    ) {

        collection::create<T>(pub, url, ctx);

    }

    entry fun mint<T>(
        pub: &Publisher, 
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        collection: &TicketCollection<T>,
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        let nft = collection::mint(pub, collection, name, description, image_url, ctx);
        transfer::public_transfer(nft, sender);
    }

    entry fun set_url<T>(self: &mut TicketCollection<T>, url: vector<u8>, pub: &Publisher) {
        collection::set_url(self, url, pub);
    }


    #[test]
    fun test_collection() {
        use sui::test_scenario::{Self, ctx};
        use sui::display::{Display};
        use std::type_name;
        use std::ascii;
        use std::debug;
        use sui_nft::ticket_nft::NFTTicket;
        use sui_nft::ticket_collection::init_for_testing;
        use sui::package::{Self, Publisher};
        let client = @0xABCD;

        // first transaction to emulate module initialization
        let scenario_val = test_scenario::begin(client);
        let scenario = &mut scenario_val;
        {
            init_for_testing(ctx(scenario));
        };

        // second transcation to check objects that admin received during first transaction
        test_scenario::next_tx(scenario, client);
        {
            // check Publisher
            let pub = test_scenario::take_from_sender<Publisher>(scenario);
            let pub_tn = type_name::get<Publisher>();
            
            let pub_module = package::published_module(&pub);
            debug::print(&pub_tn);
            assert!(pub_module == &ascii::string(b"ticket_collection"), 0);
            test_scenario::return_to_sender(scenario, pub);

            // take Display and print object
            let dp = test_scenario::take_from_sender<Display<NFTTicket>>(scenario);
            debug::print(&dp);
            test_scenario::return_to_sender(scenario, dp);
        };

        // end test
        test_scenario::end(scenario_val);
    }

    #[test]
    fun test_mint() {
        use sui::test_scenario::{Self, ctx};
        use std::debug;
        use std::string::{utf8};
        use sui_nft::ticket_collection::init_for_testing;
        use sui_nft::ticket_nft::NFTTicket;
        // create test addresses representing users
        let admin = @0xABCD;
        //let minter = @0xACEF;

        // first transaction to emulate module initialization
        let scenario_val = test_scenario::begin(admin);
        let scenario = &mut scenario_val;
        {
            init_for_testing(ctx(scenario));
            debug::print(&utf8(b"tx_1: init module collection."));
        };

        // second transaction to emulate mint
        test_scenario::next_tx(scenario, admin);
        {   
            // check Publisher
            let pub = test_scenario::take_from_sender<Publisher>(scenario);

            let collection = test_scenario::take_from_sender<TicketCollection<NFTTicket>>(scenario);
            // mint function consume collection, so no need to return to shared
            mint(&pub, b"tester #1", b"I am tester!", b"abc.xyz", &collection, ctx(scenario));
            test_scenario::return_to_sender(scenario, pub);
            test_scenario::return_to_sender(scenario, collection);
            debug::print(&utf8(b"tx_3: nft minted to minter address."));
        };
        // end test
        test_scenario::end(scenario_val);
    }

}

