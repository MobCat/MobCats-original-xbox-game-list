**Title Image**  
Icon of the game converted from save data on the xbox. This has some issues and bugs dew to old tools...  
The icon should actually be embedded into the xbe itself, however no tools exist to extract it, only the default post boot microsoft text.

**Title ID HEX**  
The hex converted Serial Number  
We brake the SN into 2 parts and remove the -  
The front half, the letters are simple ascii to hex converted.  
The back half is decimal to hex converted.  
So MS-074 would become 4D53-004A then we drop the - and add the 0x to denote it is a hex code to get 0x4D53004A

**Title ID DEC**  
The integer of the hex ID number, this is used for some dev things, but most of the time you can just enter the hex by prefixing it with 0x

**Serial Number**  
This SN is given to the game by the MS cert process, it is an abbreviation of who is publishing the game, and then how many games  
said publisher has published. So AV-081 is the 81st game Activision has published for the original xbox.  
This number does get weird however when the publishers abbreviation has already been taken so something close had to be picked.  
The publisher has published more then 999 games and they need a new abbreviation. like Vivendi Universal Games?  
(that could also be a multiple studio thing)  
Or the developer published there own game like responDesign.  
As far as I know it starts at 001, no 000 games have been found or documented.

**Title Name**  
The name the developer gave the xbe. May not be respective of what the game is actually called.  
Eg. TOCA race driver 2 and V8 Super cars 2 are both titled as just Race Driver 2... Because they are the same game, just with different settings.  
But also games that have a GoTY version or other sub headings probably wont be included in the title name.  
This does have Unicode to ASCII convert issues dew to old tools.  So it needs to be updated.  
Also some characters had to be stripped from the csv to keep GitHub happy.  
I'm not happy with this, but it will have to do for now.

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
(0) RP - ALL is a little bit of a misnomer. RP does stand for Rating Pending, However in this case, it just means no rating was set for this xbe.  
So this game is not affected by xbox parental controls.  
ESRB Table  
(0) RP - ALL, (1) AO - Adult, (2) M - Mature, (3) T - Teen, (4) E - Everyone, (5) K-A - Kids to Adults, (6) EC - Early Childhood  
ACB (Australian Classification Board) Table \[citation needed\]  
(0) CTC - Check the Classification, (1) MA 15+ Mature Accompanied, (2) M - Mature, (3) PG - Parental Guidance (4) G - General, (5) ???, (6) ???  
For the ACB how games where rated changed after the xbox came out. for eg. You were not even allowed to sell R18+ games in Australia until like the 7th console gen. When online stores like steam really picked up, where you don't have to rate your games, or put them on a retail shelve where the children might see the box art and be scared for life.  
And there was "issues" with selling AO games in the US. So most devs and publishers will try and push the game down to an M to get it actually on store shelves.  
So currently no (1) rated game has been found, we have already checked all the obvious games like manhunt, Fahrenheit, The Punisher, etc.  
(GTA SA was re-rated and then re-re-rated, but never recompiled in this time so the rating, according to the xbox never changed from (2))  
The rating tables do not exactly line up from different regions, and more so now days as the ratings changed. So more testing and documentation will be required to fix this table and build more tables.  
It's a little bit annoying because in the new system the ESRB M17+ does line up to R18+ in ACB, but no other boards like PEGI, RARA or USK have a rating for that.  
Just goes from 16 to 18. And I'm not sure if it was always like this as ratings over time change. We need rating data from 2001...  
Citation, All of this rambling could be pointless as from a little testing on the 1.00.5659 dash, parental controls for both games and movies only show when  
the xbox region is set to (1) North America. Setting it to (2) Asia only gives you parental controls for movies, the option for games is missing. And setting it to (4) Rest of the world completely removes the parental controls setting from the settings menu. Soooo yeah maybe parental controls only work or is used for NTSC-UC games, idk...

**\[Version\]**  
This info may not be that useful or arbitrary as it was developer set. So they can set it to whatever they want. Or not set it to anything at all.  
So it's only really used to distinguish different builds of the same game, but only if the developer used the version number code or set it to anything other then the default 0.  
In the Certificate header there is also a disk number hex, maybe for multi disk games.  
However after scanning 177 games, the disk number only ever returns 0x00000000. And I'm not even sure there are any multi disk xbox games.  
Games like Mettle Gear Solid just got crammed back into one disk late in development, and different revisions of the same game like classics or platinum hits get a different title id, version number or both sometimes.  
I have a check set up, so if the disk number ever returns anything other then 0 I will make a new category in this list for it. Until then, it's assumed to be always 0  
and the version number changes sometimes but not always.

**\[Media Type\]**  
The media the xbe is set to be allowed to run off. However every basic xbox mod either softmod, hardmod or xbox emulator will bypass this flag.  
The thinking is just another check for running dev games on retail, or restricting where your dev game can run.  
For eg, if you set your dev game to only run of CDs or a pressed xbox dvd, as soon as you copied it to your hdd it won't run.  
Or if you set your retail game to only ever run of a pressed xbox disk, then in theory it will only ever run off a pressed disk.  
It's also weird how granular the checks are. eg. just about every type of burned media has a flag.  
So you could set your game to only run off a burnt dual layer dvd rw, and nothing else. including a normal dvd r.  
The Chihiro arcade media board (200 MEDIA\_BOARD?) appears to be a default setting of later XDKs and all of these flags can be bypassed with a modded xbox   
Flag table we add them togther to set multible flas at once.  
0x00000001: HDD  
0x00000002: XBOX DVD  
0x00000004: Any CD / DVD  
0x00000008: CD  
0x00000010: DVD\_5\_RO  
0x00000020: DVD\_9\_RO  
0x00000040: DVD\_5\_RW  
0x00000080: DVD\_9\_RW  
0x00000100: USB Dongle?  
0x00000200: MEDIA\_BOARD?  
0x40000000: Unlock HDD  
0x80000000: NONSECURE\_MODE?  
0x00FFFFFF: MEDIA\_MASK?  
Some of these flags are unconfirmed or unknown like USB dongle, non secure mode and masked media.  
All flags minus the media masked flag set at once is 0x400001FF.

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

**\[MD5 Checksum\]**  
This is a unique MD5 checksum of the xbe this info was pulled from. If your xbe has the same checksum but not the same info, we have issues...
