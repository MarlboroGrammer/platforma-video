<template>
  <div class="container">
    <h1>Disk space used</h1>
     <div :class="circleClass">
          <span>{{percentange}}%</span>
          <div class="slice">
              <div class="bar"></div>
              <div class="fill"></div>
          </div>
      </div>
      <h1>Test to see if prod works</h1>
  </div>
</template>

<script>
import IndexService from '@/services/IndexService'

export default {
  name: 'DashboardHomeComponent',
  data () {
    return {
      total: 0,
      free: 0,
      used: 0,
      percentange: '',
      circleClass: ''
    }
  },
  methods: {
  },
  mounted: function () {
    IndexService.getInfo().then(resp => {
      this.total = resp.data.total
      this.free = resp.data.free
      this.used = resp.data.used
      this.percentange = Math.floor((this.used * 100) / this.total)
      this.circleClass = `c100 p${this.percentange} big`
    })
  }
}
</script>

<style>
  @import "./circle.css";
</style>
