<template>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <!-- Sidebar Start -->
    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="/wheelpanel" class="text-nowrap logo-img"><strong>PANEL WHEEL</strong></a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            <li class="sidebar-item">
              <div class="sidebar-link" style="cursor: pointer" @click="Navigate('Member')" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Member</span>
              </div>
              <div class="sidebar-link" style="cursor: pointer" @click="Navigate('Hadiah')" aria-expanded="false">
                <span>
                  <i class="ti ti-gift"></i>
                </span>
                <span class="hide-menu">Hadiah</span>
              </div>
              <div class="sidebar-link" style="cursor: pointer" @click="Navigate('Pengaturan')" aria-expanded="false">
                <span>
                  <i class="ti ti-settings"></i>
                </span>
                <span class="hide-menu">Pengaturan Web</span>
              </div>
              <hr>
              <div class="sidebar-link text-danger" style="cursor: pointer" @click="Logout" aria-expanded="false">
                <span>
                  <i class="ti ti-logout"></i>
                </span>
                <strong class="hide-menu">LOGOUT</strong>
              </div>
            </li>
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->
    <!--  Main wrapper -->
    <div class="body-wrapper">
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>
          </ul>
          <h5>
            {{ menu }}
          </h5>
          <Header />
        </nav>
      </header>
      <div class="container-fluid">
        <Members  v-if="menu == 'Member'" />
        <Gifts  v-if="menu == 'Hadiah'" />
        <Settings  v-if="menu == 'Pengaturan'" />
      </div>
    </div>
  </div>
</template>

<script>
import Header from '@/vue/Header.vue'
import Members from '@/vue/Members.vue'
import Gifts from '@/vue/Gifts.vue'
import Settings from '@/vue/Settings.vue'
export default {
  components: { Header, Members, Gifts, Settings },
  data() {
    return {
      menu: ''
    }
  },

  methods: {
    Initiate() {

    },

    Navigate(menu) {
      this.menu = menu
    },

    Logout() {
      window.location.href = '/logout_panel'
    },
  },

  mounted() {
    this.menu = 'Member'
    this.Initiate()
  },
}
</script>