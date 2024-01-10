# MobCat's original xbox game ID list

A.K.A Game Title ID

Just trying to expand on the lists that are already out there WITH EVEN MORE DATA  
If this list is missing useful data let me know and we'll see if we can add it.  
Please note, most of this info is extracted from the games "default.xbe"  
Games that use multiple xbes like morrowind, Unreal II, a lot of compellation disks like Midway Arcade Treasures 3  
or any game that has a Title Update, so that is actually what's booting not the default xbe may produce different metadata.  
Because of this I'm not documenting anything that's not a "default.xbe" for now, It will just devolve into madness quickly.  
Like if I where also to dump and log all the save game icons, not just the title icons.  
I do need to log TUs at some point, but just logging all the default.xbe from games is going to be enough work.

# December 2023 Update
Merry Christmas I guess.<br>
So just getting back into this yet again so time to update some things<br>
as the list is now over 600 games strong it has out grown github as well as that's to much data for the table rendering feature<br>
So I'm mostly using this repo as file storage and you can view the list now on my website [Over Here](https://www.mobcat.zip/XboxIDs/).<br>

At some point I will make the tools to generate this list available, but they need a lot of clean up and scope creep has set in so I got a lot more to add.<br>
(And some tools to just remake from python to go or c idk yet)<br>

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
do not contain title icons. Imbedded or otherwise, even know these titles are compiled with the 5849 XDK, which allows for imbedded title icons.<br><br>

XL-32788 (584C8014) `Forza Motorsport + Xbox Live Arcade (USA) (En,Ja,Fr,De,Es,It,Zh,Ko)` Is known about, however is not on this list because of the<br>
aforementioned CDX issues and that we are only documenting the default.xbe and no other xbe on the disk.<br>
(So we would be documenting the CDX menu itself, not the games it loads)<br><br>

A dump of the 1.0 copy of `Grand Theft Auto - Vice City (Japan)` is assumed to exist, however only the `Grand Theft Auto - Vice City (Japan) (Rev 1)` is available.<br>
As soon as this 1.0 copy turns up, I will add it.
