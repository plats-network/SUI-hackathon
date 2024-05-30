# NFT Ticket 


## Requirements For Initial Setup
- Install [NodeJS](https://nodejs.org/en/)
- Install [SUI CLI](https://docs.sui.io/guides/developer/getting-started/sui-install) 


## Setting Up
### 1. Clone/Download the contracts

### 2. Build move contract:
```bash
    sui move build
```

### 2. Run tests move contract:
```bash
    sui move test
```

### 3.Deploy NFT contract
Before running any scripts, you'll want to create a .env file with the following values (see .env.example):

+ Go to `scripts` folder
+ Populate .env file  with `MNEMONIC_PUBLISHER`
+ Install dependencies:
```bash
    npm install
```

+ Run the script to publish:
```bash
    node publish.js
```

### 4.Mint NFT ticket

+ Go to `scripts` folder
+ Populate .env file  with `MNEMONIC_PUBLISHER`, `PACKAGE_ID` ( **take from step 3.**), `MNEMONIC_CLIENT`

+ Install dependencies:
```bash
    npm install
```

+ Run the script to publish:
```bash
    node mint.js
```
