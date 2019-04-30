<template>
    <div :id="'reply-' + id" class="card mt-3">
        <div class="card" :class="isBest?'card-success':'card-header'">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.name" v-text="data.owner.name">
                    </a> said
                    <span v-text="ago"></span>
                </h5>
                <div v-if="signedIn">
                    <favourite :reply="data"></favourite>
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

        <div class="card-footer level">
            <div v-if="authorize('updateReply',reply)">
                <button class="btn btn-outline-secondary btn-sm mr-1" @click="editing=true">Edit</button>
                <button class="btn btn-danger btn-sm mr-1" @click="destroy">Delete</button>
            </div>
            <button class="btn btn-sm btn-outline-primary ml-a" @click="markBestReply" v-show="! isBest">Best Reply?
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
        props: ['data'],
        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                isBest: this.data.isBest,
                reply:this.data
            };
        },
        computed: {
            ago() {
                return moment(this.data.created_at).fromNow() + '...';
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
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                }).catch(error => flash(error.response.data, 'danger'));
                this.editing = false;
                flash('Updated successfully!');
            },
            destroy() {
                axios.delete('/replies/' + this.data.id);
                this.$emit('deleted', this.data.id);
                ;
            },
            markBestReply() {
                axios.post('/replies/'+this.id+'/best');
                window.events.$emit('best-reply-selected',this.data.id);
            }
        }
    }
</script>
