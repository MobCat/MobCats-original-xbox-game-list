**\[Title Image\]**  
Icon of the game converted from save data on the xbox. This has some issues and bugs dew to old tools...  
The icon should actually be embedded into the xbe itself, however no tools exist to extract it, only the default post boot microsoft text.

**\[Title ID HEX\]**  
The hex converted Serial Number  
We brake the SN into 2 parts and remove the -  
The front half, the letters are simple ascii to hex converted.  
The back half is decimal to hex converted.  
So MS-074 would become 4D53-004A then we drop the - and add the 0x to denote it is a hex code to get 0x4D53004A

**\[Title ID DEC\]**  
The integer of the hex ID number, this is used for some dev things, but most of the time you can just enter the hex by prefixing it with 0x

**\[Serial Number\]**  
This SN is given to the game by the MS cert process, it is an abbreviation of who is publishing the game, and then how many games  
said publisher has published. So AV-081 is the 81st game Activision has published for the original xbox.  
This number does get weird however when the publishers abbreviation has already been taken so something close had to be picked.  
The publisher has published more then 999 games and they need a new abbreviation. like Vivendi Universal Games?  
(that could also be a multiple studio thing)  
Or the developer published there own game like responDesign.  
As far as I know it starts at 001, no 000 games have been found or documented.

