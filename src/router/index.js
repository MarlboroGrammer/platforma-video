import Vue from 'vue'
import Router from 'vue-router'
import UploadComponent from '@/components/UploadComponent'
import VideoConfigComponent from '@/components/VideoConfigComponent'
import DashboardHomeComponent from '@/components/DashboardHomeComponent'
import HomeComponent from '@/components/HomeComponent'
import AllComponent from '@/components/videos/AllComponent'
import LoginComponent from '@/components/LoginComponent'
import SingleVideoComponent from '@/components/videos/SingleVideoComponent'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'HomeComponent',
      component: HomeComponent
    },
    {
      path: '/dashboard',
      name: 'DashboardHomeComponent',
      component: DashboardHomeComponent
    },
    {
      path: '/dashboard/video/new',
      name: 'UploadComponent',
      component: UploadComponent
    },
    {
      path: '/dashboard/video',
      name: 'AllComponent',
      component: AllComponent
    },
    {
      path: '/dashboard/config',
      name: 'VideoConfigComponent',
      component: VideoConfigComponent
    },
    {
      path: '/dashboard/login',
      name: 'LoginComponent',
      component: LoginComponent
    },
    {
      path: '/dashboard/video/:id',
      name: 'SingleVideoComponent',
      component: SingleVideoComponent
    }
  ]
})
