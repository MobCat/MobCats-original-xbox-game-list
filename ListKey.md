Placeholder ish.

\*\*Title Image\*\* - Icon of the game converted from save data on the xbox. This has some issues and bugs dew to old tools...  

\*\*Title ID HEX -\*\*  The hex converted Serial Number\<br/>  
We brake the SN into 2 parts and remove the -\<br/>  
The front half, the letters are simple ascii to hex converted.\<br/>  
The back half is decimal to hex converted.\<br/>  
So MS-074 would become 4D53-004A then we drop the - and add the 0x to denote it is a hex code 0x4D53004A\<br/>

\*\*Title ID DEX -\*\*  The integer of the hex ID number, this is used for some dev things, but most of the time you can just enter the hex by prefixing it with 0x

\*\*Serial Number -\*\* This SN is given to the game by the MS cert process, it is an abbreviation of who published the game, and then how many games  
said publusher has published. So AV-081 is the 81st game Activision has published for the original xbox.  
This number does get weird however when the publishes abbreviation has already been taken, the publisher has published more then 999 games and that need a new abbreviation  
Like Vivendi Universal Games? or the developer published there own game like responDesign

\*\*Title Name\*\* - The name the developer gave the xbe. May not be respective of what the game is actually called.  
This does have Unicode to ASCII convert issues dew to old tools.  

\*\*Publisher\*\* - This is a guess based of the SN, not all publishers have been confirmed.    
You also get some weird things like carve was published by Global Star Software which is owned by Take-Two Interactive Software.  
So the game has been marked as TT, even know it wasn't really published by them, just someone they own.

\*\*Region\*\* -  The region the xbe is set to run in. If these flags are not set correctly then your xbe may not boot on your xbox. However this can be bypassed with mods of course.  
The number at the start in an int convert from the hex flag, and is intended to make searching for regions easier.\<br/>  
0 = No region set.\<br/>  
1 = USA / Canada\<br/>  
2 = Japan\<br/>  
4 = PAL\<br/>  
So 3 would be USA / Canada + Japan, 5 would be USA + PAL, 7 would be all of them and so on.\<br/>

\*\*Rating\*\* - This table is set to the US ESRB rating, a rating number is also included so a table can be built for your region.    
(0) RP - ALL is a little bit of a misnomer. RP does stand for Rating Pending, However in this case, it just means no rating was set for this xbe. So this game is not affected by xbox parental controls.

\*\*Version\*\* - This info may not be that useful or arbitrary as it was developer set. So they can set it to whatever they want. Or not set it to anything at all.  

\*\*Media Type\*\* - The media the xbe is set to be allowed to run off. Chihiro media board appears to be a default setting of later XDKs and all of these flags can be bypassed with a modded xbox or emulator.  

\*\*Init Flags\*\* - Initialization flags for the xbe. So when the xbe boots it knows to format and mount the cache drive, or not use the extra ram of a devkit.  

\*\*Entry Point\*\* - A rough idea of where the xbe is loaded to and ran from memory. It's only really meant to tell you if the xbe is compiled as a retail or debug xbe.   
  
\*\*Cert Timestamp\*\* - A timestamp of when the xbe's certificate was generated, this is not a release date, but it's close enough.   
  
\*\*MD5 Checksum\*\* - This is a unique MD5 checksum of the xbe this info was pulled from. If your xbe has the same checksum but not the same info, we have issues...
