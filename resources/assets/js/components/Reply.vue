<template>
    <div :id="'reply-' + id" class="card mt-3">
        <div class="card" :class="isBest?'card-success':'card-header'">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + reply.owner.name" v-text="reply.owner.name">
                    </a> said
                    <span v-text="ago"></span>
                </h5>
                <div v-if="signedIn">
                    <favourite :reply="reply"></favourite>
                </div>
            </div>

        </div>
        <div class="card-body">
            <article>
                <div v-if="editing">
                    <form @submit="update">
                        <div class="form-group mt-2">
                            <textarea class="form-control" v-model="body" required></textarea>
                        </div>
                        <button class="btn btn-primary">update</button>
                        <button class="btn btn-link" @click="editing=false" type="button">Cancel</button>
                    </form>
                </div>
                <div class="body" v-else v-html="body"></div>
            </article>
        </div>

        <div class="card-footer level" v-if="authorize('owns',reply) || authorize('owns',reply.thread)">
            <div v-if="authorize('owns',reply)">
                <button class="btn btn-outline-secondary btn-sm mr-1" @click="editing=true">Edit</button>
                <button class="btn btn-danger btn-sm mr-1" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-sm btn-outline-primary ml-a" @click="markBestReply" v-if="authorize('owns',reply.thread)">Best Reply?
            </button>
        </div>
    </div>

</template>
<script>
    import Favourite from './Favourite.vue';
    import moment from 'moment';

    export default {
        components: {
            Favourite
        },
        props: ['reply'],
        data() {
            return {
                editing: false,
                id: this.reply.id,
                body: this.reply.body,
                isBest: this.reply.isBest,
                reply:this.reply
            };
        },
        computed: {
            ago() {
                return moment(this.reply.created_at).fromNow() + '...';
            },
            isBest(){
                return window.thread.best_reply_id == this.id;
            }
        },
        created(){
                window.events.$on('best-reply-selected',id=>{
                   return this.isBest = (id == this.id);
                });
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.reply.id, {
                    body: this.body
                }).catch(error => flash(error.response.reply, 'danger'));
                this.editing = false;
                flash('Updated successfully!');
            },
            destroy() {
                axios.delete('/replies/' + this.reply.id);
                this.$emit('deleted', this.reply.id);
                ;
            },
            markBestReply() {
                axios.post('/replies/'+this.id+'/best');
                window.events.$emit('best-reply-selected',this.reply.id);
            }
        }
    }
</script>
