# Katcher
A PHP video downloader for [katch.me](https://katch.me/) which converts .ts file parts to a single .mp4 video

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
#### [Download Ts Files]
##### Run Katcher in your browser

##### Visit any katch.me video URL
e.g. https://katch.me/GeorgiaAquarium/v/6507be0c-5f1c-39dd-9751-4dfaeb71debd

##### Copy any .ts URL and paste it in the URL field
One way to do this is to use Google Chrome Devtools (F12) to extract a .ts URL. Go to the network tab and copy the link of any .ts file. Sample link: https://d152nid216lr13.cloudfront.net/6507be0c-5f1c-39dd-9751-4dfaeb71debd/chunk_1.ts

[Extract Ts Image]

##### Determine the number of the first and last file
Just modify the number in the .ts URL you copied then visit it. Example to get the 75th file: https://d152nid216lr13.cloudfront.net/6507be0c-5f1c-39dd-9751-4dfaeb71debd/chunk_75.ts .

A file exists if it is downloadable. If you are presented an access denied page, it means that a file part does not exist. Most of the time, the first part is 0, and the last part is the last downloadable file. Make sure to test the next file parts if you have reached an access denied page because maybe it's just a skipped part.

[Access Denied Image]

##### Click the Download Button

This may take some time.

[Download Ts Image]

#### [Convert]
After downloading the ts files, you will be redirected to the convert page. Just click the Convert button.

[Convert Image]

#### [Download mp4]
Finally, you will be redirected to the download page where your browser will automatically download the mp4 video.

## Additional Notes
#### Missing File Parts
There are times when some files will not be downloaded due to internet connection problems. You will be prompted to re-download those files before you will be able to convert.

[Redownload Image]
