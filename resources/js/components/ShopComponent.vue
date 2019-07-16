<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background-color: #ccc;">
                        <h3 class="text-center">Available Products</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div v-for="product in products" v-bind:key="product.id" class="col-md-4 card card-body">
                                <img style="height: 120px; width: auto;" class="img-fluid" :src="'images/'+product.image" :alt="product.name"/>
                                <h4 class="text-center">{{ product.title }}</h4>
                                <h5 class="text-center">Price: ৳{{ product.price }}</h5>
                                <input style="text-align: center;" type="number" v-model="quantity" min="1" max="10">
                                <button @click="addCart(product)" class="mt-2 btn btn-success">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" style="background-color: #ccc;">
                        <h3 class="text-center">Cart Items</h3>
                    </div>
                    <div class="col-md-4 card-body">
                        <table class="table table-striped text-left">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>S.total</th>
                                    <th>Del</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(cart, n) in carts" v-bind:key="cart.id">
                                    <td>{{cart.title}}</td>
                                    <td>৳{{cart.price}}</td>
                                    <td><input style="width: 35px; text-align: center;" type="text" readonly v-model="cart.stock"></td>
                                    <td>{{ cart.stock *  cart.price}}</td>
                                    <td>
                                        <button @click="removeCart(n)" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <h4 class="text-right">Total Price: ৳{{ totalPrice }}</h4>
                        <p class="text-center"><a href="checkout" class="btn btn-primary">Proceed To Checkout</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 text-center mt-2">
                <nav>
                    <ul class="pagination">
                        <li v-bind:class="{disabled:!pagination.first_link}" class="page-item"><a href="#" @click="viewProduct(pagination.first_link)" class="page-link">&laquo;</a></li>
                        <li v-bind:class="{disabled:!pagination.prev_link}" class="page-item"><a href="#" @click="viewProduct(pagination.prev_link)" class="page-link">&lt;</a></li>
                        <li v-for="n in pagination.last_page" v-bind:key="n" v-bind:class="{active: pagination.current_page == n}" class="page-item"><a href="#" @click="viewProduct(pagination.path_page + n)" class="page-link">{{n}}</a></li>
                        <li v-bind:class="{disabled:!pagination.next_link}" class="page-item"><a href="#" @click="viewProduct(pagination.next_link)" class="page-link">&gt;</a></li>
                        <li v-bind:class="{disabled:!pagination.last_link}" class="page-item"><a href="#" @click="viewProduct(pagination.last_link)" class="page-link">&raquo;</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>

<style>
.subtotal{
    width: 80px;
}
</style>

<script>
export default {
    data(){
        return {
           products: [],
           carts: [],
           cartadd: {
               id: '',
               title: '',
               price: '',
               stock: '',
           },
           quantity: '1',
           totalPrice: '0',
           pagination: {}
        }
    },
    created() {
        this.viewProduct();
        this.viewCart();
    },
     methods: {
        viewCart(){
            if(localStorage.getItem('carts')){
                this.carts = JSON.parse(localStorage.getItem('carts'));
                this.totalPrice = this.carts.reduce((total, item)=>{
                    return total + item.stock * item.price;
                }, 0);
            }
        },
        addCart(pro){
            this.cartadd.id = pro.id
            this.cartadd.title = pro.title
            this.cartadd.price = pro.price
            this.cartadd.stock = this.quantity
            this.carts.push(this.cartadd);
            this.cartadd = {};
            this.storeCart();
        },
        removeCart(pro){
            this.carts.splice(pro, 1);
            this.storeCart();
        },
        storeCart(){
            let parsed = JSON.stringify(this.carts);
            localStorage.setItem('carts', parsed)
            this.viewCart();
        },
        viewProduct(pagi){
            pagi = pagi || 'api/products';
            fetch(pagi)
            .then(res => res.json())
            .then(res => {
                this.products = res.data
                this.pagination = {
                    current_page: res.meta.current_page,
                    last_page: res.meta.last_page,
                    from_page: res.meta.from,
                    to_page: res.meta.to,
                    total_page: res.meta.total,
                    path_page: res.meta.path+"?page=",
                    first_link: res.links.first,
                    last_link: res.links.last,
                    prev_link: res.links.prev,
                    next_link: res.links.next
                };
            })
            .catch(err => console.log(err));
        },
     }
}
</script>


