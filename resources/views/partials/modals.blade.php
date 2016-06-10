<modal :title="modals.edit.title" :show.sync="modals.edit.show" effect="zoom" width="400" v-cloak>
    <div slot="modal-body" class="modal-body">

        <div class="form-group">
            <textarea name="modalBody" id="modalBody" class="form-control" cols="30" rows="5" placeholder="Input..."
                      v-model="modals.edit.newModel.body"></textarea>
        </div>

    </div>

    <div slot="modal-footer" class="modal-footer">
        <button type="button" class="btn btn-default" @click="modals.edit.show = false">Cancel</button>
        <button type="button" class="btn btn-success" @click="updateModal()">Save</button>
    </div>
</modal>


<modal :title="modals.default.title" :show.sync="modals.default.show" effect="zoom" width="400" v-cloak>
    <div slot="modal-body" class="modal-body">

        <h3>@{{ modals.default.innerTitle }}</h3>

        <div v-if="modals.default.type == 'likes' || modals.default.type == 'comments'">
            <h3>
                <a href="@{{ modals.default.model.user.username }}">
                    <img :src="avatar" alt="" class="avatar">
                    @{{ modals.default.model.user.name }}
                </a>
                <small>@{{ modals.default.model.date }}</small>
            </h3>
            <p>@{{ modals.default.model.body }}</p>
        </div>


        <ul class="list-group" v-if="modals.default.type == 'comments'">
            <li v-for="comment in modals.default.model.comments" class="list-group-item comment">
                <comment :comment="comment"></comment>
            </li>
        </ul>

        {{--<div class="row" v-if="modals.default.type == 'likes'">
            <hr>
            <div class="col-sm-4 like-user" v-for="user in modals.default.model.likes">
                <a href="@{{ baseUrl + '/' + user.username }}">
                    <img :src="user.avatar" alt="" class="avatar-small">
                    @{{ user.name }}
                </a>
            </div>
        </div>

        <p v-if="modals.default.type == 'likes' && !modals.default.model.likes.length">
            No one has liked it so far...
        </p>--}}
        <p v-if="modals.default.type == 'comments' && !modals.default.model.comments.length">
            No one has liked it so far...
        </p>

    </div>
    <div slot="modal-footer">
        <div class="comment-form" v-if="modals.default.type == 'comments'">
            <comment-form :post="modals.default.model.id"></comment-post>
        </div>
    </div>
</modal>