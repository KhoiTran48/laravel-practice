<template>
    <div>
        <li class="list-group-item" :class="className"><slot></slot></li> 
        <span id="time">{{ time }}</span>
        <small class="badge float-right badge-danger">{{ username }}</small>
    </div>
</template>

<script>
    export default {
        props:[
            'color',
            'username',
            'time',
        ],
        computed:{
            className(){
                return "list-group-item-" + this.color
            },
            badgeClass(){
                return "badge-" + this.color;
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
    }
</script>

<style>
    #time{
        font-size: 8px;
        /* margin-top: 10px; */
        color: black;
    }
</style>