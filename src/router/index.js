import Vue from 'vue'
import Router from 'vue-router'
import UploadComponent from '@/components/UploadComponent'
import VideoConfigComponent from '@/components/VideoConfigComponent'
import DashboardHomeComponent from '@/components/DashboardHomeComponent'
import AllComponent from '@/components/videos/AllComponent'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'DashboardHomeComponent',
      component: DashboardHomeComponent
    },
    {
      path: '/video/new',
      name: 'UploadComponent',
      component: UploadComponent
    },
    {
      path: '/video',
      name: 'AllComponent',
      component: AllComponent
    },
    {
      path: '/config',
      name: 'VideoConfigComponent',
      component: VideoConfigComponent
    }
  ]
})
