import{r as m,j as o}from"./jsx-runtime-f0a4e50a.js";import"./client-888b02c5.js";import{R as u}from"./index-f0e4c749.js";import{d as B,R as x,O as b}from"./session-103011d7.js";/* empty css              */import{T as v}from"./TransactionBlock-3fb0dd4a.js";import"./bn-a0a0c8e1.js";import"./legacy-registry-4fcefa09.js";import"./index-ea9ea726.js";import"./nacl-fast-855db264.js";import"./_commonjs-dynamic-modules-302442b1.js";import"./index-54e210ce.js";import"./util-fc318f65.js";import"./hash-046412e8.js";import"./blake2b-0b963f07.js";import"./_md-5eb5ac83.js";import"./secp256k1-a06cb75b.js";function k(){const c=B(),[a,p]=m.useState([]),h=async()=>{const s=[];$(".itemBoothDetailMint").each(function(t){const e=$(this).find(".name_booth").val(),i=$(this).find(".description_booth").val(),l=$(this).find(".image-file").attr("link-img")??"https://xc6fbqjny4wfkgukliockypoutzhcqwjmlw2gigombpp2ynufaxa.arweave.net/uLxQwS3HLFUailocJWHupPJxQsli7aMgzmBe_WG0KC4",r={nameBooth:e,descriptionBooth:i,fileBooth:l};s.push(r)}),p(s)},d=async s=>{console.log("details",s);let t="";s.forEach(e=>{t+=`<div class="row mb-3">
`,t+=`    <div class="col-4">
`,t+=`        <label for="image-file">
`,t+='            <img class="img-preview img-preview-nft" src="'+e.fileBooth+`">
`,t+=`        </label>
`,t+=`    </div>
`,t+=`    <div class="col-6">
`,t+=`        <div class="col-10 mt-25">
`,t+='            <p class="class-ticket">'+e.nameBooth+`</p>
`,t+=`        </div>
`,t+=`        <div class="col-10 mt-20">
`,t+='            <p class="class-ticket">'+e.descriptionBooth+`</p>
`,t+=`        </div>
`,t+=`    </div>
`,t+=`    <div class="col-2" style="margin-top: 50px">
`,t+='        <p class="class-ticket"><a target="_blank" href="https://suiscan.xyz/testnet/tx/'+e.txhash+`">txhash</a></p>
`,t+=`    </div>
`,t+="</div>"}),$(".append-nft-booth-detail").empty().append(t)},f=async(s,t)=>{console.log(t);let e={nameBooth:t.map(n=>n.nameBooth),descriptionBooth:t.map(n=>n.descriptionBooth),fileBooth:t.map(n=>n.fileBooth)};const i=new v;let l=$('meta[name="package_id"]').attr("content"),r=$('meta[name="collection_id"]').attr("content");i.moveCall({target:`${l}::client::mint_batch_booths`,arguments:[i.pure(e.nameBooth),i.pure(e.descriptionBooth),i.pure(e.fileBooth),i.object(r)],typeArguments:[`${l}::ticket_collection::NFTTicket`]}),$(".loading").show();try{const n=await s.signAndExecuteTransactionBlock({transactionBlock:i});if(console.log("signAndExecuteTransactionBlock",n),!n.confirmedLocalExecution){alert("nft minted Booth fail!");return}t.forEach(g=>{g.txhash=n.digest}),d(t),$(".loading").hide(),alert("nft minted Booth successfully!")}catch{$(".loading").hide(),alert("nft minted Booth fails!")}};return m.useEffect(()=>{a.length>0&&(f(c,a),console.log(c),console.log(a))},[a]),o.jsxs("div",{className:"App",children:[o.jsx(x,{label:"Connect Wallet"}),o.jsx("section",{children:c.status==="connected"&&o.jsx(o.Fragment,{children:(c==null?void 0:c.account)&&o.jsx(o.Fragment,{children:o.jsx("p",{children:o.jsx("button",{id:"btnGenItemBooth",onClick:h,type:"button",class:"btn btn-primary btn-rounded waves-effect waves-light mb-2  mt-2 me-2",children:"Generate Booth"})})})})})]})}u.createRoot(document.getElementById("btnGenItemBooth")).render(o.jsx(b,{children:o.jsx(k,{})}));
