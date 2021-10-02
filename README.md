ImoutoIB - Terrible imageboard software.
========================================================

But anon-san — if it's so terrible, Why should I use it?
------------

You probably shouldn't. It's not equipped to handle huge userbases. For a small community it will do just fine.
- No support.
- No database.
- No Glowies! JS is not a requirement. Captcha and theme selection works without. IP's are hashed before storage. This does not prevent ISP's or your default server configuration from tracking access logs, use your brain before posting or use Tor.
- Supports both imageboards and textboards.
- Lacks so much in features that it will work on just about anything.
- Will probably break at random — oh well!
- It has soul..?

Requirements
------------
If even your shared hosting can't run this, you're being scammed or you messed up. 

Here's a reliable [host](https://www.hostwinds.com/7694-2.html) you can use.

Use PHP 7.3 or higher. Might work on 8, I don't know. QA is for people getting paid.

No database setup needed.

Installation
-------------

Just put it somewhere, it *should* work.

By default the configuration assumes that you have the imageboard in name.domain/ib/ and that you use apache.

If hosted in root directory, go to includes/custom.php change $prefix_folder from '/ib' to ''. Or whatever folder you put it in.

If not using apache with included htaccess to remove main.php, add 'main.php' to $main_file.

Edit /database/boards.php to create/edit/delete boards.

Defult admin user is admin:password, go to mod.php and edit this.

Any changes you want to make should be done in includes/custom.php, It will overwrite the default configuration. Consider the default config a documentation of some sort.

If you want to create different configurations per board you can go to any database/boards/board folder and create a config.php file. <?php $default_name = "Technology Enthusiast" ?> 
It will be automatically read and used when viewing or posting on that specific board.

Upgrade
-------

Like I'm gonna upgrade this soykaf software. You're on your own.

Support
--------

Gambare!!!

License
--------
See [LICENSE.md](http://github.com/ithrts/ImoutoIB/blob/master/LICENSE.md).
