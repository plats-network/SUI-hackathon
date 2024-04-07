module sui_nft::utils {
    use std::vector as vec;

    public fun is_in_list<T>(list: &vector<T>, addr: &T): bool {
        let (in_list, _i) = vec::index_of(list, addr);
        in_list
    }
}
