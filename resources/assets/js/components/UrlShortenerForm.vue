<template>
    <div class="panel panel-default">
        <div class="panel-heading" @click="toggleExpanded" style="cursor: pointer;">
            Generate URLs <span class="pull-right"><i class="fa" :class="{'fa-caret-up': ! expanded, 'fa-caret-down': expanded}"></i></span>
        </div>
        <div class="panel-body" v-show="expanded">
            <div class="form-group">
                <label>Google Account</label>
                <select v-model="auth" class="form-control">
                    <option value="">Choose One</option>
                    <option v-for="auth in auths" :value="auth.google_id">{{ auth.name }} ( {{ auth.email }}</option>
                </select>
                <span class="help-block"><a href="/login/google/">Click here</a> to connect a new Google Account.</span>
            </div>
            <div class="form-group">
                <label>URLs</label>
                <textarea class="form-control" v-model="rawUrls" rows="10" :disabled="auth === ''"></textarea>
                <span class="help-block">Fully qualified URLs. One per line.</span>
                <strong :class="{'text-success': true, 'text-warning': parsedUrls.length < 1}">{{ parsedUrls.length }} URLs inputted.</strong>
            </div>
            <div class="form-group">
                <button class="btn btn-success" :disabled="((parsedUrls.length < 1) && (auth === '')) || (isLoading)" @click="submit">
                    <span v-if="isLoading">
                        Submitting...
                    </span>
                    <span v-else>
                        Submit
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>


<script>
    import InputTag from 'vue-input-tag'

    export default {
        data() {
            return{
                auth: '',
                rawUrls: '',
                parsedUrls: [],
                tags: [],
                isLoading: false,
                expanded: true,
                auths: window.auths
            }
        },
        watch: {
            rawUrls: function () {
                this.parseUrls();
            }
        },
        methods: {
            parseUrls() {
                let items = this.rawUrls.split("\n");
                this.parsedUrls = [];

                collect(items).each(((item) => {
                    let validation = validate.single(item, {presence: true, url: true});
                    if(typeof validation === 'undefined'){
                        this.parsedUrls.push(item);
                    }
                }).bind(this));
            },
            submit() {
                this.isLoading = true;
                let formData = {
                    urls: this.parsedUrls,
                    auth: this.auth
                };
                axios.post('/urls/store', formData)
                    .then((response) => {
                        this.$notify({
                            type: 'success',
                            text: 'URLs Added. You may view them in the table below.'
                        });

                        this.isLoading = false;
                        this.rawUrls = '';
                        this.tags = [];

                        vEvent.emit('new-urls', response.data.data);
                    })
                    .catch((error) => {
                        this.$notify({
                            type: 'error',
                            text: 'Could not complete request.'
                        });
                        this.isLoading = false;
                    });
            },
            toggleExpanded(){
                this.expanded = ! this.expanded;
            }
        },
        components: {
            InputTag
        }
    }
</script>