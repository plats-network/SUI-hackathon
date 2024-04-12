module sui_nft::user {

    use sui::tx_context::{TxContext, Self};
    use sui_nft::ticket_nft::{Self as ticket, NFTTicket};
    use sui_nft::ticket_collection::{Self as collection, TicketCollection};

    entry fun claim<T>(
        collection: &mut TicketCollection<T>,
        nft: &NFTTicket, 
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        collection::transfer_nft_ticket(collection, nft, sender, ctx);

        
    }

}