<template>
  <div class="accordion shadow-sm mb-3 sticky-top" style="top: 75px; z-index:2" id="accordionExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button bg-dark py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <strong class="text-muted">Akses</strong>
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
        <div class="accordion-body p-2">
          <div class="row align-items-center">
            <form @submit.prevent="addMember" class="col-6">
              <label>Tambah</label>
              <div class="row">
                <div class="col">
                  <input :class="'form-control form-control-sm ' + inputBorder" type="text" v-model="userAdd" placeholder="Masukkan Username">
                </div>
                <div class="col-2 ps-0">
                  <button class="btn btn-sm btn-success w-100">
                    <i class="ti ti-user-plus"></i>
                  </button>
                </div>
              </div>
            </form>
            <form class="col-6 align-items-center">
              <div><label>Cari</label> <img src="storage/images/spinner.svg" alt="" width="10" v-show="isSearching"></div>
              <div>
                <input class="form-control form-control-sm" type="text" v-model="userFind" placeholder="Masukkan Username" @input="findMember">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body p-1">
      <table class="table table-hover table-striped">
        <thead class="text-center bg-dark">
          <th class="border border-light py-1">Username</th>
          <th class="border border-light py-1">Voucher</th>
          <th class="border border-light py-1">Klaim</th>
          <th class="border border-light py-1">Diproses</th>
        </thead>
        <tbody>
          <tr v-for="member in memberList" :key="member.id">
            <td class="border border-light text-left py-1">{{ member.username }}</td>
            <td class="border border-light text-left py-1">{{ member.voucher }}</td>
            <td class="border border-light text-center py-1">
              <span v-if="member.klaim == 0">Belum</span>
              <span v-else>Sudah</span>
            </td>
            <td class="border border-light text-center py-1">
              <span v-if="member.proses == 0">Belum</span>
              <span v-else>Sudah</span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      userAdd: '',
      userFind: '',
      inputBorder: 'null',
      isSearching: false,
      memberList: [],
    }
  },

  methods: {
    Initiate() {
      this.clearData()
      this.listAllMember()
    },

    clearData() {
      this.userAdd = ''
      this.userFind = ''
      this.inputBorder = 'null'
      this.isSearching = false
      this.memberList = []
    },

    listAllMember() {
      axios
      .get('/get_members')
      .then((result) => {
        this.memberList = result.data
      })
    },

    addMember() {
      if (this.userAdd == '') {
        this.inputBorder = 'border border-danger'
      } else {
        this.inputBorder = 'null'
        let data = {username: this.userAdd}
        axios
        .post('/add_member', {data: JSON.stringify(data)})
        .then(() => {
          this.clearData()
          this.listAllMember()
        })
      }
    },

    findMember() {
      this.isSearching = true
      setTimeout(() => {
        let data = { username: this.userFind }
        axios
        .post('/find_member', {data : JSON.stringify(data)})
        .then((result) => {
          this.memberList = []
          this.memberList = result.data
        })
        .finally(() => {
          this.isSearching = false
        })
      }, 200)
    },
  },

  mounted() {
    this.Initiate()
  },
}
</script>