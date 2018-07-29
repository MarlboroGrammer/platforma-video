import Api from './Api'

const PREFIX = '/api/users'

export default {
  login (userPayload) {
    return Api().post(`${PREFIX}/auth`, userPayload)
  }
}
