<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <Page>
        <template v-slot:header>
            <div class="d-flex">
                <div class="align-self-center flex-grow-1">Total: {{ products.length }}</div>
            </div>
        </template>

        <template v-slot:body>
            <div v-if="loading">
                <div class="alert alert-secondary" role="alert">
                    Loading...
                </div>
            </div>

            <div v-if="!loading">
                <div v-if="products.length > 0">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" style="width: 50px;">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">
                                    <select class="form-control" v-model="selected" @change="changeDomain($event)">
                                        <option :value="null"></option>
                                        <option v-for="domain in domains" :value="domain.id" :key="domain.id">{{ domain.name }}</option>
                                    </select>
                                </th>
                                <th scope="col" style="width: 120px;"></th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="product in products">
                                <td>{{ product.id }}</td>

                                <td>
                                    <div>{{ product.name }}</div>

                                    <div>
                                        <a :href="product.url" target="_blank">{{ product.url }}</a>
                                    </div>
                                </td>

                                <td>{{ product.domain.name }}</td>

                                <td>
                                    <button @click.prevent="findMatch(product)" class="btn btn-sm btn-primary">Find match</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-if="products.length === 0" class="alert alert-secondary" role="alert">
                    No products.
                </div>

                <FindMatchModal @match="onMatch" @close="onModalClose" :product="matching"></FindMatchModal>
            </div>
        </template>
    </Page>
</template>

<script>
    import Page from "../Base/Layout/Page";
    import {http} from "../../mixins/http";
    import FindMatchModal from "./FindMatchModal";

    export default {
        name: "MatchingTool",

        components: {FindMatchModal, Page},

        mixins: [http],

        data() {
            return {
                loading: true,

                products: [],

                matching: null,

                domains: [],

                selected: null,

                domain_id: null
            }
        },

        methods: {
            onMatch(payload) {
                this.http().post('matching-tool/match', {
                    product_id: payload.id,
                    store_product_id : this.matching.id,
                });

                if (this.selected !== null){
                    this.getRequestByDomain(this.domain_id);
                } else this.refresh();

                this.onModalClose();
            },

            onModalClose() {
                this.matching = null;
            },

            findMatch(product) {
                this.matching = product;
            },

            refresh() {
                this.http().get(this.route('matching_tool.index'))
                    .then((response) => {
                        this.products = _.get(response, ['data'], []);
                    });
            },

            getRequestByDomain(domain) {
                domain = this.domain_id;

                this.http().get('matching-tool?domain=' + domain)
                    .then(response => {
                        this.products = _.get(response, ['data'], []);
                    });
            },

            changeDomain(event) {
                this.domain_id = event.target.value;

                if (this.selected !== null){
                   this.getRequestByDomain(this.domain_id);
                } else this.refresh();
            },
        },

        mounted() {
            this.loading = true;

            this.http().get(this.route('matching_tool.index'))
                .then((response) => {
                    this.products = _.get(response, ['data'], []);

                    this.loading = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },

        created() {
            this.domains = _.get(window, ['domains'], []);
        }
    }
</script>
