import axios from 'axios'

export default () => {
  return axios.create({
    baseURL: process.env.NODE_ENV === 'production' ? 'https://48gekijodouga.net' : 'http://localhost:3000'
  })
}
