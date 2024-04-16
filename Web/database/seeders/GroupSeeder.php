<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = [
          [
            "name" => "GFI Ventures",
            "avatar" => "https://tinwritescode.github.io/plats-images/GFI%20-%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/GFI.png",
            "headline" => "A leading venture capital fund in Vietnam",
            "description_vi" =>
                "GFI Ventures là một quỹ đầu tư hàng đầu tại Việt Nam, được thành lập bởi Founder Phạm Hưởng. GFI là một quỹ đầu tư với mục đích nghiên cứu, tìm kiếm và hỗ trợ những dự án tiềm năng cần huy động vốn trong ngành công nghiệp blockchain. Không chỉ rót vốn, GFI còn cung cấp một hệ sinh thái toàn diện như truyền thông, media, cộng đồng, tư vấn giải pháp công nghệ, tối ưu sản phẩm... cho các start-up.",
            "description_en" =>
                "GFI Ventures - Golden Finance Innovation Ventures is a leading venture capital fund in Vietnam founded by Mr. Pham Huong. GFI is a venture capital fund with the purpose of researching, finding and supporting potential projects that need to raise capital in the blockchain industry. Not only funding, GFI also provides a comprehensive ecosystem such as communication, media, community, technology solution consulting, product optimization... for start-ups.",
            "website" => "https://gfiblockchain.com/",
            "telegram" => "https://t.me/gfi_blockchainchannel",
            "facebook" => "https://www.facebook.com/gfiblockchain",
            "youtube" => "https://www.youtube.com/c/GFSBlockchainInsights",
            "twitter" => "https://twitter.com/GFI_Ventures",
            "instagram" => "",
            "username" => "GFI_ventures",
            "country" => "vn"
          ],
          [
            "name" => "GFI Blockchain Insights",
            "avatar" => "https://tinwritescode.github.io/plats-images/GFI%20-%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/GFI.png",
            "headline" => "GFI Blockchain Insights",
            "description_vi" =>
                "GFI Blockchain Insights Kênh cung cấp Thông tin, Kiến thức, Tư duy và chia sẻ danh mục những dự án tiềm năng trong thị trường Blockchain.",
            "description_en" =>
                "A channel to provide to crypto and technology lovers the information, knowledge, mindset and share a list of potential projects in the Blockchain market.",
            "website" => "",
            "twitter" => "",
            "telegram" => "https://t.me/gfi_blockchain",
            "facebook" => "",
            "youtube" => "https://www.youtube.com/@GFIBlockchainInsights",
            "instagram" => "",
            "username" => "GFI_insights",
            "country" => "vn"
          ],
          [
            "name" => "GFI Airdrop Channel",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/2022-11-07 15.11.28.jpg",
            "cover" => "https://tinwritescode.github.io/plats-images/GFI.png",
            "headline" => "GFI Airdrop Channel",
            "description_vi" =>
                "GFI Airdrop Channel - Nơi chia sẻ những thông tin về các chương trình Airdrop, Giveaway, Bounty từ các dự án crypto uy tín trên thị trường. Thông tin nhanh, chính xác với đội ngũ hỗ trợ nhiệt tình từ GFI Ventures.",
            "description_en" =>
                "GFI Airdrop Channel - A platform to share information about Airdrop, Giveaway, Bounty programs from reputable crypto projects in the market. Enthusiastic supporters from GFI Ventures quickly provide accurate information.",
            "website" => "",
            "twitter" => "",
            "telegram" => "https://t.me/gfiairdropchannel",
            "facebook" => "",
            "youtube" => "",
            "instagram" => "",
            "username" => "GFI_Airdrop",
            "country" => "vn"
          ],
          [
            "name" => "Vietnam Blockchain Innovation (VBI Lab)",
            "avatar" => "https://tinwritescode.github.io/plats-images/VBI.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/GFI.png",
            "headline" => "Vietnam's blockchain developer and technology forum",
            "description_vi" =>
                "Vietnam Blockchain Innovation (VBI) là diễn đàn công nghệ blockchain dành cho những người quan tâm tới blockchain, đặc biệt là kỹ thuật dev&tech, để cùng mọi người chia sẻ, thảo luận, cùng dev, đào sâu và cập nhật những công nghệ, dự án mới nhất của blockchain. Mục tiêu của Forum là tập hợp những chuyên gia về công nghệ blockchain, các nhà nghiên cứu, yêu thích công nghệ và developer của Việt Nam ở nhiều chuyên ngành IT, chia sẻ thảo luận để mọi người đều có nền tảng cơ bản về blockchain, từ đó mọi người có thể kết với nhau thành những team để có thể khám phá khả năng của blockchain ở những lĩnh vực khác nhau, giúp chúng ta có một nguồn nhân lực blockchain mạnh.",
            "description_en" =>
                "Vietnam Blockchain Innovation (VBI) is a blockchain technology forum for those interested in blockchain, especially dev&tech to share, discuss, dev, deepen and update technologies, Blockchain's newest projects. The goal of the Forum is to gather Vietnamese blockchain technology experts, researchers, technology lovers and developers in many IT disciplines to share and discuss so that everyone has a basic foundation in blockchain. As a result, everyone can join together into teams to explore the possibilities of blockchain in different fields, which helps us to have a strong blockchain human resource.",
            "website" => "",
            "twitter" => "",
            "telegram" => "",
            "facebook" => "https://www.facebook.com/groups/vietnamblockchaininnovation",
            "username" => "VBI_labs",
            "country" => "vn"
          ],
          [
            "name" => "V2B Labs",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/V2BLab_logo_square_white.png",
            "cover" => "https://tinwritescode.github.io/plats-images/v2b cover.png",
            "headline" => "Blockchain Labs & Service Provider",
            "description_vi" =>
                "V2B Labs – Blockchain Hub tại Việt Nam, cung cấp các sản phẩm và dịch vụ blockchain toàn diện. V2B với mục đích thúc đẩy sự gắn kết blockchain trên toàn cầu và trở thành một trong 5 trung tâm blockchain hàng đầu ở Đông Nam Á.",
            "description_en" =>
                "V2B Labs is a blockchain hub in Vietnam, providing comprehensive blockchain products and services. V2B aims to empower blockchain integration globally and become a top 5 blockchain hub in South East Asia.",
            "website" => "https://www.v2blabs.com/",
            "twitter" => "https://twitter.com/V2bLabs",
            "telegram" => "https://t.me/v2blabs",
            "category" => "",
            "username" => "V2BLabs",
            "country" => "Global"
          ],

          [
            "name" => "Akather",
            "avatar" => "https://tinwritescode.github.io/plats-images/akather.jpg",
            "cover" => "https://tinwritescode.github.io/plats-images/akather cover.jpg",
            "headline" => "The metaverse platform dedicated to Edtech",
            "description_vi" =>
                "Akather là một nền tảng giáo dục Metaverse, một nền tảng “immersive learning' dựa trên Metaverse và Bl",
            "description_en" =>
                "Akather is a Metaverse education platform, a \"immersive learning\" platform based on Metaverse and Bl",
            "website" => "https://www.v2blabs.com/",
            "twitter" => "https://twitter.com/weareAkather",
            "telegram" => "https://t.me/akatherinsight",
            "youtube" => "https://www.youtube.com/channel/UCqV0AauEC8SBR9cSgyjz6vQ",
            "category" => "",
            "username" => "Akather",
            "country" => "Global"
          ],

          [
            "name" => "Near Vietnam Hub",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Near%20Vietnam%20Hub%20avt.jpeg",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Near%20Vietnam%20Official.jpeg",
            "headline" =>
                "NEAR's Vietnam Regional Hub - The largest NEAR local community!",
            "description_vi" =>
                "NEAR Viet Nam Hub là một hub mở cho tất cả mọi người quan tâm đến sử dụng hoặc xây dựng trên hệ sinh thái NEAR tại Việt Nam.",
            "description_en" =>
                "NEAR Viet Nam Hub is the regional hub open for everyone interested in learning about, using or building on the NEAR protocol in the Viet Nam.",
            "website" => "https://nearvietnamhub.org/",
            "twitter" => "https://twitter.com/NearVietnamHub",
            "telegram" => "https://t.me/nearvietnamofficial",
            "facebook" => "https://www.facebook.com/NEARVietnamOfficial",
            "youtube" => "https://www.youtube.com/c/GFSBlockchainInsights",
            "username" => "Near_VNHub",
            "country" => "vn"
          ],

          [
            "name" => "Near Protocol Global",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Near Protocol Global (1).jpeg",
            "headline" =>
                "A climate-neutral, high-speed & low transaction fee layer-1 blockchain.",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Near%20Protocol%20Global.jpeg",
            "description_vi" =>
                "NEAR Viet Nam Hub là một hub mở cho tất cả mọi người quan tâm đến sử dụng hoặc xây dựng trên hệ sinh thái NEAR tại Việt Nam.",
            "description_en" =>
                "NEAR Viet Nam Hub is the regional hub open for everyone interested in learning about, using or building on the NEAR protocol in the Viet Nam.",
            "website" => "https://near.org/",
            "twitter" => "https://twitter.com/NEARProtocoll",
            "discord" => "https://discord.com/invite/UY9Xf2k",
            "telegram" => "https://t.me/cryptonear",
            "youtube" => "https://www.youtube.com/channel/UCuKdIYVN8iE3fv8alyk1aMw",
            "username" => "Near_Protocol",
            "country" => "global"
          ],

          [
            "name" => "Octopus Network",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Octopus%20Network%20avt.jpeg",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Octopus%20Network.jpeg",
            "headline" =>
                "A NEAR-based multichain interoperable crypto-network for launching and running web3 substrate-based &EVM compatible Appchains.",
            "description_vi" =>
                "Octopus Network là một mạng lưới tiền điện tử đa chuối có thể tương tác dựa trên NEAR tạo điều kiện cho ứng dụng hoạt động chuỗi và cung cấp bảo mật cho thuê và cơ sở hạ tầng end-to-end.",
            "description_en" =>
                "The Octopus Network is a NEAR-based multichain interoperable crypto-network for launching appchains and providing rental security and end-to-end infrastructure.",
            "website" => "https://oct.network/",
            "twitter" => "https://twitter.com/oct_network",
            "discord" => "https://discord.com/invite/6GTJBkZA9Q",
            "telegram" => "https://t.me/octopusnetwork",
            "facebook" => "https://www.facebook.com/TheOctopusNetwork",
            "youtube" => "https://www.youtube.com/channel/UCkMYDmXdgjCBTBggSEAy0ZQ",
            "medium" => "https://medium.com/oct-network",
            "username" => "OctopusNetwork_Global",
            "country" => "global"
          ],

          [
            "name" => "Octopus Vietnam",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Octopus%20Vietnam%20avt.jpeg",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Octopus%20Vietnam.jpeg",
            "headline" => "The community of Octopus Network in Vietnam",
            "description_vi" =>
                "Cộng đồng mạng lưới Octopus tại Việt Nam thúc đẩy mạng lưới Octopus toàn cầu, tạo không gian kết nối, chia sẻ kiến thức về Blockchain và cập nhật thông tin về OCT.",
            "description_en" =>
                "The Octopus network community in Vietnam promotes the global Octopus network, creates a space to connect, share knowledge about Blockchain and update information about OCT.",
            "twitter" => "https://twitter.com/oct_vietnam",
            "telegram" => "https://t.me/octvietnamese",
            "username" => "OctopusNetwork_Vietnam",
            "country" => "vn"
          ],

          [
            "name" => "Octopus Accelerator Program",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Octopus%20Accelerator%20Program%20avt.jpeg",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Octopus%20Accelerator%20Program.jpeg",
            "headline" =>
                "Octopus Accelerator Program - a quarterly offered ten-week course",
            "description_vi" =>
                "Octopus Accelerator Program là một khóa học kéo dài 10 tuần được mở theo quý. Khóa học mở và có thể tổng hợp cho các nhà phát triển, nhà sáng lập và nhóm Web3. Ngay cả những người sáng lập độc lập cũng có thể tham gia. Các khóa học được tổ chức thông qua hội thảo trực tuyến và các phiên cố vấn với các chuyên gia.",
            "description_en" =>
                "Octopus Accelerator Program is a quarterly offered ten-week course which is both open and composable to developers, founders, and Web3 Teams. Even solo founders can apply. Courses are held via online seminars and mentor sessions with invited experts.",
            "website" => "https://accelerator.oct.network/",
            "twitter" => "https://twitter.com/oct_acc",
            "username" => "Octopus_AcceleratorProgram",
            "country" => "global"
          ],


          [
            "name" => "Nearity 🅝 | Create Without Limits",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Nearity%20%F0%9F%85%9D%20_%20Create%20Without%20Limits%20avt.jpeg",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Nearity%20%F0%9F%85%9D%20_%20Create%20Without%20Limits.jpeg",
            "description_vi" =>
                "Nearity ra đời với ý tưởng giảm giá thành của các thiết bị chất lượng cao phục vụ hội nghị. Nearity đã thiết kế lại một loạt thiết bị phục vụ hội nghị với mục tiêu tạo ra các thiết bị phục vụ hội nghị chất lượng tốt nhất, dễ sử dụng với mức giá rất hợp lý. Nearity tin rằng mọi doanh nghiệp nên được tiếp cận công nghệ tiên tiến nhất, và Nearity có sứ mệnh biến điều đó thành hiện thực.",
            "description_en" =>
                "Nearity was born out of the idea that high quality, easy-to-use conference products shouldn’t be too expensive. They re-designed a series of conference products with our values: to create the best-quality conferencing products that are easy to use at a very reasonable price. We believe that every business should have access to great, flexible new technology and we’re on a mission to make it happen.",
            "headline" =>
                "Latest news and updates, analytics, insights of Near Ecosystem",
            "website" => "",
            "twitter" => "https://twitter.com/nearity_org",
            "telegram" => "https://t.me/nearity",
            "facebook" => "https://www.facebook.com/neairtyorg",
            "youtube" => "https://www.youtube.com/channel/UCOy_RVFYGom7wbgIMdjE4cw",
            "username" => "Nearity",
            "country" => "global"
          ],

          [
            "name" => "Near Insider 🅝",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Near%20Insider%20%F0%9F%85%9D%20avt.jpeg",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Near%20Insider%20%F0%9F%85%9D.jpeg",
            "headline" =>
                "Latest News, Insight, On-chain and Data analytics for NEAR ecosystem.",
            "description_vi" =>
                "NEAR Insider là một nền tảng truyền thông cho hệ sinh thái NEAR, bao gồm các tin vắn, chủ đề về xu hướng, dữ liệu, phân tích, các chuỗi, thông tin tổng quan.",
            "description_en" =>
                "NEAR Insider is a media for NEAR ecosystem, about fast news, data-news, insight topics, analytics topics, on-chain topics, brief overview infographics.",
            "website" => "",
            "twitter" => "https://twitter.com/near_insider",
            "telegram" =>
                "https://t.me/near_insider.                https://t.me/nearinsider_chat",
            "facebook" => "https://www.facebook.com/near.insider",
            "youtube" => "https://www.youtube.com/c/NearInsiderTV",
            "category" => "",
            "username" => "Near_Insider",
            "country" => "global"
          ],
          [
            "name" => "Aurora Official",
            "avatar" => "https://tinwritescode.github.io/plats-images/Aurora%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/Aurora.jpeg",
            "headline" => "Aurora is a Bridge + EVM Scaling Solution for Ethereum.",
            "description_vi" =>
                "Aurora là một Ethereum Virtual Machine (EVM) được xây dựng trên blockchain Near. Nền tảng này là sự kết hợp sức mạnh của Near và Ethereum để mang đến một giải pháp hoàn hảo cho các nhà phát triển dApp trên nền tảng Ethereum có thể mở rộng và di chuyển qua Near.Aurora cho phép các dApp sở hữu những ưu điểm như tốc độ nhanh, khả năng mở rộng tốt. Ngoài ra, các nhà phát triển có thể tận dụng nhiều tính năng độc đáo của Near về phí gas cho người dùng của họ cũng như phần thưởng hấp dẫn cho nhà phát triển.",
            "description_en" =>
                "Aurora is a solution, that allows to execute Ethereum contracts in a more performant environment—NEAR blockchain, a modern layer-1 blockchain which is fast (2-3 second transaction finalization), scalable, and carbon neutral. Aurora is an Ethereum Virtual Machine (EVM) implemented as a smart contract on NEAR Protocol. We are here to help scale Ethereum ecosystem for developers to operate their apps on an Ethereum-compatible, high-throughput, scalable, and future-safe platform, with low transaction costs for their users.",
            "website" => "https://aurora.dev/",
            "twitter" => "https://twitter.com/auroraisnear",
            "discord" => "https://discord.com/invite/dEFJBz8HQV",
            "telegram" => "https://t.me/auroraisnear",
            "facebook" => "https://www.facebook.com/auroraisnear",
            "category" => "",
            "username" => "Aurora_Official",
            "country" => "global"
          ],
          [
            "name" => "Aurora Vietnam",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Aurora%20Vietnam%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/Aurora.jpeg",
            "headline" =>
                "Official Community of Aurora in Vietnam, a part of Near Vietnam Hub",
            "description_vi" =>
                "Official Community of Aurora in Vietnam, a part of Near Vietnam Hub\nAurora là một Ethereum Virtual Machine (EVM) được xây dựng trên blockchain Near. Nền tảng này là sự kết hợp sức mạnh của Near và Ethereum để mang đến một giải pháp hoàn hảo cho các nhà phát triển dApp trên nền tảng Ethereum có thể mở rộng và di chuyển qua Near. Aurora cho phép các dApp sở hữu những ưu điểm như tốc độ nhanh, khả năng mở rộng tốt. Ngoài ra, các nhà phát triển có thể tận dụng nhiều tính năng độc đáo của Near về phí gas cho người dùng của họ cũng như phần thưởng hấp dẫn cho nhà phát triển.",
            "description_en" =>
                "Aurora is a solution, that allows to execute Ethereum contracts in a more performant environment—NEAR blockchain, a modern layer-1 blockchain which is fast (2-3 second transaction finalization), scalable, and carbon neutral. Aurora is an Ethereum Virtual Machine (EVM) implemented as a smart contract on NEAR Protocol. We are here to help scale Ethereum ecosystem for developers to operate their apps on an Ethereum-compatible, high-throughput, scalable, and future-safe platform, with low transaction costs for their users.",
            "website" => "",
            "twitter" => "",
            "telegram" => "https://t.me/auroravnofficial",
            "facebook" => "",
            "youtube" => "",
            "category" => "",
            "username" => "Aurora_Vietnam",
            "country" => "vn"
          ],
          [
            "name" => "Cardano Community",
            "avatar" => "https://tinwritescode.github.io/plats-images/Cardano.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/Cardano%20avt.png",
            "headline" =>
                "A decentralized blockchain based on peer-reviewed research and highly secure Haskell coding language",
            "description_vi" =>
                "Cardano (ADA) là một mạng lưới blockchain Proof-of-Stake (PoS), dựa trên một loạt các thành phần thiết kế bao gồm nền tảng phát triển dApp, sổ cái được hỗ trợ multi-asset và các hợp đồng thông minh có thể xác minh.",
            "description_en" =>
                "Cardano is a proof-of-stake blockchain platform: the first to be founded on peer-reviewed research and developed through evidence-based methods. It combines pioneering technologies to provide unparalleled security and sustainability to decentralized applications, systems, and societies. With a leading team of engineers, Cardano exists to redistribute power from unaccountable structures to the margins – to individuals – and be an enabling force for positive change and progress.",
            "website" => "https://cardano.org/",
            "twitter" => "https://twitter.com/Cardano",
            "reddit" => "https://www.reddit.com/r/cardano/",
            "telegram" => "https://t.me/Cardano",
            "facebook" => "https://www.facebook.com/groups/CardanoCommunity",
            "category" => "",
            "username" => "Cardano_Community",
            "country" => "global"
          ],

          [
            "name" => "Flow",
            "avatar" => "https://tinwritescode.github.io/plats-images/Flow%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/Flow.jpeg",
            "headline" =>
                "Ecofriendly blockchain designed for Web3 apps without compromising UX",
            "description_vi" =>
                "FLOW là một blockchain nền tảng với khả năng mở rộng, tốc độ xử lý giao dịch nhanh, chi phí rẻ hơn rất nhiều lần so với Bitcoin hay Ethereum. – FLOW được tạo ra nhằm phát triển một hệ sinh thái dành cho trò chơi phi tập trung, Dapps, NFT và các tài sản kỹ thuật số theo hình thức phi tập trung.",
            "description_en" =>
                "Flow is a proof of stake blockchain designed to be the foundation of Web3 and the open metaverse, supporting consumer-scale decentralized applications, NFTs, DeFi, DAOs, PFP projects, and more. Powered by Cadence, an original programming language built specifically for digital assets, Flow empowers developers to innovate and push the limits that will bring the next billion to Web3. Created by a team that has consistently delivered industry-leading consumer-scale experiences including CryptoKitties, NBA Top Shot, and NFL ALL DAY, Flow is an open, decentralized platform with a thriving ecosystem of creators from top brands, development studios, venture-backed startups, crypto leaders, and more.",
            "website" => "https://flow.com/",
            "twitter" => "https://twitter.com/flow_blockchain",
            "discord" => "https://discord.com/invite/J6fFnh2xx6",
            "telegram" => "https://t.me/flow_community",
            "youtube" => "https://www.youtube.com/channel/UCs9r5lqmYQsKCpLB9jKwocg",
            "category" => "",
            "username" => "Flow_Global",
            "country" => "global"
          ],
          [
            "name" => "Flow Vietnam",
            "avatar" => "https://tinwritescode.github.io/plats-images/flow-vn.png",
            "cover" => "https://tinwritescode.github.io/plats-images/Flow.jpeg",
            "headline" => "Community of Flow in Vietnam",
            "description_vi" =>
                "FLOW là một blockchain nền tảng với khả năng mở rộng, tốc độ xử lý giao dịch nhanh, chi phí rẻ hơn rất nhiều lần so với Bitcoin hay Ethereum. – FLOW được tạo ra nhằm phát triển một hệ sinh thái dành cho trò chơi phi tập trung, Dapps, NFT và các tài sản kỹ thuật số theo hình thức phi tập trung.",
            "description_en" =>
                "Flow is a proof of stake blockchain designed to be the foundation of Web3 and the open metaverse, supporting consumer-scale decentralized applications, NFTs, DeFi, DAOs, PFP projects, and more. Powered by Cadence, an original programming language built specifically for digital assets, Flow empowers developers to innovate and push the limits that will bring the next billion to Web3. Created by a team that has consistently delivered industry-leading consumer-scale experiences including CryptoKitties, NBA Top Shot, and NFL ALL DAY, Flow is an open, decentralized platform with a thriving ecosystem of creators from top brands, development studios, venture-backed startups, crypto leaders, and more.",
            "website" => "",
            "twitter" => "",
            "discord" => "https://discord.gg/flow",
            "telegram" => "https://t.me/flow_vi",
            "youtube" => "",
            "category" => "",
            "username" => "Flow_Vietnam",
            "country" => "vn"
          ],

          [
            "name" => "FileCoin",
            "avatar" => "https://tinwritescode.github.io/plats-images/FileCoin%20avt.png",
            "cover" => "https://tinwritescode.github.io/plats-images/FileCoin.png",
            "headline" =>
                "A decentralized storage network designed to store humanity's most important information.",
            "description_vi" =>
                "Filecoin được hiểu là một mạng lưu trữ phi tập trung được dùng để lưu trữ các thông tin quan trọng của người dùng. Nó cho phép bất kỳ ai cũng có thể thuê những nguồn dự trữ dữ liệu trên máy tính. Người thuê sẽ phải trả một lượng tiền tương ứng để có thể lưu trữ các dữ liệu của họ, còn những người khai thác bộ nhớ này sẽ nhận được một mức thưởng xứng đáng.",
            "description_en" =>
                "Filecoin is a protocol token whose blockchain runs on a novel proof, called Proof-of-Spacetime, where blocks are created by miners that are storing data. Filecoin protocol provides a data storage and retrieval service via a network of independent storage providers that does not rely on a single coordinator, where: (1) clients pay to store and retrieve data, (2) Storage Miners earn tokens by offering storage (3) Retrieval Miners earn tokens by serving data",
            "website" => "https://filecoin.io/",
            "twitter" => "https://twitter.com/Filecoin",
            "discord" => "https://discord.com/invite/ipfs",
            "telegram" => "https://t.me/filecoinio",
            "facebook" => "https://www.facebook.com/Filecoin.io",
            "category" => "",
            "username" => "FileCoin",
            "country" => "global"
          ],

          [
            "name" => "Yogain",
            "avatar" => "https://tinwritescode.github.io/plats-images/Yogain%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/Yogain.jpeg",
            "headline" =>
                "A Web3 lifestyle app with the YogaAndEarn concept using ARTechnology, SocialFi and GameFi elements",
            "description_vi" =>
                "Yogain là một ứng dụng về lối sống Web3 với khái niệm YogaAndEarn sử dụng các yếu tố ARTechnology, SocialFi và GameFi",
            "description_en" =>
                "A pioneer in Mobile Yoga Practice Experience, boosting Yoga practice and online interaction. Users can practice Yoga and find joyful moments right on mobile phone",
            "website" => "https://www.yogain.io/",
            "twitter" => "https://twitter.com/YogainOfficial",
            "discord" => "https://discord.com/invite/vZ52yrMzRs",
            "facebook" => "https://www.facebook.com/YogainOfficial0/",
            "category" => "",
            "username" => "Yogain",
            "country" => "global"
          ],

          [
            "name" => "Horizonland",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Horizonland%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/Horizonland%20.jpeg",
            "headline" => "A New Dawn of Esport Metaverse",
            "description_vi" =>
                "Horizonland là một trung tâm chơi game độc đáo, kết hợp các công nghệ tiên tiến và kết hợp nhiều trò chơi esport trong một không gian ảo. Với các điểm nổi bật đáng chú ý, Horizonland sẽ tự tin trở thành một nền tảng game thú vị cho người chơi và dẫn đầu sóng metaverse hiện tại. Đặc biệt khi đến với Horizonland, người chơi sẽ được biến thành avatar và nhập vào một thế giới kỳ ảo với công nghệ VR. Họ có thể làm tất cả những gì họ muốn làm và là ai họ muốn là. Ngoài việc được giải trí bởi các hoạt động game thú vị, người chơi có thể tận dụng các cơ hội MMO mà họ có thể nghĩ rằng chỉ có trong giấc mơ.",
            "description_en" =>
                "Horizonland is the one-and-only playground that integrates cutting-edge technologies and combines multiple esport games in one virtual universe. With its outstanding highlights, Horizonland will confidently become an addictive game platform for players and lead the current metaverse wave. The special thing when coming to Horizonland is that players will be transformed into avatars and enter a surreal world with VR technology. They can do all the things they want to do and be who they want to be. Aside from being entertained by the game's exciting activities, players may take advantage of various MMO opportunities that they might think to exist only in dreams.",
            "website" => "https://horizonland.io/",
            "twitter" => "https://twitter.com/Horizonland_io",
            "discord" => "https://discord.com/invite/PhNrNVBqdD",
            "facebook" => "A New Dawn of Esport Metaverse",
            "youtube" => "https://www.youtube.com/channel/UCB5G4Sdj1rMpf4EoxDGRjAg",
            "category" => "",
            "username" => "Horizonland",
            "country" => "global"
          ],

          [
            "name" => "Nearlend DAO",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Nearlend%20DAO%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/Nearlend%20DAO.jpeg",
            "headline" => "Open-Source Capital Market on NEAR Protocol",
            "description_vi" => "",
            "description_en" =>
                "Nearlend is a Near Protocol-based, open-source money market protocol aimed at establishing liquidity pools whose interest rates are determined by an algorithm based on the supply and demand. Supplier supply assets to the liquidity pool to earn interest, while borrowers take a loan from liquidity pool and pay interest on their debt. In essence, Nearlend bridges the gaps between lenders who wish to accrue interest from idle funds and borrowers who want to borrow funds for productive or investment use. As well as many similar protocols on the market today, Nearlend lowers the barrier for financial services by allowing users to interact directly with the protocol for interest rates without needing to negotiate loan terms (e.g., maturity, interest rate, counterparty, collaterals), thereby creating a more efficient money market.",
            "website" => "https://nearlenddao.com/",
            "twitter" => "https://twitter.com/NearlendDao",
            "discord" => "https://discord.com/invite/pXvHhT9rwM",
            "telegram" => "https://t.me/nearlenddao",
            "youtube" => "",
            "category" => "",
            "username" => "Nearlend_DAO",
            "country" => "global"
          ],

          [
            "name" => "NearFi",
            "avatar" => "https://tinwritescode.github.io/plats-images/NearFi%20avt.jpeg",
            "cover" => "https://tinwritescode.github.io/plats-images/NearFi.jpeg",
            "headline" => "The NEAR DeFi Experience on your palm",
            "description_vi" =>
                "Kyber Network là một trung tâm thanh khoản và giao dịch tiền điện tử đa chuỗi kết nối thanh khoản từ các nguồn khác nhau để cho phép giao dịch với tỷ giá tốt nhất.",
            "description_en" =>
                "NearFi Wallet is NEAR blockchain wallet, designed to allow NEAR holders to interact with all Defi services on NEAR blockchain on mobile. With NearFi Wallet, users would have a truly NEAR experience on the go and everywhere. NearFi integrates all the Defi services on NEAR blockchain, like AMM, lending, margin DEX, stable coin swap, and yield farming.",
            "website" => "https://nearfi.finance/",
            "twitter" => "https://twitter.com/NearFi_Wallet",
            "discord" => "https://discord.com/invite/zvCyV8Qper",
            "telegram" => "https://t.me/nearfiwallet",
            "youtube" => "",
            "category" => "",
            "username" => "NearFi",
            "country" => "global"
          ],
          
          [
            "name" => "Kyber Network",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/Kyber%20Network%20avt.jpeg",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/Kyber%20Network.jpeg",
            "headline" => "Trade tokens at the Best Rates in DeFi",
            "description_vi" =>
                "Kyber Network là một trung tâm thanh khoản và giao dịch tiền điện tử đa chuỗi kết nối thanh khoản từ các nguồn khác nhau để cho phép giao dịch với tỷ giá tốt nhất.",
            "description_en" =>
                "Kyber Network is a multi-chain crypto trading and liquidity hub that connects liquidity from different sources to enable trades at the best rates .",
            "website" => "https://kyber.network/",
            "twitter" => "https://twitter.com/kybernetwork/",
            "discord" => "https://discord.com/invite/NB3vc8J9uv",
            "telegram" => "https://t.me/kybernetwork",
            "youtube" => "https://www.youtube.com/channel/UCQ-8mEqsKM3x9dTT6rrqgJw",
            "category" => "",
            "username" => "KyberNetwork",
            "country" => "global"
          ],
          [
            "name" => "Xpert Media",
            "avatar" =>
                "https://tinwritescode.github.io/plats-images/CryptoXpert Logo-02.png",
            "cover" =>
                "https://tinwritescode.github.io/plats-images/XPert Media Official Logo White-03.png",
            "headline" => "a DeFi, GameFi to Metaverse, NFT and Infrastructure media hub",
            "description_vi" =>
                "Xpert Media là một trung tâm truyền thông với đội ngũ cố vấn và tiếp thị nội bộ giàu kinh nghiệm. Chúng tôi có kinh nghiệm hỗ trợ một loạt các dự án từ DeFi, GameFi đến Metaverse, NFT và Infrastructure",
            "description_en" =>
                " Xpert Media is a media hub with a highly experienced in-house marketing team and advisors. We have experience supporting a wide range of projects from DeFi, GameFi to Metaverse, NFT and Infrastructure. ",
            "website" => "https://www.v2blabs.com/",
            "twitter" => "https://twitter.com/_cryptoxpert",
            "telegram" => "https://t.me/cryptoxpert_ANN",
            "category" => "",
            "username" => "Xpert Media",
            "country" => "Global"
          ]
        ];

        foreach ($groups as $group) {
            $groupCheck = Group::where('username', $group['username'])->first();

            if ($groupCheck) {
                continue;
            }

            Group::create([
               'name' =>  $group['name'],
               'username' =>  $group['username'],
               'country' =>  $group['country'],
               'desc_vn' =>  $group['description_vi'],
               'desc_en' =>  $group['description_en'],
               'avatar_url' =>  $group['avatar'],
               'cover_url' =>  $group['cover'],
               'headline' =>  $group['headline'],
               'site_url' =>  isset($group['website']) ? $group['website'] : '',
               'twitter_url' =>  isset($group['twitter']) ? $group['twitter'] : '',
               'telegram_url' =>  isset($group['telegram']) ? $group['telegram'] : '',
               'youtube_url' =>  isset($group['youtube']) ? $group['youtube'] : '',
               'discord_url' =>  isset($group['discord']) ? $group['discord'] : '',
            ]);
        }
    }
}
