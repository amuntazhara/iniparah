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
          <div class="row">
            <form @submit.prevent="addMember" class="col-6">
              <label class="mb-1">Tambah Member</label>
              <div class="row align-items-center">
                <div class="col-5">
                  <input :class="'form-control form-control-sm ' + inputBorderUser" type="text" v-model="userAdd" placeholder="Username">
                </div>
                <div class="col-5">
                  <input :class="'form-control form-control-sm ' + inputBorderVoucher" type="text" v-model="voucherAdd" placeholder="Voucher" @focus="createVoucher">
                </div>
                <div class="col-2">
                  <button class="btn btn-sm btn-success w-100">
                    <i class="ti ti-user-plus"></i>
                  </button>
                </div>
              </div>
              <!-- <label>Tambah</label>
              <div class="row">
                <div class="col">
                  <input :class="'form-control form-control-sm ' + inputBorder" type="text" v-model="userAdd" placeholder="Masukkan Username">
                </div>
                <div class="col-2 ps-0">
                  <button class="btn btn-sm btn-success w-100">
                    <i class="ti ti-user-plus"></i>
                  </button>
                </div>
              </div> -->
            </form>
            <form class="col-6 align-items-center">
              <label class="mb-1">Cari</label> <img src="storage/images/spinner.svg" alt="" width="10" v-show="isSearching">
              <input class="form-control form-control-sm" type="text" v-model="userFind" placeholder="Masukkan Username" @input="findMember">
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
              <span v-if="member.klaim == 0" class="text-danger">Belum</span>
              <span v-else class="text-success">Sudah</span>
            </td>
            <td class="border border-light text-center py-1">
              <div v-if="member.proses == 0">
                <span class="me-2 text-danger">Belum</span>
                <button class="btn btn-sm btn-success py-0 px-1" data-bs-target="#konfirmasi" data-bs-toggle="modal" @click="konfirmasi(member.username)">
                  <small>Konfirmasi</small>
                </button>
              </div>
              <div v-else>
                <span class="text-success">Sudah</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

<!-- MODAL KONFIRMASI -->
  <div class="modal fade" id="konfirmasi" tabindex="-1" role="dialog" aria-labelledby="konfirmasiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body pb-0">
        <img src="storage/images/spinner.svg" alt="" width="20" v-show="isProcessing" class="float-end">
          <div>Tandai hadiah SUDAH diproses untuk username <strong>{{ userConfirm }}</strong>?</div>
          <Transition>
            <div v-if="confirmError == true" class="alert alert-danger py-1 mt-3">
              User {{ userConfirm }} belum klaim voucher spin.
            </div>
          </Transition>
        </div>
        <div class="modal-footer">
          <button class="btn btn-sm btn-light" data-bs-dismiss="modal" @click="confirmError = false">Tidak</button>
          <button class="btn btn-sm btn-success" style="width: 60px" @click="confirmUser(userConfirm)">Ya</button>
        </div>
      </div>
    </div>
  </div>
  <!-- END MODAL KONFIRMASI -->
</template>

<script>
import Voucher from 'voucher-code-generator'
export default {
  data() {
    return {
      userAdd: '',
      voucherAdd: '',
      userFind: '',
      inputBorderUser: 'null',
      inputBorderVoucher: 'null',
      isSearching: false,
      isProcessing: false,
      isRegistering: false,
      memberList: [],
      userConfirm: '',
      confirmError: false,
    }
  },

  methods: {
    Initiate() {
      this.clearData()
      this.listAllMember()
    },

    clearData() {
      this.userAdd = ''
      this.voucherAdd = ''
      this.userFind = ''
      this.inputBorderUser = 'null'
      this.inputBorderVoucher = 'null'
      this.isSearching = false
      this.memberList = []
      this.userConfirm = ''
      this.confirmError = false
    },

    listAllMember() {
      axios
      .get('/get_members')
      .then((result) => {
        this.memberList = result.data
      })
    },

    addMember() {
      this.inputBorderUser = null
      this.inputBorderVoucher = null

      if (this.userAdd == '') {
        this.inputBorderUser = 'border border-danger'
      } else if (this.voucherAdd == '') {
        this.inputBorderVoucher = 'border border-danger'
      } else {
        this.inputBorderUser = null
        this.inputBorderVoucher = null
        let data = {
          username: this.userAdd,
          voucher: this.voucherAdd[0] // dari library hasil generate-nya dalam bentuk array
        }
        axios
        .post('/add_member', {data: JSON.stringify(data)})
        .then((result) => {
          this.clearData()
          this.listAllMember()
          console.log(result.data)
        })
      }
    },

    createVoucher() {
      let voucher = Voucher.generate({
        length: 16,
        count: 1,
        charset: '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
      });
      this.voucherAdd = voucher
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

    konfirmasi(username) {
      this.userConfirm = username
    },
    confirmUser(username) {
      this.isProcessing = true
      let data = { username: username }
      axios
      .post('/confirm_process', { data: JSON.stringify(data) })
      .then((result) => {
        console.log(result)
        this.confirmError = false
        this.listAllMember()
        $('#konfirmasi').modal('hide')
      })
      .catch((error) => {
        console.log(error.response.data)
        this.confirmError = true
      })
      .finally(() => {
        this.isProcessing = false
      })
    },

  },

  mounted() {
    this.Initiate()
  },
}
</script>