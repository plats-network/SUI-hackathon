use anchor_lang::prelude::*;

pub mod mint;
pub mod sell;
pub mod claim;
mod constants;

use mint::*;
use sell::*;
use claim::*;


declare_id!("CYKRXdd4hnrKkA2HpdJAMcmeUauYXDJ4D5hqrnXUsFDg");


#[program]
pub mod nft {
    use super::*;

    pub fn mint(
        ctx: Context<MintNft>, 
        metadata_title: String, 
        metadata_symbol: String, 
        metadata_uri: String,
    ) -> Result<()> {
        mint::mint(
            ctx,
            metadata_title,
            metadata_symbol,
            metadata_uri,
        )
    }

    pub fn sell(
        ctx: Context<SellNft>,
    ) -> Result<()> {
        sell::sell(
            ctx,
        )
    }

    pub fn claim(
        ctx: Context<ClaimNft>
    ) -> Result<()> {
        claim::claim(ctx)
    }
}