var express = require('express')
var db = require('../helper/db.js')
var path = require('path')
var tokenMW = require('./middleware/verifyToken.js')
var shelljs = require('shelljs')
var router = express.Router()

router.get('/', tokenMW.authenticate, function (req, res) {
  db.get().query('SELECT * FROM videos ORDER BY id DESC', function (err, rows) {
    if (err) 
    	 return res.send(err)
    res.json(JSON.stringify(rows))
  })
})

router.post('/save', tokenMW.authenticate, function (req, res) {
  
  let count = 0
  let videos = Array.from(req.body)
  console.log('Video API : Videos about to be saved:', videos)
  videos.forEach(v => {
    var keys = Object.keys(v)
    var values = keys.map(function (key) { return "'" + v[key] + "'" })
    db.get().query('INSERT INTO videos (' + keys.join(',') + ') VALUES (' + values.join(',') + ')', (err, rows) => {
      console.log('Err is', err)
    	if (err) 
    	 res.status(500).send(err)


    })
  })
  res.send('success')
})

router.delete('/delete', tokenMW.authenticate, function (req, res) {
  let videoLink = Array.from(req.body)
  console.log('Boiz about to be gone:', videoLink)
  let error = false
  videoLink.forEach(link => {
    console.log(link)
    db.get().query('DELETE FROM videos WHERE link = ?', [link], (err, rows) => {
      if (err){
        error = true
        return
      } else {
        shelljs.exec('../Scripts/delete_video.sh ' + link)
        
      }
    })
  })
  if(error)
    res.status(500).send(err)
  else
    res.send('success')
})

router.get('/:link', function (req, res) {
  let quality = parseInt(req.query.q)
  let link = req.params.link
  let fileToSend = link
  switch (quality) {
    case 480:
      fileToSend = link + '_h264_480.mp4'
      break
    case 720:
      fileToSend = link + '_h264_720.mp4'
      break
  }
  console.log(fileToSend)
  res.sendFile(path.join(__dirname, '../Encodes/' + fileToSend))
//  res.sendFile(path.resolve('../encoder/Encodes/' + fileToSend))
})
module.exports = router
