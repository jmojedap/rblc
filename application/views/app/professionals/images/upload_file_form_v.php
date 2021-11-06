<form accept-charset="utf-8" method="POST" id="file_form" @submit.prevent="send_file_form">
    <div class="form-group row">
        <div class="col-md-8">
            <select name="cat_1" v-model="form_values.cat_1" class="form-control" required>
                <option v-for="(option_cat_1, key_cat_1) in options_cat_1" v-bind:value="key_cat_1">{{ option_cat_1 }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-8">
            <input
                type="file" id="field-file"
                name="file_field" ref="file_field"
                required class="form-control"
                v-on:change="handle_file_upload()"
                >
        </div>
        <div class="col-md-4">
            <button class="btn btn-success btn-block" type="submit">Upload</button>
        </div>
    </div>
</form>

<div id="upload_response"></div>