<template>
    <div class="container">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div style="background-color: #ccc;" class="card-header"><h3 class="text-center">Order Details</h3></div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th width="100">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="cart in carts" v-bind:key="cart.id">
                                <td>{{ cart.title }}</td>
                                <td>৳{{ cart.price }}</td>
                                <td>{{ cart.stock }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="mt-2 text-right">
                        <p>Total Price: ৳{{ totalPrice }}</p>
                        <a href="#" class="btn btn-success">Checkout with Stripe</a>
                        <button class="btn btn-primary">Checkout with Paypal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<style>

</style>


<script>
export default {
    data(){
        return {
            carts: [],
            totalPrice: '0'
        }
    },
    created(){
        this.viewCart();
    },
    methods:{
        viewCart(){
            if(localStorage.getItem('carts')){
                this.carts = JSON.parse(localStorage.getItem('carts'));
                this.totalPrice = this.carts.reduce((total, item)=>{
                    return total + item.stock * item.price;
                }, 0);
            }
        }
    }
}
</script>

