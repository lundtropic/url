<template>
    <div>
        <div class="panel">
            <div class="panel-heading text-center">
                <span class="pull-left">Last Analytics Update: {{ lastUpdate }}</span>Processed URLs <span class="pull-right"><a href="http://goo.gl/" target="_blank">Goo.gl Dashboard</a></span>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <select class="form-control" v-model="selectedCollection">
                    <option v-for="collection in collections" :key="collection.id" :value="collection">
                        {{ collection.name !== null ? collection.name : collection.created_at }}
                    </option>
                </select>
                <div v-if="selectedCollection !== null">
                    <div class="row" style="margin:10px;">
                        <div class="col-lg-12">
                            <div class="pull-right">
                                <div class="form-inline">
                                    <div class="form-group" v-if="editing">
                                        <input type="text" class="form-control" v-model="selectedCollection.name">
                                        <button type="button" class="btn btn-primary"
                                                :disabled="selectedCollection.name.length < 1"
                                                @click="submitEdit"
                                        >Save</button>
                                        <button type="button" class="btn btn-default" @click="cancelEdit">X</button>
                                    </div>
                                    <button class="btn btn-info" v-if="!editing" @click="startEdit">Rename</button>
                                    <button class="btn btn-danger" @click="startDelete">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Short</th>
                            <th>Long</th>
                            <th>All Time</th>
                            <th>Month</th>
                            <th>Week</th>
                            <th>Day</th>
                            <th>Two Hours</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="url in selectedCollection.urls" :key="url.id">
                            <td><a :href="url.short_url" target="_blank">{{ url.short_url }}</a></td>
                            <td><a :href="url.long_url" target="_blank">{{ url.long_url | truncate }}</a></td>
                            <td>{{ url.all_time }}</td>
                            <td>{{ url.month }}</td>
                            <td>{{ url.week }}</td>
                            <td>{{ url.day }}</td>
                            <td>{{ url.two_hours }}</td>
                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ selectedCollection['totals']['all_time'] }}</td>
                            <td>{{ selectedCollection['totals']['month'] }}</td>
                            <td>{{ selectedCollection['totals']['week'] }}</td>
                            <td>{{ selectedCollection['totals']['day'] }}</td>
                            <td>{{ selectedCollection['totals']['two_hours'] }}</td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</template>

<style>
    tfoot{
        font-weight: bold;
    }
</style>

<script>
    export default {
        data() {
            return {
                collections: [],
                selectedCollection: null,
                editing: false,
                previousName: '',
                lastUpdate: null
            }

        },
        mounted() {
            this.reload();
            this.lastUpdate = window.last_update;

            vEvent.on('new-urls', () => this.reload());
        },
        watch: {
            collections(value) {
                value.forEach(function(item, key){
                    let collect = window.collect(item.urls);
                    item['totals'] = {};
                    item['totals']['all_time'] = collect.sum('all_time');
                    item['totals']['month'] = collect.sum('month');
                    item['totals']['week'] = collect.sum('week');
                    item['totals']['day'] = collect.sum('day');
                    item['totals']['two_hours'] = collect.sum('two_hours');
                });

                if(this.selectedCollection !== null){
                    this.selectedCollection = collect(this.collections).where('id', this.selectedCollection.id).first();
                }
            }
        },
        methods: {
            reload() {
                axios.get('/urls/list')
                    .then((response) => this.collections = response.data.data);
            },
            startEdit() {
                this.editing = true;
                this.previousName = this.selectedCollection.name;
            },
            cancelEdit() {
                this.selectedCollection.name = this.previousName;
                this.previousName = '';
                this.editing = false;
            },
            submitEdit() {
                axios.post('/urls/update/' + this.selectedCollection.id, {'name': this.selectedCollection.name})
                    .then((response) => this.successEdit(response.data.data))
                    .catch((response) => this.errorEdit(response));
            },
            successEdit(data) {
                this.reload();
                this.previousName = '';
                this.editing = false;

                this.$notify({
                    type: 'success',
                    text: 'Collection renamed'
                });
            },
            errorEdit(response) {
                this.$notify({
                    type: 'danger',
                    text: 'Couldn\'t do that for some reason. U sux Joel.'
                });
                this.previousName = '';
                this.editing = false;
            },
            startDelete() {
                let r = confirm('U sure bro?!');
                if(r === true){
                    axios.post('/urls/destroy/' + this.selectedCollection.id)
                        .then((response) => this.successDelete())
                        .catch((response) => this.errorDelete());
                }
            },
            successDelete() {
                this.reload();
                this.selectedCollection = null;
                this.$notify({
                    type: 'success',
                    text: 'Collection deleted.'
                });
            },
            errorDelete() {
                this.$notify({
                    type: 'danger',
                    text: 'Couldn\'t do that for some reason. U sux Joel.'
                });
                this.previousName = '';
                this.editing = false;
            }
        },
        filters: {
            truncate: function (value) {
                if (!value) return '';
                if(value.length < 76) return value;
                return value.substring(0, 75) + '...';
            }
        }
    }
</script>