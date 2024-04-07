#[test_only]
module sui_nft::sui_nft_tests {
    use sui_nft::sui_nft::{Self, NFT};
    use sui::test_scenario::{Self, next_tx, ctx};
    use sui::transfer;
    use std::string;
    #[test]
    fun mint_transfer_works() {
        let publisher = @0xA;
        let client = @0xB;
        let user = @0xC;

        // create the NFT
        let scenario_val = test_scenario::begin(publisher);

        let scenario = &mut scenario_val;
        next_tx(scenario, publisher); 
        {
            sui_nft::init_for_testing(ctx(scenario))
        };

        next_tx(scenario, client);
        {
            
            sui_nft::mint_to_sender(b"test", b"a test", b"https://www.sui.io", test_scenario::ctx(scenario));
            
        };

        // send it from client to user
        next_tx(scenario, client);
        {
            
            let nft = test_scenario::take_from_sender<NFT>(scenario);
            //transfer::transfer(nft, user);
            sui_nft::transfer(nft, user,test_scenario::ctx(scenario));
        };
        
        test_scenario::end(scenario_val); 
    }


}

