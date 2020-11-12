<template>
    <div>
        {{garden_name}}
        <img style="width:50%;height:50%" :src="picture" v-if="picture != 'http://localhost/Laravel/Gardeners-Diary/public/storage/images'">
    </div>
</template>

<script>
    export default {
        props:{
            garden_name: {
                default: "No Name",
                type: String
            },
            picture: {}
        }
    }
</script>