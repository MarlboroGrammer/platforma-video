<template>
    <div class="container">
        <div v-if="loading" class="loading">
            <img src="https://nsm09.casimages.com/img/2018/08/19//18081902023414575215853601.gif" width="200px">
        </div>
        <div v-if="!loading">
            <div v-if="alert">
                <div class="alert alert-success" role="alert">
                  Link copied!
                </div>
            </div>
            <div v-if="deleteToggled">
                <div class="alert alert-info" role="alert">
                  Select video(s) to delete <button v-on:click="()=>{deleteToggled=false; emptyTBD()}" class="btn btn-secondary">Cancel</button>
                  <button v-if="this.toBeDeleted.length !== 0" class="btn btn-danger" @click="deleteVideos">Delete</button>
                </div>
            </div>
            <div id="products" class="row list-group">
            <div class="well well-sm">
                <div class="row">
                    <div class="col-lg-9 col-sm-5 col-xs-5">
                        <div class="input-group">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                          <input type="text" class="form-control video-search">
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-3 col-xs-3">
                        <div class="btn-group">
                            <button id="grid" class="btn btn-secondary btn-sm">
                                <span class="glyphicon glyphicon-search"></span>Search
                            </button>&nbsp;&nbsp;
                            <button id="grid" class="btn btn-danger btn-sm" v-on:click="()=>deleteToggled=true">
                                <span class="glyphicon glyphicon-trash"></span>Delete videos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
                <div v-if="videos.length == 0" class="no-vid-container">
                    <h1 class="novid">No videos uploaded.</h1>
                    <router-link to="/video/new">
                        <br/>
                      <button class="btn btn-primary">
                        Go to upload <span class="glyphicon glyphicon-cloud-upload"></span>
                      </button>
                    </router-link>
                </div>
                <!-- Pagination Start -->
                <paginate ref="paginator"
                  name="videos"
                  :list="videos"
                  :per="12"
                  :pageCount="videos.length"
                >
                  <div class="padded-box row">
                    <div class="col-md-4" v-for="(video) in paginated('videos')" :key="video.id">
                      <div class="card text-center">
                        <img class="card-img-top" :src="'https://48gekijodouga.net/i/'+video.thumbnail" alt="Thumbnail" width="330" v-if="env === 'production'" />
                        <img class="card-img-top" :src="'http://localhost:3000/i/'+video.thumbnail" alt="Thumbnail" width="330" v-if="env !== 'production'"/>
                        <div class="card-body">
                        <div class="card-text">
                            <h5 class="group inner list-group-item-heading" style="overflow:hidden;overflow-y:auto;text-overflow: ellipsis;height: 58px;">
                            {{video.title}}
                            </h5>
                            <strong>Encoded:</strong>
                            <span v-if="video.encoded">
                                <p class="glyphicon glyphicon-ok"></p>
                            </span>
                            <span v-if="!video.encoded">
                                <p class="glyphicon glyphicon-remove"></p>
                            </span>
                            <br/><strong>HD Processed:</strong>
                            <span v-if="video.hd_encode === 'y'">Yes</span> <span v-if="video.encoded"><span v-if="video.hd_size">({{Math.floor(video.hd_size / 1024 / 1024)}} MBs)</span></span>
                            <span v-if="video.hd_encode === 'n'">No</span>
                            <span v-if="video.hd_encode === 'p'">Processing</span><br/>
                            <strong>SD Processed:</strong>
                            <span v-if="video.sd_encode === 'y'">Yes</span> <span v-if="video.encoded">(<span v-if="video.sd_size">{{Math.floor(video.sd_size / 1024 / 1024)}} MBs</span>)</span>
                            <span v-if="video.sd_encode === 'n'">No</span>
                            <span v-if="video.sd_encode === 'p'">Processing</span><br/>
                            <strong>Original size:</strong> {{Math.floor(video.original_size / 1024 / 1024) }} MBs <br/>
                            <strong>Duration:</strong> {{Math.floor(video.duration / 60)}}:{{video.duration % 60}} <br/>
                            <input :id="video.link" :value="video.link" style="text-align:center;"><br/>
                            <button class="btn btn-secondary" v-on:click="getRawLink(video.link)" style="margin-top:5px;"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy Link</button>  <button class="btn btn-danger" style="margin-top:5px;"><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i> Thumbnail</button>
                            <br/>
                            <label v-if="deleteToggled" class="checkboxcontainer">
                            <input type="checkbox" :value="video.link" @change="showStuff($event)"><br/>
                            <span class="checkmark"></span>
                            </label>
                        </div>
                        </div>
                      </div>
                      <br/>
                    </div>
                  </div>
                </paginate>
                <!-- Pagination end -->
            </div>
            <center><paginate-links for="videos" :show-step-links="true" v-if="videos.length !== 0"></paginate-links></center>
        </div>
    </div>
