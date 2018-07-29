import Api from './Api'

const PREFIX = '/api/video'
export default {

  getAll (token) {
    let config = { headers: { 'Authorization': 'JWT ' + token } }
    return Api().get(`${PREFIX}/`, config)
  },
  addVideos (vidsArray, token) {
    let config = { headers: { 'Authorization': 'JWT ' + token } }
    return Api().post(`${PREFIX}/save`, vidsArray, config)
  }
}
