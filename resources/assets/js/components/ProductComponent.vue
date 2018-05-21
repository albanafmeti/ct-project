<template>
    <div>
        <!-- pulse loader -->
        <div class="loading-div" v-if="is_loading">
            <div class="pulse-loader">
                <pulse-loader :loading="true" color="#007bff"></pulse-loader>
            </div>
        </div>

        <div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Total Value</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="product in products">
                    <th scope="row">{{ product.id }}</th>
                    <td>{{ product.name }}</td>
                    <td>{{ product.quantity }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.created_at }}</td>
                    <td>{{ product.total_value }}</td>
                    <td>
                        <a href="#" class="btn btn-success" @click.prevent="editProduct(product)"><span
                            class="fa fa-pencil"></span></a>
                        <a href="#" class="btn btn-danger" @click.prevent="deleteProduct(product)"><span
                            class="fa fa-times"></span></a>
                    </td>
                </tr>
                <tr class="bg-primary text-light">
                    <th scope="row">#</th>
                    <th>TOTAL</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>{{ totalValueSum }}</th>
                    <th></th>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>

<script>

    import PulseLoader from 'vue-spinner/src/PulseLoader.vue';

    export default {
        name: "ProductComponent",
        components: {
            PulseLoader
        },
        props: ['productsUrl', 'deleteProductUrl'],
        data: function () {
            return {
                is_loading: false,

                products: []

            }
        },
        computed: {
            totalValueSum: function () {
                let sum = 0;
                for (let product of this.products) {
                    sum += product.total_value;
                }
                return sum;
            }
        },
        methods: {
            getList() {
                this.is_loading = true;

                axios.get(this.productsUrl)
                    .then(response => {

                        this.products = response.data.data;

                    }).catch(error => {

                    Helpers.handleError(error.response);

                }).finally(() => {

                    this.is_loading = false;

                });
            },
            editProduct(item) {
                this.$events.$emit('EDIT_PRODUCT', item);
            },
            deleteProduct(item) {
                let _this = this;
                this.$dialog.confirm(`<p class='confirm-dialog-p'>If you delete this record, it'll be gone forever.</p>`)
                    .then(function (dialog) {

                        let url = _this.deleteProductUrl.replace('__id__', item.id);
                        axios.delete(url)
                            .then(response => {
                                let data = response.data;
                                if (data.success) {
                                    toastr.success(data.message);
                                    _this.$events.$emit('PRODUCT_DELETED');

                                } else {
                                    toastr.error(data.message);
                                }
                            })
                            .catch(error => {
                                Helpers.handleError(error.response);
                            })
                            .finally(() => {
                                dialog.close();
                            });
                    });
            },
        },
        events: {
            'PRODUCT_ADDED': function () {
                this.getList();
            },
            'PRODUCT_DELETED': function () {
                this.getList();
            },
            'PRODUCT_UPDATED': function () {
                this.getList();
            },

        },
        created: function () {
            this.getList();
        }
    }
</script>

<style scoped>

    .loading-div {
        width: 100%;

    }
</style>
