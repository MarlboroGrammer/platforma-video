<template>
  <div class="container">
    <div v-if="loading">
            <img src="https://gifimage.net/wp-content/uploads/2017/09/ajax-loading-gif-transparent-background-4.gif" class="loading">
        </div>
    <div class="row" >
      <div v-if="noVideos()">
        <h2 class="error">Something went <br/>wrong :(</h2>
        <router-link to="/video">
          <br/>
          <a href="#">Go back</a>
        </router-link>
      </div>
      <div v-if="!noVideos()">
        <h2>Preview your videos</h2><br/>
        <div class="col-md-4" v-for="video in videos" :key="video.name"  >
          <div class="panel panel-default">
            <div class="panel-heading">
              <span class="glyphicon glyphicon-film"></span> {{video.title}}
            </div>
            <div class="panel-body">
              <img :src="getImgUrl(video.thumbnail)" width="300">
              <hr>
              <div class="form-group">
                <label class="pull-left">Download link</label>
                <input type="text" v-model="video.download_link" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-success pull-right btn-lg" v-on:click="submit" >
          <span class="glyphicon glyphicon-ok"></span>
          Save
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import VideosService from '@/services/VideosService'

export default {
  name: 'HelloWorld',
  data () {
    return {
      videos: JSON.parse(localStorage.getItem('videos')) || this.$route.params.videos,
      imgsArr: [],
      loading: false
    }
  },
  methods: {
    submit () {
      this.videos.forEach(v => {
        v.link = this.randomId()
      })
      VideosService.addVideos(this.videos).then(resp => {
        this.loading = true
        if (resp.data === 'success') {
          this.loading = false
          this.$router.push({name: 'AllComponent'})
        }
      })
    },
    show () {
      console.log(this.videos)
      console.log('Testing random id gen: ', this.randomId())
    },
    randomId () {
      return Math.random().toString(32).replace('0.', '')
    },
    getImgUrl (pic) {
      if (!this.noVideos()) {
        return require('../assets/thumbnails/' + pic)
      }
      return '#'
    },
    noVideos () {
      return this.videos === undefined || this.videos === 'undefined' ||
        this.videos === null || this.videos.length === 0
    }
  },
  beforeCreate: function () {
    this.loading = true
  },
  mounted: function () {
    console.log('videos array: ', this.videos)
    console.log('No videos?', this.noVideos())
    console.log('LocalStorage videos', localStorage.getItem('videos'))
    this.loading = false
  }
}
</script>

<style>
  .error{
    margin-top: 55px;
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
  .container{
    text-align: center;
  }
</style>
