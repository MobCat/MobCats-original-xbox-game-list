# MobCat's super simple XISO attacher builder for og xbox
Better more catchy name pending.<br>
[Live Demo](https://mobcat.zip/attacher)

# Info
This is not really part of the Title ID database, but it is used with the database, and parts of the website link directly to this tool. so lets make it open.<br>
But the hole tool minus the binary data is only 6KB, so it wasn't worth making a hole new repo just for this one tool.<br>

# Setup
0. Make sure your web server has PHP and SQLite support.
1. Download this `attacher` folder and place it somewhere on your web server.
2. Download the [titleIDs.db](https://mobcat.zip/XboxIDs/titleIDs.db) if you have not done already.
2. edit line 56<br>
`$db = new SQLite3('../XboxIDs/titleIDs.db');`<br>
To point to where you have the title DB saved on your web server.
3. Done, that's it.

# Usage
You can go directly to the URL and the text boxes will let you set a title id used for save and cheats<br>
or you can use `index.php?XMID=EA03801A` for eg to auto populate the title ID from and XMID stored in the database<br>
you can also use `index.php?XMID=EA03801A&download` to auto download the attacher xbe without customizing in.

# Bug?
Basically just Unicode things. It's always Unicode.
I've done my best to allow for Unicode support for the Game Title <i>however</i> it is completely up to your custom dashboard 
on how / if it wants to support this.<br>
For eg. UnleashX will display ® and ™ but, will somehow convert 戦国無双 to romanji and display Sengoku Muso<br>
The stock xbox dashboard supports Unicode title ids for save data fine btw...<br><br>

The 42 char limit for Game Titles is just a guess. The pre compiled xbe has space for at least 100 chars<br>
but most themes for custom dashboards have trouble or is just awkward to view more then 20 or so chars.<br>
I'm not sure what the actual max char limit for title names is for the stock dashboard, either from a technical point, or for a needs to pass certification point.

# TODO:
Maybe add support for the [API](https://mobcat.zip/XboxIDs/api.php?xmid=EA03801A&imgs)<br>
Then the attacher does not need it's own copy of the db, and the attacher site can have options to download box art thumbnails as well.<br>
Or it can auto build attacher, format thumbnail, and download all of it in a zip or something. Will have to ask the community what they want / need.
