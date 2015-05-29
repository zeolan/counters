var CountersListView = Backbone.View.extend({
    template: _.template($("#counters-collection-template").html()),

    events: {
        "click .addNewCounter": "addNewCounter",
        "click .removeCounter": "removeSubview"
    },

    initialize: function() {
        this._subviews = [];

		$('.main-menu').hide();
        this.collection = new CountersCollection();
		
		console.log('CountersListView->initialize');

        this.collection.fetch({ reset: true });

        this.listenTo(this.collection, "remove", this.removeSubview);
        //this.listenTo(this.collection, "add", this.addSubview);
        this.listenTo(this.collection, "reset", this.render);
    },

    removeSubview: function(model) {
		console.log('CountersListView->removeSubview');
		console.log(model);
        var view = _.find(this._subviews, function(view) { return view.model === model; });
        if (view) {
            this._subviews.splice(_.indexOf(this._subviews, view), 1);
            view.remove();
        }
    },

    addSubview: function(model) {
        var view = new CountersListItemView({ model: model });
        this.$(".collections").prepend(view.render().$el);
//		this.$("tbody").append(view.render().$el);
		console.log('CountersListView->addSubview');
        this._subviews.push(view);
    },

    render: function() {
		console.log('CountersListView->render');
        this.$el.html(this.template);

        _.invoke(this._subviews, "remove");

        this.collection.forEach(_.bind(this.addSubview, this));

        return this;
    },

    addNewCounter: function() {
        window.app.navigate("counters/new", {trigger: true});
		this.addSubview(new CounterModel({state: 'new'}));
    },

    remove: function() {
        _.invoke(this._subviews, "remove");

        Backbone.View.prototype.remove.apply(this, arguments);
    }
});