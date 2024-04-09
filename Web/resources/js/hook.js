import { useMemo } from 'react';

import { AnchorProvider, setProvider } from '@project-serum/anchor';
import { useAnchorWallet, useConnection } from '@solana/wallet-adapter-react';

export function useAnchorProvider() {
    const wallet = useAnchorWallet();
    const { connection } = useConnection();
    return useMemo(() => {
      if (!wallet) return undefined;
      const anchorProvider = new AnchorProvider(connection, wallet, { commitment: 'confirmed' });
      setProvider(anchorProvider);
      return anchorProvider;
    }, [connection, wallet]);
  }