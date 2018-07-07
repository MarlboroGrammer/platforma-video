import Api from './Api'

const PREFIX = '/api/video'
export default {

  getAll () {
    return Api().get(`${PREFIX}/`)
  },
  addVideos (vidsArray) {
    return Api().post(`${PREFIX}/save`, vidsArray)
  }
}
