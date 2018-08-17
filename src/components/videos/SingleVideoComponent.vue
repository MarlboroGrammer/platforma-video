<template>
  <div>
    {{links}}
  </div>
</template>
<style>
</style>

<script>
/* eslint-disable */
import VideosService from '@/services/VideosService'
import path from 'path'
export default {
  name: 'SingleVideoComponent',
  data () {
    return {
      id: this.$route.params.id,
      links: []
    }
  },
  methods: {
  },
  mounted: function () {
    VideosService.getLinks(this.$route.params.id, this.$store.getters.getToken).then(res => {
      let vid = JSON.parse(res.data)[0]
      if (vid.hd) {
        if ((vid.hd_encode !== 'y') && (vid.sd_encode !== 'y')) {
          this.links.push(`https://48gekijodouga.net/o/${this.id}.mp4`)
        }
        else if ((vid.hd_encode === 'y') && (vid.sd_encode !== 'y')) {
          this.links.push(`https://48gekijodouga.net/e/${this.id}_h264_720.mp4`)
        }
        else if ((vid.hd_encode === 'y') && (vid.sd_encode === 'y')) {
          this.links.push(`https://48gekijodouga.net/e/${this.id}_h264_720.mp4`)
          this.links.push(`https://48gekijodouga.net/e/${this.id}_h264_480.mp4`)
        }
      }
      else{
        if ((vid.sd_encode !== 'y')) {
          this.links.push(`https://48gekijodouga.net/o/${this.id}.mp4`)
        }

        if ((vid.sd_encode === 'y')) {
          this.links.push(`https://48gekijodouga.net/e/${this.id}_h264_480.mp4`)
        }
      }
    })
  }
}
</script>

