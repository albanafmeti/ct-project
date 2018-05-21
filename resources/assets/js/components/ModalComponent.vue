<template>
    <div class="modal fade" id="edit-product-modal" tabindex="-1" role="dialog"
         aria-labelledby="Add Entity" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form v-if="product">

                        <!-- pulse loader -->
                        <div class="loading-div" v-if="is_loading">
                            <div class="pulse-loader">
                                <pulse-loader :loading="true" color="#007bff"></pulse-loader>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name"
                                   v-model="product.name"/>
                        </div>
                        <div class="form-group">
                            <label>Quantity in stock</label>
                            <input type="number" name="quantity" class="form-control" placeholder="Quantity"
                                   v-model="product.quantity"/>
                        </div>
                        <div class="form-group">
                            <label>Price per item</label>
                            <input type="number" name="price" class="form-control" placeholder="Price"
                                   v-model="product.price"/>
                        </div>
                        <p class="text-right">
                            <button type="button" class="btn btn-primary" title="Add" :disabled="is_loading"
                                    @click.prevent="update">
                                <span class="oi oi-plus"></span>&nbsp;Update
                            </button>
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</template>

<script>

    import PulseLoader from 'vue-spinner/src/PulseLoader.vue';

    export default {
        name: "ModalComponent",
        components: {
            PulseLoader
        },
        props: ['editProductUrl'],
        data: function () {
            return {
                is_loading: false,

                product: {
                    name: null,
                    quantity: null,
                    price: null
                }

            };
        },
        methods: {
            update() {
                this.is_loading = true;

                let url = this.editProductUrl.replace('__id__', this.product.id);
                axios.put(url, this.product).then(response => {
                    let data = response.data;

                    if (data.success) {
                        toastr.success(data.message);
                        $('#edit-product-modal').modal('hide');
                        this.$events.$emit('PRODUCT_UPDATED');

                    } else {
                        toastr.error(data.message);
                    }
                }).catch(error => {
                    Helpers.handleError(error.response);
                }).finally(() => {

                    this.is_loading = false;

                });
            },
        },
        events: {
            'EDIT_PRODUCT': function (product) {
                $('#edit-product-modal').modal('show');
                this.$nextTick(() => {
                    this.product = product;
                });
            }
        }
    }
</script>


<style lang="scss" scoped>

    form {
        position: relative;
    }

    .loading-div {
        position: absolute;
        text-align: center;
        left: 0;
        right: 0;
        width: 100%;
        height: 100%;
        z-index: 100;
        background-color: rgba(255, 255, 255, 0.7);

        .pulse-loader {
            position: absolute;
            top: 50%;
            width: 100%;
        }
    }
</style>
