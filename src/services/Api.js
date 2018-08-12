import axios from 'axios'

export default () => {
  return axios.create({
    baseURL: process.env.NODE_ENV === 'production' ? 'http://48gekijodouga.net:3000' : 'http://localhost:3000'
  })
}
