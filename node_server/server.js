const express       = require('express');
const morgan        = require('morgan');
const cookieParser  = require('cookie-parser');
const session       = require('express-session');
const path          = require('path');
const bodyParser    = require('body-parser');
const magnetLink 	= require('magnet-link');
const fs 			= require('fs');
const torrentStream = require('torrent-stream');
const schedule      = require('node-schedule');
const requestify    = require('requestify');

let User = {};
let Film = {};
let Torrent = {};
let Files = [];
let streamFile = '';
let defaultTitle = '';

const app = express();

const port = process.env.PORT || 8000;

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

schedule.scheduleJob('0 0 * * *', () => {
    requestify.get('http://localhost:8080/hypertube/web/function/gamno')
    .then(function(response) {
        response.getBody();
    });
});

app.post('/get_info', (req, res) => {
    let info = JSON.parse(req.body.data);
    var videos = 'public/videos/';
    User = info[0];
    Film = info[1];
    Torrent = info[2];

    if (!fs.existsSync('public/films/' + Torrent.imdbID + Torrent.number)) {
        if (Torrent.hash) {
            magnetLink(Torrent.url, (err, link) => {
                var engine = torrentStream(link, {
                    path: 'public/films/' + Torrent.imdbID + Torrent.number,
                });

                engine.on('ready', () => {
                    engine.files.forEach((file) => {
                        Files.push(file.name);
                        var format = file.name.split('.').pop();

                        if (format === 'mp4' || format === 'webm' || format === 'ogg' || format === 'mkv') {
                            let stream = file.createReadStream();

                            if (!fs.existsSync(videos)) {
                                fs.mkdirSync(videos);
                            }
                            streamFile = '' + videos + '/' + file.name;
                            streamFile = streamFile.substr(7);
                            stream.pipe(fs.createWriteStream(videos + '/' + file.name));
                        }
                        if (format === 'vtt') {
                            let stream = file.createReadStream();

                            if (!fs.existsSync(videos)) {
                                fs.mkdirSync(videos);
                            }
                            defaultTitle = '' + videos + '/' + file.name;
                            defaultTitle = defaultTitle.substr(7);
                            stream.pipe(fs.createWriteStream(videos + '/' + file.name));
                        }
                    });
                });
                engine.on('idle', () => {
                    fs.unlinkSync('public/' + streamFile);
                    fs.unlinkSync('public/' + defaultTitle);
                });
            });
        } else {
            var engine = torrentStream(Torrent.url, {
                path: 'public/films/' + Torrent.imdbID + Torrent.number,
            });

            engine.on('ready', () => {
                engine.files.forEach((file) => {
                    Files.push(file.name);
                    var format = file.name.split('.').pop();

                    if (format === 'mp4' || format === 'webm' || format === 'ogg' || format === 'mkv') {
                        var stream = file.createReadStream();
                        if (!fs.existsSync(videos)){
                            fs.mkdirSync(videos);
                        }
                        streamFile = '' + videos + '/' + file.name;
                        streamFile = streamFile.substr(7);
                        stream.pipe(fs.createWriteStream(videos + '/' + file.name));
                    }
                    if (format === 'vtt') {
                        let stream = file.createReadStream();

                        if (!fs.existsSync(videos)) {
                            fs.mkdirSync(videos);
                        }
                        defaultTitle = '' + videos + '/' + file.name;
                        defaultTitle = defaultTitle.substr(7);
                        stream.pipe(fs.createWriteStream(videos + '/' + file.name));
                    }
                });
            });

            engine.on('idle', () => {
                console.log('done');
                fs.unlinkSync('public/' + streamFile);
                fs.unlinkSync('public/' + defaultTitle);
            });
        }
    } else {
        fs.readdir('public/films/' + Torrent.imdbID + Torrent.number, (err, files) => {
            var dir = 'public/films/' + Torrent.imdbID + Torrent.number + '/' + files;
            fs.readdir(dir, (err, files) => {
                files.forEach(file => {
                    var format = file.split('.').pop();
                    Files.push(file);

                    if (format === 'mp4' || format === 'webm' || format === 'ogg' || format === 'mkv') {
                        streamFile = dir + '/' + file;
                        streamFile = streamFile.substr(7);
                    }
                    if (format === 'vtt') {
                        defaultTitle = dir + '/' + file;
                        defaultTitle = defaultTitle.substr(7);
                    }
                });
            });
        });
    }
    res.send('OK');
});

app.get('/', (req, res) => {
    setTimeout(()=> {
        res.render('index', { film: Film, src: streamFile, subtitles: defaultTitle });
    }, 5000);
});

app.listen(port);

console.log('\x1b[33m%s\x1b[0m', '========= hypertube server =========');
console.log('\x1b[32m%s\x1b[0m', `server is runing on port: ${port}`);
console.log('\x1b[33m%s\x1b[0m', '=================================');
console.log('\x1b[35m%s\x1b[0m', 'Dev: ykondrat, sandruse, mvorona, egaragul');
console.log('\x1b[35m%s\x1b[0m', `Jolly Roger's Team`);
