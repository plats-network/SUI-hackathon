module sui_nft::sui_nft {
    // use sui::url::{Self, Url};
    use std::string::{utf8};
    use std::string;
    use sui::object::{Self, ID, UID};
    use sui::event;
    use sui::transfer;
    use sui::tx_context::{Self, TxContext};
    // The creator bundle: these two packages often go together.
    use sui::package;
    use sui::display;



    struct NFT has key, store {
        id: UID,
        /// Name for the token
        name: string::String,
        /// Description of the token
        description: string::String,
        /// URL for the token
        image_url: string::String,
        // TODO: allow custom attributes
    }

    // ===== Events =====

    struct NFTMinted has copy, drop {
        // The Object ID of the NFT
        object_id: ID,
        // The creator of the NFT
        creator: address,
        // The name of the NFT
        name: string::String,
    }


    struct SUI_NFT has drop {}



    fun init(otw: SUI_NFT,ctx: &mut TxContext) {
        let keys = vector[utf8(b"name"), utf8(b"description"), utf8(b"image_url")];
        let values = vector[utf8(b"{name}"), utf8(b"{description}"), utf8(b"{image_url}")];
        // Claim the `Publisher` for the package!
        let publisher = package::claim(otw, ctx);

        // Get a new `Display` object for the `Hero` type.
        let display = display::new_with_fields<NFT>(
            &publisher, keys, values, ctx
        );

        // Commit first version of `Display` to apply changes.
        display::update_version(&mut display);

        transfer::public_transfer(publisher, tx_context::sender(ctx));
        transfer::public_transfer(display, tx_context::sender(ctx));
    }



    // ===== Public view functions =====

    /// Get the NFT's `name`
    public fun name(nft: &NFT): &string::String {
        &nft.name
    }

    /// Get the NFT's `description`
    public fun description(nft: &NFT): &string::String {
        &nft.description
    }

    /// Get the NFT's `image_url`
    public fun image_url(nft: &NFT): &string::String{
        &nft.image_url
    }


    // ===== Entrypoints =====

    /// Create a new devnet_nft
    public entry fun mint_to_sender(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        let nft = NFT {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url)
        };

        event::emit(NFTMinted {
            object_id: object::id(&nft),
            creator: sender,
            name: nft.name,
        });

        transfer::public_transfer(nft, sender);
    }

    /// Transfer `nft` to `recipient`
    public entry fun transfer(
        nft: NFT, recipient: address, _: &mut TxContext
    ) {
        transfer::public_transfer(nft, recipient)
    }

    public entry fun update_description(
        nft: &mut NFT,
        new_description: vector<u8>,
        _: &mut TxContext
    ) {
        nft.description = string::utf8(new_description)
    }
    #[test_only] public fun init_for_testing(ctx: &mut TxContext) { init(SUI_NFT{}, ctx) }


}

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

