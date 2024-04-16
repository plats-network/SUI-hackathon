window.Vue = require('vue').default;
window.axios = require('axios');
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var ES6Promise = require("es6-promise");
ES6Promise.polyfill();
import ElementUI from 'element-ui';
import { ElementTiptapPlugin } from 'element-tiptap';
import QrcodeVue from 'qrcode.vue'
import locale from 'element-ui/lib/locale/lang/vi'
import * as VueGoogleMaps from "vue2-google-maps" // Import package
import VueSocialSharing from 'vue-social-sharing'
var filter = function(text, length, clamp){
    clamp = clamp || '...';
    var node = document.createElement('div');
    node.innerHTML = text;
    var content = node.textContent;
    return content.length > length ? content.slice(0, length) + clamp : content;
};

Vue.filter('truncate', filter);
// import DisableAutocomplete from 'vue-disable-autocomplete';
Vue.use(ElementUI, { locale })
Vue.use(ElementTiptapPlugin, { /* plugin options */ });
Vue.component('task', require('./components/task/Index.vue').default);
Vue.component('dashboard', require('./components/dashboard/index.vue').default);
Vue.component('task-edit', require('./components/task/Edit.vue').default);
Vue.component('task-create', require('./components/task/Create.vue').default);
Vue.component('reward', require('./components/reward/index.vue').default);
Vue.component('group', require('./components/group/index.vue').default);
Vue.component('user', require('./components/user/index.vue').default);
Vue.component('event', require('./components/event/index.vue').default);
Vue.component('event-edit', require('./components/event/edit.vue').default);
Vue.component('event-create', require('./components/event/create.vue').default);
Vue.component('event-preview', require('./components/event/preview.vue').default);
Vue.component('event-user', require('./components/event/event-user.vue').default);

// Vue.component('dashboard', require('./components/dashboard/index.vue').default);
Vue.component('home', require('./components/web/home.vue').default);
Vue.component('detail', require('./components/web/detail.vue').default);
Vue.component('likes', require('./components/web/likes.vue').default);
Vue.component('history', require('./components/web/history.vue').default);
import CKEditor from 'ckeditor4-vue';
Vue.use( CKEditor );
import VueQrcodeReader from "vue-qrcode-reader";
Vue.use(VueQrcodeReader);

Vue.use(VueSocialSharing);
Vue.config.productionTip = false
// Vue.use(VueGoogleMaps, {
//     load: {
//         key: "AIzaSyCXT96ABLIhOwW6sXq2Wi_ziycmm_60vo8",
//         libraries: "places"
//     }
// });

// Vue.use(DisableAutocomplete);
const app = new Vue({
    el: '#app',
});
