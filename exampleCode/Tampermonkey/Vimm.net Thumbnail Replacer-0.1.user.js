// ==UserScript==
// @name         Vimm.net Thumbnail Replacer
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  Replaces Xbox box art thumbnails on vimms to the "correct" ones from MobCat's database. If no box art is available on page, one will be added, then replaced.
// @author       MobCat
// @match        https://vimm.net/vault/*
// @grant        GM_xmlhttpRequest
// ==/UserScript==

//Bugs
// If game is !BAD DUMP! aka bad xiso convert, or we otherwise do not have it in the db yet
// We will just get a missing image error from browser. Overwriting whatever cover vimms may of had.
// This is a temp bug, as soon as we fix our dataset, this wont be an issue. Outside of the games we wont have in the db, like hacks and homebrew.

// If the page on vimms does not supply a checksum or is otherwise broken
// We don't have any check or error handlers for this. It wont brake the page, it will just vomit error junk into console

// If the box art thumbnail is replaced with poorly scaled red text reading [MISSING]
// This means this game is in MobCat's db, however no verified cover scan of it has been found yet.
// This scaling issue is a bug with MobCat's db, not with vimms or this script.

(function() {
    'use strict';

    // Get the redump MD5 chucksum listed on vimms
    const md5Text = document.querySelector('#data-md5').textContent;

    // Chuck the MD5 Checksum at MobCat's database
    const newImageUrl = 'https://mobcat.zip/XboxIDs/title.php?redump=' + md5Text;

    // Setup conts for the new thumbnail download links we will be adding to the page
    const XBMCAnchor = document.createElement('a');
    const UnleashXAnchor = document.createElement('a');
    // Setup XMBC link
    XBMCAnchor.href = newImageUrl + '&thumbnail=xbmc';
    XBMCAnchor.textContent = 'Download XMBC Thumbnail';

    // Setup UnleashX Link
    UnleashXAnchor.href = newImageUrl + '&thumbnail=unleashx';
    UnleashXAnchor.textContent = 'Download UnleashX Thumbnail';


    // If this page on vimms does not have a cover art element at all, then we have to add it
    let imageElement = document.querySelector('img[src^="/image.php"]');

        if (!imageElement) {
            // Define the container where we want to insert the thumbnail
            const container = document.evaluate('/html/body/div[2]/div[2]/div/main/div[2]/div[2]', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;

            // Create the HTML structure that will be inject
            const divInlineBlock = document.createElement('div');
            divInlineBlock.style.display = 'inline-block';

            const table = document.createElement('table');
            table.border = "1";
            table.className = "centered";

            const tbody = document.createElement('tbody');
            const tr = document.createElement('tr');
            const td = document.createElement('td');

            // Vimms default settings for this element.
            imageElement = document.createElement('img');
            imageElement.style.display = 'block';
            imageElement.width = 244;
            imageElement.height = 340;

            td.appendChild(imageElement);
            tr.appendChild(td);
            tbody.appendChild(tr);
            table.appendChild(tbody);
            divInlineBlock.appendChild(table);

            const boxDiv = document.createElement('div');
            boxDiv.style.textAlign = 'center';
            boxDiv.style.fontSize = '10pt';
            boxDiv.textContent = 'Box';
            divInlineBlock.appendChild(boxDiv);

            container.appendChild(divInlineBlock);
            imageElement.src = newImageUrl + '&thumbnail=embedThumb';

            // Appeend the new download links to the new 'Box' we just made
            boxDiv.appendChild(XBMCAnchor);
            boxDiv.appendChild(UnleashXAnchor);
            boxDiv.insertBefore(document.createElement('br'), XBMCAnchor);
            boxDiv.insertBefore(document.createElement('br'), UnleashXAnchor);

        } else {
            // If the cover art element exists, then we simply just find and replace it
            // Replace vimms image.php?type=box&amp;id=
            // With MobCat's title.php?redump=
            document.querySelector('img[src^="/image.php"]').src = newImageUrl + '&thumbnail=embedThumb';
            const boxDiv = document.querySelector('div[style="text-align:center; font-size:10pt"]');

            // Appeend the new download links to 'Box'
            boxDiv.appendChild(XBMCAnchor);
            boxDiv.appendChild(UnleashXAnchor);
            boxDiv.insertBefore(document.createElement('br'), XBMCAnchor);
            boxDiv.insertBefore(document.createElement('br'), UnleashXAnchor);
        }


})();