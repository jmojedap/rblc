<?php
    $json_scenes = "[{id: 1, title: 'Escena inicial', parent_id: 0, qty_childs: 0, level: 0, selected: true}]";
    if ( strlen($row->content_json) ) { $json_scenes = $row->content_json; }
?>

<div id="corpo_app">
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h2>{{ current.title }} <small>{{ current.id }}</small></h2>
                    <p>¿Qué harías si...?</p>
                    <div>
                        <form accept-charset="utf-8" method="POST" id="scene_form" @submit.prevent="send_form" @click="handler(arg, event)">
                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label text-right">Título</label>
                                <div class="col-md-8">
                                    <input
                                        name="title" type="text" class="form-control"
                                        required
                                        v-model="form_values.title"
                                    >
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button class="btn btn-primary w120p"> Agregar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table bg-white">
                        <thead>
                            <th>Title</th>
                            <th width="10px"></th>
                        </thead>
                        <tbody>
                            <tr v-for="(scene, scene_key) in scenes" v-show="scene.parent_id == current.id">
                                <td>{{ scene.title }}</td>
                                <td>
                                    <button class="a4" v-on:click="set_scene(scene.id, scene_key)">
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <button class="btn btn-secondary mt-2" v-show="current.parent_id > 0" v-on:click="set_scene(current.parent_id)">
                <i class="fa fa-arrow-left"></i> Volver
            </button>
        </div>
        <div class="col-md-7">
            <button class="btn btn-success w120p" v-on:click="save_poll">
                Save
            </button>
            <a href="<?= base_url("app/corpo/answer") ?>" class="btn btn-light w120p">
                Responder
            </a>
            <table class="table bg-white mt-2">
                <thead>
                    <th width="10px"></th>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Level</th>
                    <th>Parent ID</th>
                    <th>Qty Childs</th>
                </thead>
                <tbody>
                    <tr v-for="(scene, scene_key) in scenes" v-bind:class="{'table-info': scene.selected }">
                        <td>
                            <button class="a4" v-on:click="set_scene(scene.id, scene_key)">
                                <i class="fa fa-arrow-left"></i>
                            </button>
                        </td>
                        <td>{{ scene.id }}</td>
                        <td>{{ scene.title }}</td>
                        <td>{{ scene.level }}</td>
                        <td>{{ scene.parent_id }}</td>
                        <td>{{ scene.qty_childs }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    new Vue({
        el: '#corpo_app',
        created: function(){
            this.start();
        },
        data: {
            scenes: <?= $json_scenes ?>,
            current: {},
            level: 0,
            form_values:{
                title: '',
            }
        },
        methods: {
            start: function(){
                //this.current = this.scenes[0];
                this.set_scene(1,0);
            },
            set_scene: function(scene_id, scene_key){
                this.current = this.scenes.find(scene => scene.id === scene_id);
                this.set_selected(scene_key);
            },
            //Marcar scene selected
            set_selected: function(scene_key){
                //Recorrer scenes
                this.scenes.forEach(element => {
                    element.selected = false;
                });
                this.scenes[scene_key].selected = true;
            },
            send_form: function(){
                var new_scene = {
                    id: this.current.id * 10 + this.current.qty_childs + 1,
                    title: this.form_values.title,
                    parent_id: this.current.id,
                    qty_childs: 0,
                    level: this.current.level + 1,
                    selected: false
                };
                this.scenes.push(new_scene);
                this.current.qty_childs += 1;
                this.clean_form();
            },
            clean_form: function(){
                this.form_values.title = '';
            },
            save_poll: function(){
                const params = new URLSearchParams();
                params.append('content_json', JSON.stringify(this.scenes));
                axios.post(url_api + 'posts/update/13541', params)
                .then(response => {
                    if ( response.data.status == 1) {
                        toastr['success']('Encuesta guardada');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });  
            },
        }
    });
</script>