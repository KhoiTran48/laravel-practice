<template>
    <div>
        <div v-if="!cropped" class="mt-5">
            <vue-avatar
                :width=400
                :height=400
                :border=0
                ref="vueavatar"
                @vue-avatar-editor:image-ready="onImageReady"
                :image="avatar"
            >
            </vue-avatar>
            <br>
            <vue-avatar-scale
                ref="vueavatarscale"
                @vue-avatar-editor-scale:change-scale="onChangeScale"
                :width=250
                :min=1
                :max=3
                :step=0.02
            >
            </vue-avatar-scale>
            <br>
            <img src="" id="img-1">
            <button v-on:click="saveClicked">Click</button>
        </div>
        <div v-if="cropped" class="mt-5">
            <img class="img-fluid mt-3" :src="avatar" alt="Image"/>
            <a class="btn btn-success ml-3" @click.prevent="upload">Upload By Vue</a>
            <a class="btn btn-danger" @click.prevent="cancel">Cancel</a>
            <button v-on:click="startCropping">Back</button>
        </div>
    </div>
</template>

<script>

    import VueAvatar from './vue_avatar_editor/VueAvatar.vue'
    import VueAvatarScale from './vue_avatar_editor/VueAvatarScale.vue'

    export default {
        components: {
            VueAvatar,
            VueAvatarScale
        },
        props:['user'],
        data(){
            return {
                avatar: `storage/${this.user.avatar}`,
                cropped: false,
                fileSelected: null,

                // 2 data này không dùng nữa, 
                // để nhìn cho zui thôi vì liên quan getImage, read
                // func: getImage, read k dùng nữa
                // vẫn giữ đống này để tham khảo code
                loaded: false,
                file: null,
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {

            // <input type="file" @change="getImage"/>
            // được dùng khi input type select file
            // read content selected file as base64 and show it on screen
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
                let form = new FormData();
                form.append("avatar", this.avatar);

                axios.post("/upload_by_vue", form)
                .then(res => this.$toasted.show('Avatar is uploaded!', {type: 'success'}));
                this.loaded = false;
            },
            cancel(){
                this.avatar = `storage/${this.user.avatar}`;
                this.loaded = false;
                this.cropped = false;
            },

            // for vue_avatar_editor
            onChangeScale (scale) {
                this.$refs.vueavatar.changeScale(scale)
            },
            saveClicked(){
                var img = this.$refs.vueavatar.getImageScaled()
                this.avatar = img.toDataURL();
                this.cropped = true;
            },
            onImageReady(scale){
                this.fileSelected = this.$refs.vueavatar.getImageScaled().toDataURL();
                this.$refs.vueavatarscale.setScale(scale)
            },
            startCropping(){
                this.avatar = this.fileSelected;
                this.cropped = false;
            }
        },
    }
</script>
