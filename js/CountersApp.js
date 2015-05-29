var CountersApp = Backbone.Router.extend({
    routes: {
        /*"counters/new": "addCounter",
        /*"counters/:id/edit": "editCounter",*/
        "counters(/)": "viewCountersList",
        "(/)": "viewCountersList"
    },

    initialize: function() {
        this.activeView = undefined;
    },

    viewCountersList: function() {
        this.activeView && this.activeView.remove();

        this.activeView = new CountersListView();
        console.log('viewCountersList');
        $(".main-container").html(this.activeView.$el);
    }

    /*addCounter: function() {
        this.editCounter();
    },*/

    /*editCounter: function(id) {
        this.activeView && this.activeView.remove();

        var model = new CounterModel({ id: id });

        this.activeView = new EditCounterView({ model: model });

        $("body").html(this.activeView.$el);
    }*/
});