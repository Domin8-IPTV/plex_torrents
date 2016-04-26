#PlexTorrent


A small app I've built to let my friends and I add our torrents to one big Plex server. 


The Plex Service file is ran on a scheduler on the local plex server. 



That service checks (periodically) for new torrents; if one is found, it goes through some logic to determin if it's a direct link or magnet. 


If the link is a magnet link it adds it directly, if it's a URL then it scrapes the site for the link and adds it. 


