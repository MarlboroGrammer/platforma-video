var express = require('express')
var db = require('../helper/db.js')

var router = express.Router()

router.get('/', function (req, res) {
  db.get().query('SELECT * FROM videos', function (err, rows) {
    if (err) 
    	 return done(err)
    res.json(JSON.stringify(rows))
  })
})

router.post('/save', function (req, res) {
  let count = 0
  let videos = Array.from(req.body)
  videos.forEach(v => {
    var keys = Object.keys(v)
    var values = keys.map(function (key) { return "'" + v[key] + "'" })
    db.get().query('INSERT INTO videos (' + keys.join(',') + ') VALUES (' + values.join(',') + ')', (err, rows) => {
    	if (err) 
    	 res.status(500).send(err)
    })
  })
  res.send('success')
})

module.exports = router