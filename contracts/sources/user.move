module sui_nft::user {

    use sui::tx_context::{TxContext, Self};
    use sui_nft::ticket_nft::{Self as ticket, NFTTicket};

    entry fun claim(
        nft: NFTTicket, 
        ctx: &mut TxContext
    ) {
        let sender = tx_context::sender(ctx);
        ticket::transfer_nft_ticket(nft, sender, ctx);

        
    }

}