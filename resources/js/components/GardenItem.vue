<template>

    <div class="card m-2" style="width: 20rem;">
        <a :href=garden_link>
            <img class="card-img img-fluid" :src=newPicture style="height: 14vw" alt="Garden Item Image">

            <div class="card-img-overlay d-flex">
                <h1 class="align-self-center mx-auto text-white">{{ garden_name }}</h1>

                <form :action=del method="post">
                    <input name="_token" type="hidden" :value=csrf>
                    <input name="_method" type="hidden" value="DELETE">
                    <button class="btn btn-danger" type="submit">Delete</button>
                </form>
            </div>
        </a>
    </div>

</template>

<script>
    export default {
        props:{
            garden_name: String,
            picture: String,
            garden_link: String,
            csrf: String,
            del: String
        },
        computed: {
            newPicture() {
                if (this.picture == "http://localhost/Laravel/Gardeners-Diary/public/storage/images") {
                    return "http://localhost/Laravel/Gardeners-Diary/public/Images/gardenItem.jpg";
                } else {
                    return this.picture;
                }
            }
        }
    }
</script>