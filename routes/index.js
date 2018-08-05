var express = require('express')
var router = express.Router()

var formidable = require('formidable')
var path = require('path')
var fs = require('fs')

var diskspace = require('diskspace')

const ffmpegInstaller = require('@ffmpeg-installer/ffmpeg')
const ffmpeg = require('fluent-ffmpeg')

function randomId () {
  return Math.random().toString(32).replace('0.', '').replace(',', '')
}

ffmpeg.setFfmpegPath(ffmpegInstaller.path)

var array = []

/* GET home page. */
router.get('/diskinfo', function (req, res) {
  diskspace.check('C:/', function (err ,result) {
    return res.send(result)
  })
})
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
  form.uploadDir = path.join(__dirname, '../Originals')
  // every time a file has been uploaded successfully,
  // rename it to it's orignal name
  form.on('file', function(field, file) {
     let duration = 0
     let fname = randomId ()
     let fnameWithExt = fname + '.' + file.name.split('.')[1]

    fs.renameSync(file.path , path.join(form.uploadDir, fnameWithExt))
    
    // console.log('File recieved:', file)
  
    // Call ffmpeg to take screenshots
    let thumb = ''
    var proc = new ffmpeg(path.join(form.uploadDir, fnameWithExt ))
    .on('filenames', function(filenames) {
      console.log('screenshots are ' + filenames)
      thumb = filenames
    })
    .takeScreenshots(
      {
        count: 1,
        filename: fname,
        timemarks: [15], // number of seconds
        size: '1024x576' 
      }, 
      path.join(form.uploadDir, `../Originals/thumbnails/`)  , function(err) {
        console.log('screenshots were saved', err)
      })
    //Database logic
    array.push(
      {
        title: file.name.split('.')[0], 
        thumbnail: thumb, 
        link: fname,
        original_size: file.size,
        path: form.uploadDir.split('\\').join('/'),
        duration: duration
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
    console.log('Uploaded videos:', array)
    //Save array to local storage
    response.send(array)
    //End saving
    array = []
  })

  // parse the incoming request containing the form data
  form.parse(req)
})

module.exports = router
