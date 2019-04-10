<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-3">
        <textarea
                id="body"
                class="form-control"
                placeholder="Have something to say?"
                rows="5"
                required
                v-model="body">
        </textarea>
            </div>
            <button class="btn btn-default" @click="addReply">Submit</button>
        </div>
        <p class="text-center text-dark mt-4" v-else>Please <a href="/login">Sign In</a> to be able to
            participate in the forum</p>
    </div>
</template>

<script>
    export default {
        props:['endpoint'],
        data() {
            return {
                body: '',
            }
        },
        computed: {
            signedIn() {
                return window.App.signedIn;
            }
        },
        methods: {
            addReply() {
                axios.post(this.endpoint, {body: this.body})
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply has been posted');
                        this.$emit('created', data);
                    })
            }
        }
    }
</script>

<style scoped>

</style>
