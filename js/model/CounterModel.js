var CounterModel = Backbone.Model.extend({
    urlRoot: "/api/counters",

    defaults: {
        type: undefined,
        initValue: "0",
        curValue: "0",
		unit: "",
        title: "",
		total: "",
		active: "",
		state: "default"
    },

	initialize: function() {
		console.log("CounterModel->initialize");
		var initValue = parseInt(this.get(initValue));
		var curValue = parseInt(this.get(curValue));
		var totalValue = (curValue - initValue);
		if(!isNaN(totalValue)) this.set(total, totalValue);
	}
    /*validation: {
        type: [
            {
                required: true
            }
        ],
        initialValue: [
            {
                required: true
            }
        ]
    }*/
});

//_.extend(CounterModel.prototype, Backbone.Validation.mixin);