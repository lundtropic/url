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
                    <option v-for="auth in auths" :value="auth.google_id">{{ auth.name }} ( {{ auth.email }} )</option>
                </select>
                <span class="help-block"><a href="/login/google/">Click here</a> to connect a new Google Account.</span>
            </div>
            <div class="form-group">
                <label>Name</label>
                <input type="text" class="form-control" placeholder="Name" v-model="name" :disabled="auth === ''">
                <span class="help-block">Optional. If left blank, server timestamp will be used.</span>
            </div>
            <div class="form-group">
                <label>HTML, Raw or Goo.gl URLs</label>
                <select v-model="htmlOrUrls" class="form-control">
                    <option value="html" selected>HTML</option>
                    <option value="urls">Raw URLs</option>
                    <option value="short_urls">Goo.gl URLs</option>
                </select>
                <span class="help-block">Choose to parse HTML or a URL list.</span>
            </div>
            <div class="form-group" v-if="htmlOrUrls == 'html'">
                <label>HTML</label>
                <textarea class="form-control" v-model="rawHtml" rows="10" :disabled="auth === ''"></textarea>
                <span class="help-block">Paste raw HTML. Links are extracted from all valid href attributes.</span>
                <strong :class="{'text-success': true, 'text-warning': parsedUrls.length < 1}">{{ parsedUrls.length }} URLs inputted.</strong>
            </div>
            <div class="form-group" v-if="htmlOrUrls == 'urls'">
                <label>Raw URLs</label>
                <textarea class="form-control" v-model="rawUrls" rows="10" :disabled="auth === ''"></textarea>
                <span class="help-block">Fully qualified URLs. One per line.</span>
                <strong :class="{'text-success': true, 'text-warning': parsedUrls.length < 1}">{{ parsedUrls.length }} URLs inputted.</strong>
            </div>
            <div class="form-group" v-if="htmlOrUrls == 'short_urls'">
                <label>Short URLs</label>
                <textarea class="form-control" v-model="rawShortUrls" rows="10" :disabled="auth === ''"></textarea>
                <span class="help-block">Goo.gl short URLs. One per line.</span>
                <strong :class="{'text-success': true, 'text-warning': parsedUrls.length < 1}">{{ parsedUrls.length }} URLs inputted.</strong>
            </div>
            <div class="form-group">
                <button class="btn btn-success" :disabled="((parsedUrls.length < 1)) || (isLoading)" @click="submit">
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

    export default {
        data() {
            return{
                auth: '',
                rawUrls: '',
                rawShortUrls: '',
                rawHtml: '',
                name: '',
                htmlOrUrls: 'html',
                parsedUrls: [],
                isLoading: false,
                expanded: true,
                auths: window.auths
            }
        },
        watch: {
            rawUrls: function () {
                this.parseUrls();
            },
            rawShortUrls: function () {
                this.parseShortUrls();
            },
            rawHtml: function () {
                this.parseHtml();
            }
        },
        methods: {
            parseShortUrls() {
                let items = this.rawShortUrls.split("\n");
                this.parsedUrls = [];

                collect(items).each(((item) => {
                    let validation = validate.single(item, {presence: true, url: true});
                    if(typeof validation === 'undefined'){
                        if(item.indexOf('goo.gl') !== -1){
                            this.parsedUrls.push(item);
                        }
                    }
                }).bind(this));
            },
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
            parseHtml() {
                this.parsedUrls = [];
                let items =  window.htmlHrefs(this.rawHtml);

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
                    auth: this.auth,
                    name: this.name
                };

                if(this.htmlOrUrls === 'short_urls'){
                    formData.type = 'existing';
                }else{
                    formData.type = 'new';
                }

                axios.post('/urls/store', formData)
                    .then((response) => {
                        this.$notify({
                            type: 'success',
                            text: 'URLs Added. You may view them in the table below.'
                        });

                        this.isLoading = false;
                        this.rawUrls = '';
                        this.rawShortUrls = '';
                        this.rawHtml = '';
                        this.parsedUrls = [];

                        vEvent.emit('new-urls', response.data.data);
                    })
                    .catch((error) => {
                        this.$notify({
                            type: 'error',
                            text: 'Couldn\'t do that for some reason. U sux Joel.'
                        });
                        this.isLoading = false;
                    });
            },
            toggleExpanded(){
                this.expanded = ! this.expanded;
            }
        }
    }
</script>