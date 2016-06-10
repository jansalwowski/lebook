<template>
    <a :href="userURL">
        <img :src="comment.avatar" alt="" class="avatar-small">
        {{ comment.name }}
    </a>
    <p>
        {{ comment.body }}
    </p>
    <span class="likes">
        <span class="btn">
            {{ comment.likesCount }}
        </span>

        <a :class="[comment.liked ? 'liked' : '' ]" @click="likeComment">
            <i class="fa fa-thumbs-o-up"></i>
        </a>
    </span>
    <small class="pull-right date" v-dateshow="comment.date"></small>
</template>

<style>
</style>

<script>
    export default{
        props: ['comment'],

        data: function () {
            return {
            };
        },

        computed: {
            userURL(){
                return baseUrl + '/' + this.comment.username;
            }
        },

        methods: {
            likeComment: function () {
                this.$http({
                    url: baseUrl + '/api/comments/' + this.comment.id + '/like',
                    method: 'GET'
                }).then(function (response) {
                    this.$dispatch('CommentLiked', this.comment);
                }.bind(this), function (response) {
                    alert('Error during request! Try again.');
                }.bind(this));
            }
        },

        directives: {
            dateshow: function (value) {
                this.el.innerText = moment.utc(value).local().fromNow(); // Convert date from UTC to Local Date and readable format
            }
        }
    }
</script>