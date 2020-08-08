new Vue({ });
Vue.component('counter',
{
    data: function () {
        return {
            count: 0
        }
    },
    template: '<button @click="count++">{{count}}</button>'
}
);

var app = new Vue({
    el : '#app',
    data : {
        
    }
});
