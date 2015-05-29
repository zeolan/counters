var CountersCollection = Backbone.Collection.extend({
    model: CounterModel,

    url: "/api/counters"
});