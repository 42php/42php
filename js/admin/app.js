Vue.use(VueMaterial);

var store = {
    state: {
        user: {data: null},
        title: "Panneau d'administration"
    },
    updateUserData: function() {
        api.get('me', {}, {
            any: function(ret) {
                store.state.user = ret;
            }
        });
    }
};

var App = new Vue({
    el: '#app',
    data: {
        app: store
    },
    mounted: function() {
        this.app.updateUserData();
    }
});