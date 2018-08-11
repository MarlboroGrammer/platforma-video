import axios from 'axios'

export default () => {
  return axios.create({
    baseURL: 'http://48gekijodouga.net:3000/'
  })
}
