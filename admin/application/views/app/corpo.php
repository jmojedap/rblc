<a href="<?= base_url("app/corpo/creator") ?>" class="btn btn-light w120p">
    <i class="fa fa-arrow-left"></i>
    Construir
</a>

<div id="corpo_app" class=" center_box_750">
    <div class="card">
        <div class="card-body">
            <h2>{{ current.title }}</h2>
            <p>
                Respuesta: {{ answers }} &middot;
                Level: {{ level }}
            </p>
            <p>¿Qué harías si...?</p>
            <div class="d-flex justify-content-around">
                <button
                    class="btn btn-lg w150p"
                    v-bind:class="{'btn-light': ! scene.selected, 'btn-info': scene.selected }"
                    v-for="(scene, scene_key) in scenes"
                    v-show="scene.parent_id == current.id"
                    v-on:click="set_answer(scene.id, scene_key)"
                    >
                    {{ scene.title }}
                </button>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <button class="btn btn-secondary w120p" v-show="current.parent_id > 0" v-on:click="set_scene(current.parent_id)">
            <i class="fa fa-arrow-left"></i> Atrás
        </button>
        <button class="btn btn-success w120p" v-show="level == max_level" v-on:click="save_answer">
            Guardar
        </button>
    </div>
</div>

<script>
    const scenes = [
        {id: 1, title: 'Inicio', parent_id: 0, level: 0, selected: false},
        {id: 11, title: 'Escena 11', parent_id: 1, level: 1, selected: false},
        {id: 12, title: 'Escena 12', parent_id: 1, level: 1, selected: false},
        {id: 13, title: 'Escena 13', parent_id: 1, level: 1, selected: false},
        {id: 111, title: 'Escena 111', parent_id: 11, level: 2, selected: false},
        {id: 112, title: 'Escena 112', parent_id: 11, level: 2, selected: false},
        {id: 113, title: 'Escena 113', parent_id: 11, level: 2, selected: false},
        {id: 121, title: 'Escena 121', parent_id: 12, level: 2, selected: false},
        {id: 122, title: 'Escena 122', parent_id: 12, level: 2, selected: false},
        {id: 123, title: 'Escena 123', parent_id: 12, level: 2, selected: false},
        {id: 131, title: 'Escena 131', parent_id: 13, level: 2, selected: false},
        {id: 132, title: 'Escena 132', parent_id: 13, level: 2, selected: false},
        {id: 133, title: 'Escena 133', parent_id: 13, level: 2, selected: false},
        {id: 1111, title: 'Escena 1111', parent_id: 111, level: 3, selected: false},
        {id: 1112, title: 'Escena 1112', parent_id: 111, level: 3, selected: false},
        {id: 1113, title: 'Escena 1113', parent_id: 111, level: 3, selected: false},
        {id: 1121, title: 'Escena 1121', parent_id: 112, level: 3, selected: false},
        {id: 1122, title: 'Escena 1122', parent_id: 112, level: 3, selected: false},
        {id: 1123, title: 'Escena 1123', parent_id: 112, level: 3, selected: false},
        {id: 1131, title: 'Escena 1131', parent_id: 113, level: 3, selected: false},
        {id: 1132, title: 'Escena 1132', parent_id: 113, level: 3, selected: false},
        {id: 1133, title: 'Escena 1133', parent_id: 113, level: 3, selected: false},
        {id: 1211, title: 'Escena 1211', parent_id: 121, level: 3, selected: false},
        {id: 1212, title: 'Escena 1212', parent_id: 121, level: 3, selected: false},
        {id: 1213, title: 'Escena 1213', parent_id: 121, level: 3, selected: false},
        {id: 1221, title: 'Escena 1221', parent_id: 122, level: 3, selected: false},
        {id: 1222, title: 'Escena 1222', parent_id: 122, level: 3, selected: false},
        {id: 1223, title: 'Escena 1223', parent_id: 122, level: 3, selected: false},
        {id: 1231, title: 'Escena 1231', parent_id: 123, level: 3, selected: false},
        {id: 1232, title: 'Escena 1232', parent_id: 123, level: 3, selected: false},
        {id: 1233, title: 'Escena 1233', parent_id: 123, level: 3, selected: false},
        {id: 1311, title: 'Escena 1311', parent_id: 131, level: 3, selected: false},
        {id: 1312, title: 'Escena 1312', parent_id: 131, level: 3, selected: false},
        {id: 1313, title: 'Escena 1313', parent_id: 131, level: 3, selected: false},
        {id: 1321, title: 'Escena 1321', parent_id: 132, level: 3, selected: false},
        {id: 1322, title: 'Escena 1322', parent_id: 132, level: 3, selected: false},
        {id: 1323, title: 'Escena 1323', parent_id: 132, level: 3, selected: false},
        {id: 1331, title: 'Escena 1331', parent_id: 133, level: 3, selected: false},
        {id: 1332, title: 'Escena 1332', parent_id: 133, level: 3, selected: false},
        {id: 1333, title: 'Escena 1333', parent_id: 133, level: 3, selected: false},
        {id: 11111, title: 'Escena 11111', parent_id: 1111, level: 4, selected: false},
        {id: 11122, title: 'Escena 11122', parent_id: 1112, level: 4, selected: false},
        {id: 11133, title: 'Escena 11133', parent_id: 1113, level: 4, selected: false},
        {id: 11211, title: 'Escena 11211', parent_id: 1121, level: 4, selected: false},
        {id: 11222, title: 'Escena 11222', parent_id: 1122, level: 4, selected: false},
        {id: 11233, title: 'Escena 11233', parent_id: 1123, level: 4, selected: false},
        {id: 11311, title: 'Escena 11311', parent_id: 1131, level: 4, selected: false},
        {id: 11322, title: 'Escena 11322', parent_id: 1132, level: 4, selected: false},
        {id: 11333, title: 'Escena 11333', parent_id: 1133, level: 4, selected: false},
        {id: 12111, title: 'Escena 12111', parent_id: 1211, level: 4, selected: false},
        {id: 12122, title: 'Escena 12122', parent_id: 1212, level: 4, selected: false},
        {id: 12133, title: 'Escena 12133', parent_id: 1213, level: 4, selected: false},
        {id: 12211, title: 'Escena 12211', parent_id: 1221, level: 4, selected: false},
        {id: 12222, title: 'Escena 12222', parent_id: 1222, level: 4, selected: false},
        {id: 12233, title: 'Escena 12233', parent_id: 1223, level: 4, selected: false},
        {id: 12311, title: 'Escena 12311', parent_id: 1231, level: 4, selected: false},
        {id: 12322, title: 'Escena 12322', parent_id: 1232, level: 4, selected: false},
        {id: 12333, title: 'Escena 12333', parent_id: 1233, level: 4, selected: false},
        {id: 13111, title: 'Escena 13111', parent_id: 1311, level: 4, selected: false},
        {id: 13122, title: 'Escena 13122', parent_id: 1312, level: 4, selected: false},
        {id: 13133, title: 'Escena 13133', parent_id: 1313, level: 4, selected: false},
        {id: 13211, title: 'Escena 13211', parent_id: 1321, level: 4, selected: false},
        {id: 13222, title: 'Escena 13222', parent_id: 1322, level: 4, selected: false},
        {id: 13233, title: 'Escena 13233', parent_id: 1323, level: 4, selected: false},
        {id: 13311, title: 'Escena 13311', parent_id: 1331, level: 4, selected: false},
        {id: 13322, title: 'Escena 13322', parent_id: 1332, level: 4, selected: false},
        {id: 13333, title: 'Escena 13333', parent_id: 1333, level: 4, selected: false},
        {id: 11111, title: 'Escena 11111', parent_id: 1111, level: 4, selected: false},
        {id: 11122, title: 'Escena 11122', parent_id: 1112, level: 4, selected: false},
        {id: 11133, title: 'Escena 11133', parent_id: 1113, level: 4, selected: false},
        {id: 11211, title: 'Escena 11211', parent_id: 1121, level: 4, selected: false},
        {id: 11222, title: 'Escena 11222', parent_id: 1122, level: 4, selected: false},
        {id: 11233, title: 'Escena 11233', parent_id: 1123, level: 4, selected: false},
        {id: 11311, title: 'Escena 11311', parent_id: 1131, level: 4, selected: false},
        {id: 11322, title: 'Escena 11322', parent_id: 1132, level: 4, selected: false},
        {id: 11333, title: 'Escena 11333', parent_id: 1133, level: 4, selected: false},
        {id: 12111, title: 'Escena 12111', parent_id: 1211, level: 4, selected: false},
        {id: 12122, title: 'Escena 12122', parent_id: 1212, level: 4, selected: false},
        {id: 12133, title: 'Escena 12133', parent_id: 1213, level: 4, selected: false},
        {id: 12211, title: 'Escena 12211', parent_id: 1221, level: 4, selected: false},
        {id: 12222, title: 'Escena 12222', parent_id: 1222, level: 4, selected: false},
        {id: 12233, title: 'Escena 12233', parent_id: 1223, level: 4, selected: false},
        {id: 12311, title: 'Escena 12311', parent_id: 1231, level: 4, selected: false},
        {id: 12322, title: 'Escena 12322', parent_id: 1232, level: 4, selected: false},
        {id: 12333, title: 'Escena 12333', parent_id: 1233, level: 4, selected: false},
        {id: 13111, title: 'Escena 13111', parent_id: 1311, level: 4, selected: false},
        {id: 13122, title: 'Escena 13122', parent_id: 1312, level: 4, selected: false},
        {id: 13133, title: 'Escena 13133', parent_id: 1313, level: 4, selected: false},
        {id: 13211, title: 'Escena 13211', parent_id: 1321, level: 4, selected: false},
        {id: 13222, title: 'Escena 13222', parent_id: 1322, level: 4, selected: false},
        {id: 13233, title: 'Escena 13233', parent_id: 1323, level: 4, selected: false},
        {id: 13311, title: 'Escena 13311', parent_id: 1331, level: 4, selected: false},
        {id: 13322, title: 'Escena 13322', parent_id: 1332, level: 4, selected: false},
        {id: 13333, title: 'Escena 13333', parent_id: 1333, level: 4, selected: false},
        {id: 11111, title: 'Escena 11111', parent_id: 1111, level: 4, selected: false},
        {id: 11122, title: 'Escena 11122', parent_id: 1112, level: 4, selected: false},
        {id: 11133, title: 'Escena 11133', parent_id: 1113, level: 4, selected: false},
        {id: 11211, title: 'Escena 11211', parent_id: 1121, level: 4, selected: false},
        {id: 11222, title: 'Escena 11222', parent_id: 1122, level: 4, selected: false},
        {id: 11233, title: 'Escena 11233', parent_id: 1123, level: 4, selected: false},
        {id: 11311, title: 'Escena 11311', parent_id: 1131, level: 4, selected: false},
        {id: 11322, title: 'Escena 11322', parent_id: 1132, level: 4, selected: false},
        {id: 11333, title: 'Escena 11333', parent_id: 1133, level: 4, selected: false},
        {id: 12111, title: 'Escena 12111', parent_id: 1211, level: 4, selected: false},
        {id: 12122, title: 'Escena 12122', parent_id: 1212, level: 4, selected: false},
        {id: 12133, title: 'Escena 12133', parent_id: 1213, level: 4, selected: false},
        {id: 12211, title: 'Escena 12211', parent_id: 1221, level: 4, selected: false},
        {id: 12222, title: 'Escena 12222', parent_id: 1222, level: 4, selected: false},
        {id: 12233, title: 'Escena 12233', parent_id: 1223, level: 4, selected: false},
        {id: 12311, title: 'Escena 12311', parent_id: 1231, level: 4, selected: false},
        {id: 12322, title: 'Escena 12322', parent_id: 1232, level: 4, selected: false},
        {id: 12333, title: 'Escena 12333', parent_id: 1233, level: 4, selected: false},
        {id: 13111, title: 'Escena 13111', parent_id: 1311, level: 4, selected: false},
        {id: 13122, title: 'Escena 13122', parent_id: 1312, level: 4, selected: false},
        {id: 13133, title: 'Escena 13133', parent_id: 1313, level: 4, selected: false},
        {id: 13211, title: 'Escena 13211', parent_id: 1321, level: 4, selected: false},
        {id: 13222, title: 'Escena 13222', parent_id: 1322, level: 4, selected: false},
        {id: 13233, title: 'Escena 13233', parent_id: 1323, level: 4, selected: false},
        {id: 13311, title: 'Escena 13311', parent_id: 1331, level: 4, selected: false},
        {id: 13322, title: 'Escena 13322', parent_id: 1332, level: 4, selected: false},
        {id: 13333, title: 'Escena 13333', parent_id: 1333, level: 4, selected: false}
    ];
