module sui_nft::ticket_collection {
    use std::string::{Self, utf8, String};
    use sui::object::{Self, ID, UID};
    use std::option::{Self, Option};
    use sui::event;
    use sui::transfer;
    use sui::tx_context::{Self, TxContext};
    use sui::url::{Self, Url};
    use sui::package::{Self, Publisher};
    use std::vector::{Self};
    use sui_nft::utils::{Self, is_in_list};
    use sui_nft::ticket_nft::NFTTicket;
    use sui::display;
    use sui::table::{Self, Table};

    use std::ascii;
    use std::type_name;

    friend sui_nft::client;

    struct TicketCollection<phantom T> has key {
        id: UID,
        name: String,
        description: String,
        url: Option<Url>,
    }

    // Error code
    const ENotOwner: u64 = 0;



    // OTW
    struct TICKET_COLLECTION has drop {}


    fun init(otw: TICKET_COLLECTION, ctx:&mut TxContext) {
        // 1. Create a publisher (OTW required). This grants root user privileges.
        let sender = tx_context::sender(ctx);
        let publisher: Publisher = package::claim(otw, ctx);

        // 2. Create collection object (shared object)
        // Create genesis collection
        create_and_share<NFTTicket>(
            &publisher,
            b"https://npiece.xyz/nfts/",
            ctx
        );

        // 3. Send publisher to sender
        transfer::public_transfer(publisher, sender);
    }

    // Provide accessibility to client for creating new collection 
    public(friend) fun create_and_share<T: key>(
        pub: &Publisher,
        image_url: vector<u8>,
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        let collection = create<T>(pub, ctx);
        set_image_url(&mut collection, image_url, pub);

        let name_field = collection.name;
        string::append_utf8(&mut name_field, b": {name}");

        let dp = display::new<T>(pub, ctx);
        display::add(&mut dp, utf8(b"name"), name_field);
        display::add(&mut dp, utf8(b"description"), utf8(b"{description}"));
        display::add(&mut dp, utf8(b"image_url"), utf8(b"{url}"));
        display::update_version(&mut dp);

        transfer::share_object(collection);
        transfer::public_transfer(dp, sender);

    }

    public(friend) fun set_image_url<T>(self: &mut TicketCollection<T>, image_url: vector<u8>, pub: &Publisher) {
        assert_authority<T>(pub);
        self.url = option::some(url::new_unsafe_from_bytes(image_url));
    }

    // HELPER FUNCTION
    // ANYONE CAN CALL
    fun create<T>(pub: &Publisher, ctx: &mut TxContext): TicketCollection<T> {
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
        }
    }

    public(friend) fun assert_authority<T>(pub: &Publisher) {
        assert!(package::from_package<T>(pub), ENotOwner);
    }


}