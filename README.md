# AQ Hub

AQ Hub is a API dedicated to **AdventureQuest Worlds (AQW)**. Its main goal is to serve as a **wiki-style resource for the game**, providing structured and easy-to-access information for players.

### How AQ Hub gathers data?

Basically, we run a number of scrapers across the wiki, official char pages, and in-game sources. This multi-source process is how we ensure our item data is accurate and dependable.

The flow is pretty simple; we just need the **player name** to start this process:

1. **Player Name**
2. **Find** the player's **CCID** on the AQW Charpage
3. **Call** the player's inventory API using the CCID
4. **Scrape** the data for each item from the AQWiki

![mine-player-items](/.github/images/mine-player-items.jpg)

Easy enough, right?

Basically, if I have access to all player names and all items in the game are in their inventories, I will have all the item data.

But how can I get player names easily?

To achieve this, I first needed to study how the game client connects to its servers. Using this knowledge, I built a library [dayvsonspacca/aqw-socket-client](https://github.com/dayvsonspacca/aqw-socket-client) that allows connection to an AQW server via PHP. Once connected, I receive logs detailing what players are doing on my screen. This is the BOOM! moment: I simply need to keep an account logged in to continuously capture and save the names, and with those names, I can get the items!

![mine-player-items](/.github/images/mine-players-name.jpg)