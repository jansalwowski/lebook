Array.prototype.unique = function () {
    var a = this.concat();
    for (var i = 0; i < a.length; ++i) {
        for (var j = i + 1; j < a.length; ++j) {
            if (a[i] === a[j])
                a.splice(j--, 1);
        }
    }

    return a;
};

String.prototype.capitalizeFirstLetter = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
};


window.moment = require('moment');
// window.moment = require('moment-timezone');

import Vue from "vue";
import VueResource from "vue-resource";
import VueValidator from "vue-validator";
import Wall from "./components/wall.vue";
import CommentForm from "./components/comment-form.vue";
import Comment from "./components/comment.vue";
import Alerts from "./mixins/alerts";
import Modals from "./mixins/modals";
import store from "./vuex/store";

window.Vue = Vue;
window.VueStrap = require('vue-strap/dist/vue-strap.min.js');

Vue.use(VueResource);
Vue.use(VueValidator);

Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('value');

new Vue({
    el: 'body',

    data(){
        return {
            data: {},
            asyncTemplate: '<span>{{ item.name }}</span>',
            ok: false,
            image: '',
            baseUrl: baseUrl
        };
    },

    components: {
        Wall,
        Comment,
        CommentForm,
        'dropdown': VueStrap.dropdown,
        'tooltip': VueStrap.tooltip,
        'modal': VueStrap.modal,
        'tabs': VueStrap.tabset,
        'alert': VueStrap.alert,
        'tab': VueStrap.tab,
        'accordion': VueStrap.accordion,
        'panel': VueStrap.panel,
        'typeahead': VueStrap.typeahead
    },

    methods: {
        selectUser(items) {
            window.location.replace(items.username)
        },

        follow(user_id){
            this.$http.get(baseUrl + '/api/users/' + user_id + '/follow')
                .then(function (response) {
                    var message = response.data.message;

                }.bind(this))
                .catch(function (response) {

                }.bind(this));
        },

        uploadAvatar: function (e) {
            e.preventDefault();

            var files = this.$els.avatar.files;

            var data = new FormData();
            data.append('_method', 'PUT');
            data.append('image', files[0]);

            console.log(data);

            this.$http.post(baseUrl + '/api/users/avatar', data).then(function (response) {
                var message = response.data.message;
                this.$dispatch('alertSuccess', message);
            }).catch(function (response) {
                var message = "Avatar upload Failed!";
                var errors = response.data;
                for (var error in errors) {
                    if (errors.hasOwnProperty(error)) {
                        message += "<br/>" + errors[error][0];
                    }
                }
                this.$dispatch('alertDanger', message);
            });
        },

        onFileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length)
                return;
            this.createImage(files[0]);
        },

        createImage(file) {
            var image = new Image();
            var reader = new FileReader();
            var vm = this;

            reader.onload = (e) => {
                vm.image = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        removeImage: function (e) {
            this.image = '';
        }
    },

    mixins: [Alerts, Modals],

    store: store
});