var CountersListItemView =  Backbone.View.extend({
    //el: "<tr>",
	className: "col-lg-4",

    template: _.template($("#counter-view-template").html()),

    events: {
        "click .editCounter": "editCounter",
        "click .removeCounter": "removeCounter",
        "click .saveCounter": "saveCounter",
        "click .cancelCounter": "cancelCounter"
    },

    render: function() {
        if(this.model.get('state') === 'default') {
			this.$el.html(this.template(this.model.toJSON()));
		} else if(this.model.get('state') === 'new') {
			this.$el.addClass('tr-edit').html(_.template($("#counter-new-template").html())(this.model.toJSON()));
		    this.$el.find("select").val(this.model.get('type'));
		}
        return this;
    },

    editCounter: function() {
		console.log("editCounter");
		this.$el.addClass('tr-edit').html(_.template($("#counter-edit-template").html())(this.model.toJSON()));
		this.$el.find("select").val(this.model.get('type'));
    },

	saveCounter: function() {
		console.log("saveCounter");
		this.model.save({
			type: this.$el.find("select").val(),
			initValue: parseInt(this.$el.find("input[col='init']").val()),
			curValue: this.$el.find("input[col='current']").val(),
			title: this.$el.find("input[col='title']").val(),
			unit: this.$el.find("input[col='unit']").val()
		});
		this.$el.html(this.template(this.model.toJSON()));
    },

    cancelCounter: function() {
		console.log("cancelCounter");
		this.$el.removeClass('tr-edit');
        this.render();
    },
	
    removeCounter: function() {
		console.log("removeCounter");
        this.model.destroy({ id: this.model.get('id'), wait: true });
		this.render();
    }
});