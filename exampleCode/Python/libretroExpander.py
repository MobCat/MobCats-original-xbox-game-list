#!/env/Python3.10.4
#/MobCat (2024)

# This example will download MobCat's Title ID cover scan thumbnail image set
# and then convert and rename them in accordance to the libretro schema as kinda detailed here
# http://thumbnailpacks.libretro.com/Microsoft%20-%20Xbox/Named_Boxarts/resize.sh
# Full image set can be viewed from this folder
# http://thumbnailpacks.libretro.com/Microsoft%20-%20Xbox/Named_Boxarts/
# I wanted to actually download this image set, clean it up, and then add to it from MobCat's
# image set, however, after blacklisting over 70% of this image set to be cleaned and fixed
# I figured it would be better for a simple example script, that we just build a new set from scratch
# rather then trying to fix and cludge an existing set.

import os
import requests        # pip install requests==2.28.0
from PIL import Image  # pip install Pillow==10.4.0
from io import BytesIO
import sqlite3

#hide cursor, it makes the download bar look ugly.
print("\033[?25l", end="")

# Check if we have the db downloaded
if not os.path.exists('titleIDs.db'):
    # Download it if we don't
    print('Downloading titleIDs.db...')
    url = 'https://mobcat.zip/XboxIDs/titleIDs.db'
    response = requests.get(url, stream=True)
    total_size = int(response.headers.get('content-length', 0))
    progress_bar_size = 50

    with open('titleIDs.db', 'wb') as f:
        for data in response.iter_content(1024): #1 Kibibyte chunk size
            f.write(data)
            progress = int(f.tell() * progress_bar_size / total_size)
            print(f"\r[{'█' * progress}{' ' * (progress_bar_size - progress)}] {f.tell()/1024/1024:.2f} MB / {f.tell() * 100 / total_size:.2f}% ", end="")
    print("\nDatabase download complete.\n")
        
# Check if our download folder excists.
if not os.path.exists('Named_Boxarts'):
    # If it don't, make it so.
    os.makedirs('Named_Boxarts')

# Define where we are going to download cover thumbnails from.
# Same variable schema as the api and config.php
#CNDPrefix = "http://192.168.1.99/XboxIDs/CoverScans/"; # 1297
CNDPrefix = "https://raw.githubusercontent.com/MobCat/MobCats-original-xbox-game-list/main/";

# Load title ID db
conn = sqlite3.connect('titleIDs.db')
conn.row_factory = sqlite3.Row
cursor = conn.cursor()

# Get title ID info from the database for all titles that have cover scans uploaded
# We are ASSuming that if there is any info in Cover_Stats then there is a default thumbnail for us to download
# We should be checking the CDN Folders API, but this is faster and works 99.9% of the time.
cursor.execute("SELECT `XMID`, `Filename`, `Cover_Stats` FROM `TitleIDs` WHERE `Cover_Stats` IS NOT NULL")
thumbLst = cursor.fetchall()
cnt = 1
cntMax = len(thumbLst)  
for thumb in thumbLst:
    # If the thumbnail does not excist in our download folder, download it
    if not os.path.exists(f"Named_Boxarts/{thumb['Filename']}.png"):
        print(f"[{cnt}/{cntMax}]Download {thumb['XMID']}: {thumb['Filename']}.png")
        # Download the thumbnail and rename it to the redump iso filename
        response = requests.get(f"{CNDPrefix}thumbnail/{thumb['XMID'][:2]}/{thumb['XMID']}.jpg")
        # Convert jpg to png and resize it to 320x448, in accordance with resize.sh
        # This convert is a little bit of a cludge, but as we are only 20px off, it's not to bad.. but not grate either..
        img = Image.open(BytesIO(response.content))
        img_resized = img.resize((320, 448))
        img_resized.save(f"Named_Boxarts/{thumb['Filename']}.png", "PNG")

        cnt += 1