</template>

<style>
    .no-vid-container {
      text-align: center;
    }
    .novid{
        margin-top: 15px;
        color: #68757E;
        font-size: 6em;
    }
    .loading{
        position: relative;
        text-align: center;
    }

    @media (max-width: 376px) {
      .loading{
        position: relative;
        text-align: center;
      }
    }
    .well {
      background-color: #333131;
      width: 100%;
      margin-left: auto;
      margin-right: auto;
    }
    .video-search, .video-search:focus{
      background-color: #333;
      color: #fff;
    }
    .input-group-addon{
      padding-right: 25px;
      background-color: rgb(39, 36, 36);
    }
    .glyphicon { margin-right:5px; }
    ul { -webkit-padding-start: 0px;}
    ul.paginate-links{
      display: inline-flex;
      list-style: none;
      cursor: pointer;
    }
    ul.paginate-links li{
      padding-right: 15px;
      padding-left: 15px;
      background-color: #2f2f2f;
      color: #4988b3;
    }
    ul.paginate-links li.active{
      background-color: #3b6c8f;
    }
    ul.paginate-links li.active a{
      color: #fff;
      background-color: #427aa1;
      border-color: #427aa1;
    }
    ul.paginate-links li.right-arrow, ul.paginate-links li.left-arrow{
      border: none;
    }
    ::-webkit-scrollbar {
        width: 10px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    ::-webkit-scrollbar-thumb {
        background: #888;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
.checkboxcontainer {
    display: block;
    cursor: pointer;
    font-size: 30px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    margin-bottom: -70px;
}

/* Hide the browser's default checkbox */
.checkboxcontainer input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

/* Create a custom checkbox */
.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 25px;
    width: 25px;
    background-color: #eee;
}

/* On mouse-over, add a grey background color */
.checkboxcontainer:hover input ~ .checkmark {
    background-color: #ccc;
}

/* When the checkbox is checked, add a blue background */
.checkboxcontainer input:checked ~ .checkmark {
    background-color: #427aa1;
}

/* Create the checkmark/indicator (hidden when not checked) */
.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

/* Show the checkmark when checked */
.checkboxcontainer input:checked ~ .checkmark:after {
    display: block;
}

/* Style the checkmark/indicator */
.checkboxcontainer .checkmark:after {
    left: 9px;
    top: 5px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
}
</style>

<script>

import VideosService from '@/services/VideosService'

export default {
  data () {
    return {
      videos: [],
      loading: true,
      alert: false,
      deleteToggled: false,
      env: process.env.NODE_ENV,
      toBeDeleted: [],
      paginate: ['videos']
    }
  },
  methods: {
    emptyTBD () {
      this.toBeDeleted = []
    },
    isLoading (status) { this.loading = status },
    gridView () {
      console.log('Hey')
      $('#products .item').removeClass('list-group-item')
      $('#products .item').addClass('grid-group-item')
    },
    listView () {
      $('#products .item').removeClass('grid-group-item')
      $('#products .item').addClass('list-group-item')
    },
    getRawLink (rawVideoLink) {
      var rawVidLink = document.getElementById(rawVideoLink)
      rawVidLink.select()
      document.execCommand(`Copy`)
      this.alert = true
      setTimeout(() => {
        this.alert = false
      }, 1000)
    },
    showStuff (ev) {
      if (ev.target.checked) {
        this.toBeDeleted.push(ev.target.value)
      } else {
        this.toBeDeleted.includes(ev.target.value) && this.toBeDeleted.splice(this.toBeDeleted.indexOf(ev.target.value), 1)
      }
      console.log(this.toBeDeleted)
    },
    deleteVideos () {
      this.isLoading(true)
      console.log('Token boi in deleteVideo:', this.$store.getters.getToken)
      VideosService.deleteVideos(this.toBeDeleted, this.$store.getters.getToken).then(res => {
        this.toBeDeleted.forEach(tbd => {
          var found = this.videos.find((v) => v.link === tbd)
          if (found) {
            console.log(`Found boi ${found} at ${this.videos.indexOf(found)}`)
            this.videos.splice(this.videos.indexOf(found), 1)
          }
        })
        this.isLoading(false)
        this.deleteToggled = false
        this.emptyTBD()
      }).catch(err => {
        this.isLoading(false)
        alert('Could not delete, please try again later!', err)
      })
    }
  },
  beforeRouteUpdate () {
    VideosService.getAll(this.$store.getters.getToken).then(vids => {
      this.isLoading(false)
      this.videos = vids.data
      this.videos = JSON.parse(this.videos)
    })
  },
  mounted: function () {
    setTimeout(() => {
      VideosService.getAll(this.$store.getters.getToken).then(vids => {
        this.isLoading(false)
        this.videos = vids.data
        this.videos = JSON.parse(this.videos)
      })
    }, 700)
  }
}
</script>