**\[Title Name\]**  
The name the developer gave the xbe. May not be respective of what the game is actually called by the public.  
Eg. TOCA race driver 2 and V8 Super cars 2 are both titled as just Race Driver 2... Because they are the same game, just with different settings.  
But also games that have a GoTY version or other sub headings probably wont be included in the title name.  
If the title is missing a name, or it is not very descriptive like DJFFNY your best bet is to get the games Serial Number, in this case EA-073<br>
and ctrl+f if it in [this](https://raw.githubusercontent.com/MobCat/MobCats-original-xbox-game-list/main/redump-xbox.json) json file I made for re-dump games.<br>
This may be automated at some point, but there isn't enough exactly correlating information from redump and my list to get the titles on the right versions of the game at this time.<br>

**\[Publisher\]**  
This is a guess based of the SN, not all publishers have been confirmed or known.    
You also get some weird things like carve was published by Global Star Software which is owned by Take-Two Interactive Software.  
So the game has been marked as TT, even know it wasn't really published by them, just someone they own.  
Demo titles for kiosks are marked with a XK and Xbox special bundled or live demo disk are marked with XL.  
An almost complete list (missing RD - responDesign and probs more) can be found [Here](https://xboxdevwiki.net/Xbe).

**\[Region\]**  
The region the xbe is set to run in. If these flags are not set correctly then your xbe may not boot on your xbox. However this can be bypassed with mods of course.  
The number at the start in an int convert from the hex flag, and is intended to make searching for regions easier.  
0 = No region set.  
1 = USA / Canada  
2 = Japan  
4 = PAL  
So 3 would be USA / Canada + Japan, 5 would be USA + PAL, 7 would be all of them and so on.

**\[Rating\]**  
This table is set to the US ESRB rating by default, a rating number is also included so a table can be built for your region.
(However this rating system is only ever used for US xbox games, and is otherwise ignored or not added by other region games and consoles)<br>    
(0) RP - ALL is a little bit of a misnomer. RP does stand for Rating Pending, However in this case, it just means no rating was set for this xbe.  
So this game is not affected by xbox parental controls.  
ESRB Table  
(0) RP - ALL, (1) AO - Adult, (2) M - Mature, (3) T - Teen, (4) E - Everyone, (5) K-A - Kids to Adults, (6) EC - Early Childhood  
There was "issues" with selling AO games in the US. So most devs and publishers will try and push the game down to an M to get it actually on store shelves.  
So currently no (1) rated legitimately published game has been found. We have already checked all the obvious games like manhunt, Fahrenheit, The Punisher, etc.  
(GTA SA was re-rated and then re-re-rated, but never recompiled in this time so the rating, according to the xbox never changed from (2))  
  
From a little more testing on the 1.00.5659 dash, parental controls for both games and movies only show when  
the xbox region is set to (1) North America. Setting it to (2) Asia only gives you parental controls for movies, the option for games is missing. And setting it to (4) Rest of the world AKA PAL, completely removes the parental controls setting from the settings menu. So parental controls for games seem to only be used for NTSC-UC games.

**\[Version\]**  
This info may not be that useful or arbitrary as it was developer set. So they can set it to whatever they want. Or not set it to anything at all.  
So it's only really used to distinguish different builds of the same game, but only if the developer used the version number code or set it to anything other then the default 0.  
In the Certificate header there is also a disk number hex, maybe for multi disk games.  
However after scanning 177 games, the disk number only ever returns 0x00000000. And I'm not even sure there are any multi disk xbox games.  
Games like Mettle Gear Solid just got crammed back into one disk late in development, and different revisions of the same game like classics or platinum hits get a different title id, version number or both sometimes.  
I have a check set up, so if the disk number ever returns anything other then 0 I will make a new category in this list for it. Until then, it's assumed to be always 0  
and the version number changes sometimes but not always.

**\[Media Type\]**  
The media the xbe is set to be allowed to run off. However every basic xbox mod either softmod, hardmod or xbox emulator will bypass this flag.  <br>
The thinking is just another check for running dev games on retail (outside of the signing key), or restricting where your dev game can run.  <br>
For eg, if you set your dev game to only run off CDs or a pressed xbox dvd, as soon as you copied it to your hdd it won't run. <br> 
Or if you set your retail game to only ever run off a pressed xbox disk, then in theory it will only ever run off a pressed disk.  <br>
It's also weird how granular the checks are. eg. just about every type of burned media has a flag.  <br>
So you could set your game to only run off a burnt dual layer dvd rw, and nothing else. including a normal dvd r.  <br>
To set multiple flags at once like Chihiro media board and xbox dvd we just add them together, so 0x00000202 in that case.<br>
All flags minus the masked media and non-secure mode flags set at once is 0x400003FF.<br>
Another common media type flag is 0x400001FF.<br>
0x00000001: HDD  <br>
0x00000002: XBOX DVD  <br>
0x00000004: Any CD / DVD  <br>
0x00000008: CD  <br>
0x00000010: DVD\_5\_RO  <br>
0x00000020: DVD\_9\_RO  <br>
0x00000040: DVD\_5\_RW  <br>
0x00000080: DVD\_9\_RW  <br>
0x00000100: USB Dongle <br>
0x00000200: Chihiro\_MEDIA\_BOARD <br>
0x40000000: Unlock HDD  <br>
0x80000000: NONSECURE\_MODE?  <br>
0x00FFFFFF: MEDIA\_MASK?  <br>
Some of these flags are unconfirmed or unknown like non secure mode and masked media.  <br>
USB Dongle refers to the xbox dvd media remote, the dvd player software lives in and runs from the dongle you plug into the console. So the media has to be signed to run like that.<br>
Chihiro MEDIA BOARD refers to the Chihiro arcade cabinets. This appears to be a default setting for later XDKs post 5455. More common with 5849.<br>


**\[Init Flags\]**  
Initialization flags for the xbe. So when the xbe boots it knows to format and mount the cache drive, or not use the extra ram of a devkit.  
Or any other things the game needs to know about your xbox hardware before it boots the game.  
I also believe that if the game is set to only use 64mb of ram, the ram upgraded mod for retail xboxs wont do anything. Even if you bypass the flag. The game is just not programed to use that much ram so it wont.  
Init table, again adding them together will set multiple flags at once.  
0x00000001: Mount Utility Drive  
0x00000002: Format Utility Drive  
0x00000004: 64MB RAM limit  
0x00000008: Don't setup HDD  
0x80000000: Unused high bit (8)?

**\[Entry Point\]**  
A rough idea of where the xbe is loaded to and ran from memory. It's only really meant to tell you if the xbe is compiled as a retail or debug xbe.  
X is used as a placeholder as the exact memory address is never the same, just the start of it is.  
0xA8XXXXXX: Retail game  
0x94XXXXXX: Debug or devkit game  
0xE6XXXXXX: Alpha kit game or early devkit game (DVT3 stuff) but this is unconfirmed.

**\[Cert Timestamp\]**  
A timestamp of when the xbe's certificate was generated, this is not a release date, but it's close enough. And will give you an idea of when the game was made.  
Like for eg. the Chihiro arcade media board thing, we can confirm that any game that has it is from around 2003+ and games that don't, are made at launch or up until then.  
I need to do some more homework to see what XDK revision came out then that devs had to upgrade to and test out this default flag.

**\[XDK Version\]**<br>
This is the version of the xbox development kit that was used to compile this default.xbe
Sometimes a game will have multiple XDK vers listed on my list.
This is because sometimes a developer may not have there XDK fully up to date for various reasons.
So some libraries used will be older then others, you will have to use the tools to view which libraries are compiled for witch XDK.
For the most part, and especially now days 20 years on, this will not impact gameplay.
The xbox will just load those compiled libraries from the xbe as is, and as needed, and the xbox will just play them fine  (v1.6 xbox issues aside which is kinda unrelated to this).

**\[MD5 Checksum\]**  
This is a unique MD5 checksum of the xbe this info was pulled from. If your xbe has the same checksum but not the same info, we have issues...
