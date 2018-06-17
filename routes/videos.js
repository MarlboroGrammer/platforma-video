var express = require('express')
var db = require('../helper/db.js')

var router = express.Router()

router.get('/', function (req, res) {
  db.get().query('SELECT * FROM videos', function (err, rows) {
    if (err) 
    	 return done(err)
    res.send(JSON.stringify(rows))
  })
})

module.exports = router