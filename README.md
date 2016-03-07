# Katcher
A PHP video downloader for [katch.me](https://katch.me/) which converts .ts file parts to .mp4

## Requirements
* PHP 5.6+
* Webserver: [Apache](http://www.apache.org/)
* [FFmpeg](https://www.ffmpeg.org/download.html)

## Installation
* Clone master in git
* Make ffmpeg an executable in terminal
* Create an apache vhost and set the document root to /public
* Run composer install

## Usage
##### Run Katcher in your browser
##### Visit any katch.me video URL
e.g. https://katch.me/NAThomestead/v/ec10b833-38d9-312a-a502-cc1371513d88
##### Get any .ts URL
One way this can be done is to use the Google Chrome Devtools (F12). Go to the network tab and copy the link of a .ts file.
https://d152nid216lr13.cloudfront.net/ec10b833-38d9-312a-a502-cc1371513d88/chunk_0.ts
##### Paste it in the URL field
##### Determine the number of the first and last file
Just modify the number in the .ts URL you copied. (e.g. 250 - for example https://d152nid216lr13.cloudfront.net/ec10b833-38d9-312a-a502-cc1371513d88/chunk_250.ts) For example you want to go to the 250th part just do this:
If you are presented an access denied page, that is the indication that a file part does not exist. The last part is the last downloadable file. Make sure to test the next file parts if you have reached an access denied page. There is a tendency that other file parts are just skipped.
#### Click the Download Button

After all the .ts files are downloaded, you will be redirected to the convert page. From there, if everything is alright, just click the Convert button.

Finally, you will be redirected to the download page where you can download the mp4 video.



