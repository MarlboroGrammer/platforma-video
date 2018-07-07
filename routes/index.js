var express = require('express')
var formidable = require('formidable')
var router = express.Router()
var path = require('path')
var fs = require('fs')
const ffmpegInstaller = require('@ffmpeg-installer/ffmpeg')
const ffmpeg = require('fluent-ffmpeg');

ffmpeg.setFfmpegPath(ffmpegInstaller.path)

let array = []

/* GET home page. */

router.get('/configure', function (req, res) {
  res.render('config')
})
router.post('/upload', function(req, res){
  // create an incoming form object
  var form = new formidable.IncomingForm()
  //Specify maximum file size, in this case we need 5GB
  form.maxFileSize = 5000 * 1024 * 1024;

  // specify that we want to allow the user to upload multiple files in a single request
  form.multiples = true
  // store all uploads in the /uploads directory
  form.uploadDir = path.join(__dirname, '../uploads')
  // every time a file has been uploaded successfully,
  // rename it to it's orignal name
  form.on('file', function(field, file) {
    fs.rename(file.path , path.join(form.uploadDir, file.name))
    
    console.log('File recieved:', file)
    // Call ffmpeg to take screenshots
    let thumb = ''
    var proc = new ffmpeg(path.join(form.uploadDir, file.name))
    .on('filenames', function(filenames) {
      console.log('screenshots are ' + filenames)
      thumb = filenames
    })
    .takeScreenshots(
      {
        count: 1,
        filename: file.name.split('.')[0],
        timemarks: [ '180' ] // number of seconds
      }, 
      path.join(form.uploadDir, `../uploads/thumbnails/`)  , function(err) {
        console.log('screenshots were saved', err)
      })
    //Database logic
    array.push(
      {
        title: file.name, 
        thumbnail: thumb, 
        original_size: file.size,
        path: form.uploadDir.split('\\').join('/')
      })
  })
  // log any errors that occur
  form.on('error', function(err) {
    console.log('An error has occured: \n' + err)
  })
  //Scoping issue
  let response = res
  // once all the files have been uploaded, send a response to the client
  form.on('end', () => {
  	console.log(array)
    //Save array to local storage
    response.send(array)
    //End saving
    array = []
  })

  // parse the incoming request containing the form data
  form.parse(req)
})

module.exports = router
