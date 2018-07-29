
<template>
    <div class="container">
        <div v-if="loading">
            <img src="https://gifimage.net/wp-content/uploads/2017/09/ajax-loading-gif-transparent-background-4.gif" class="loading">
        </div>
        <div v-if="!loading">
            <div v-if="alert">
                <div class="alert alert-success" role="alert">
                  Link copied!
                </div>
            </div>
            <div class="well well-sm">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-9">
                        <div class="input-group">
                          <input type="text" class="form-control">
                          <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn-group">
                            <button id="list" class="btn btn-default btn-sm" v-on:click="listView">
                                <span class="glyphicon glyphicon-th-list"></span>List
                            </button>
                            <button id="grid" class="btn btn-default btn-sm" v-on:click="gridView">
                                <span class="glyphicon glyphicon-th"></span>Grid
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
                            <img class="group list-group-image" :src="'http://localhost:3000/i/'+video.thumbnail" alt="Thumbnail" width="350" />
                        </div>
                        <div class="caption">
                            <h4 class="group inner list-group-item-heading">
                                {{video.title}}
                            </h4>
                            <p class="group inner list-group-item-text">
                                <strong>Encoded:</strong>
                                <span v-if="video.encoded">
                                    Yes <br><span v-if="video.hd_size"><strong>HD Size:</strong>  {{Math.floor(video.hd_size / 1024 / 1024)}} MBs</span>
                                    <span v-if="video.sd_size"><strong>SD Size:</strong>  {{Math.floor(video.sd_size / 1024 / 1024)}} MBs</span>
                                </span>
                                <span v-if="!video.encoded">No</span><br>
                                <strong>HD Processed:</strong>
                                <span v-if="video.hd_encode === 'y'">Yes</span>
                                <span v-if="video.hd_encode === 'n'">No</span>
                                <span v-if="video.hd_encode === 'p'">Pending</span><br>
                                <strong>SD Processed:</strong>
                                <span v-if="video.sd_encode === 'y'">Yes</span>
                                <span v-if="video.sd_encode === 'n'">No</span>
                                <span v-if="video.sd_encode === 'p'">Pending</span><br>
                                <strong>Original size:</strong> {{Math.floor(video.original_size / 1024 / 1024) }} MBs
                            </p>
                            <div class="row">
                                <div class="col-md-4">
                                    <button class="btn btn-primary" v-on:click="getLink(video.link)">
                                    <i class="fa fa-clipboard" aria-hidden="true"></i> Copy link
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-danger" v-on:click="getLink(video.link)">
                                    <i class="fa fa-clipboard" aria-hidden="true"></i> Copy link
                                    </button>
                                </div>
                            </div>
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
        padding: 0px;
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
    }
    .item.list-group-item:after
    {
        clear: both;
    }
    .list-group-item-text
    {
        margin: 0 0 11px;
    }
</style>

<script>

import VideosService from '@/services/VideosService'

export default {
  data () {
    return {
      videos: [],
      loading: true,
      alert: false
    }
  },
  methods: {
    gridView () {
      console.log('Hey')
      $('#products .item').removeClass('list-group-item')
      $('#products .item').addClass('grid-group-item')
    },
    listView () {
      $('#products .item').removeClass('grid-group-item')
      $('#products .item').addClass('list-group-item')
    },
    getImgUrl (pic) {
      return require(`../../../uploads/thumbnails/${pic}`) || ''
    },
    getLink (vidId) {
      let vidLink = 'http://localhost:3000/api/video/' + vidId + '?q=480'
      navigator.clipboard.writeText(vidLink)
      this.alert = true
      setTimeout(() => {
        this.alert = false
      }, 1000)
    }
  },
  beforeRouteUpdate () {
    VideosService.getAll(this.$store.getters.getToken).then(vids => {
      this.loading = false
      this.videos = vids.data
      this.videos = JSON.parse(this.videos)
    })
  },
  mounted: function () {
    VideosService.getAll(this.$store.getters.getToken).then(vids => {
      this.loading = false
      this.videos = vids.data
      this.videos = JSON.parse(this.videos)
    })
  }
}
</script>
