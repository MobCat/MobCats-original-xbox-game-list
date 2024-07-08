# how to <s>steal</s> rehost this project

<ol>
<li>Clone / download this repo</li>
<li>Download the DB file from this repo or the most up to date ver from here https://mobcat.zip/XboxIDs/titleIDs.db</li>
<li>Make sure the contents of <code>website</code> are in the root of your web host</li>
<li>Upload the <code>cover</code>, <code>disc</code>,<code>icon</code>, <code>thumbnail</code> and <code>xbx</code> folders to your CDN</li>
<sub>Make sure your CDN allows direct URL addresses for eg https://Your.CDN/XboxIDs/thumbnail/MS/MS10003W.jpg</sub>
<li>Edit <code>$CNDPrefix</code> and <code>$CNDDirLst</code> cars in <code>config.php</code> to point to your URLs.<br>
<code>$CNDPrefix</code> must have a <code>/</code> at the end of the string</li>
</ol> 
