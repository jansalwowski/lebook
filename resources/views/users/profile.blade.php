@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="panel panel-default">
            <div class="panel-heading">
                Profile
            </div>
            <div class="panel-body">

                <tabs>
                    <tab header="Profile">

                        <accordion :one-at-atime="true">
                            <panel header="Avatar">
                                <div>

                                    <div class="row" style="height: 300px;">

                                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" v-if="image">
                                            <h4>Preview:</h4>
                                            <img :src="image" class="img-responsive"/>
                                        </div>

                                        <div class="col-xs-9">

                                            <form action="">
                                                <div class="form-group">
                                                    <input v-el:avatar type="file" name="avatar" id="avatar" @change="onFileChange" class=form-control>
                                                </div>

                                                <div class="form-group">
                                                    <input class="btn btn-primary form-control" type="submit"
                                                           value="Submit" @click="uploadAvatar">
                                                </div>
                                            </form>

                                        </div>
                                    </div>


                                </div>
                            </panel>
                            <panel header="Profile description">
                                {{ $user->description }}
                            </panel>
                        </accordion>


                    </tab>

                    <tab header="Photos">
                        Photos
                    </tab>

                    <tab header="Following">
                        Following
                    </tab>

                    <tab header="Followers">
                        Followers
                    </tab>
                </tabs>

            </div>
        </div>

    </div>

@stop