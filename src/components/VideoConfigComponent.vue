<template>
  <div class="container">
    <div v-if="loading">
            <img src="https://nsm09.casimages.com/img/2018/08/09//18080905543214575215842027.gif" class="loading">
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
              <img :src="'https://vidovii.tn:3000/i/'+ video.thumbnail" width="300">
              <hr>
              <div class="form-group">
                <label class="pull-left">Download link</label>
                <input type="text" v-model="video.download_link" class="form-control">
              </div>
            </div>
          </div>
        </div>
        <button class="btn btn-success pull-right btn-sm" v-on:click="submit" >
            <span class="glyphicon glyphicon-ok"></span>
            Save
          </button>
          <button class="btn btn-danger pull-right btn-sm" v-on:click="cancel">
            <span class="glyphicon glyphicon-trash"></span>
            Cancel
          </button>
      </div>
    </div>
  </div>
</template>

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
      this.loading = true
      VideosService.addVideos(this.videos).then(resp => {
        if (resp.data === 'success') {
          localStorage.setItem('videos', {})
          this.loading = false
          this.$router.push({name: 'AllComponent'})
        }
      })
    },
    /* getImgUrl (pic) {
      if (!this.noVideos() && !this.loading) {
        return require('~static/uploads/' + pic) || ''
      }
      return '#'
    },
    */
    noVideos () {
      return this.videos === undefined || this.videos === 'undefined' ||
        this.videos === null || this.videos.length === 0
    },
    cancel () {
      localStorage.setItem('videos', {})
      this.$router.push({name: 'AllComponent'})
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
