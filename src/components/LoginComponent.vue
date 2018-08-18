<template>
  <div class="login-form">
  <div class="main-div">
    <div class="panel">
      <h2>Admin Login</h2>
      <p>Please enter your email and password</p>
    </div>
    <div class="alert alert-danger" v-if="error">
      Please verify your credentials
    </div>
    <form id="Login">
      <div class="form-group">
        <input type="email" class="form-control" id="inputEmail" placeholder="Email Address" v-model="email">
      </div>
      <div class="form-group">
        <input type="password" class="form-control" id="inputPassword" placeholder="Password" v-model="password">
      </div>
      <div class="forgot">
        <a href="reset.html">Forgot password?</a>
      </div>
      <button type="submit" class="btn btn-primary" v-on:click="login">Login</button>
    </form>
  </div>
</div>
</template>
<style>

.form-heading { color:#fff; font-size:23px;}
.panel h2{ color:#444444; font-size:18px; margin:0 0 8px 0;}
.panel p { color:#777777; font-size:14px; margin-bottom:30px; line-height:24px;}
.login-form .form-control {
  background: #f7f7f7 none repeat scroll 0 0;
  border: 1px solid #d4d4d4;
  border-radius: 4px;
  font-size: 14px;
  height: 50px;
  line-height: 50px;
}
@media screen and (min-width: 800px) {
  .main-div {
  background: #ffffff none repeat scroll 0 0;
  border-radius: 2px;
  margin: 10px auto 30px;
  max-width: 38%;
  padding: 50px 70px 70px 71px;
}
}
@media screen and (max-width: 415px) {
  .main-div {
    background: #ffffff none repeat scroll 0 0;
    border-radius: 2px;
    margin: 10px auto 30px;
    width: inherit;
    padding: 50px 70px 70px 71px;
  }
}

.login-form .form-group {
  margin-bottom:10px;
}
.login-form{ text-align:center;}
.forgot a {
  color: #777777;
  font-size: 14px;
  text-decoration: underline;
}
.login-form  .btn.btn-primary {
  background: #f0ad4e none repeat scroll 0 0;
  border-color: #f0ad4e;
  color: #ffffff;
  font-size: 14px;
  width: 100%;
  height: 50px;
  line-height: 50px;
  padding: 0;
}
.forgot {
  text-align: left; margin-bottom:30px;
}
.botto-text {
  color: #ffffff;
  font-size: 14px;
  margin: auto;
}
.login-form .btn.btn-primary.reset {
  background: #ff9900 none repeat scroll 0 0;
}
.back { text-align: left; margin-top:10px;}
.back a {color: #444444; font-size: 13px;text-decoration: none;}

</style>

<script>
import AuthService from '@/services/AuthService'
import store from '@/store/store'

export default {
  name: 'Login',
  data () {
    return {
      email: '',
      password: '',
      error: false,
      errors: []
    }
  },
  methods: {
    login (event) {
      this.error = false
      event.preventDefault()
      AuthService.login({email: this.email, password: this.password}).then(resp => {
        store.dispatch('setToken', resp.data.token)
        store.dispatch('setUser', resp.data.user)
        window.location.href = '/'
      }).catch(err => {
        this.error = true
        this.erros.push(err)
      })
    }
  },
  mounted: () => console.log(AuthService)
}
</script>
