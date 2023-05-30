<template>
  <div class="accordion shadow-sm mb-3 sticky-top" style="top: 75px; z-index:2" id="aksesMember">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button class="accordion-button bg-dark py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <strong class="text-muted">Akses</strong>
        </button>
      </h2>
      <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#aksesMember">
        <div class="accordion-body p-2">
          <div class="row">
            <form @submit.prevent="addMember" class="col-9">
              <strong class="mb-1">Tambah Member</strong> <img src="images/spinner.svg" alt="" width="10" v-show="isRegistering">
              <div class="row align-items-center">
                <div class="col-8 mb-2">
                  <div class="row align-items-center">
                    <div class="col-3">
                      <small>Username</small>
                    </div>
                    <div class="col-9">
                      <input :class="'form-control form-control-sm ' + inputBorderUser" type="text" v-model="userAdd" placeholder="Username">
                    </div>
                  </div>
                </div>
                <div class="col-4 mb-2">
                  <div class="row align-items-center">
                    <div class="col-4">
                      <small>Hadiah</small>
                    </div>
                    <div class="col-8">
                      <select :class="'form-select form-select-sm ' + inputBorderGift" v-model="giftSet">
                        <option v-for="(prize, index) in prizeList" :key="prize.id" :value="index + 1">{{ prize.hadiah }}</option>
                      </select>
                      <!-- <input :class="'form-control form-control-sm text-center'" type="number" v-model="giftSet"> -->
                    </div>
                  </div>
                </div>
                <div class="col-8">
                  <div class="row align-items-center">
                    <div class="col-3">
                      <small>Voucher</small>
                    </div>
                    <div class="col-6">
                      <input :class="'form-control form-control-sm ' + inputBorderVoucher" type="text" v-model="voucherAdd" placeholder="Voucher">
                    </div>
                    <div class="col-3">
                      <button  type="button" class="btn btn-sm btn-warning w-100 text-dark" @click="createVoucher">
                        <i class="ti ti-refresh"></i> <span>Generate</span>
                      </button>    
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <button type="submit" class="btn btn-sm btn-success w-100">
                    <i class="ti ti-user-plus"></i> <span>Tambah Member</span>
                  </button>
                </div>
              </div>
            </form>
            <form class="col-3 align-items-center">
              <strong class="mb-1">Cari</strong> <img src="images/spinner.svg" alt="" width="10" v-show="isSearching">
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
        <img src="images/spinner.svg" alt="" width="20" v-show="isProcessing" class="float-end">
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
      prizeList: [],
      userAdd: '',
      giftSet: '',
      voucherAdd: '',
      userFind: '',
      inputBorderUser: null,
      inputBorderGift: null,
      inputBorderVoucher: null,
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
      this.listPrizes()
    },

    clearData() {
      this.userAdd = ''
      this.giftSet = ''
      this.voucherAdd = ''
      this.userFind = ''
      this.inputBorderUser = null
      this.inputBorderGift = null
      this.inputBorderVoucher = null
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

    listPrizes() {
      axios
      .get('/get_prizes')
      .then((result) => {
        this.prizeList = result.data
      })
    },

    addMember() {
      this.inputBorderUser = null
      this.inputBorderGift = null
      this.inputBorderVoucher = null

      if (this.userAdd == '') {
        this.inputBorderUser = 'border border-danger'
      } else if (this.giftSet == '') {
        this.inputBorderGift = 'border border-danger'
      } else if (this.voucherAdd == '') {
        this.inputBorderVoucher = 'border border-danger'
      } else {
        this.inputBorderUser = null
        this.inputBorderGift = null
        this.inputBorderVoucher = null
        this.isRegistering = true
        let data = {
          username: this.userAdd,
          voucher: this.voucherAdd[0], // dari library hasil generate-nya dalam bentuk array
          gift: this.giftSet,
        }
        axios
        .post('/add_member', {data: JSON.stringify(data)})
        .then((result) => {
          this.clearData()
          this.listAllMember()
          console.log(result.data)
        })
        .finally(() => {
          this.isRegistering = false
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