import{a as S,n as H,b as E,e as I,t as g,i as L,c as D,o as U,d as w,f as z,u as y}from"./_md-5eb5ac83.js";const C=new Uint8Array([0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,14,10,4,8,9,15,13,6,1,12,0,2,11,7,5,3,11,8,12,0,5,2,15,13,10,14,3,6,7,1,9,4,7,9,3,1,13,12,11,14,2,6,5,10,4,0,15,8,9,0,5,7,2,4,10,15,14,1,11,12,6,8,3,13,2,12,6,10,0,11,8,3,4,13,7,5,15,14,1,9,12,5,1,15,14,13,4,10,0,7,6,3,9,2,8,11,13,11,7,14,12,1,3,9,5,0,15,4,8,6,2,10,6,15,14,9,11,3,0,8,12,2,13,7,1,4,10,5,10,2,8,4,7,6,1,5,15,11,9,14,3,12,13,0,0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,14,10,4,8,9,15,13,6,1,12,0,2,11,7,5,3]);class X extends S{constructor(h,s,i={},r,v,e){if(super(),this.blockLen=h,this.outputLen=s,this.length=0,this.pos=0,this.finished=!1,this.destroyed=!1,H(h),H(s),H(r),s<0||s>r)throw new Error("outputLen bigger than keyLen");if(i.key!==void 0&&(i.key.length<1||i.key.length>r))throw new Error(`key must be up 1..${r} byte long or undefined`);if(i.salt!==void 0&&i.salt.length!==v)throw new Error(`salt must be ${v} byte long or undefined`);if(i.personalization!==void 0&&i.personalization.length!==e)throw new Error(`personalization must be ${e} byte long or undefined`);this.buffer32=E(this.buffer=new Uint8Array(h))}update(h){I(this);const{blockLen:s,buffer:i,buffer32:r}=this;h=g(h);const v=h.length,e=h.byteOffset,n=h.buffer;for(let l=0;l<v;){this.pos===s&&(L||D(r),this.compress(r,0,!1),L||D(r),this.pos=0);const a=Math.min(s-this.pos,v-l),f=e+l;if(a===s&&!(f%4)&&l+a<v){const o=new Uint32Array(n,f,Math.floor((v-l)/4));L||D(o);for(let b=0;l+s<v;b+=r.length,l+=s)this.length+=s,this.compress(o,b,!1);L||D(o);continue}i.set(h.subarray(l,l+a),this.pos),this.pos+=a,this.length+=a,l+=a}return this}digestInto(h){I(this),U(h,this);const{pos:s,buffer32:i}=this;this.finished=!0,this.buffer.subarray(s).fill(0),L||D(i),this.compress(i,0,!0),L||D(i);const r=E(h);this.get().forEach((v,e)=>r[e]=w(v))}digest(){const{buffer:h,outputLen:s}=this;this.digestInto(h);const i=h.slice(0,s);return this.destroy(),i}_cloneInto(h){const{buffer:s,length:i,finished:r,destroyed:v,outputLen:e,pos:n}=this;return h||(h=new this.constructor({dkLen:e})),h.set(...this.get()),h.length=i,h.finished=r,h.destroyed=v,h.outputLen=e,h.buffer.set(s),h.pos=n,h}}const c=new Uint32Array([4089235720,1779033703,2227873595,3144134277,4271175723,1013904242,1595750129,2773480762,2917565137,1359893119,725511199,2600822924,4215389547,528734635,327033209,1541459225]),t=new Uint32Array(32);function k(B,h,s,i,r,v){const e=r[v],n=r[v+1];let l=t[2*B],a=t[2*B+1],f=t[2*h],o=t[2*h+1],b=t[2*s],p=t[2*s+1],u=t[2*i],d=t[2*i+1],x=y.add3L(l,f,e);a=y.add3H(x,a,o,n),l=x|0,{Dh:d,Dl:u}={Dh:d^a,Dl:u^l},{Dh:d,Dl:u}={Dh:y.rotr32H(d,u),Dl:y.rotr32L(d,u)},{h:p,l:b}=y.add(p,b,d,u),{Bh:o,Bl:f}={Bh:o^p,Bl:f^b},{Bh:o,Bl:f}={Bh:y.rotrSH(o,f,24),Bl:y.rotrSL(o,f,24)},t[2*B]=l,t[2*B+1]=a,t[2*h]=f,t[2*h+1]=o,t[2*s]=b,t[2*s+1]=p,t[2*i]=u,t[2*i+1]=d}function A(B,h,s,i,r,v){const e=r[v],n=r[v+1];let l=t[2*B],a=t[2*B+1],f=t[2*h],o=t[2*h+1],b=t[2*s],p=t[2*s+1],u=t[2*i],d=t[2*i+1],x=y.add3L(l,f,e);a=y.add3H(x,a,o,n),l=x|0,{Dh:d,Dl:u}={Dh:d^a,Dl:u^l},{Dh:d,Dl:u}={Dh:y.rotrSH(d,u,16),Dl:y.rotrSL(d,u,16)},{h:p,l:b}=y.add(p,b,d,u),{Bh:o,Bl:f}={Bh:o^p,Bl:f^b},{Bh:o,Bl:f}={Bh:y.rotrBH(o,f,63),Bl:y.rotrBL(o,f,63)},t[2*B]=l,t[2*B+1]=a,t[2*h]=f,t[2*h+1]=o,t[2*s]=b,t[2*s+1]=p,t[2*i]=u,t[2*i+1]=d}class G extends X{constructor(h={}){super(128,h.dkLen===void 0?64:h.dkLen,h,64,16,16),this.v0l=c[0]|0,this.v0h=c[1]|0,this.v1l=c[2]|0,this.v1h=c[3]|0,this.v2l=c[4]|0,this.v2h=c[5]|0,this.v3l=c[6]|0,this.v3h=c[7]|0,this.v4l=c[8]|0,this.v4h=c[9]|0,this.v5l=c[10]|0,this.v5h=c[11]|0,this.v6l=c[12]|0,this.v6h=c[13]|0,this.v7l=c[14]|0,this.v7h=c[15]|0;const s=h.key?h.key.length:0;if(this.v0l^=this.outputLen|s<<8|65536|1<<24,h.salt){const i=E(g(h.salt));this.v4l^=w(i[0]),this.v4h^=w(i[1]),this.v5l^=w(i[2]),this.v5h^=w(i[3])}if(h.personalization){const i=E(g(h.personalization));this.v6l^=w(i[0]),this.v6h^=w(i[1]),this.v7l^=w(i[2]),this.v7h^=w(i[3])}if(h.key){const i=new Uint8Array(this.blockLen);i.set(g(h.key)),this.update(i)}}get(){let{v0l:h,v0h:s,v1l:i,v1h:r,v2l:v,v2h:e,v3l:n,v3h:l,v4l:a,v4h:f,v5l:o,v5h:b,v6l:p,v6h:u,v7l:d,v7h:x}=this;return[h,s,i,r,v,e,n,l,a,f,o,b,p,u,d,x]}set(h,s,i,r,v,e,n,l,a,f,o,b,p,u,d,x){this.v0l=h|0,this.v0h=s|0,this.v1l=i|0,this.v1h=r|0,this.v2l=v|0,this.v2h=e|0,this.v3l=n|0,this.v3h=l|0,this.v4l=a|0,this.v4h=f|0,this.v5l=o|0,this.v5h=b|0,this.v6l=p|0,this.v6h=u|0,this.v7l=d|0,this.v7h=x|0}compress(h,s,i){this.get().forEach((l,a)=>t[a]=l),t.set(c,16);let{h:r,l:v}=y.fromBig(BigInt(this.length));t[24]=c[8]^v,t[25]=c[9]^r,i&&(t[28]=~t[28],t[29]=~t[29]);let e=0;const n=C;for(let l=0;l<12;l++)k(0,4,8,12,h,s+2*n[e++]),A(0,4,8,12,h,s+2*n[e++]),k(1,5,9,13,h,s+2*n[e++]),A(1,5,9,13,h,s+2*n[e++]),k(2,6,10,14,h,s+2*n[e++]),A(2,6,10,14,h,s+2*n[e++]),k(3,7,11,15,h,s+2*n[e++]),A(3,7,11,15,h,s+2*n[e++]),k(0,5,10,15,h,s+2*n[e++]),A(0,5,10,15,h,s+2*n[e++]),k(1,6,11,12,h,s+2*n[e++]),A(1,6,11,12,h,s+2*n[e++]),k(2,7,8,13,h,s+2*n[e++]),A(2,7,8,13,h,s+2*n[e++]),k(3,4,9,14,h,s+2*n[e++]),A(3,4,9,14,h,s+2*n[e++]);this.v0l^=t[0]^t[16],this.v0h^=t[1]^t[17],this.v1l^=t[2]^t[18],this.v1h^=t[3]^t[19],this.v2l^=t[4]^t[20],this.v2h^=t[5]^t[21],this.v3l^=t[6]^t[22],this.v3h^=t[7]^t[23],this.v4l^=t[8]^t[24],this.v4h^=t[9]^t[25],this.v5l^=t[10]^t[26],this.v5h^=t[11]^t[27],this.v6l^=t[12]^t[28],this.v6h^=t[13]^t[29],this.v7l^=t[14]^t[30],this.v7h^=t[15]^t[31],t.fill(0)}destroy(){this.destroyed=!0,this.buffer32.fill(0),this.set(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0)}}const O=z(B=>new G(B));export{O as b};
