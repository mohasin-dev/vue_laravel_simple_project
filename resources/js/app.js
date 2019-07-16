
require('./bootstrap');

window.Vue = require('vue');
window.swal = require('sweetalert');


// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('product-component', require('./components/ProductComponent.vue').default);
Vue.component('shop-component', require('./components/ShopComponent.vue').default);
Vue.component('checkout-component', require('./components/CheckoutComponent.vue').default);

const app = new Vue({
    el: '#app',
});
