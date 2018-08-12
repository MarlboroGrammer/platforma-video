<template>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <span class="glyphicon glyphicon-cloud-upload"></span>
            <h2>Select your files to be uploaded</h2>
            <div class="progress">
              <div class="progress-bar" role="progressbar"></div>
            </div>
            <button class="btn btn-lg upload-btn" type="button" v-on:click="selectFiles">Upload</button>
          </div>
        </div>
      </div>
    </div>

    <input id="upload-input" type="file" name="uploads[]" multiple="multiple" v-on:change="uploadFiles" accept=".mp4,.mkv"><br/>
  </div>
</template>

<script>
import VideosService from '@/services/VideosService'
import store from '@/store/store'

export default {
  name: 'HelloWorld',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  },
  methods: {
    selectFiles () {
      $('#upload-input').click()
      $('.progress-bar').text('0%')
      $('.progress-bar').width('0%')
    },
    uploadFiles () {
      console.log('Getting ready to upload...')
      var files = $('#upload-input').get(0).files

      if (files.length > 0) {
        // create a FormData object which will be sent as the data payload in the
        // AJAX request
        var formData = new FormData()

        // loop through all the selected files and add them to the formData object
        for (var i = 0; i < files.length; i++) {
          var file = files[i]

          // add the files to formData object for the data payload
          formData.append('uploads[]', file, file.name)
        }

        let self = this
        let url = process.env.NODE_ENV === 'production' ? 'http://48gekijodouga.net:3000/api/upload' : 
        'http://localhost:3000/api/upload'
        $.ajax({
          url: url,
          type: 'POST',
          beforeSend: function (xhr) {
            xhr.setRequestHeader('Authorization', `JWT ${store.getters.getToken}`)
            xhr.setRequestHeader('Gay header', 'Gay token test')
          },
          data: formData,
          processData: false,
          contentType: false,
          success: function (data) {
            console.log('upload successful!\n' + data)
            VideosService.addVideos(data, store.getters.getToken).then(resp => {
              console.log(resp)
              if (resp.data === 'success') {
                self.loading = false
                self.$router.push({name: 'AllComponent'})
              }
            })
          },
          xhr: function () {
            // create an XMLHttpRequest
            var xhr = new XMLHttpRequest()
            // listen to the 'progress' event
            xhr.upload.addEventListener('progress', function (evt) {
              if (evt.lengthComputable) {
                // calculate the percentage of upload completed
                var percentComplete = evt.loaded / evt.total
                percentComplete = parseInt(percentComplete * 100)
                // update the Bootstrap progress bar with the new percentage
                $('.progress-bar').text(percentComplete + '%')
                $('.progress-bar').width(percentComplete + '%')
                // once the upload reaches 100%, set the progress bar text to done
                if (percentComplete === 100) {
                  $('.progress-bar').html('Done')
                }
              }
            }, false)
            return xhr
          }
        })
      }
    }
  },
  mounted: function () {
  }
}
</script>

<style scoped>
  .container{
    text-align: center;
  }
  .btn:focus, .upload-btn:focus{
    outline: 0 !important;
  }

  html,
  body {
    height: 100%;
    background-color: #4791D2;
  }

  body {
    text-align: center;
    font-family: 'Raleway', sans-serif;
  }

  .row {
    margin-top: 80px;
  }

  .upload-btn {
    color: #ffffff;
    background-color: #F89406;
    border: none;
  }

  .upload-btn:hover,
  .upload-btn:focus,
  .upload-btn:active,
  .upload-btn.active {
    color: #ffffff;
    background-color: #FA8900;
    border: none;
  }

  h4 {
    padding-bottom: 30px;
    color: #B8BDC1;
  }

  .glyphicon {
    font-size: 5em;
    color: #9CA3A9;
  }

  h2 {
    margin-top: 15px;
    color: #68757E;
  }

  #upload-input {
    display: none;
  }

  @media (min-width: 768px) {
    .main-container {
      width: 100%;
    }
  }

  @media (min-width: 992px) {
    .container {
      width: 450px;
    }
  }

</style>
