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
                <div v-if="selectedCollection !== ''">
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
                selectedCollection: '',
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
            }
        },
        methods: {
            reload() {
                console.log('hello');
                axios.get('/urls/list')
                    .then((response) => this.collections = response.data.data);
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