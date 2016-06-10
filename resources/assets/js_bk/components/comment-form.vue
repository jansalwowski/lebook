<template>

    <div class="row">
        <div class="col-sm-10 col-xs-10 form-group">
            <input type="text" class="form-control" name="body" placeholder="Your comment..." v-model="body">
        </div>


        <div class="col-sm-2 col-xs-1">
            <button class="btn btn-sm btn-primary" @click="submit">
                <i class="fa fa-spinner fa-spin" v-if="creatingComment"></i>
                <i class="fa fa-pencil" v-if="!creatingComment"></i><span class="hidden-xs"> Submit</span>
            </button>
        </div>
    </div>

</template>

<style>
</style>

<script>
    export default{
        props: ['post'],

        data: function () {
            return {
                body: '',
                creatingComment: false
            };
        },

        methods: {
            submit: function () {
                this.creatingComment = true;
                this.$http.post(baseUrl + '/api/posts/' + this.post + '/comment', {body: this.body})
                        .then(function (response) {
                            var user = response.data.user;
                            var comment = response.data.data;
                            this.$dispatch('CommentWasAdded', user, comment);
                            this.body = '';
                            this.creatingComment = false;

                        }.bind(this), function (response) {
                            this.creatingComment = false;
                            var message = "Comment could not have been added!";
                            var errors = response.data;
                            for (var error in errors) {
                                if (errors.hasOwnProperty(error)) {
                                    message += "<br/>" + errors[error][0];
                                }
                            }
                            this.$dispatch('alertDanger', message);
                        }.bind(this));
            }
        }
    }

</script>

