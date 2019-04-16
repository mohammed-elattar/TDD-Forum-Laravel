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
            <button class="btn btn-outline-dark" @click="addReply">Submit</button>
        </div>
        <p class="text-center text-dark mt-4" v-else>Please <a href="/login">Sign In</a> to be able to
            participate in the forum</p>
    </div>
</template>

<script>
    import 'jquery.caret';
    import 'at.js';
    export default {
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
        mounted() {
            $('#body').atwho({
                at: "@",
                delay: 750,
                callbacks: {
                    remoteFilter: function (query, callback) {
                        $.getJSON("/api/users", {name: query}, function (usernames) {
                            callback(usernames)
                        });
                    }
                }
            });
        },
        methods: {
            addReply() {
                axios.post(location.pathname + '/replies', {body: this.body})
                    .then(({data}) => {
                        this.body = '';
                        flash('Your reply has been posted');
                        this.$emit('created', data);
                    }).catch(error => flash(error.response.data, 'danger'))
            }
        }
    }
</script>

<style scoped>

</style>
