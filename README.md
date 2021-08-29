ImoutoIB - Terrible imageboard software.
========================================================

But anon-san — if it's so terrible, Why should I use it?
------------

Well for starters it has:

- No support...
- No database?
- No glowies!
- Lacks so much in features that it will work on just about anything. Uwah!
- Will probably break at random — oh well!
- but most important of all!! It has soul...

I think that last one is a selling point!

![Imouto...](https://i.imgur.com/TYhmmlW.jpg "Imouto...")


Requirements
------------
If even your shared hosting can't run this, you're being scammed or you messed up. 

Anyways, use PHP 7. Might work on 5 or 8, I don't know!!! QA is for people getting paid...

No database needed.

Installation
-------------

Just put it somewhere, it *should* work.

By default the configuration assumes that you have the imageboard in name.domain/ib/ and that you use apache.

If hosted in root directory, go to includes/custom.php change $prefix_folder from '/ib' to ''. Or whatever folder you put it in.

If not using apache with included htaccess to remove main.php, add 'main.php' to $main_file.

Edit /database/boards.php to create/edit/delete boards and /database/users.php to create/edit/delete users. (I guess I could have a mod.php default username+pw to allow this to be unnecessary later?)

Upgrade
-------

\>Implying I'm gonna upgrade this soykaf software.

Just replace everything except database folder and your custom.php file. 

You didn't change any of the templates, RIGHT?!

Support
--------

Gambare!!!

I basically reinvented the wheel except instead of making it nice and round I made it a square.

License
--------
See [LICENSE.md](http://github.com/ithrts/ImoutoIB/master/README.md).
