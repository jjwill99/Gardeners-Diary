<template>
    <div>
        <article class="message" :class="accordionClasses1">
            <div class="message-header" @click="toggleAccordion1">
                How to add a new garden
            </div>
            <div class="message-body">
                <div class="message-content">
                    <ol>
                        <li>Go to the "My Gardens" page</li>
                        <li>Click the "Add New Garden" button</li>
                        <li>Enter your garden details</li>
                        <li>Click "Submit"</li>
                    </ol>
                </div>
            </div>
        </article>

        <br />
        <br />

        <article class="message" :class="accordionClasses2">
            <div class="message-header" @click="toggleAccordion2">
                How to customise a garden
            </div>
            <div class="message-body">
                <div class="message-content">
                    <ol>
                        <li>Go to your chosen garden</li>
                        <li>Click the "Edit Garden" button</li>
                        <li>Make your changes</li>
                        <li>Click "Save Layout" to save the garden</li>
                    </ol>
                </div>
            </div>
        </article>

        <br />
        <br />

        <article class="message" :class="accordionClasses3">
            <div class="message-header" @click="toggleAccordion3">
                How to add a new plant
            </div>
            <div class="message-body">
                <div class="message-content">
                    <ol>
                        <li>Go to your chosen garden</li>
                        <li>Click the "Edit Garden" button</li>
                        <li>Click the "Add Plant" button</li>
                        <li>Enter the plant details</li>
                        <li>Click "Submit" to save the plant</li>
                    </ol>
                </div>
            </div>
        </article>

        <br />
        <br />

        <article class="message" :class="accordionClasses4">
            <div class="message-header" @click="toggleAccordion4">
                How to customise the plants on one tile
            </div>
            <div class="message-body">
                <div class="message-content">
                    <ol>
                        <li>Go to your chosen garden</li>
                        <li>Click the "Edit Garden" button</li>
                        <li>Select your chosen plant</li>
                        <li>Click on the icon/s you want to remove</li>
                        <li>If the tile is empty, click again to refill it</li>
                    </ol>
                </div>
            </div>
        </article>

        <br />
        <br />

        <article class="message" :class="accordionClasses5">
            <div class="message-header" @click="toggleAccordion5">
                How to save a garden history
            </div>
            <div class="message-body">
                <div class="message-content">
                    <ol>
                        <li>Go to your chosen garden</li>
                        <li>Click the "Save To History" button</li>
                        <li>Enter the garden details</li>
                        <li>Click "Submit" to save the garden history</li>
                    </ol>
                </div>
            </div>
        </article>
    </div>
</template>

<script>
export default {
    data: function(){
        return {
            isOpen1: true,
            isOpen2: false,
            isOpen3: false,
            isOpen4: false,
            isOpen5: false,
        }
    },
    methods: {
        toggleAccordion1: function() {
            this.isOpen1 = !this.isOpen1;
        },
        toggleAccordion2: function() {
            this.isOpen2 = !this.isOpen2;
        },
        toggleAccordion3: function() {
            this.isOpen3 = !this.isOpen3;
        },
        toggleAccordion4: function() {
            this.isOpen4 = !this.isOpen4;
        },
        toggleAccordion5: function() {
            this.isOpen5 = !this.isOpen5;
        }
    },
    computed: {
        accordionClasses1: function() {
            return {
                'is-closed': !this.isOpen1,
                'is-primary': this.isOpen1,
                'is-dark': !this.isOpen1
            };
        },
        accordionClasses2: function() {
            return {
                'is-closed': !this.isOpen2,
                'is-primary': this.isOpen2,
                'is-dark': !this.isOpen2
            };
        },
        accordionClasses3: function() {
            return {
                'is-closed': !this.isOpen3,
                'is-primary': this.isOpen3,
                'is-dark': !this.isOpen4
            };
        },
        accordionClasses4: function() {
            return {
                'is-closed': !this.isOpen4,
                'is-primary': this.isOpen4,
                'is-dark': !this.isOpen4
            };
        },
        accordionClasses5: function() {
            return {
                'is-closed': !this.isOpen5,
                'is-primary': this.isOpen5,
                'is-dark': !this.isOpen5
            };
        }
    }
}
</script>

<style scoped>

    .main {
        text-align: center;
    }

    .message {
        width: 50vw;
        margin-left: auto;
        margin-right: auto;
        
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 18px;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
    }

    .message-header {
        cursor: pointer;
        font-size: 2vh;
        font-weight: bold;
    }

    .message-body   {
        padding: 0;
        overflow: hidden;
        transition: 0.3s ease all;
        background-color: #f1f1f1;
    }

    .is-closed .message-body {
        max-height: 0;
    }

    .message-content {
        padding: 20px;
        font-size: 1.5vh;
    }

</style>