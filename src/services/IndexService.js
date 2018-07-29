import Api from './Api'

const PREFIX = '/api'

export default {
  getInfo () {
    return Api().get(`${PREFIX}/diskinfo`)
  }
}
