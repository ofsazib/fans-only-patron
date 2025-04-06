import Vue from 'vue';
import Messages from './Messages.vue'
import CreatePost from './CreatePost.vue'
import UpdatePost from "./UpdatePost";

import Toast from "vue-toastification"
import VueChatScroll from 'vue-chat-scroll'
import VueImg from 'v-img'
import VTooltip from 'v-tooltip'

import 'vue-loading-overlay/dist/vue-loading.css'
import "vue-toastification/dist/index.css"

Vue.use(Toast, {'position': 'top-center'});
Vue.use(VueChatScroll);
Vue.use(VueImg);
Vue.use(VTooltip);

VTooltip.options.defaultTemplate = '<div class="tooltip-vue" role="tooltip"><div class="tooltip-vue-arrow"></div><div class="tooltip-vue-inner"></div></div>';
VTooltip.options.defaultArrowSelector = '.tooltip-vue-arrow, .tooltip-vue__arrow';
VTooltip.options.defaultInnerSelector = '.tooltip-vue-inner, .tooltip-vue__inner';

if(document.getElementById("vue-messages-app")) {
    const vueMessages = new Vue({
        render: h => h(Messages),
    }).$mount('#vue-messages-app');
}

if(document.getElementById("vue-create-post")) {
    const vuePostCreator = new Vue({
        render: h => h(CreatePost),
    }).$mount('#vue-create-post');
}

if(document.getElementById("vue-update-post")) {
    const vuePostCreator = new Vue({
        render: h => h(UpdatePost),
    }).$mount('#vue-update-post');
}