<template xmlns:v-slot="http://www.w3.org/1999/XSL/Transform">
    <Modal :show="showModal" :modal-id="'find-match-modal'" @close="onClose">
        <template v-slot:title>Find a match for: {{ matchingProductName }}</template>

        <div>
            <table class="table">
                <thead class="thead-light">
                    <tr>
                        <th scope="col" style="width: 70px;">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col" style="width: 150px;">Category</th>
                        <th scope="col" style="width: 150px;">Brand</th>
                        <th scope="col" style="width: 100px;"></th>
                    </tr>

                    <tr>
                        <th scope="col">
                            <input v-model="form.id" type="text" name="id" class="form-control" />
                        </th>
                        <th scope="col">
                            <input v-model="form.name" type="text" name="name" class="form-control" />
                        </th>
                        <th scope="col">
                            <select v-model="form.category_id" class="form-control" name="category">
                                <option></option>
                                <option v-for="category in categories" :value="category.id">{{ category.name }}</option>
                            </select>
                        </th>
                        <th scope="col">
                            <select v-model="form.brand_id" class="form-control" name="brand">
                                <option></option>
                                <option v-for="brand in brands" :value="brand.id">{{ brand.name }}</option>
                            </select>
                        </th>
                        <th scope="col"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="loading">
                        <td colspan="5">
                            <div class="alert alert-secondary" role="alert">
                                Loading...
                            </div>
                        </td>
                    </tr>

                    <tr v-for="result in results">
                        <td>{{ result.id }}</td>
                        <td>{{ result.name }}</td>
                        <td>{{ productCategory(result) }}</td>
                        <td>{{ productBrand(result) }}</td>
                        <td class="text-right">
                            <button @click.prevent="match(result)" class="btn btn-primary btn-sm">Match</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Modal>
</template>

<script>
    import Modal from "../Base/Layout/Modal";
    import {http} from "../../mixins/http";

    export default {
        name: "FindMatchModal",

        components: {Modal},

        props: ['product'],

        mixins: [http],

        data() {
            return {
                loading: false,

                results: [],

                form: {
                    id: '',
                    category_id: '',
                    brand_id: '',
                    name: ''
                },

                categories: [],

                brands: []
            }
        },

        computed: {
            showModal() {
                return !_.isNull(this.product) && !_.isUndefined(this.product);
            },

            hasResults() {
                return this.results.length > 0;
            },

            matchingProductName() {
                return _.get(this.product, ['name'], '');
            }
        },

        methods: {
            onClose() {
                this.reset();

                this.$emit('close');
            },

            reset() {
                this.results = [];

                this.form = {
                    id: '',
                    category_id: '',
                    brand_id: '',
                    name: ''
                }
            },

            doSearch() {
                this.results = [];

                this.loading = true;

                this.http().get(this.buildSearchUrl())
                    .then((response) => {
                        this.loading = false;

                        this.results = _.get(response, ['data'], [])
                    })
                    .catch(() => {
                        this.loading = false;
                    });
            },

            buildSearchUrl() {
                return this.route('matching_tool.search') + '?category_id=' + this.form.category_id
                    + '&brand_id=' + this.form.brand_id
                    + '&id=' + this.form.id
                    + '&name=' + decodeURIComponent(this.form.name);
            },

            productBrand(product) {
                return _.get(product, ['brand', 'name'], '');
            },

            productCategory(product) {
                return _.get(product, ['category', 'name'], '');
            },

            match(product) {
                this.$emit('match', product);
            }
        },

        created() {
            this.categories = _.get(window, ['categories'], []);
            this.brands = _.get(window, ['brands'], []);

            this.search = _.debounce(this.doSearch, 500);
        },

        watch: {
            'form.id': function () {
                this.search();
            },
            'form.category_id': function () {
                this.search()
            },
            'form.brand_id': function () {
                this.search();
            },
            'form.name': function () {
                this.search();
            },
        }
    }
</script>
