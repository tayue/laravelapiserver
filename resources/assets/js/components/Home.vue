<template>
    <h1 @click="logout">Vue SPA 认证</h1>

</template>
<script>
    export default {
        data(){
            return {
                email: null,
                password: null,
                error: false
            }
        },
        methods: {


            logout(){
                console.log("logout");
                var app = this
                this.$auth.logout({
                    params: {
                        email: app.email,
                        password: app.password
                    }
                });
            }
        }
    }
</script>
