<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Accept-Encoding" content="gzip, deflate">
        <meta name="Content-Encoding" content="gzip">
        <title>Spinning Wheel</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        {{-- <script src="https://dunggramer.github.io/disable-devtool/disable-devtool.min.js" defer></script> --}}
        <script src="phaser.min.js"></script>
    </head>
    <body class="antialiased main">
        <div class="col-12" style="height: 100vh">
            {{-- Game Canvas --}}
            <div id="game-canvas"></div>
        </div>
        
        {{-- MODAL RULES --}}
        <div class="modal fade" id="rules" tabindex="-1" role="dialog" aria-labelledby="rulesLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body mb-0">
                        <h3 class="text-center">
                            <strong>ATURAN MAIN</strong>
                        </h3>
                        <table class="table">
                            <tr>
                                <td class="p-0" style="width: 20px !important">1</td>
                                <td class="p-0 mb-2">Spin hanya bisa dilakukan oleh member yang memasukkan username dan kode voucher yang valid.</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="width: 20px !important">2</td>
                                <td class="p-0 mb-2">Kode voucher didapatkan melalui [...]</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="width: 20px !important">3</td>
                                <td class="p-0 mb-2">Member hanya bisa melakukan spin sekali.</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="width: 20px !important">4</td>
                                <td class="p-0 mb-2">Untuk bantuan live chat, dapat diakses melalui <a href="">link ini</a>.</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer pt-0">
                        <a role="button" class="btn btn-lg w-100" data-bs-dismiss="modal">
                            <h3><strong class="">OK</strong></h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- END MODAL RULES --}}

        {{-- MODAL NO SPIN --}}
        <div class="modal fade" id="noSpin" tabindex="-1" role="dialog" aria-labelledby="noSpinLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body mb-0">
                        <h3 class="text-center">
                            <strong>MAAF!</strong>
                        </h3>
                        <p>Voucher Anda telah digunakan untuk spin. Silahkan hubungi admin apabila hadiah belum diterima.</p>
                    </div>
                    <div class="modal-footer pt-0">
                        <a role="button" class="btn btn-lg w-100" data-bs-dismiss="modal">
                            <strong class="">OK</strong>
                        </a>
                        <button type="button" class="btn w-100" onclick="otherVoucher()">
                            <strong class="text-danger">Gunakan voucher lain</strong>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        {{-- END MODAL NO SPIN --}}
        
        {{-- MODAL USERNAME & VOUCHER --}}
        <div class="modal fade" id="form" tabindex="-1" role="dialog" aria-labelledby="formLabel" aria-hidden="true" data-bs-keyboard="false" data-bs-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form>
                        @csrf
                        <div class="modal-body pb-0">
                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-sm-4">USERNAME</div>
                                <div class="col-12 col-sm-8">
                                    <input type="text" class="form-control" id="username">
                                </div>
                            </div>
                            <div class="row align-items-center mb-2">
                                <div class="col-12 col-sm-4">VOUCHER</div>
                                <div class="col-12 col-sm-8">
                                    <input type="text" class="form-control" id="voucher">
                                </div>
                            </div>
                            <div id="errMsg" class="text-danger text-center d-none mb-2"></div>
                        </div>
                        <div class="modal-footer pt-0">
                            <a role="button" class="btn btn-success w-100" onclick="verify()">
                                <h3 class="mb-0"><strong>SUBMIT</strong></h3>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- END MODAL USERNAME & VOUCHER --}}

        {{-- MODAL HADIAH --}}
        <div class="modal fade" id="prize" tabindex="-1" role="dialog" aria-labelledby="prizeLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h2>SELAMAT!!!</h2>
                        <h4>Anda mendapatkan hadiah</h4>
                        <h1 class="mt-2"><strong id="finalPrize"></strong>.</h1>
                        <br />
                        <small>Mohon tunggu &plusmn; 10 menit sampai hadiah Anda diproses.</small>
                    </div>
                    <div class="modal-footer pt-0">
                        <a role="button" class="btn btn-lg w-100" data-bs-dismiss="modal">
                            <h3><strong class="">OK</strong></h3>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- END MODAL HADIAH --}}

        {{-- PAKAI VITE --}}
        @vite('resources/js/main.js')
    </body>
    <script>
        // Untuk session spin
        var usr = null
        var hadiah = null

        // Game config untuk Phaser.Game
        var game
        var gameOptions = {
            slices: 8,
            slicePrizes: [
                @php foreach($spinner as $key) : @endphp
                    "{{ $key['hadiah'] }}",
                @php endforeach @endphp
            ],
            rotationTime: 8000
        }

        window.onload = function () {
            var scene = new playGame()
            scene.canSpin = false
            
            var gameConfig = {
                type: Phaser.AUTO,
                parent: 'game-canvas',
                scale: {
                    mode: Phaser.Scale.FIT,
                    width: window.innerWidth,
                    height: window.innerHeight,
                },
                backgroundColor: '#000',
                // Panggil Class playGame
                scene: scene
            }

            // Inisialisai Phaser
            game = new Phaser.Game(gameConfig, 'game-canvas')
            window.focus()
            $('#rules').modal('show')

            document.getElementById('rules').addEventListener('hidden.bs.modal', function (event) {
                $('#form').modal('show')
            })
        }

        class playGame extends Phaser.Scene {
            constructor() {
                super("PlayGame")
            }
            
            preload() {
                this.load.image("background", window.location.href + "images/{{ $assets->background }}")
                this.load.image("board", window.location.href + "images/{{ $assets->wheel }}")
                this.load.image("pin", window.location.href + "images/{{ $assets->pin }}")
                this.load.image("logo", window.location.href + "images/{{ $assets->logo }}")
            }

            create() {
                this.bg = this.add.sprite(game.config.width / 2, game.config.height /2, "background")
                this.bg.setScale(1)
                this.wheel = this.add.sprite(game.config.width / 2.6, game.config.height / 2.1, "board")
                this.wheel.setScale(0.4)
                this.pin = this.add.sprite(game.config.width / 2.6, game.config.height / 2.1, "pin")
                this.logo = this.add.sprite(game.config.width / 2, game.config.height / 1.1, "logo")
                this.logo.setScale(1)
                this.prizeText = this.add.text(game.config.width / 2.5, game.config.height / 1.4, "", {
                    font: "bold 64px Arial",
                    align: "center",
                    color: "black"
                })

                this.prizeText.setOrigin(0.5)

                this.input.on('pointerdown', this.spinWheel, this)
            }

            spinWheel() {
                if (this.canSpin) {
                    const rangeLower = [320, 275, 230, 185, 140, 95, 50, 5]
                    const rangeUpper = [355, 310, 365, 220, 175, 130, 85, 40]

                    const degree1 = rangeLower[hadiah - 1]
                    const degree2 = rangeUpper[hadiah - 1]

                    var rounds = Phaser.Math.Between(14, 20)
                    var degrees = Phaser.Math.Between(degree1, degree2)
                    var prize = gameOptions.slices - 1 - Math.floor(degrees / (360 / gameOptions.slices))
                    this.tweens.add({
                        targets: [this.wheel],
                        angle: 360 * rounds + degrees,
                        duration: gameOptions.rotationTime,
                        ease: "Cubic.easeOut",
                        callbackScope: this,
                        onComplete: function (tween) {
                            this.canSpin = false
                            $('#finalPrize').text(gameOptions.slicePrizes[prize])
                            $('#prize').modal('show')
                            // updateDB()
                        }
                    })
                } else {
                    $('#noSpin').modal('show')
                }
            }
        }

        function verify() {
            $('#errMsg').addClass('d-none')
            const config = {
                headers:{
                    header1: 'XMLHttpRequest',
                }
            }
            const data = {
                username: $('#username').val(),
                voucher: $('#voucher').val()
            }
            window.axios.post('/verify', {data: JSON.stringify(data)}, config)
                .then((result) => {
                    usr = result.data.username
                    hadiah = result.data.hadiah
                    $('#form').modal('hide')
                    game.scene.scenes[0].canSpin = true
                })
                .catch((error) => {
                    $('#errMsg').text(error.response.data)
                    $('#errMsg').removeClass('d-none')
                })
        }

        function otherVoucher() {
            $('#noSpin').modal('hide')
            $('#form').modal('show')
        }

        function updateDB() {
            const data = usr
            axios
            .post('/update_member', {data: JSON.stringify(data)})
            .then((result) => {
                console.log(result.data)
            })
            console.log('selesai')
        }
    </script>
</html>
