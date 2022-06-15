# spotifyapi
A spotify api module for displaying recently played songs on spotify. 

##What it does

###Block
The block displays the last X played songs.

###Charts
The index page will give you access to most played songs. The default is last weeks most played songs counting from Choosen day to Choosen day. The day of charts start day is done in module settings through the admin.

Archieve will give you access to past weeks charts. Monthly chart is for the last 30 days, and all time chart is from first entry untill end of week day this week.

Difference in charts is accumulative and classic view. Accumulative is counting from first entry until choosen week, year while classic is for the choosen week only.

##How to use
1) Install as a normale Xoops Module under admin->modules->install spotifyapi
2) Go to https://developer.spotify.com/dashboard/login and log in using your spotify user and password.
3) Press the create new app and fill out the form. 
4) Press the edit settings on your newly created app.
5) Leave the page open and open a new going back to the Xoops Module Spotifyapi settings.
6) In the Xoops Module spotifyapi settings copy the relocate uri and paste to the spotify app "Redirect URIs" and press "ADD"
7) In the Developer Dashboard in spotify, click "Show client secret" and copy the 2 values "Client ID" & "Client Secret" to the inputs in the Xoops Module settings.
8) Activate the block for Spotifyapi.
9) done!
