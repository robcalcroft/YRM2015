var express = require('express'),
	app = express();

app.use(express.static('build/'))

app.listen(process.env.PORT || 3000);