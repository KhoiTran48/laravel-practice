<template>
    <div class="mt-5">
        <input type="file" name="avatar" @change="getImage"/>
        <a href="#" v-if="loaded" class="btn btn-success ml-3" @click.prevent="upload">Upload By Vue</a>
        <a href="#" v-if="loaded" class="btn btn-danger" @click.prevent="cancel">Cancel</a>
        <img class="img-fluid mt-3" :src="avatar" alt="Image"/>
    </div>
</template>

<script>
    export default {
        props:['user'],
        data(){
            return {
                avatar: `storage/${this.user.avatar}`,
                loaded: false,
                file: null
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            getImage(e){
                let image = e.target.files[0];
                this.read(image);

                let form = new FormData();
                form.append("avatar", image);
                this.file = form;
            },
            read(image){
                let reader = new FileReader();
                reader.readAsDataURL(image);
                reader.onload = e => {
                    this.avatar = e.target.result;
                }
                this.loaded = true
            },
            upload(){
                axios.post("/upload_by_vue", this.file)
                .then(res => this.$toasted.show('Avatar is uploaded!', {type: 'success'}));
                this.loaded = false;
            },
            cancel(){
                this.avatar = `storage/${this.user.avatar}`;
                this.loaded = false
            }
        },
    }
</script>
