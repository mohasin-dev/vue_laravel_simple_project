<template>
    <div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background-color: #ccc;">
                        <h3 class="text-center">Available Products</h3>
                    </div>
                    <div class="row">
                        <div style="border: 1px solid #f5f5f5;" v-for="product in products" v-bind:key="product.id" class="col-md-6 card-body">
                            <img style="height: 170px;" class="img-fluid" :src="'images/'+product.image" :alt="product.name"/>
                            <h4>{{ product.title }}</h4>
                            <p>{{ product.description }}</p>
                            <div class="row">
                                <div class="col-md-6">Price: ৳{{ product.price }}</div>
                                <div class="col-md-6">Stock: {{ product.stock }}</div>
                            </div>
                            <p class="text-center mt-2">
                                <button @click="editProduct(product)" class="btn btn-sm btn-warning">Edit</button>
                                <button @click="deleteProduct(product.id)" class="btn btn-sm btn-danger">Delete</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" style="background-color: #ccc;">
                        <h3>Add New Product</h3>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" v-model="product.title">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea type="text" class="form-control" v-model="product.description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="text" class="form-control" v-model="product.price">
                            </div>
                            <div class="form-group">
                                <label>Stock</label>
                                <input type="text" class="form-control" v-model="product.stock">
                            </div>
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" v-on:change="onImageChange">
                            </div>
                            <button @click.prevent="clearProduct()" class="btn btn-info">Cancle</button>
                            <button v-if="add" @click.prevent="addProduct()" class="btn btn-primary">Add Product</button>
                            <button v-if="edit" @click.prevent="updateProduct(product.id)" class="btn btn-warning">Update Product</button>
                        </form>
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

</style>

<script>
export default {
    data(){
        return {
           products: [],
           product: {
               id: '',
               title: '',
               description: '',
               price: '',
               stock: '',
               image: '',
           },
           add: true,
           edit: false,
           pagination: {}
        }
    },
    created() {
        this.viewProduct();
    },
    methods: {
         onImageChange(e){
                //console.log(e.target.files[0]);
                ////this.image = e.target.files[0];
                var fileReader = new FileReader()
                fileReader.readAsDataURL(e.target.files[0])
                fileReader.onload = (e) => {
                    this.product.image = e.target.result
                }
                //console.log(this.product)
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
        addProduct(){
            fetch('api/products', {
                method: 'post',
                body: JSON.stringify(this.product),
                headers: {
                    'content-type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                swal("Success!", "Product has been added", "success");
                this.product.title = '',
                this.product.description = '',
                this.product.price = '',
                this.product.stock = '',
                this.viewProduct();
            })
            .catch(err => {
                swal("failed!", "Something went wrong", "error");
            });
        },
        editProduct(pro){
            this.add = false;
            this.edit = true;
            this.product.id = pro.id
            this.product.title = pro.title
            this.product.description = pro.description
            this.product.price = pro.price
            this.product.stock = pro.stock
        },
        updateProduct(id){
            fetch(`api/products/${id}`, {
                method: 'put',
                body: JSON.stringify(this.product),
                headers: {
                    'content-type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                 swal("Success!", "Product has been updated", "success");
                this.product.title = '',
                this.product.description = '',
                this.product.price = '',
                this.product.stock = '',
                this.add = true;
                this.edit = false;
                this.viewProduct();
            })
            .catch(err => {
                swal("failed!", "Something went wrong", "error");
            });
        },
        deleteProduct(id){
            swal({
                title: "Are you sure?",
                text: "Product will be deleted",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,
            }).then((willdelete)=>{
                if(willdelete){
                    fetch(`api/products/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                    swal("Success!", "Product has been deleted", "success");
                        this.viewProduct();
                    })
                    .catch(err => {
                        swal("failed!", "Something went wrong", "error");
                    });
                }
            });
        },
        clearProduct(){
            this.add = true;
            this.edit = false;
            this.product.title = '',
            this.product.description = '',
            this.product.price = '',
            this.product.stock = ''
        }
    },
}
</script>

