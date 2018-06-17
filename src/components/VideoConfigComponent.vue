<template>
  <div class="container">
    <div class="row" >
      <h2>Preview your videos</h2><br/>
      <div class="col-md-4" v-for="video in videos" :key="video.name">
        <div class="panel panel-default">
          <div class="panel-heading">
            <span class="glyphicon glyphicon-film"></span> {{video.name}}
          </div>
          <div class="panel-body">
            <img src="../assets/placeholder.png" width="300">
            <hr>
            <div class="form-group">
              <label class="pull-left">Download link</label>
              <input type="text" v-model="video.dwlink" class="form-control">
            </div>
          </div>
        </div>
      </div>
    </div>
    <button class="btn btn-success pull-right btn-lg" v-on:click="show">
      <span class="glyphicon glyphicon-ok"></span>
      Save
    </button>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      videos: this.$route.params.videos,
      imgsArr: []
    }
  },
  methods: {
    show () {
      console.log(this.videos)
      console.log('Testing random id gen: ', this.randomId())
    },
    randomId () {
      return Math.random().toString(32).replace('0.', '')
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
