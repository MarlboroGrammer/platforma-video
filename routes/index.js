var express = require('express')
var formidable = require('formidable')
var router = express.Router()
var path = require('path')
var fs = require('fs')
let array = []

/* GET home page. */

router.get('/configure', function (req, res) {
  res.render('config')
})
router.post('/upload', function(req, res){
  // create an incoming form object
  var form = new formidable.IncomingForm()
  // specify that we want to allow the user to upload multiple files in a single request
  form.multiples = true
  // store all uploads in the /uploads directory
  form.uploadDir = path.join(__dirname, '../uploads')
  // every time a file has been uploaded successfully,
  // rename it to it's orignal name
  form.on('file', function(field, file) {
    fs.rename(file.path , path.join(form.uploadDir, file.name))
    //Database logic
    array.push({name: file.name})
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
