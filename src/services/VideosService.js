import Api from './Api'

const PREFIX = 'video'
export default {

  getAll () {
    return Api().get(`${PREFIX}/`)
  },
  addVideos (vidsArray) {
    return Api().post(`${PREFIX}/save`, vidsArray)
  }
}
