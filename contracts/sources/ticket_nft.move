module sui_nft::ticket_nft {
    use std::string;
    use sui::object::{Self, ID, UID};
    use sui::event;
    use sui::transfer;
    use sui::tx_context::{Self, TxContext};
    friend sui_nft::user;

    struct NFTTicket has key, store {
        id: UID,
        /// Name for the token
        name: string::String,
        /// Description of the token
        description: string::String,
        /// URL for the token
        image_url: string::String,
        /// Collection ID
        collection_id: ID,
        //claimed: bool,
        catogory: string::String,

    }


    // ===== Events =====

    struct NFTMinted has copy, drop {
        // The Object ID of the NFT
        object_id: ID,
        // The creator of the NFT
        creator: address,
        creator_id: ID 
    }

    // ===== Entrypoints =====

    public fun mint_to_sender(
        name: vector<u8>,
        description: vector<u8>,
        image_url: vector<u8>,
        collection_id: ID,
        catogory: vector<u8>,
        ctx: &mut TxContext
    ): NFTTicket {
        let nft = NFTTicket {
            id: object::new(ctx),
            name: string::utf8(name),
            description: string::utf8(description),
            image_url: string::utf8(image_url),
            collection_id,
            catogory: string::utf8(catogory),
            //claimed: false,
        };


        let nft_id = object::uid_to_bytes(&nft.id);
        let recipient = tx_context::sender(ctx);
        let recipient_id = object::id_from_address(*&recipient);
        event::emit(NFTMinted {
            object_id: object::id_from_bytes(*&nft_id), 
            creator: recipient,
            creator_id: recipient_id
        });
        //transfer::public_transfer(nft, recipient)
        //nft_id
        nft
    }

    /// Transfer `nft` to `recipient`
    public(friend) fun transfer_nft_ticket(
        nft: NFTTicket, recipient: address, _: &mut TxContext
    ) {
        transfer::public_transfer(nft, recipient)
    }



}



