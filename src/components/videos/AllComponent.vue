<template>
    <div class="container">
        <div v-if="loading">
            <img src="https://nsm09.casimages.com/img/2018/08/09//18080905543214575215842027.gif" class="loading">
        </div>
        <div v-if="!loading">
            <div v-if="alert">
                <div class="alert alert-success" role="alert">
                  Link copied!
                </div>
            </div>
            <div v-if="deleteToggled">
                <div class="alert alert-info" role="alert">
                  Select video(s) to delete <button v-on:click="()=>{deleteToggled=false; emptyTBD()}" class="btn btn-primary">Cancel</button>
                  <button v-if="this.toBeDeleted.length !== 0" class="btn btn-danger" @click="deleteVideos">Delete</button>
                </div>
            </div>
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
                            <button id="grid" class="btn btn-default btn-sm" v-on:click="()=>deleteToggled=true">
                                <span class="glyphicon glyphicon-trash"></span>Delete videos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="products" class="row list-group">
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
                  :per="6"
                  :pageCount="videos.length"
                >
                  <div class="padded-box row">
                    <div class="col-md-4" v-for="(video) in paginated('videos')" :key="video.id">
                      <div class="card text-center">
                        <img class="card-img-top" :src="'https://48gekijodouga.net/i/'+video.thumbnail" alt="Thumbnail" width="330" v-if="env === 'production'" />
                        <img class="card-img-top" :src="'http://localhost:3000/i/'+video.thumbnail" alt="Thumbnail" width="330" v-if="env !== 'production'"/>
                        <div class="card-body">
                          <p class="card-text">
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
                            <strong>HD Processed:</strong>
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
                            <button class="btn btn-primary" v-on:click="getRawLink(video.link)" style="margin-top:5px;"><i class="fa fa-clipboard" aria-hidden="true"></i> Copy Link</button>  <button class="btn btn-danger" style="margin-top:5px;"><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i> Thumbnail</button>
                            <br/>
                          </p>
                        </div>
                      </div>
                      <input type="checkbox" :value="video.link" v-if="deleteToggled" @change="showStuff($event)">
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
        font-family: 'Poppins', sans-serif;
    }
    .loading{
        position: relative;
        top: 55%;
        left: 35%;
        padding-bottom: 110px;
    }

    @media (max-width: 376px) {
      .loading{
        position: relative;
        padding-bottom: 110px;
      }
    }
    .well {
      background-color: #333131;
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
    ul.paginate-links{
      font-family: CamptonBold;
      font-size: 18px;
      display: inline-flex;
      list-style: none;
      cursor: pointer;
    }
    ul.paginate-links li{
      font-family: CamptonBold;
      font-size: 18px;
      text-align: center;
      display: inline;
      margin: 10px;
      padding: 5px;
      padding-right: 15px;
      padding-left: 15px;
      border: 2px solid rgb(115, 134, 213);
      border-radius: 15%;
    }
    ul.paginate-links li.active{
      background-color: rgb(115, 134, 213);
    }
    ul.paginate-links li.active a{
      color: white;
    }
    ul.paginate-links li.right-arrow, ul.paginate-links li.left-arrow{
      border: none;
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
