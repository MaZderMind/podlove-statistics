podlove-statistics
==================

A Podcast Log-Analyzer with neat Graphs, build with the fabulous ExtJS 4 Framework which contributes for the nice GUI and HTML5 Graphs.


Setup
-----

setting up the Analyzer is pretty simple. Call its job.php on a regular basis (depending on how often you want your statistics being updated) and open the index.php in the browser to see all the prettiness of your podcast's downloads.
For an example look at the crontab provided along with the files in this repository.

On the command line to the job.php you can specify as much log files as you want. There are a lot more things you can control using command line parameters, but a basic call could look like this

    php job.php /var/log/httpd/podcast-vhost/access_log /var/log/httpd/podcast-vhost/access_log.1

Do you see how two files are specified? The one ending with .1 is a log file, rotated away using logrotate. The Analyzer will do its best to check which parts of the file changed and what needs to be parsed and calculated.

Kinds of Statistics
-------------------

Basically the Analyzer splits the world into two pieces: Podcasts and all the rest. From a technical point of view, media-files are detected using their file extensions. All files which are not of type mp3 (or ogg, mp4, oga, m4a, ...) are considered "normal hits". This is true for rs-feeds, gifs, pngs, htmls and such.

Podcasts are measured using a metric called [http://blog.chaosradio.ccc.de/index.php/2009/08/08/podcast-statistik-selbst-gemacht-complete-downloads/ complete downloads]. Spoken simply, most modern media frameworks will open multiple connections, requesting different parts of the file using Range-Requests and do a lot of weird things to your media files, so that the only useful metric is the sum of bytes downloaded per file divided by the size of the file, calculated for each file. This metric gives you an estimation on how often some one downloaded the whole file. It does however not recognize two people each loading one half of the file - but this is about statistics, not science ;)

Normal files are just counted. How often was the file downloaded? This question is most interesting for feeds. If you don't have separate log-files for your media-files and your blog/website, you'll want to configure a white list of files being recognized (besides audio- or video-files which are always counted).

All numbers are calculated on an hourly basis and accumulated to daily numbers, so you'll see if your listeners tend to download your files in the morning, in the evening or just minutes after you updated the feed. You'll get all information sparated by file type (mp3 vs. ogg, for example), aggregated by episode, displayed by date and time and a whole lot more.

Strategy
--------

First the process tries to examine which data needs to be processed. This is important in order to avoid duplicates. All de-duplication is done on the basis of the time-stamp. The job will work on all given files sequentially.

For each file the process first seeks to the end - 100 bytes and checks all lines in this last KB worth of data. If all lines are older then the last processed row, the file is likely already processed and will be skipped.
If the file has fresh rows at the end, the process scans through it from the beginning to the end, ignoring lines older then the last processed dataset.

Recommendation: The faster your logs grow, the more often you should rotate them. It's not a problem to plow through 50 MB, but reading 500 MB line by line is another kind of task. Seeking to the end and checking for new rows is relatively cheap, so having multiple smaller files is not such a big problem as having one gigantically-huge multi-gigabyte log-file.

Contact
-------

If you have any questions just ask at github@mazdermind.de or via the Github messaging system.

Peter