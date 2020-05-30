# Anime Movie Scraper API
This is an API built on laravel which fetches the anime movie and provide direct streaming links.

# API Endpoints

<b>Get all episode links </b>
/api/movieList
Type = GET
response = latest anime episodes with title, image, episode link

<b>Get total episode and all related episode links by passing any episode link</b>
/api/episodeList
method type = GET
param = episode
response = totalEpisode , episode_list 

<b>Get direct m3u8 by passing episode link<b>
/api/movieLink
Type = GET 
param = episode
response = m3u8Link

<b>Search by keyword and get all releated list</b>
/api/search
Type = GET
param = keyword
response = title,link,released

#Plans
To built complete website of anime with api on side.
