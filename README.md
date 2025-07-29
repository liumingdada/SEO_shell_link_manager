# SEO_shell_link_manager 

***（初级版本 当前只提供客户端引用的API文件，服务端管理程序等待后期开放）

SEO应用方向的一套SHELL站链接管理系统，基于PHP，用于识别 Googlebot 爬虫，并根据与 API 的交互动态插入链接，具备多 API 故障转移和心跳机制，有防官方封锁，防管理员人工检测的功能。

# php-googlebot-link-manager

A PHP system to identify Googlebot crawlers, dynamically insert links via API interaction, and support multi-API fallback to avoid blocking.


## Overview
This project aims to intelligently manage links on website homepages and first-level directories by:  
- Identifying Googlebot crawlers accurately  
- Synchronizing data with servers via heartbeat mechanisms  
- Dynamically inserting specified links based on crawler identification  
- Avoiding API blocking through multi-address fallback  


## Core Features
1. **Multi-API Address Management**  
   - Initialize primary and backup API addresses  
   - Automatically update API lists via server responses to prevent blocking  

2. **Googlebot Identification**  
   - Validate via local IP list cache  
   - Supplemental DNS verification (using `nslookup`) for User-Agent matches  
   - Auto-add verified IPs to local lists  

3. **Heartbeat & Data Sync**  
   - Asynchronous heartbeat requests (to avoid blocking page loads)  
   - Configurable interval (default 30 mins) or forced updates via `updateSD.php?AC=DO`  
   - Independent `updateSD.php` module for local data submission to servers  

4. **Dynamic Link Insertion**  
   - Output links at code-embedded positions (supports custom locations like friend links, navigation bars)  
   - Conditional insertion based on page scope and crawler identification  

5. **Local Cache Management**  
   - Overwrite local cache with server responses (IP lists, links, API addresses, etc.)  
   - Temporary storage for new Googlebot IPs before submission  


## Tech Stack
- PHP 7.0+  
- JSON for data interaction  
- Local file caching  
- DNS query (via `nslookup`)  


## Usage
1. **Initialization**  
   - Configure initial API addresses in the config file  
   - Deploy core files (`index.php`, `updateSD.php`) to your server  

2. **Configuration**  
   - Set heartbeat interval via `updateSD.php?AC=[minutes]` (e.g., `AC=30` for 30 mins)  
   - Define link insertion scopes (homepage/first-level directories) in the config  

3. **Operation**  
   - The system auto-triggers checks on eligible pages  
   - Use `updateSD.php?AC=DO` to force data sync  


## Contributing
Contributions are welcome!  
- Submit issues for bugs or feature requests  
- Open PRs for optimizations (e.g., multi-crawler support, performance improvements)  


## SEO程序定制
联系wechat: liumingdada (备注github SEO)   
