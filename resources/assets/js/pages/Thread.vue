<template>

</template>

<script>
    import Replies from '../components/Replies.vue';
    export default {
        name:'thread-view',
        components:{Replies},
        props:['initialRepliesCount'],
        data(){
            return{
                repliesCount:this.initialRepliesCount
            }
        }
    }
</script>

<style scoped>

</style>
