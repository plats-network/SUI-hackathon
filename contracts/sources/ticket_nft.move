module sui_nft::ticket_nft {
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



    struct NFTTicket has key, store {
        id: UID,
        /// Name for the token
        name: string::String,
        /// Description of the token
        description: string::String,
        /// URL for the token
        image_url: string::String,
        /// Collection ID
        collection_id: ID 
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


    // ===== Public view functions =====

    /// Get the NFT's `name`
    public fun name(nft: &NFTTicket): &string::String {
        &nft.name
    }

    /// Get the NFT's `description`
    public fun description(nft: &NFTTicket): &string::String {
        &nft.description
    }

    /// Get the NFT's `image_url`
    public fun image_url(nft: &NFTTicket): &string::String{
        &nft.image_url
    }


    // ===== Entrypoints =====

    /// Create a new devnet_nft
    public entry fun mint_to_sender(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        collection_id: ID,
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        let nft = NFTTicket {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url),
            collection_id,
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
        nft: NFTTicket, recipient: address, _: &mut TxContext
    ) {
        transfer::public_transfer(nft, recipient)
    }

    public entry fun update_description(
        nft: &mut NFTTicket,
        new_description: vector<u8>,
        _: &mut TxContext
    ) {
        nft.description = string::utf8(new_description)
    }
    #[test_only] public fun init_for_testing(ctx: &mut TxContext) { init(SUI_NFT{}, ctx) }


}



