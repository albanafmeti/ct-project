<template>
    <form>

        <!-- pulse loader -->
        <div class="loading-div" v-if="is_loading">
            <div class="pulse-loader">
                <pulse-loader :loading="true" color="#007bff"></pulse-loader>
            </div>
        </div>

        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" placeholder="Name" v-model="product.name"/>
        </div>
        <div class="form-group">
            <label>Quantity in stock</label>
            <input type="number" name="quantity" class="form-control" placeholder="Quantity"
                   v-model="product.quantity"/>
        </div>
        <div class="form-group">
            <label>Price per item</label>
            <input type="number" name="price" class="form-control" placeholder="Price" v-model="product.price"/>
        </div>
        <p class="text-right">
            <button type="button" class="btn btn-primary" title="Add" :disabled="is_loading"
                    @click.prevent="store">
                <span class="oi oi-plus"></span>&nbsp;Add
            </button>
        </p>
    </form>
</template>

<script>
    import PulseLoader from 'vue-spinner/src/PulseLoader.vue';

    export default {
        name: "FormComponent",
        components: {
            PulseLoader
        },
        props: ['addProductUrl'],
        data: function () {
            return {
                is_loading: false,

                product: {
                    name: null,
                    quantity: null,
                    price: null
                }

            }
        },
        methods: {
            store() {
                this.is_loading = true;

                axios.post(this.addProductUrl, this.product).then(response => {
                    let data = response.data;

                    if (data.success) {
                        toastr.success(data.message);

                        this.product = {
                            name: null,
                            quantity: null,
                            price: null
                        };

                        this.$events.$emit('PRODUCT_ADDED');

                    } else {
                        toastr.error(data.message);
                    }
                }).catch(error => {
                    Helpers.handleError(error.response);
                }).finally(() => {

                    this.is_loading = false;

                });
            },
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
