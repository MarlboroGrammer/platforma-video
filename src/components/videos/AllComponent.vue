
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
                    <div class="col-md-1"></div>
                    <div class="col-md-8">
                        <div class="input-group">
                          <input type="text" class="form-control">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="btn-group">
                            <button id="list" class="btn btn-default btn-sm" v-on:click="listView">
                                <span class="glyphicon glyphicon-th-list"></span>List
                            </button>
                            <button id="grid" class="btn btn-default btn-sm" v-on:click="gridView">
                                <span class="glyphicon glyphicon-th"></span>Grid
                            </button>
                            <button id="grid" class="btn btn-default btn-sm" v-on:click="()=>deleteToggled=true">
                                <span class="glyphicon glyphicon-trash"></span>Delete videos
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="products" class="row list-group">
                <div v-if="videos.length == 0">
                    <h1 class="novid">No videos uploaded.</h1>
                    <router-link to="/video/new">
                        <br/>
                      <button class="btn btn-primary">
                        Go to upload <span class="glyphicon glyphicon-cloud-upload"></span>
                      </button>
                    </router-link>
                </div>
                <div class="item  ol-xs-4 col-lg-4 grid-group-item" v-for="video in videos" :key="video.id">
                    <div class="thumbnail">
                        <div v-if="video.thumbnail">
                            <img class="group list-group-image" :src="'http://48gejikodouga:3000/i/'+video.thumbnail" alt="Thumbnail" width="350" v-if="process.env.NODE_ENV === 'production'" />
                            <img class="group list-group-image" :src="'http://localhost:3000/i/'+video.thumbnail" alt="Thumbnail" width="350" v-if="process.env.NODE_ENV !== 'production'"/>
                        </div>
                        <div class="caption">
                            <h4 class="group inner list-group-item-heading" style="overflow:hidden;overflow-y:auto;text-overflow: ellipsis;height: 58px;">
                                {{video.title}}
                            </h4>
                            <p class="group inner list-group-item-text">
                                <strong>Encoded:</strong>
                                <span v-if="video.encoded">
                                    <p class="glyphicon glyphicon-ok"></p>
                                </span>
                                <span v-if="!video.encoded">
                                    <p class="glyphicon glyphicon-remove"></p>
                                </span>
                                <br/>
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
                            </p>
                            <input type="checkbox" :value="video.link" v-if="deleteToggled" @change="showStuff($event)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .novid{
        margin-top: 15px;
        margin-left: 150px;
        color: #68757E;
        font-size: 6em;
        font-family: 'Poppins', sans-serif;
    }
    .loading{
        position: absolute;
        top: 40%;
        left: 50%;
    }
    .input-group-addon{
        background-color: none;
    }
    .glyphicon { margin-right:5px; }
    .thumbnail
    {
        margin-bottom: 20px;
        -webkit-border-radius: 0px;
        -moz-border-radius: 0px;
        border-radius: 0px;
    }

    .item.list-group-item
    {
        float: none;
        width: 100%;
        background-color: #fff;
        margin-bottom: 10px;
    }
    .item.list-group-item:nth-of-type(odd):hover,.item.list-group-item:hover
    {
        background: #428bca;
    }

    .item.list-group-item .list-group-image
    {
        margin-right: 10px;
    }
    .item.list-group-item .thumbnail
    {
        margin-bottom: 0px;
    }
    .item.list-group-item .caption
    {
        padding: 9px 9px 0px 9px;
    }
    .item.list-group-item:nth-of-type(odd)
    {
        background: #eeeeee;
    }

    .item.list-group-item:before, .item.list-group-item:after
    {
        display: table;
        content: " ";
    }

    .item.list-group-item img
    {
        float: left;
        width: 350px;
        height: 200px;
    }
    .item.list-group-item:after
    {
        clear: both;
    }
    .list-group-item-text
    {
        margin: 0 0 11px;
    }
    .grid-group-item{
        min-height: 495px;
    }
    .grid-group-item .links-pad{
        padding: 8%;
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
      toBeDeleted: []
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
