<template>
  <div class="card">
    <div class="card-body p-2">
      <div class="row">
        <div class="col-5">
          <form @submit.prevent="updatePrize">
            <div class="row align-items-center mb-1" v-for="(prize, index) in prizeList" :key="prize.id">
              <div class="col-1 text-center">
                {{ index + 1 }}
              </div>
              <div class="col-4">
                {{ prize.deskripsi }}
              </div>
              <div class="col-7">
                <input type="text" class="form-control form-control-sm" :value="prize.hadiah" ref="prizes">
              </div>
            </div>
            <button class="btn btn-sm btn-info mt-2">
              Update Hadiah
            </button>
          </form>
        </div>
        <div class="col-7 border py-3 bg-light">
          <div class="row mb-2">
            <div class="col-12 text-end">
              <a href="/" target="_blank" class="btn btn-sm btn-success mx-1"><i class="ti ti-external-link"></i> Lihat Web</a>
            </div>
          </div>
          <div class="row align-items-center">
            <div class="col-4">
              <img :src="'images/' + assets.wheel" alt="" class="w-100">
            </div>
            <div class="col-4">
              <img :src="'images/' + assets.pin" alt="" class="w-100">
            </div>
            <div class="col-4">
              <img :src="'images/' + assets.background" alt="" class="w-100">
            </div>
          </div>
          <div class="row align-items-center mt-1">
            <div class="col-4">
              <button class="btn btn-sm btn-info w-100" data-bs-toggle="modal" data-bs-target="#formUpload" @click="upload('Wheel')"><i class="ti ti-pokeball"></i> Update Wheel</button>
            </div>
            <div class="col-4">
              <button class="btn btn-sm btn-info w-100" data-bs-toggle="modal" data-bs-target="#formUpload" @click="upload('Pin')"><i class="ti ti-arrow-up-tail"></i> Update Pin</button>
            </div>
            <div class="col-4">
              <button class="btn btn-sm btn-info w-100" data-bs-toggle="modal" data-bs-target="#formUpload" @click="upload('Background')"><i class="ti ti-photo"></i> Update Background</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="formUpload" tabindex="-1" role="dialog" aria-labelledby="formUploadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body mb-0">
          <img src="images/spinner.svg" alt="" width="25" class="float-end" v-show="isUploading">
          <h5 class="text-center">
            <strong>Upload {{ uploadHeader }}</strong>
          </h5>
          <div class="row mt-3">
            <div class="col-7">
              <form @submit.prevent="updateImage(uploadHeader)" class="text-center">
                <input type="file" @change="onFileChange" ref="fileName" accept="image/*" class="form-control form-control-sm mb-1">
                <small>Jenis file didukung: <strong>.jpg .jpeg .png .svg</strong></small>
                <br />
                <small v-show="errorMessage" class="text-danger mb-0">{{ errorMessage }}</small>
                <button type="submit" class="btn btn-sm btn-success w-100 mb-2 mt-4">Update</button>
                <button type="button" class="btn btn-sm btn-light w-100" data-bs-dismiss="modal">Batal</button>
              </form>
            </div>
            <div class="col-5">
              <img :src="image" class="w-100 shadow">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      prizeList: [],
      assets: {},
      uploadHeader: '',
      image: '',
      errorMessage: '',
      isUploading: false,
    }
  },

  methods: {
    Initiate() {
      this.getPrizes()
      this.getAssets()
    },

    getPrizes() {
      axios
      .get('/get_prizes')
      .then((result) => {
        this.prizeList = result.data
      })
    },

    getAssets() {
      axios
      .get('/get_assets')
      .then((result) => {
        this.assets = result.data
      })
    },

    updatePrize() {
      let data = {}
      let prizeLength = this.$refs.prizes.length
      let i = 0
      while(i < prizeLength) {
        data['prize'+i] = this.$refs.prizes[i].value
        i++
      }

      axios
      .post('/update_prizes', {data: data})
      .then((result) => {
        console.log(result)
      })
    },

    upload(header) {
      this.$refs.fileName.value = ''
      this.errorMessage = ''
      this.uploadHeader = header
      if (this.uploadHeader == 'Wheel')
        this.image = 'images/' + this.assets.wheel
      if (this.uploadHeader == 'Pin')
        this.image = 'images/' + this.assets.pin
      if (this.uploadHeader == 'Background')
        this.image = 'images/' + this.assets.background
    },

    onFileChange(e) {
      const files = e.target.files || e.dataTransfer.files;
      if (!files.length)
        return;
      this.createImage(files[0]);
    },

    createImage(file) {
      const image = new Image();
      const reader = new FileReader();
      const vm = this;
      reader.onload = (e) => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },

    updateImage(header) {
      this.isUploading = true

      let data = {
        file: this.$refs.fileName.files[0],
        head: header
      }
      axios
      .post('/update_asset', data, { headers:{'Content-Type': 'multipart/form-data'} })
      .then((result) => {
        console.log(result.data)
        this.getAssets()
        $('#formUpload').modal('hide')
      })
      .catch((error) => {
        console.log(error.response.data)
        this.errorMessage = error.response.data
      })
      .finally(() => {
        this.isUploading = false
      })
    },
  },

  mounted() {
    this.Initiate()
  },
}
</script>