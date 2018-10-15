<template>
  <div>
    {{links}}
    <div id="rmpPlayer"></div>
  </div>
</template>
<style>
  #rmpPlayer {
    height: 100%;
    width: 100%;
    background-color: #000;
    overflow: hidden;
    margin: 0;
    padding: 0;
  }
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
    let prefix = process.env.NODE_ENV === 'production' ? 'https://vidovii.tn' : 'http://localhost:3000'
    VideosService.getLinks(this.$route.params.id, this.$store.getters.getToken).then(res => {
      let vid = JSON.parse(res.data)[0]
      if (vid.hd) {
        if ((vid.hd_encode !== 'y') && (vid.sd_encode !== 'y')) {
          this.links.push(`${prefix}/o/${this.id}.mp4`)
        }
        else if ((vid.hd_encode === 'y') && (vid.sd_encode !== 'y')) {
          this.links.push(`${prefix}/e/${this.id}_h264_720.mp4`)
        }
        else if ((vid.hd_encode === 'y') && (vid.sd_encode === 'y')) {
          this.links.push(`${prefix}/e/${this.id}_h264_720.mp4`)
          this.links.push(`${prefix}/e/${this.id}_h264_480.mp4`)
        }
      }
      else{
        if ((vid.sd_encode !== 'y')) {
          this.links.push(`${prefix}/o/${this.id}.mp4`)
        }

        if ((vid.sd_encode === 'y')) {
          this.links.push(`${prefix}/e/${this.id}_h264_480.mp4`)
        }
      }
          var src = {
            mp4: [
              this.links
            ]
          };
          var settings = {
            licenseKey: 'Kl8lOXN6ZTlzczI3az9yb201ZGFzaXMzMGRiMEElXyo=',
            // Here are our iframe settings
            iframeMode: true,
            iframeAllowed: true,
            sharing: false,
            sharingCode: '<iframe width="640" height="266" src="link" style="border:none;" allowfullscreen></iframe>',
            skin: 's1',
            src: src,
            preload: 'auto',
            poster: 'https://your-poster-url.jpg', //Edit here
            // ad-blocker detection settings
            adBlockerDetection: true,
            adBlockerDetectedPreventPlayback: true,
            adBlockerDetectedMessage: '動画を再生するために、Adblockを無効にしてください \n Please disable your Adblocker to view this video ',
            ads: true,
            //Skin
            skinBackgroundColor: 'rgba(74, 170, 166, 0.75)',
            skinButtonColor: 'FFFFFF',
            skinAccentColor: '000000'
          };
          var elementID = 'rmpPlayer';
          var rmp = new RadiantMP(elementID);
          rmp.init(settings);
    })
  }
}
</script>

