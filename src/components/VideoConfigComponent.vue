<template>
  <div class="container">
    <div class="row" >
      <h2>Preview your videos</h2><br/>
      <div class="col-md-4" v-for="video in videos" :key="video.name">
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
    </div>
    <button class="btn btn-success pull-right btn-lg" v-on:click="submit">
      <span class="glyphicon glyphicon-ok"></span>
      Save
    </button>
  </div>
</template>

<script>
import VideosService from '@/services/VideosService'

export default {
  name: 'HelloWorld',
  data () {
    return {
      videos: this.$route.params.videos,
      imgsArr: []
    }
  },
  methods: {
    submit () {
      this.videos.forEach(v => {
        v.link = this.randomId()
      })
      VideosService.addVideos(this.videos).then(resp => {
        if (resp.data === 'success') {
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
      return require('../assets/thumbnails/' + pic)
    }
  },
  mounted: function () {
  }
}
</script>

<style>
  .container{
    text-align: center;
  }
</style>
