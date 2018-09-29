import axios from 'axios'

export default () => {
  return axios.create({
    baseURL: process.env.NODE_ENV === 'production' ? 'https://vidovii.tn' : 'http://localhost:3000'
  })
}
