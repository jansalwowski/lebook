<template>
    <div class="container">
        <div class="row">

            <div class="col-md-3">

                <div class="profile">
                    <a href="#avatar-photo">
                        <img :src="user.avatar" alt="Avatar" class="img-responsive">
                    </a>

                    <h3 class="text-center">
                        {{ user.name }}

                        <span>

                            <button class="btn btn-xs btn-primary" @click="follow()"
                                    v-if="!isFollowed">
                                <i class="fa fa-plus"></i> Follow
                            </button>

                            <button class="btn btn-xs btn-primary" @click="unfollow()"
                                    v-if="isFollowed">
                                <i class="fa fa-minus"></i> Unfollow
                            </button>

                        </span>
                    </h3>
                    <p>
                        Joined at: <span v-date="user.created_at"></span>
                    </p>
                </div>

                <div class="panel panel-default user-photos">
                    <div class="panel-body">
                        <h3>Photos
                            <small>{{ user.photosCount }}</small>
                        </h3>

                        <div class="row">
                            <div class="col-xs-4" v-for="photo in user.photos">
                                <a href="#user-photo">
                                    <img :src="photo.url" alt="Avatar" class="img-responsive">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default followed-users">
                    <div class="panel-body">
                        <h3>Following
                            <small>{{ user.followingCount }}</small>
                        </h3>

                        <div class="row">
                            <div class="col-xs-4" v-for="following in user.following">
                                <tooltip
                                        effect="scale"
                                        placement="top"
                                        trigger="hover"
                                        :content="following.name">
                                    <a :href="baseUrl + '/' + following.username">
                                        <img :src="baseUrl + '/images/avatar/' + following.username" :alt="following.username"
                                             class="img-responsive">
                                    </a>
                                </tooltip>
                            </div>
                        </div>

                    </div>
                </div>
                

                <div class="panel panel-default followed-by-users">
                    <div class="panel-body">
                        <h3>Followers
                            <small>{{ user.followersCount }}</small>
                        </h3>

                        <div class="row">
                            <div class="col-xs-4" v-for="follower in user.followers">
                                <tooltip
                                        effect="scale"
                                        placement="top"
                                        trigger="hover"
                                        :content="follower.name">
                                    <a :href="baseUrl + '/' + follower.username">
                                        <img :src="baseUrl + '/images/avatar/' + follower.username" :alt="follower.username"
                                             class="img-responsive">
                                    </a>
                                </tooltip>
                            </div>
                        </div>

                    </div>
                </div>


            </div>

            <div class="col-md-8">

                <wall :url="wallURL"></wall>

            </div>
        </div>
    </div>
</template>

<style>
</style>

<script>

    import Wall from './wall.vue';

    export default{

        props: ['user'],

        data: function () {
            return {
                baseUrl: baseUrl
            };
        },

        computed: {
            wallURL(){
                return this.baseUrl + '/api/wall/' + this.user.username;
            }
        },

        methods: {

            follow(){

                this.$http.post(baseUrl + '/api/follow/' + this.user.id).then(function (response) {
                    this.user.followed = true;
                    this.$dispatch('alertSuccess', 'You are not following ' + this.user.username + '!');
                }.bind(this), function (response) {
                    this.$dispatch('alertDanger', response.data.error.message);
                }).bind(this);

            },

            unfollow(){

            }

        },

        components: {
            Wall,
            'tooltip': VueStrap.tooltip
        },

        directives: {
            date: function (value) {
                this.el.innerText = moment.utc(value).local().fromNow(); // Convert date from UTC to Local Date and readable format

            }
        }
    }
</script>