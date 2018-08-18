<template>
  <div id="app">
    <div v-if="loggedIn">
      <nav id="navbar-top" class="navbar fixed-top navbar-expand-md navbar-dark">
        <div class="container">
          <!-- Company name shown on mobile -->
          <router-link class="navbar-brand" to="/">
            <img :src="require('./assets/images/logo.png')" height="50" alt="Vidovii" class="navbar-brand">
          </router-link>

          <!-- Mobile menu toggle -->
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <!-- Main navigation items -->
          <div class="collapse navbar-collapse" id="mainNavbar">
              <ul class="navbar-nav mr-auto">
                  <li data-toggle="collapse" data-target=".navbar-collapse.show" class="nav-item">
                    <router-link class="nav-link" to="/">
                      <span class="glyphicon glyphicon-home"></span>&nbsp;&nbsp;Home
                    </router-link>
                  </li>
                  <li data-toggle="collapse" data-target=".navbar-collapse.show" class="nav-item">
                    <router-link class="nav-link" to="/video">
                      <span class="glyphicon glyphicon-th"></span>&nbsp;&nbsp;All videos
                    </router-link>
                  </li>
                  <li data-toggle="collapse" data-target=".navbar-collapse.show" class="nav-item">
                    <router-link class="nav-link" to="/video/new">
                      <span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;&nbsp;Upload
                    </router-link>
                  </li>
                  <li data-toggle="collapse" data-target=".navbar-collapse.show" class="nav-item">
                    <a class="nav-link" to="/logout" @click="logout()">
                      <span class="glyphicon glyphicon-log-out"></span>&nbsp;&nbsp;Logout
                    </a>
                  </li>
              </ul>
          </div>
        </div>
      </nav>
        <!-- Page Content Holder -->
      <section id="weclome" class="card-container">
        <div class="container">
          <router-view/>
        </div>
      </section>
    </div>
    <div v-if="!loggedIn">
      <LoginComponent/>
    </div>
  </div>
</template>

<style>
  @import 'assets/css/style.css';
</style>

<script>
import LoginComponent from '@/components/LoginComponent'
import store from '@/store/store'
export default {
  name: 'App',
  components: {
    'LoginComponent': LoginComponent
  },
  methods: {
    logout () {
      this.$store.dispatch('logout', null)
      window.location.href = '/'
    }
  },
  data () {
    return {
      loggedIn: this.$store.getters.isLoggedIn || false
    }
  },
  mounted: function () {
    console.log('Login state!', store.getters.isLoggedIn)
    $('#sidebarCollapse').on('click', function () {
      $('#sidebar').toggleClass('active')
    })
  }
}
</script>
