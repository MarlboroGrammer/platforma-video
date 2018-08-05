<template>
  <div id="app">
    <div class="wrapper" v-if="loggedIn">
      <!-- Sidebar Holder -->
      <nav id="sidebar">
        <div class="sidebar-header">
          <h4>PlatformA Dashboard</h4>
        </div>

        <ul class="list-unstyled components">
          <p>Hello, Admin</p>
          <li class="active">
            <router-link to="/">Home</router-link>
          </li>
          <li class="active">
            <a href="#videoSubmenu" data-toggle="collapse" aria-expanded="false">Video</a>
            <ul class="collapse list-unstyled" id="videoSubmenu">
              <li>
                <router-link to="/video">All videos</router-link>
              </li>
              <li><router-link to="/video/new">Add a new video</router-link></li>
              <li><a href="#">Reports</a></li>
            </ul>
          </li>
          <li>
            <a href="#">Contact</a>
          </li>
          <li>
            <a href="#" @click="logout">Logout</a>
          </li>
        </ul>
      </nav>

      <!-- Page Content Holder -->
      <div id="content">
        <router-view/>
      </div>
    </div>
    <div v-if="!loggedIn">
      <LoginComponent/>
    </div>
  </div>
</template>

<style>
@import "https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700";

body {
    font-family: 'Poppins', sans-serif;
    background: #fafafa;
}

p {
    font-family: 'Poppins', sans-serif;
    font-size: 1.1em;
    font-weight: 300;
    line-height: 1.7em;
    color: #999;
}

a, a:hover, a:focus {
    color: inherit;
    text-decoration: none;
    transition: all 0.3s;
}
.btn-primary {
  background-color: #7386D5;
  border: 1px solid #7386D5;
}
.btn-primary:hover {
  background-color: #6d7fcc;
  border: 1px solid #6d7fcc;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* ---------------------------------------------------
    SIDEBAR STYLE
----------------------------------------------------- */
.wrapper {
    display: flex;
    align-items: stretch;
}

#sidebar {
    min-width: 250px;
    max-width: 250px;
    background: #7386D5;
    color: #fff;
    transition: all 0.3s;
}

#sidebar.active {
    margin-left: -250px;
}

#sidebar .sidebar-header {
    padding: 20px;
    background: #6d7fcc;
}

#sidebar ul.components {
    padding: 20px 0;
    border-bottom: 1px solid #47748b;
}

#sidebar ul p {
    color: #fff;
    padding: 10px;
}

#sidebar ul li a {
    padding: 10px;
    font-size: 1.1em;
    display: block;
}
#sidebar ul li a:hover {
    color: #7386D5;
    background: #fff;
}

#sidebar ul li.active > a, a[aria-expanded="true"] {
    color: #fff;
    background: #6d7fcc;
}

a[data-toggle="collapse"] {
    position: relative;
}

a[aria-expanded="false"]::before, a[aria-expanded="true"]::before {
    content: '\e259';
    display: block;
    position: absolute;
    right: 20px;
    font-family: 'Glyphicons Halflings';
    font-size: 0.6em;
}
a[aria-expanded="true"]::before {
    content: '\e260';
}

ul ul a {
    font-size: 0.9em !important;
    padding-left: 30px !important;
    background: #6d7fcc;
}

ul.CTAs {
    padding: 20px;
}

ul.CTAs a {
    text-align: center;
    font-size: 0.9em !important;
    display: block;
    border-radius: 5px;
    margin-bottom: 5px;
}

a.download {
    background: #fff;
    color: #7386D5;
}

a.article, a.article:hover {
    background: #6d7fcc !important;
    color: #fff !important;
}

/* ---------------------------------------------------
    CONTENT STYLE
----------------------------------------------------- */
#content {
    padding: 20px;
    min-height: 100vh;
    min-width: 169vh;
    transition: all 0.3s;
}

/* ---------------------------------------------------
    MEDIAQUERIES
----------------------------------------------------- */
@media (max-width: 768px) {
    #sidebar {
        margin-left: -250px;
    }
    #sidebar.active {
        margin-left: 0;
    }
    #sidebarCollapse span {
        display: none;
    }
}

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
