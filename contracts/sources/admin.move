module sui_nft::admin {
    use std::vector as vec;

    use sui::tx_context::TxContext;
    use sui::package::Publisher;
    use sui_nft::ticket_collection::{Self as collection, TicketCollection, Clients};


    entry fun add_client<T>(self: &mut Clients, addr: address, pub: &Publisher) {
        collection::add_client<T>(self, addr, pub);
    }

    // batch processes
    entry fun batch_add_client<T>(self: &mut Clients, addrs: vector<address>, pub: &Publisher) {
        let (i, len) = (0u64, vec::length(&addrs));
        while (i < len) {
            let addr = vec::pop_back(&mut addrs);
            collection::add_client<T>(self, addr, pub);
            i = i + 1;
        }
    }

    entry fun batch_remove_client<T>(self: &mut Clients, addrs:vector<address>, pub: &Publisher) {
        let (i, len) = (0u64, vec::length(&addrs));
        while (i < len) {
            let addr = vec::pop_back(&mut addrs);
            collection::remove_client<T>(self, addr, pub);
        }
    }
}