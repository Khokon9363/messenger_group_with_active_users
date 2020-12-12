<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center"><h3>{{ me }}-({{ active }})</h3></div>
                    <div class="row">
                    <div class="col-3">
                    <div class="card-body">
                        <ul class="text-center">
                            <li class="users" v-for="user in users" :key="user.id">{{ user.name }}</li>
                        </ul>
                    </div>
                    </div>
                    <div class="col-9">
                    <div class="card-body" v-chat-scroll="{always: false, smooth: true,}">
                        <ul>
                            <div v-for="message in messages" :key="message.id">
                            <li class="text-left" v-if="sessionId != message.sender"><span>{{ message.message }} -: <small>{{ message.user.name }}</small></span></li>
                            <li class="text-right" v-else><span><small>{{ message.user.name }}</small> :- {{ message.message }}</span></li>
                            </div>
                        </ul>
                    </div>
                    <form @submit.prevent="store()">
                        <input type="text" v-model="message" class="form-control" placeholder="Write here..">
                    </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // user online offline real-time
            this.getMessages()
            Echo.join('messenger')
                .here((users) => {
                    this.active = users.length
                    this.users = users
                })
                .joining((user) => {
                    this.users.push(user)
                    this.active = this.users.length
                })
                .leaving((user) => {
                    this.users.splice(this.users.indexOf(user),1)
                    this.active = this.users.length
                })
            Echo.private('messenger')
                .listen('MessengerEvent', (e) => {
                    // console.log(e.message.message);
                    this.getMessages()
                });

        },
        data(){
            return{
                users : [],
                messages : [],
                me : document.getElementById('sessionName').value,
                sessionId : Number(document.getElementById('sessionId').value),
                message : null,
                active : null,
            }
        },
        methods:{
            store(){
                axios.post('store',{
                    message : this.message
                })
                .then(response =>{
                    this.getMessages()
                    this.message = null
                    this.$toastr('success', 'Stored Succefully', 'Succees')
                })
                .catch(error =>{
                    console.log(error);
                })
            },
            getMessages(){
                axios.get('get_messages')
                .then(response =>{
                    this.messages = response.data
                })
                .catch(error =>{
                    console.log(error);
                })
            }
        }
    }
</script>
<style scoped>
    small{
        font-size: 10px;
        color: blue;
    }
    ul{
        list-style-type: none;
        padding: 0;
    }
    ul .users{
        border: 1px solid lightgray;
        padding-top: 8px;
        padding-bottom: 8px;
    }
    row{
        margin: 0;
    }
    .col-3{
        height: 620px;
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .text-left{
        color: black;
        background-color: rgb(85, 250, 107);;
        padding: 10px;
        border-radius: 18px;
        margin-bottom: 6px;
        font-size: 16px;
    }
    .text-right{
        color: black;
        background-color: aqua;
        padding: 10px;
        border-radius: 18px;
        font-size: 16px;
    }
    .col-3{
        height: 620px;
        overflow-x: overlay;
        padding: 0;
        border-right: 1px solid gainsboro;
    }
    .card-body{
        height: 620px;
        overflow-x: overlay;
        padding-top: 5px;
    }
</style>
