<template>
    <div :id="'reply-' + id" class="card mt-3">
        <div class="card-header">
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
                <div class="body" v-else v-text="body"></div>
            </article>
        </div>

        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-outline-secondary btn-sm mr-1" @click="editing=true">Edit</button>
            <button class="btn btn-danger btn-sm mr-1" @click="destroy">Delete</button>
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
                id:this.data.id,
                body: this.data.body
            };
        },
        computed:{
          signedIn(){
              return window.App.signedIn;
          },
            canUpdate(){
              return this.authorize(user=>this.data.user_id == user.id);
            },
            ago(){
              return moment(this.data.created_at).fromNow()+'...';
            }
        },
        methods: {
            update() {
                axios.patch('/replies/' + this.data.id, {
                    body: this.body
                }).catch(error=>flash(error.response.data,'danger'));
                this.editing = false;
                flash('Updated successfully!');
            },
            destroy() {
                axios.delete('/replies/' + this.data.id);
                this.$emit('deleted',this.data.id);;
            }
        }
    }
</script>
