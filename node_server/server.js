const express       = require('express');
const morgan        = require('morgan');
const cookieParser  = require('cookie-parser');
const session       = require('express-session');
const path          = require('path');
const bodyParser    = require('body-parser');
const magnet 		= require('magnet-uri');
const magnetLink 	= require('magnet-link');
const WebTorrent 	= require('webtorrent');
const client 		= new WebTorrent();

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
	if (Torrent.hash) {
		magnetLink(Torrent.url, function (err, link) {
			client.add(link, { path: './' + Torrent.imdbID + Torrent.number }, function (torrent) {
				torrent.on('done', function () {
					console.log('torrent download finished')
				});
			});
		});
	} else {
		client.add(Torrent.url, { path: './' + Torrent.imdbID + Torrent.number }, function (torrent) {
			torrent.on('done', function () {
				console.log('torrent download finished')
			});
		});
	}
    res.render('index');
});

app.listen(port);

console.log('\x1b[33m%s\x1b[0m', '========= Matcha server =========');
console.log('\x1b[32m%s\x1b[0m', `server is runing on port: ${port}`);
console.log('\x1b[33m%s\x1b[0m', '=================================');
console.log('\x1b[35m%s\x1b[0m', 'Dev: Yevhen Kondratyev');
console.log('\x1b[35m%s\x1b[0m', 'Email: kondratyev.yevhen@gmail.com');