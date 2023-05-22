// the game itself
var game;
var gameOptions = {
    // slices (prizes) placed in the wheel
    slices: 8,

    // prize names, starting from 12 o'clock going clockwise
    slicePrizes: [
        // <?php foreach($spinner as $key) : ?>
        //     "<?= $key['hadiah'] ?>",
        // <?php endforeach; ?>
        
        "Rp.5.000", // 316 - 360
        "Rp.10.000", // 271 - 315
        "Rp.15.000", // 226 - 270
        "Rp.20.000", // 181 - 225
        "Rp.25.000", // 136 - 180
        "Rp.50.000", // 91 - 135
        "Rp.75.000", // 46 - 90
        "Rp.100.000", // 0 - 45
    ],

    // wheel rotation duration, in milliseconds
    rotationTime: 1000
}
// once the window loads...
window.onload = function () {
    // game configuration object
    var gameConfig = {
        // render type
        type: Phaser.AUTO,

        // game width, in pixels
        // width: 800,

        // game height, in pixels
        // height: 800,

        // game background color
        scale: {
            mode: Phaser.Scale.FIT,
            parent: 'phaser-example',
            width: 800,
            height: 800
        },
        backgroundColor: '#000',
        // scenes used by the game
        scene: [playGame]
    };

    // game constructor
    game = new Phaser.Game(gameConfig);

    // pure javascript to give focus to the page/frame and scale the game
    window.focus()

    // resize();
    // window.addEventListener("resize", resize, false);
}
// PlayGame scene
class playGame extends Phaser.Scene {
    // constructor
    constructor() {
        super("PlayGame");
    }

    // method to be executed when the scene preloads
    preload() { // loading assets
        this.load.image("background", window.location.href + "images/background.png");
        this.load.image("board", window.location.href + "images/wheel0.png");
        this.load.image("pin", window.location.href + "images/pin.png");
        this.load.image("logo", window.location.href + "images/logo.png");
    }

    // method to be executed once the scene has been created
    create() {
        // adding the wheel in the middle of the canvas
        this.bg = this.add.sprite(game.config.width / 2, game.config.height / 2, "background");
        
        // adding the wheel in the middle of the canvas
        this.wheel = this.add.sprite(game.config.width / 3, game.config.height / 2.1, "board");
        this.wheel.setScale(0.5);

        // adding the pin in the middle of the canvas
        this.pin = this.add.sprite(game.config.width / 3, game.config.height / 2.1, "pin");

        // adding the pin in the middle of the canvas
        this.logo = this.add.sprite(game.config.width / 2, game.config.height / 1.15, "logo");

        // adding the text field
        this.prizeText = this.add.text(game.config.width / 2.5, game.config.height / 1.4, "", {
            font: "bold 64px Arial",
            align: "center",
            color: "black"
        });

        // center the text
        this.prizeText.setOrigin(0.5);

        // the game has just started = we can spin the wheel
        this.canSpin = true;

        // waiting for your input, then calling "spinWheel" function
        this.input.on("pointerdown", this.spinWheel, this);
    }
    // function to spin the wheel
    spinWheel() {
        // can we spin the wheel?
        if (this.canSpin) {
            // resetting text field
            // this.prizeText.setText("");

            // the wheel will spin round from 2 to 4 times. This is just coreography
            var rounds = Phaser.Math.Between(4, 6);

            // then will rotate by a random number from 0 to 360 degrees. This is the actual spin
            var degrees = Phaser.Math.Between(321, 355);

            // before the wheel ends spinning, we already know the prize according to "degrees" rotation and the number of slices
            var prize = gameOptions.slices - 1 - Math.floor(degrees / (360 / gameOptions.slices));

            // now the wheel cannot spin because it's already spinning
            this.canSpin = false;
            // animation tweeen for the spin: duration 3s, will rotate by (360 * rounds + degrees) degrees

            // the quadratic easing will simulate friction
            this.tweens.add({
                // adding the wheel to tween targets
                targets: [this.wheel],

                // angle destination
                angle: 360 * rounds + degrees,

                // tween duration
                duration: gameOptions.rotationTime,

                // tween easing
                ease: "Cubic.easeOut",

                // callback scope
                callbackScope: this,

                // function to be executed once the tween has been completed
                onComplete: function (tween) {
                    // displaying prize text
                    // this.prizeText.setText(gameOptions.slicePrizes[prize]);

                    // player can spin again
                    this.canSpin = false;

                    // show modal popup with prize
                    $('#finalPrize').text(gameOptions.slicePrizes[prize])
                    $('#prize').modal('show')
                }
            });
        }
    }
}

/*
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
*/