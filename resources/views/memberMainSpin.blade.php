<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Spinning Wheel</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <style>body{margin:0;background:linear-gradient(180deg,rgba(209,179,151,1)0%,rgba(177,173,126,1)100%) !important};.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}canvas{display:block;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}</style>
        <link src="main.css">
        {{-- <script src="https://dunggramer.github.io/disable-devtool/disable-devtool.min.js" defer></script> --}}
        <script src="//cdn.jsdelivr.net/npm/phaser@3.60.0/dist/phaser.min.js"></script>
    </head>
    <body class="antialiased">
        <div class="col-12" style="height: 100vh">
            <div id="game-canvas"></div>
        </div>
        
        {{-- Modal Rule --}}
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
                                <td class="p-0 mb-2">Kode voucher didapatkan melalui ...</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="width: 20px !important">3</td>
                                <td class="p-0 mb-2">Member hanya bisa melakukan spin sekali.</td>
                            </tr>
                            <tr>
                                <td class="p-0" style="width: 20px !important">4</td>
                                <td class="p-0 mb-2">Untuk bantuan live chat, dapat diakses melalui ...</td>
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

        {{-- Modal Hadiah --}}
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
        @vite('resources/js/main.js')
    </body>
    <script>
        var game
        var gameOptions = {
            slices: 8,
            slicePrizes: [
                @php foreach($spinner as $key) : @endphp
                    "{{ $key['hadiah'] }}",
                @php endforeach @endphp
            ],
            rotationTime: 5000
        }
        window.onload = function () {
            var gameConfig = {
                type: Phaser.AUTO,
                parent: 'game-canvas',
                scale: {
                    mode: Phaser.Scale.FIT,
                    width: window.innerWidth,
                    height: window.innerHeight,
                },
                backgroundColor: '#000',
                scene: [playGame]
            }

            game = new Phaser.Game(gameConfig, 'gameCanvas')
            window.focus()
            // resize();
            // window.addEventListener("resize", resize, true);
            $('#form').modal('show')
        }
        class playGame extends Phaser.Scene {
            constructor() {
                super("PlayGame")
            }
            
            preload() {
                this.load.image("background", window.location.href + "images/background.png")
                this.load.image("board", window.location.href + "images/wheel0.png")
                this.load.image("pin", window.location.href + "images/pin.png")
                this.load.image("logo", window.location.href + "images/logo.png")
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
                this.canSpin = true
                this.input.on("pointerdown", this.spinWheel, this)
            }
            spinWheel() {
                if (this.canSpin) {
                    var rounds = Phaser.Math.Between(4, 6)
                    var degrees = Phaser.Math.Between(321, 355)
                    var prize = gameOptions.slices - 1 - Math.floor(degrees / (360 / gameOptions.slices))
                    this.canSpin = false
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

                            update_db()
                        }
                    })
                }
            }
        }

        
        // pure javascript to scale the game
        function resize() {
            var canvas = document.querySelector("canvas");
            var windowWidth = window.innerWidth;
            var windowHeight = window.innerHeight;
            var windowRatio = windowWidth / windowHeight;
            var gameRatio = game.config.width / game.config.height;
            console.log(gameRatio)
            console.log(windowRatio)
            if (windowRatio < gameRatio) {
                canvas.style.width = Math.ceil(windowWidth) + "px";
                canvas.style.height = ((windowWidth / gameRatio)) + "px";
            }
            else {
                canvas.style.width = ((windowHeight * gameRatio)) + "px";
                canvas.style.height = Math.ceil(windowHeight) + "px";
            }
        }

        function update_db() {
            const data = '{{ session()->get("username") }}'
            console.log(data)
            // axios
            // .post('/update_member', {data: data})
            // .then((result) => {
            //     console.log(result.data)
            // })
        }
    </script>
</html>
