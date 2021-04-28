<template>
    <div class="flex-center position-ref">
        <div class="content">
            <div class="title m-b-md">
                Welcome to a Gardener's Diary!
            </div>

            <div class="slideshow-container">
                <div class="mySlides">
                    <img :src="edit" style="width: 100%;">
                    <div class="text">You can create your virtual garden!</div>
                </div>

                <div class="mySlides">
                    <img :src="activities" style="width: 100%;">
                    <div class="text">You can track your gardening activities!</div>
                </div>

                <div class="mySlides">
                    <img :src="history1" style="width: 100%;">
                    <div class="text">You can save your garden's history...</div>
                </div>

                <div class="mySlides">
                    <img :src="history2" style="width: 100%;">
                    <div class="text">... and see how your garden has changed!</div>
                </div>

                <a class="prev" @click="plusSlides(-1)">&#10094;</a>
                <a class="next" @click="plusSlides(1)">&#10095;</a>
            </div>

            <br />

            <div style="text-align:center">
                <span class="dot" @click="currentSlide(1)"></span>
                <span class="dot" @click="currentSlide(2)"></span>
                <span class="dot" @click="currentSlide(3)"></span>
                <span class="dot" @click="currentSlide(4)"></span>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function(){
        return {
            edit: "./Images/Edit Example.png",
            activities: "./Images/Activities Example.png",
            history1: "./Images/History Example 1.png",
            history2: "./Images/History Example 2.png",
            slideIndex: 1
        }
    },
    mounted(){
        this.showSlides(this.slideIndex);
    },
    methods: {
        plusSlides(n){
            this.showSlides(this.slideIndex += n);
        },
        currentSlide(n){
            this.showSlides(this.slideIndex = n);
        },
        showSlides(n){
            var i;
            var slides = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("dot");

            if (n > slides.length){
                this.slideIndex = 1
            }
            
            if (n < 1) {
                this.slideIndex = slides.length
            }

            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";  
            }

            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            
            slides[this.slideIndex-1].style.display = "block";  
            dots[this.slideIndex-1].className += " active";
        }
    }
}
</script>

<style scoped>
    html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
        font-weight: 200;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 84px;
    }

    .links > a {
        color: #636b6f;
        padding: 0 25px;
        font-size: 13px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 30px;
    }

    /* asd */

    .mySlides {
        display: none
    }
    
    img {
        vertical-align: middle;
    }

    /* Slideshow container */
    .slideshow-container {
        max-width: 1000px;
        position: relative;
        margin: auto;
    }

    /* Next & previous buttons */
    .prev, .next {
        cursor: pointer;
        position: absolute;
        top: 50%;
        width: auto;
        padding: 16px;
        margin-top: -22px;
        color: white;
        background-color: rgba(209, 203, 203, 0.383);
        font-weight: bold;
        font-size: 18px;
        transition: 0.6s ease;
        border-radius: 0 3px 3px 0;
        user-select: none;
    }

    /* Position the "next button" to the right */
    .next {
        right: 0;
        border-radius: 3px 0 0 3px;
    }

    /* Position the "previous button" to the right */
    .prev {
        left: 0;
        border-radius: 0 3px 3px 0;
    }

    /* On hover, add a black background color with a little bit see-through */
    .prev:hover, .next:hover {
        background-color: rgba(0,0,0,0.8);
    }

    /* Caption text */
    .text {
        font-size: 30px;
        padding: 8px 12px;
        position: absolute;
        bottom: 8px;
        width: 100%;
        text-align: center;
        font-weight: bold;
        color: white;
        text-shadow:
            -1px -1px 0 #000,
            1px -1px 0 #000,
            -1px 1px 0 #000,
            1px 1px 0 #000; 
    }

    /* Number text (1/3 etc) */
    .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
    }

    /* The dots/bullets/indicators */
    .dot {
        cursor: pointer;
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
    }

    .active, .dot:hover {
        background-color: #717171;
    }

</style>