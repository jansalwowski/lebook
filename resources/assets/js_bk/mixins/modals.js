export default{
    data(){
        return {
            modals: {
                edit: {
                    title: 'Edit',
                    model: {
                        id: 0,
                        body: ''
                    },
                    newModel: {
                        body: ''
                    },
                    rules: {
                        body: {'required': true, 'min': 5}
                    },
                    type: 'post',
                    show: false
                },

                default: {
                    title: 'Info',
                    model: {
                        id: 0,
                        body: '',
                        comments: [],
                        likes: []
                    },
                    modelType: 'post',
                    innerTitle: '',
                    type: 'default',
                    show: false
                }

            }
        }
    },

    computed: {
        avatar(){
            return baseUrl + '/images/avatar/' + this.modals.default.model.user.username;
        }
    },
    
    methods: {

        updateModal() {
            var url = baseUrl + '/api/' + this.modals.edit.type + 's/' + this.modals.edit.model.id;
            var requestData = this.getRequestData();
            this.$http.patch(url, requestData)
                .then(function (response) {
                    var post = response.data.data;

                    var event = this.modals.edit.type + 'Updated';

                    this.$broadcast(event, post);
                    this.modals.edit.show = false;
                }.bind(this), function (response) {
                    var message = "Post could not have been updated!<br/>";
                    var errors = response.data;
                    for (var error in errors) {
                        if (errors.hasOwnProperty(error)) {
                            message += "<br/>" + errors[error][0];
                        }
                    }
                    this.$dispatch('alertDanger', message);
                }.bind(this));
        },

        getRequestData(){
            var request = {};
            var fields = this.modals.edit.rules;
            for (var field in fields) {
                if (fields.hasOwnProperty(field)) {
                    request[field] = this.modals.edit.newModel[field];
                }
            }
            return request;
        },

        generateFields(){
            var fields = this.modals.edit.rules;
            for (field in fields) {
                if (fields.hasOwnProperty(field)) {
                    // modal-body template
                }
            }
        },

        showEditModalPanel(){
            this.modals.edit.show = true;
        }

    },

    events: {
        showCommentsModal(model, modelType = 'post'){
            this.modals.default.title = 'Comments';
            this.modals.default.type = 'comments';
            this.modals.default.modelType = modelType;
            this.modals.default.model = model;
            this.modals.default.show = true;
        },

        showLikesModal(model, modelType = 'post'){
            this.modals.default.title = 'Likes';
            this.modals.default.type = 'likes';
            this.modals.default.modelType = modelType;
            this.modals.default.model = model;
            this.modals.default.show = true;
        },

        showEditModal(model, rules, type = 'post'){
            this.modals.edit.model = model;
            this.modals.edit.rules = rules;
            this.modals.edit.type = type;

            var newModel = {};
            for (var rule in rules) {
                if (model.hasOwnProperty(rule)) {
                    newModel[rule] = model[rule];
                }
            }
            this.modals.edit.newModel = newModel;

            this.showEditModalPanel();
        },

        CommentWasAdded(user, comment){
            this.$broadcast('CommentWasAdded', user, comment);
        },

        CommentLiked(comment){
            this.$broadcast('CommentWasLiked', comment);
        }
    },

    directives: {
        dateshow: function (value) {
            this.el.innerText = moment.utc(value).local().fromNow(); // Convert date from UTC to Local Date and readable format
        }
    }
}