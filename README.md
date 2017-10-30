# hypertube

This is a web application that allows a user to search and watch videos.
Player is directly integrated into the site, and the videos is downloaded through the BitTorrent protocol.

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources
      node_server         contains the NodeJS server as torrent-client and video streaming

## User part
* The application allow user to register, requesting at least an email address, a username, a profile photo, a name, a first name and a password that is a little secure.
* The user able to login with his or her email and password. It also able to receive a mail to reset his password in case user forget.
* The user able to disconnect with a single click from any page on the site.
* The user able to select a preferred language, which is default to English.

![N|Solid](https://raw.githubusercontent.com/ykondrat/hypertube/master/screen/signin.png)

![N|Solid](https://raw.githubusercontent.com/ykondrat/hypertube/master/screen/signup.png)

![N|Solid](https://raw.githubusercontent.com/ykondrat/hypertube/master/screen/profile.png)

## Library part

This part present at:
 - A field of research
 - A list of miniatures

![N|Solid](https://raw.githubusercontent.com/ykondrat/hypertube/master/screen/main.png)

Research:
 - Search by typing a name of film
 - Set genre of film
 - sort and filter of result

![N|Solid](https://raw.githubusercontent.com/ykondrat/hypertube/master/screen/search.png)

## Film part

 User can chose the torrent link that he like to start watching video

![N|Solid](https://raw.githubusercontent.com/ykondrat/hypertube/master/screen/player.png)