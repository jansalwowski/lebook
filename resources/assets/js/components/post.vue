<template>


    <div class="panel panel-default post animated fadeInDown {{ class }}" transition="fade">
        <div class="panel-body">

            <h4>
                <a href="{{ post.user.username }}" class="author">
                    <img :src="post.user.avatar" alt="" class="avatar">
                    <span class="user">
                        {{ post.user.name }}
                    </span>
                </a>
                <small v-dateshow="post.created_at"></small>
            </h4>

            <span class="body">
                {{{ post.body }}}
            </span>

            <dropdown class="btn-group options" v-if="post.owns">
                <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a @click="editPost()"><i class="fa fa-edit"></i> Edit</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a @click="deletePost"><i class="fa fa-trash"></i> Delete</a>
                    </li>
                </ul>
            </dropdown>

            <hr>

        <span class="likes">
            <span @click="showLikesModal" class="btn">
                {{ post.likesCount }}
            </span>


            <a class="{{ post.liked ? 'liked' : '' }}" @click="like">
                <i class="fa fa-thumbs-o-up"></i>
            </a>
        </span>

        <span class="comments">
            <span @click="showCommentsModal" class="btn">
                {{ post.commentsCount }}
                <i class="fa fa-comment-o"></i>
            </span>
        </span>


            <comment-form :post="post.id"></comment-post>

        </div>
    </div>


</template>

<script>
    import CommentForm from './comment-form.vue';
    window.VueStrap = require('vue-strap/dist/vue-strap.min.js');


    export default{

        props: ['post'],

        data: function () {
            return {
                baseUrl: baseUrl,
                body: '',
                class: '',
            };
        },

        ready(){
            this.post.date = window.moment(this.post.created_at, "YYYY-MM-DD HH:II:SS").fromNow();
        },

        methods: {
            like: function () {
                this.$http({url: baseUrl + '/api/posts/' + this.post.id + '/like', method: 'GET'}).then(function (response) {
                    if (this.post.liked) {
                        this.post.likesCount--;
                    } else {
                        this.post.likesCount++;
                    }
                    this.post.liked = !this.post.liked;
                }.bind(this), function (response) {
                    alert('Error during request! Try again.');
                });
            },

            showLikesModal: function () {
                this.likesModal = !this.likesModal;

                this.$http({url: baseUrl + '/api/posts/' + this.post.id + '/likes', method: 'GET'}).then(function (response) {
                    var likedBy = response.data;
                    this.post.likes = likedBy;
                    this.$dispatch('showLikesModal', this.post);
                }.bind(this), function (response) {
                    alert('Error during request! Try again.');
                });
            },

            showCommentsModal: function () {

                this.$http({
                    url: baseUrl + '/api/posts/' + this.post.id + '/comments',
                    method: 'GET'
                }).then(function (response) {
                    var comments = response.data;
                    this.post.comments = comments;
                    this.$dispatch('showCommentsModal', this.post);
                }.bind(this), function (response) {
                    alert('Error during request! Try again.');
                });
            },

            editPost: function () {
                var rules = {
                    body: {'required': true, 'min': 5}
                };
                this.$dispatch('showEditModal', this.post, rules);
            },

            deletePost: function () {
                this.class = 'fadeOutUp';
                this.$http.delete(baseUrl + '/api/posts/' + this.post.id, {body: this.body})
                        .then(function (response) {
                            setTimeout((function () {

                                this.$parent.deletePost(response.data.id);

                            }).bind(this), 500);
                        }.bind(this), function (response) {
                            console.log('Error while deleting post!');
                        }.bind(this));
            }
        },

        components: {
            CommentForm,
            'dropdown': VueStrap.dropdown,
        },

        directives: {
            dateshow: function (value) {
                this.el.innerText = moment.utc(value).local().fromNow(); // Convert date from UTC to Local Date and readable format

            }
        }
    }
</script>