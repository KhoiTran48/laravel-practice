/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import UploadForm from './components/UploadForm.vue';
import Message from './components/chat/message.vue';
import Toasted from 'vue-toasted';
import VueChatScroll from 'vue-chat-scroll';

Vue.use(VueChatScroll);
Vue.use(Toasted)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    components: {UploadForm, Message},
    data:{
        message: '',
        chat:{
            message: [],
            username: [],
            color: [],
            time: [],
        },
        typing: '',
        numberOfUser: 0,
    },
    methods: {
        send(){
            if(this.message.length != 0){
                this.chat.message.push(this.message);
                this.chat.username.push('you');
                this.chat.color.push('success');
                this.chat.time.push(this.getTime());
                axios.post('/send', {
                    message: this.message,
                    chat: this.chat
                })
                .then(response => {
                    this.message = '';
                })
                .catch(error => {
                });
                
            }
        },
        getTime(){
            let time = new Date();
            return time.getHours() + ":" + time.getMinutes();
        },
        getOldMessages(){
            axios.post('/getOldMessage')
                  .then(response => {
                    if (response.data != '') {
                        this.chat = response.data;
                    }
                  })
                  .catch(error => {
                    console.log(error);
                  });
        },
        deleteSession(){
            axios.post('/deleteSession')
            .then(response=> this.$toasted.show('Chat history is deleted', {type: 'success'}).goAway(1500) );
        }
    },
    watch:{
        message(){
            Echo.private('chat')
                .whisper('typing', {
                    message: this.message,
                });
        }
    },
    mounted(){
        this.getOldMessages();
        Echo.private('chat')
            .listen('ChatEvent', (e) => {
                this.chat.message.push(e.message);
                this.chat.username.push(e.username);
                this.chat.color.push('warning');
                this.chat.time.push(this.getTime());
                axios.post('/saveToSession',{
                    chat : this.chat
                })
                .then(response => {
                })
                .catch(error => {
                    console.log(error);
                });
            })
            .listenForWhisper('typing', (e) => {
                if(e.message != ''){
                    this.typing = 'typing...';
                }else{
                    this.typing = '';
                }
            });
        Echo.join(`chat`)
            .here((users) => {
                this.numberOfUser = users.length;
            })
            .joining((user) => {
                this.$toasted.show(user.name + ' is joined the chat room!', {type: 'success'}).goAway(1500);
                this.numberOfUser++;
            })
            .leaving((user) => {
                this.$toasted.show(user.name + ' is leaved the chat room!', {type: 'error'}).goAway(1500);
                this.numberOfUser--;
            });
    }
});
