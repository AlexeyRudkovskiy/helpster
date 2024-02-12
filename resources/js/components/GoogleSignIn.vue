<script>
import {defineComponent} from 'vue'

export default defineComponent({
    name: "GoogleSignIn",
    mounted() {
        const ref = this.$el;

        const handleCredentialResponse = (test) => {
            console.log('Done');
            console.log(test);
        }

        window.onload = function () {
            window.google.accounts.id.initialize({
                client_id: '200795533005-h78er9sv73f5m3dkhpe22pohpij0nqoa.apps.googleusercontent.com',
                callback: handleCredentialResponse
            });
            window.google.accounts.id.renderButton(ref, {theme: "filled_blue"});
            window.google.accounts.id.prompt();
        }
    }
})
</script>

<template>
<div></div>
</template>

<style scoped>

</style>
