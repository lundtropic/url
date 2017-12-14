<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                Processed URLs <span class="pull-right"><a href="http://goo.gl/">Goo.gl Dashboard</a></span>
            </div>
            <div class="panel-body">
                <div v-if="! urls.length" class="text-center">
                    <span class="text-info"><strong>Please process some URLs above.</strong></span>
                </div>
                <div v-else>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-condensed">
                            <thead>
                            <tr>
                                <th>
                                    Short
                                </th>
                                <th>
                                    Long
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="url in urls">
                                <td style="white-space: nowrap;"><a :href="url.short">{{ url.short }}</a></td>
                                <td><a :href="url.long">{{ url.long | truncate }}</a></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                urls: []
            }

        },
        mounted() {
            vEvent.on('new-urls', ((data) => {
                console.log(data);
                this.urls = data;
            }).bind(this));
        },
        filters: {
            truncate: function (value) {
                if (!value) return '';
                return value.substring(0, 75) + '...';
            }
        }
    }
</script>