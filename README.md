# Katcher
A video downloader for [katch.me](https://katch.me/) which converts .ts file parts to a single .mp4 video

## Requirements
* PHP 5.6+
* Webserver: [Apache](http://www.apache.org/)
* [FFmpeg](https://www.ffmpeg.org/download.html)

## Installation
* Clone repository
* Make "ffmpeg" an executable command in terminal
* Create an apache vhost and set the document root to /public
* Run composer install

## Usage
#### [Download Ts Files]
##### Run Katcher in your browser

##### Visit any katch.me video URL
e.g. https://katch.me/GeorgiaAquarium/v/6507be0c-5f1c-39dd-9751-4dfaeb71debd

##### Copy any .ts URL and paste it in the URL field
One way to do this is to use Google Chrome Devtools (F12) to extract a .ts URL. Go to the network tab and copy the link of any .ts file.

e.g. https://d152nid216lr13.cloudfront.net/6507be0c-5f1c-39dd-9751-4dfaeb71debd/chunk_1.ts

Note: If there is nothing shown in the network tab, just refresh the page with the tab opened.

![Extract Ts](https://raw.githubusercontent.com/poycham/katcher/master/common/img/extract-ts.png "Extract Ts")

##### Determine the number of the first and last file
Modify the number in the .ts URL you copied, then visit it.

e.g. https://d152nid216lr13.cloudfront.net/6507be0c-5f1c-39dd-9751-4dfaeb71debd/chunk_75.ts (75th file)

A file exists if you can download it. If you are presented an access denied page, that file part does not exist. Most of the time, the first part is 0, and the last part is the last downloadable file. Make sure to test the next file parts if you have reached an access denied page because maybe it's not the end.

![Access Denied](https://raw.githubusercontent.com/poycham/katcher/master/common/img/access-denied.png "Access Denied")

##### Click the Download Button

This may take some time.

![Download Ts](https://raw.githubusercontent.com/poycham/katcher/master/common/img/download-ts.png "Download Ts")

#### [Convert]
After downloading the ts files, you will be redirected to the convert page. Just click the Convert button.

![Convert](https://raw.githubusercontent.com/poycham/katcher/master/common/img/convert.png "Convert")

#### [Download Mp4]
Finally, you will be redirected to the download page where your browser will automatically download the mp4 video.

![Download](https://raw.githubusercontent.com/poycham/katcher/master/common/img/download.png "Download")

## Additional Notes
#### Missing File Parts
There are times when some files will not be downloaded due to internet connection problems. You will be prompted to re-download those files before you will be able to convert.

![Redownload Missing Files](https://raw.githubusercontent.com/poycham/katcher/master/common/img/missing-files.png "Redownload Missing Files")
