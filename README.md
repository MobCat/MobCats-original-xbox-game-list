# MobCat's original xbox game ID list
[mobcat.zip/XboxIDs](https://www.mobcat.zip/XboxIDs/)

A.K.A Game Title ID

Just trying to expand on the lists that are already out there WITH EVEN MORE DATA<br>
And to allow for this data to be easily downloaded and used in your xbox projects.<br>
Just get the current up-to date sqlite db from [Here](https://mobcat.zip/XboxIDs/titleIDs.db)<br>
and start indexing it like a normal database.<br>
If this list is missing useful data let me know and we'll see if we can add it.  
Please note, most of this info is extracted from the games "default.xbe"  
Games that use multiple xbes like morrowind, Unreal II, a lot of compellation disks like Midway Arcade Treasures 3  
or any game that has a Title Update, so that is actually what's booting not the default xbe may produce different metadata.  
Because of this I'm not documenting anything that's not a "default.xbe" for now, It will just devolve into madness quickly.  
Like if I where also to dump and log all the save game icons, not just the title icons.  
I do need to log TUs at some point, but just logging all the default.xbe from games is going to be enough work.

# How is this info generated
When building this list I aimed to have no subjective data. 99% of this data is extracted directly from the metadata including in the games xbe.<br>
I don't want to name'n'shame any old lists, but this was me trying to get away from things like made up region codes and subjective title names.<br>
Sadly we do have to pull a complete title name as the imbedded title name is incomplete for our purposes, that is the only external data though.<br>
TBH the current way im doing this is shit, but the current tools are lacking..<br><br>

We obtain a clean copy of a redump xbox iso<br>
Repack it into a xiso for ease of storage and use<br>
Use PowerISO command line tools to extract the `default.xbe` from the the root of the xiso<br>
(I haven't finished building my tool for reading xdvdfs and this xbe inside said xdvdfs yet, using powerISO is slower, but less friction)<br>
We pass this xbe into a custom tool written in go to extract all the header info listed [here](https://xboxdevwiki.net/Xbe)<br>
(This tool will be made available to the public "when it's done". it contains to much spaghetti right now for public use.)<br><br>

This info is then decoded and formatted from known hex flags.<br>
For eg, allowed media types. HDD = 0x1, Dongle = 0x100. So 0x101 = HDD + Dongle.<br>
After all of this we are still left with 3 fields of missing data. The icon xbe, the XMID and a complete title name.<br><br>

XMID is generated from already extracted data.<br>
Halo 2 = Title ID: 4D530064, SN: MS-100, Build Ver: 0x0000000A, Region: 7<br>
We take the SN and remove the -<br>
We take the build ver, truncate it to the last tow hex 0A, then convert that into dec 10<br>
We take the region code and look it up on a list<br>
'1': 'A', '2': 'J', '3': 'K', '4': 'E', '5': 'L', '7': 'W'<br>
<sup>\[citation needed] 6 is missing or unknown</sup><br>
Then we concatenate them all together. MS100 10 W<br><br>

Xbes do have the title Icons imbedded in the xbe, however no tool curently exist to pull it<br>
so I just simple copy the game to my xbox, run it and then pull the icon from the xbox.<br>
`/E/UData/4d530064/SaveImage.xbx`<br>
Then we attempt to convert this xbx into a png using `xprextract2.exe`<br>
However this only supports DXT1/2/4 textures. it can not read D3DFMT_X8R8G8B8 used in lator xdks.<br>
If you would like to build a better tool that be awesome, all source xbx images can be found in the xbx folder on this github.<br>
At some point I will make a list of X8R8G8B8 xbxs.<br>
For the most part these xbx images are just dds images with custom file headers. So if you know what you are doing a tool can be made, I don't know what I'm doing though.<br>
PLEASE NOTE: DO NOT convert these xbxs into jpgs. A bunch of xbxs contain transparencies like 4C410007, 4D4A0017, 4D53005A, 58580007, 4E4D0019...<br>
Crushing them into a jpg will lose this alpha layer data.<br>
The final bit of data we need is a better title name as the imbedded one is missing or truncated a lot of the time, we pull this data from dbox<br>
by looking up the md5 hash of our extracted xbe on there website. This also adds a 3rd party cross reference to our data to make sure it was extracted correctly.<br>

# December 2023 Update
Merry Christmas I guess.<br>
So just getting back into this yet again so time to update some things<br>
as the list is now over 600 games strong it has out grown github as well as that's to much data for the table rendering feature<br>
So I'm mostly using this repo as file storage and you can view the list now on my website [Over Here](https://www.mobcat.zip/XboxIDs/).<br>

At some point I will make the tools to generate this list available, but they need a lot of clean up and scope creep has set in so I got a lot more to add.<br>
(And some tools to just remake from python to go or c idk yet)<br>

# May 2024 Update
Thanks to some members of the Xbox scene I now know about XMID (Xbox manufacturing ID)<br>
(Its a new ID to me, so I still have to learn and 1000% check to make sure I got the gen right..)<br>
This new ID data point will allow me to cross reference redump and DBox to make sure all my data is good.<br>
However, this also means I know now that, far to much of my data is bad. So there is going to be a huge purge to remove all bad data.<br>
This sucks, a lot, but it has to be done.<br>
On the bright side, we now have proper title names with our new `ENG_Name` field, so we can finally fix that stack of EA and other games that had missing title names.<br>

# File lists
So in this repo there are 2 main folders, `xbx` and `icon`.<br>
`xbx` is the raw original game icon ripped from the xbox<br>
`icon` is the xbx icon converted into a png where possible<br>
If you would like to download the whole database file, this can be done from my website, listed above.<br><br>

Because of the state of the currant tool set, not all xbx icons can be converted into pngs as later games where compiled with newer XDKs
that the tools do not account for or know how to read properly.<br>
And for these later games, the xbxs can be ripped right from the xbe itself without needing to run the game first.<br>
But yeah still need to finish making those tools...

# Notes
It appears that all CDX Menu games like MS-32973 (4D5380CD) `Halo 2 Multiplayer Map Pack (World) (En,Ja,Fr,De,Es,It,Zh,Ko)` <br>
Do not contain title icons. Imbedded or otherwise, even know these titles are compiled with the 5849 XDK, which allows for imbedded title icons.<br>
Also a lot of the time, the developer won't even change the title name from the default sample code `CDX` name. Because of this we will not be documenting CDX demos just yet.<br>
We will need to add a few more bits of info to our list like filenames so we don't end up with like 30 entries that are just `CDX` with no icons.<br><br>

XL-32788 (584C8014) `Forza Motorsport + Xbox Live Arcade (USA) (En,Ja,Fr,De,Es,It,Zh,Ko)` Is known about, however is not on this list because of the<br>
aforementioned CDX issues and that we are only documenting the default.xbe and no other xbe on the disk.<br>
(So we would be documenting the CDX menu itself, not the games it loads)<br><br>

LA-0016 (4C410010) `LucasArts Xbox Experience Volume 01 (USA)`<br>
is another CDX demo pack that is known about but not on my list.<br><br>

A dump of the 1.0 copy of `Grand Theft Auto - Vice City (Japan)` is assumed to exist, however only the `Grand Theft Auto - Vice City (Japan) (Rev 1)` is available.<br>
As soon as this 1.0 copy turns up, I will add it.<br><br>

TC-013 (5443000D) Ninja Gaiden Black (Japan) <br>
`Ninja Gaiden Black (Japan) (En,Ja).iso`<br>
Is on a list of available xbox isos to download, however it is currently unavailable for me to download. It is also unconfirmed this version is even real.<br>
I'm putting it here more as a note, not sure if it's real, but if it is, then we know about it, but it's missing like `Grand Theft Auto - Vice City (Japan)`.<br><br>

Official Xbox Magazine (OGX) cover disks<br>
These have been skipped for now, we will get back to them but they are CDX games, so I need to add that data category to the list<br>
I would also like to find a complete redump iso set, for all regions of this before I work on it.<br><br>

(45460001) Nobunaga Mahjong<br>
`SincyouMj`<br>
Is known about but missing. As this is a Japanese game, a dump may not exist yet or may be hard to find.<br><br>
