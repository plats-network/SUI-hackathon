
import React, { useMemo } from 'react';
import { createRoot } from "react-dom/client";
import { SolanaProvider } from './provider.jsx';


export default function RootLayout({
                                       children,
                                   }) {
    return (
        <SolanaProvider children={undefined}>{children}</SolanaProvider>
    );
}


