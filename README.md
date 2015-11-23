vtt2srt
=======

A php command-line utility that converts Web Video Text Tracks files (.vtt) to SubRip Subtitle File (.srt)

So, one of these days I was ready to watch some movie, without realizing that its subtitles were on Web Video Text Tracks (.vtt) format, and my player of choice wouldn't read there files. Then, I did what most people would do: googled "vtt 2 srt", "how to convert vtt files to srt files", and so on.

To my surprise, utilities that convert vtt files to srt are scarce. The one that I found was a nodejs class that does not work at all. But, after looking at its source and figuring out the logic, I wrote a simple PHP script that did the job. Eventually, I rewrote it into a class.

The executables are at bin/ folder. To use it PHP command line executable is needed. Example of usage:

```sh
cd bin
php vtt2srt.php "Dawn.of.the.Planet.of.the.Apes.2014.vtt" "Dawn.of.the.Planet.of.the.Apes.2014.srt"
```

For Windows users' convenience, a executable batch file is provided.

```sh
cd bin
vtt2srt "Dawn.of.the.Planet.of.the.Apes.2014.vtt" "Dawn.of.the.Planet.of.the.Apes.2014.srt"
```

and if you wanna use in .php
```
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__.'/projectname/Vtt2Srt.php');
$convert = new \ledat\Vtt2Srt("Dawn.of.the.Planet.of.the.Apes.2014.vtt","Dawn.of.the.Planet.of.the.Apes.2014.srt");
$convert->run();
```
Hope it can be usefull to you. Have a nice day.
