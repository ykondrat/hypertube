const express       = require('express');
const morgan        = require('morgan');
const cookieParser  = require('cookie-parser');
const session       = require('express-session');
const path          = require('path');
const bodyParser    = require('body-parser');
const magnet 		= require('magnet-uri');
const magnetLink 	= require('magnet-link');
const fs 			= require('fs');
const torrentStream = require('torrent-stream');
// const WebTorrent 	= require('webtorrent');
// const client 		= new WebTorrent();

let User = {};
let Film = {};
let Torrent = {};


const app = express();

const port = process.env.PORT || 3000;

app.set('views', path.join(__dirname, 'views'));
app.set('view engine', 'jade');

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

app.use(morgan('dev'));

app.use(cookieParser());
app.use(express.static(path.join(__dirname, 'public')));
app.use(session({
    secret: 'qwerqwerqwer',
	resave: false,
	saveUninitialized: true
}));

app.post('/get_info', (req, res) => {
    let info = JSON.parse(req.body.data);
    User = info[0];
    Film = info[1];
    Torrent = info[2];
    res.send('OK');
});

app.get('/', (req, res) => {
	console.log(Film);
	console.log(Torrent);

    var videos = 'public/videos';

    var count = 0;

	if (Torrent.hash) {

		magnetLink(Torrent.url, function (err, link) {
			
			var engine = torrentStream(link, {
		        path: 'films/',
		    });

		    engine.on('ready', function () {
		    	var s;
        		engine.files.forEach(function (file) {
            		var format = file.name.split('.').pop();

		            if (format === 'mp4' || format === 'webm' || format === 'ogg' || format === 'mkv') {
		                var stream = file.createReadStream();
		                
		                if (!fs.existsSync(videos)){
		  
		                    fs.mkdirSync(videos);
		                }
		                console.log('matching movie format');
		                console.log('path is the following: ' + videos + '/' + file.name)
		                s = '' + videos + '/' + file.name;
		                s = s.substr(7);
		                stream.pipe(fs.createWriteStream(videos + '/' + file.name));
		                
		            }
        		});
        		setTimeout(function(){
        			res.render('index', { src: s });	
        		}, 5000);
    		});
		});

	} else {
		var engine = torrentStream(Torrent.url, {
        	path: 'films/',
    	});
    	
		engine.on('ready', function () {
        	engine.files.forEach(function (file) {
            	var format = file.name.split('.').pop();

            	console.log(file);
            // filesNum = filesNum + 1;
            // // console.log('filename:', file.name);
            // if (format === 'mp4' || format === 'webm' || format === 'ogg' || format === 'mkv') {
            //     var stream = file.createReadStream();
            //     if (!fs.existsSync(videos)){
            //         console.log('public/videos directory has been created');
            //         fs.mkdirSync(videos);
            //     }
            //     console.log('matching movie format');
            //     console.log('path is the following: ' + videos + '/' + file.name)
            //     stream.pipe(fs.createWriteStream(videos + '/' + file.name));
            // }
            // else {
            //     console.log('non-supported video format or other type of file');
            // }
        	})
    	})
	}

    
	// if (Torrent.hash) {
	// 	magnetLink(Torrent.url, function (err, link) {
	// 		client.add(link, { path: './' + Torrent.imdbID + Torrent.number }, function (torrent) {
	// 			torrent.on('done', function () {
	// 				console.log('torrent download finished')
	// 			});
	// 		});
	// 	});
	// } else {
	// 	client.add(Torrent.url, { path: './' + Torrent.imdbID + Torrent.number }, function (torrent) {
	// 		torrent.on('done', function () {
	// 			console.log('torrent download finished')
	// 		});
	// 	});
	// }
});

app.listen(port);

console.log('\x1b[33m%s\x1b[0m', '========= hypertube server =========');
console.log('\x1b[32m%s\x1b[0m', `server is runing on port: ${port}`);
console.log('\x1b[33m%s\x1b[0m', '=================================');
console.log('\x1b[35m%s\x1b[0m', 'Dev: Yevhen Kondratyev');
console.log('\x1b[35m%s\x1b[0m', 'Email: kondratyev.yevhen@gmail.com');

/** Film
{ number: '3',
  imdbID: 'tt0468569',
  Title: 'The Dark Knight',
  Year: '2008',
  Runtime: '152 min',
  Released: '18 Jul 2008',
  Genre: 'Action, Crime, Drama',
  Director: 'Christopher Nolan',
  Writer: 'Jonathan Nolan (screenplay), Christopher Nolan (screenplay), Christopher Nolan (story), David S. Goyer (story), Bob Kane (characters)',
  Actors: 'Christian Bale, Heath Ledger, Aaron Eckhart, Michael Caine',
  Plot: 'When the menace known as the Joker emerges from his mysterious past, he wreaks havoc and chaos on the people of Gotham, the Dark Knight must accept one of the greatest psychological and physical tests of his ability to fight injustice.',
  Language: 'English, Mandarin',
  Country: 'USA, UK',
  Awards: 'Won 2 Oscars. Another 151 wins & 154 nominations.',
  Poster: 'https://images-na.ssl-images-amazon.com/images/M/MV5BMTMxNTMwODM0NF5BMl5BanBnXkFtZTcwODAyMTk2Mw@@._V1_SX300.jpg',
  Metascore: '82',
  imdbRating: '9.0',
  Production: 'Warner Bros. Pictures/Legendary' } **/