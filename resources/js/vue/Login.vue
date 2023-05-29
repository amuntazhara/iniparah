<template>
  <div class="row bg-dark align-items-center justify-content-center m-0" style="height: 100vh">
    <div class="col-11 col-sm-4 bg-light p-1">
      <Transition>
        <img src="images/spinner.svg" alt="" width="25" class="float-end" v-show="isLoading">
      </Transition>
      <div class="px-5 py-4">
        <h4 class="text-center text-primary mb-3"><strong>Login Panel</strong></h4>
        <form @submit.prevent="Validate">
          <div class="input-group mb-3 shadow-sm">
            <span class="input-group-text border-0" id="basic-addon1"><i class="ti ti-user"></i></span>
            <input type="text" :class="'form-control form-control-sm ' + usrBorder" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" v-model="username" ref="username">
          </div>
          <div class="input-group mb-3 shadow-sm">
            <span class="input-group-text border-0" id="basic-addon2"><i class="ti ti-lock"></i></span>
            <input type="password" :class="'form-control form-control-sm ' + pswBorder" placeholder="Password" aria-label="Password" aria-describedby="basic-addon2" v-model="password" ref="password">
          </div>
          <Transition>
            <div v-show="loginMessage" class="alert alert-danger py-1 text-center"><small>{{ loginMessage }}</small></div>
          </Transition>
          <Transition>
            <div v-show="loginSuccess" class="alert alert-success py-1 text-center"><small>Mengalihkan Anda ke Panel...</small></div>
          </Transition>
          <button class="btn btn-success w-100 shadow-sm" :disabled="isLoading">
            <strong>LOGIN</strong>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      username: '',
      password: '',
      usrBorder: '',
      pswBorder: '',
      isEmpty: false,
      isLoading: false,
      loginMessage: '',
      loginSuccess: false,
    }
  },

  methods: {
    Validate() {
      if (this.username == '')
        this.usrBorder = 'border-danger',
        this.isEmpty = true
      else
        this.usrBorder = ''
        this.isEmpty = false

      if (this.password == '')
        this.pswBorder = 'border-danger',
        this.isEmpty = true
      else
        this.pswBorder = '',
        this.isEmpty = false

      if (this.isEmpty == false) 
        this.Login()
    },

    Login() {
      this.isLoading = true
      let data = {username: this.username, password: this.password}
      axios
      .post('/logging_in', {data: JSON.stringify(data)})
      .then((result) => {
        this.loginSuccess = true
      })
      .catch((error) => {
        this.usrBorder = 'border-danger',
        this.isEmpty = true,
        this.$refs.username.focus(),
        this.loginMessage = error.response.data

        this.loginSuccess = false
      })
      .finally(() => {
        this.isLoading = false
        setTimeout(() => {
          this.usrBorder = ''
          this.pswBorder = ''
          this.isEmpty = false
          this.loginMessage = ''
          if (this.loginSuccess == true)
            window.location.href = '/wheelpanel'
        }, 2500);
      })
    },
  },

  mounted() {
    this.$refs.username.focus()
  },
}
</script>