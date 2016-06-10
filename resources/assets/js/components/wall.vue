<template>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Write a post!</h4>
        </div>

        <div class="panel-body post-form">

            <div class="form-group">
                <textarea name="body" rows="3" placeholder="Write here what you are thinking about now..."
                          class="form-control" v-model="body"></textarea>
            </div>

            <div class="form-group">
                <button class="btn btn-sm btn-primary pull-right" @click="submitPost">
                    <i class="fa fa-spinner fa-spin" v-if="creatingPost"></i>
                    <i class="fa fa-pencil" v-if="!creatingPost"></i> Submit
                </button>
            </div>

        </div>
    </div>


    <div class="wall">
        <post :post="post" v-for="post in posts"></post>
    </div>

    <div class="col-xs-2 col-xs-offset-5">
        <button class="btn btn-default" @click="loadPosts">
            <i class="fa fa-spinner fa-spin" v-if="loadingPosts"></i>
            <i class="fa fa-caret-down" v-if="!loadingPosts"></i> Load More
        </button>
    </div>

</template>

<script>
    import Post from './post.vue'
    import CommentForm from './comment-form.vue';
    export default{

        props: {
            url: {default: baseUrl + '/api/wall'}
        },

        data(){
            return {
                posts: [],
                body: '',
                last: 0,
                loadingPosts: false,
                creatingPost: false
            }
        },

        ready(){
            this.getPosts();
        },

        components: {
            Post,
            CommentForm
        },

        methods: {
            getPosts: function () {
                this.$http.get(this.url)
                        .then(function (response) {
                            this.posts = response.data.wall;
                            this.last = response.data.last;
                        }.bind(this), function (response) {
                            console.log('Error while loading posts!');
                        }.bind(this));
            },

            submitPost: function () {
                this.creatingPost = true;
                this.$http.post(baseUrl + '/api/posts', {body: this.body})
                        .then(function (response) {
                            this.posts.unshift(response.data.post);
                            this.body = '';
                            this.creatingPost = false;
                            this.last++;
                            this.$dispatch('alertSuccess', 'Post submited!');
                        }.bind(this), function (response) {
                            this.creatingPost = false;
                            var message = "Post was not created.";
                            var errors = response.data;
                            for (var error in errors) {
                                if(errors.hasOwnProperty(error)){
                                    message += '<br/>' + errors[error][0];
                                }
                            }
                            this.$dispatch('alertDanger', message);
                        }.bind(this));
            },

            loadPosts: function () {
                this.loadingPosts = true;
                this.$http.get(this.url, {last: this.last})
                        .then(function (response) {
                            this.posts = this.posts.concat(response.data.wall).unique();
                            this.last = response.data.last;
                            this.loadingPosts = false;
                        }.bind(this), function (response) {
                            console.log('Error while loading posts!');
                            this.loadingPosts = false;
                        }.bind(this));
            },

            deletePost: function (post_id) {
                for (var i = 0; i < this.posts.length; i++)
                    if (this.posts[i] && this.posts[i].id == post_id) {
                        this.posts.splice(i, 1);
                        break;
                    }

                this.$dispatch('alertInfo', 'Post deleted.');
            },

            addComment(user, comment){
                var post = this.findPost(comment.post_id);

                if (post.hasOwnProperty('comments') && !! post.comments.length) {
                    post.comments.push({
                        name: user.name,
                        username: user.username,
                        body: comment.body,
                        date: comment.created_at
                    });
                }

                post.commentsCount++;
            },

            likeComment(comment){
                var post = this.findPost(comment.post_id);

                if (post.hasOwnProperty('comments') && !! post.comments.length) {

                    for(var i=0; i < post.comments.length; i++){

                        if( post.comments[i].id == comment.id){
                            if(post.comments[i].liked){
                                post.comments[i].liked = false;
                                post.comments[i].likesCount--;
                            }else{
                                post.comments[i].liked = true;
                                post.comments[i].likesCount++;
                            }
                            break;
                        }
                    }
                }

            },

            findPost(id){
                for (var i = 0; i < this.posts.length; i++) {
                    if (this.posts[i].id === id) {
                        return this.posts[i];
                    }
                }
                return {};
            },

            updatePost(post){
                var findPost = this.findPost(post.id);
                findPost['body'] = post.body;
                findPost['updated_at'] = post.updated_at;
            }
        },

        events: {

            CommentWasLiked(comment){
                this.likeComment(comment);
            },

            CommentWasAdded(user, comment){
                this.addComment(user, comment);
                this.$dispatch('alertSuccess', 'Comment successfully created!');
            },

            postUpdated(post){
                this.updatePost(post);
                this.$dispatch('alertSuccess', 'Post updated!');
            }
        },

        directives: {
            dateshow: function (value) {
                this.el.innerText = moment.utc(value).local().fromNow(); // Convert date from UTC to Local Date and readable format

            }
        }

    }
</script>