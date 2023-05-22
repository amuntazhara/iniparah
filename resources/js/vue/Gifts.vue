<template>
  <div class="card">
    <div class="card-body p-2">
      <div class="row">
        <div class="col-4">
          <form @submit.prevent="updatePrize">
            <div class="row align-items-center mb-1" v-for="prize in prizeList" :key="prize.id">
              <div class="col-4">
                {{ prize.deskripsi }}
              </div>
              <div class="col-8">
                <input type="text" class="form-control form-control-sm" :value="prize.hadiah" ref="prizes">
              </div>
            </div>
            <button class="btn btn-sm btn-info mt-2">
              Update Hadiah
            </button>
          </form>
        </div>
        <div class="col-8 border py-3 bg-light">
          <div class="row">
            <div class="col-3">
              <img src="images/wheel0.png" alt="" class="w-100">
            </div>
            <div class="col-3">
              <img src="images/pin.png" alt="" class="w-100">
            </div>
            <div class="col-3">
              <img src="images/background0.png" alt="" class="w-100">
            </div>
          </div>
          <div class="row">
            <div class="col-3">
              <button class="btn btn-sm btn-info mx-1"><i class="ti ti-pokeball"></i> Update Papan</button>
            </div>
            <div class="col-3">
              <button class="btn btn-sm btn-info mx-1"><i class="ti ti-arrow-up-tail"></i> Update Pin</button>
            </div>
            <div class="col-3">
              <button class="btn btn-sm btn-info mx-1"><i class="ti ti-photo"></i> Update Background</button>
            </div>
            <a href="/" target="_blank" class="btn btn-sm btn-success mx-1"><i class="ti ti-world"></i> Preview Web</a>
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
    }
  },

  methods: {
    Initiate() {
      axios
      .get('/get_prizes')
      .then((result) => {
        this.prizeList = result.data
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
    }
  },

  mounted() {
    this.Initiate()
  },
}
</script>