##Young Researchers in Mathematics 2015 conference

The homepage for the 2015 Young Researchers in Mathematics conference held at Oxford University

The site is [hosted here](http://yrm2015.co.uk)

---
###Installation
1. Clone the repo
`git clone https://github.com/robcalcroft/YRM2015.git`
2. Make sure you have Ruby, Ruby Gems, NodeJS and NPM installed
3. Install SASS and Compass
`gem install compass`
4. Install grunt CLI 
`[sudo] npm install -g grunt-cli`
5. Get all package dependencies
`cd YRM2015 && npm install`

###Building
To build the project run `grunt build`.

To build a debug version of the project run `grunt debug`.

**Note: By default `grunt watch` will run the debug task.**


###Running
You will need a web server running PHP5 for the API.