</script>

<script>
    new Vue({
        el: '#corpo_app',
        created: function(){
            this.start();
        },
        data: {
            scenes: <?= $row->content_json ?>,
            current: {},
            answers: [0,0,0,0],
            level: 0,
            max_level: 4
        },
        methods: {
            start: function(){
                this.set_scene(1);
            },
            //Establercer respuesta en array answers
            set_answer: function(scene_id, scene_key){
                
                this.answers[this.level] = scene_id;
                this.clean_following();
                this.set_scene(scene_id);
                this.set_selected(scene_key);
            },
            //Establecer escena como la actual que se muestra
            set_scene: function(scene_id){
                this.current = this.scenes.find(scene => scene.id === scene_id);
                this.level = this.current.level;
            },
            //Marcar scene selected
            set_selected: function(scene_key){
                //Recorrer scenes
                this.scenes.forEach(element => {
                    //Desmarcar hermanos
                    if ( element.parent_id == this.current.parent_id ) { element.selected = false; }
                    //Desmarcar siguientes
                    if ( element.level > this.level ) { element.selected = false}
                });
                this.scenes[scene_key].selected = true;
            },
            //Borrar respuestas siguientes por ser dependientes
            clean_following: function(){
                for (let index = this.level + 1; index < this.answers.length; index++) {
                    this.answers[index] = 0;
                }
            },
            save_answer: function(){
                toastr['success']('Guardado');
            },
        }
    });
</script>