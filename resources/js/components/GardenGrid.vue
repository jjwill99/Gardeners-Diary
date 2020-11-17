<template>
    <div class="border border-dark" :style="{ width: (70/garden_width) + 'vw', height: (70/garden_width) + 'vw'}">T</div>
</template>

<script>
    export default {
        props:{
            garden_width: {
                default: 0,
                type: Number
            }
        }
    }
</script>