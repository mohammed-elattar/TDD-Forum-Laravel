<template>
    <div>
        <div v-for="(reply,index) in items" :key="reply.id">
            <reply :reply="reply" @deleted="remove(index)"></reply>
        </div>
        <p v-if="$parent.locked">
            This Thread is locked , no more replies are allowed
        </p>
        <new-reply @created="add" v-if="! $parent.locked"></new-reply>
        <paginator :dataSet="dataSet" @changed="fetch"></paginator>
    </div>

</template>

<script>
    import Reply from './Reply.vue';
    import NewReply from './NewReply.vue';
    import collections from '../mixins/collection';

    export default {
        name: 'replies',
        components: {Reply, NewReply},
        data() {
            return {dataSet: false}
        },
        created() {
            this.fetch();
        },
        mixins: [collections],
        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);
            },
            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
                window.scrollTo(0,0);
            },
            url(page) {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/);
                    page = query ? query[1] : 1;
                }
                return `${location.pathname}/replies?page=${page}`;
            }
        }
    }
</script>

<style scoped>

</style>
