<script id="sensors-table" type="text/template">
    <caption>
        <button type="button" id="add" class="btn-sensor">Add</button>
        <button type="button" id="cancel" class="btn-sensor">Cancel</button>
    </caption>
    <thead>
        <tr>
            <th column="id" style="width: 7%"><div>Device ID</div></th>
            <th column="office" style="width: 7%"><div>Office</div></th>
            <th column="location" style="width: 7%"><div>Location</div></th>
            <th column="LastTemp" style="width: 8%"><div>Temperature, C&deg;</div></th>
            <th column="minTemp" style="width: 8%"><div>Min Temp., C&deg;</div></th>
            <th column="maxTemp" style="width: 8%"><div>Max Temp., C&deg;</div></th>
            <th column="LastHumid" style="width: 8%"><div>Humidity, %</div></th>
            <th column="voltLevel" style="width: 8%"><div>Battery Level, %</div></th>
            <th column="LastTime" style="width: 8%"><div>Last Updated</div></th>
            <th column="workTimeFrom" style="width: 8%"><div>Work Time From</div></th>
            <th column="workTimeTo" style="width: 8%"><div>Work Time To</div></th> 
            <th column="comment" style="width: 8%"><div>Comments</div></th> 
            <th style="width: 7%"><div>Actions</div></th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</script>
<script id="sensor-row" type="text/template">
    <td><div><a href="/office/<%= office %>/sensor/<%= id %>"><%= id %></div></td>
    <td><div><a href="/office/<%= office %>/table">L<%= office %></a></div></td>
    <td><div><%= location %></div></td>
    <td class="<%= tempColor %>"><div><%= LastTemp %></div></td>
    <td><div><%= minTemp %></div></td>
    <td><div><%= maxTemp %></div></td>
    <td class="<%= humidColor %>"><div><%= LastHumid %></div></td>
    <td class="<%= voltColor %>"><div><%= voltLevel %></div></td>
    <td><div style="color: <%= timeColor %>"><%= LastTime %></div></td>
    <td><div><%= workTimeFrom %></div></td>
    <td><div><%= workTimeTo %></div></td>   
    <td><div><%= comment %></div></td>
    <td><div class="edit" id="edit-<%= id %>">Edit</div><div class="delete" id="delete-<%= id %>">Delete</div></td>
</script>
<script id="sensor-row-edit" type="text/template">
    <td><div><%= id %></div></td>
    <td class="office"><input type="text" col="office" value="<%= office %>" /></td>
    <td class="location"><input col="location" type="text" value="<%= location %>" /></td>
    <td><div><%= LastTemp %></div></td>
    <td><div><input type="text" col="minTemp" value="<%= minTemp %>" /></div></td>
    <td><div><input type="text" col="maxTemp" value="<%= maxTemp %>" /></div></td>
    <td><div><%= LastHumid %></div></td>
    <td><div><%= LastVolt %></div></td>
    <td><div><%= LastTime %></div></td>
    <td><div><input type="text" col="workTimeFrom" value="<%= workTimeFrom %>" /></div></td>
    <td><div><input type="text" col="workTimeTo" value="<%= workTimeTo %>" /></div></td>
    <td><div><input type="text" col="comment" value="<%= comment %>" /></div></td>
    <td><div class="back" id="back-<%= id %>">Back</div><div class="save" id="save-<%= id %>">Save</div></td>
</script>
<script id="sensor-row-delete" type="text/template">
    <td><div><%= id %></div></td>
    <td><div>L<%= office %></div></td>
    <td><div><%= location %></div></td>
    <td class="<%= tempColor %>"><div><%= LastTemp %></div></td>
    <td><div><%= minTemp %></div></td>
    <td><div><%= maxTemp %></div></td>
    <td class="<%= humidColor %>"><div><%= LastHumid %></div></td>
    <td class="<%= voltColor %>"><div><%= voltLevel %></div></td>
    <td><div style="color: <%= timeColor %>"><%= LastTime %></div></td>
    <td><div><%= workTimeFrom %></div></td>
    <td><div><%= workTimeTo %></div></td>   
    <td><div><%= comment %></div></td>
    <td><div class="back" id="back-<%= id %>">Back</div><div class="save" id="save-<%= id %>">Save</div></td>
</script>
<script id="sensor-row-add" type="text/template">
    <td><input type="text" col="id" value="<%= id %>" /></td>
    <td class="office"><input type="text" col="office" value="<%= office %>" /></td>
    <td class="location"><input type="text" col="location" value="<%= location %>" /></td>
    <td><div><%= LastTemp %></div></td>
    <td><div><input type="text" col="minTemp" value="<%= minTemp %>" /></div></td>
    <td><div><input type="text" col="maxTemp" value="<%= maxTemp %>" /></div></td>
    <td><div><%= LastHumid %></div></td>
    <td><div><%= LastVolt %></div></td>
    <td><div><%= LastTime %></div></td>
    <td><div><input type="text" col="workTimeFrom" value="<%= workTimeFrom %>" /></div></td>
    <td><div><input type="text" col="workTimeTo" value="<%= workTimeTo %>" /></div></td>
    <td><div><input type="text" col="comment" value="<%= comment %>" /></div></td>
    <td><div class="back" id="back-<%= id %>">Back</div><div class="save" id="save-<%= id %>">Save</div></td>
</script>
<script>
var Behaviors={};

Backbone.Marionette.Behaviors.behaviorsLookup = function() { 
    return Behaviors; 
}
Behaviors.Render = Marionette.Behavior.extend({
    events: {
        "click th": "headerClick",
        "click .edit": "editClick",
        "click .save": "saveClick",
        "click .delete": "deleteClick",
        "click #add": "addClick",
        "click #cancel": "cancelClick",
        "click .back" : "backClick",
    },
    editClick: function(e) {
        var $el = $(e.currentTarget),
        id = $el.attr('id').split('-', 2)[1],
        model = this.view.collection.get(id);
        model.set({state: 'edit'});
    },
    saveClick: function(e) {
        var $el = $(e.currentTarget),
            id = $el.attr('id').split('-', 2)[1],
            model = this.view.collection.get(id);
        if(model.get('state') === 'delete'){
            model.save({state: 'delete'});
            this.view.collection.remove(model);
        } else if(model.get('state') === 'add'){
            $("#add").show();
            $("#cancel").hide();
            model.save({
                id: $("input[col='id']").val(),
                comment: $("input[col='comment']").val(),
                office: $("input[col='office']").val(),
                location: $("input[col='location']").val(),
                workTimeFrom: $("input[col='workTimeFrom']").val(),
                workTimeTo: $("input[col='workTimeTo']").val(),
                minTemp: $("input[col='minTemp']").val(),
                maxTemp: $("input[col='maxTemp']").val()
            });
            model.set({state: 'default'});
        } else if(model.get('state') === 'edit'){
            model.save({
                comment: $("input[col='comment']").val(),
                office: $("input[col='office']").val(),
                location: $("input[col='location']").val(),
                workTimeFrom: $("input[col='workTimeFrom']").val(),
                workTimeTo: $("input[col='workTimeTo']").val(),
                minTemp: $("input[col='minTemp']").val(),
                maxTemp: $("input[col='maxTemp']").val()
            });
            model.set({state: 'default'});
        }
        this.view.render();
    },
    deleteClick: function(e) {
        var $el = $(e.currentTarget),
            id = $el.attr('id').split('-', 2)[1],
            model = this.view.collection.get(id);
        model.set({state: 'delete'});
    },
    backClick: function(e) {
        var $el = $(e.currentTarget),
            id = $el.attr('id').split('-', 2)[1],
            model = this.view.collection.get(id);
        model.set({state: 'default'});
        this.render();
    },
    addClick: function(e) {
        var model = this.view.collection.add(Sensor);
        model.set({state: 'add'});
        this.render();
        $("#add").hide();
        $("#cancel").show();
    },
    cancelClick: function(){
        var model = this.view.collection.findWhere({state: 'add'});
        this.view.collection.remove(model);
        this.render();
        $("#add").show();
        $("#cancel").hide();
    },
    headerClick: function(e) {
        var $el = $(e.currentTarget),
                ns = $el.attr('column'),
                cs = this.view.collection.sortAttribute;

        if (ns == cs) {
            this.view.collection.sortDirection *= -1;
        } else {
            this.view.collection.sortDirection = 1;
        }

        $el.closest('thead').find('span').attr('class', 'ui-icon none');

        if (this.view.collection.sortDirection == 1) {
            $el.find('span').removeClass('none').addClass(this.sortUpIcon);
        } else {
            $el.find('span').removeClass('none').addClass(this.sortDnIcon);
        }

        this.view.collection.sortSensors(ns);
    }
});
var Sensor = Backbone.Model.extend({
    defaults: {
        id: '0',
        office: '',
        location: '',
        LastTemp: '',
        minTemp: '0.0',
        maxTemp: '0.0',
        LastHumid: null,
        LastVolt: null,
        LastTime: '',
        timeColor: '',
        pastTime: '',
        comment: '',
        state: 'default',
        tempColor: 'normal',
        humidColor: 'normal',
        voltColor: 'normal',
        voltLevel: '',
        workTimeFrom: '',
        workTimeTo: ''
    }
});
var Sensors = Backbone.Collection.extend({
    model: Sensor,
    sortAttribute: "id",
    sortDirection: 1,
    url: "/summary.php",
    sortSensors: function(attr) {
        this.sortAttribute = attr;
        this.sort();
    },
    comparator: function(a, b) {
        if(this.sortAttribute === "id"){
            var a = parseFloat(a.get(this.sortAttribute)),
                    b = parseFloat(b.get(this.sortAttribute)); 
        } else {
            var a = a.get(this.sortAttribute),
                b = b.get(this.sortAttribute);
        }

        if (a == b)
            return 0;

        if (this.sortDirection == 1) {
            return a > b ? 1 : -1;
        } else {
            return a < b ? 1 : -1;
        }
    }

});

var SensorsTable = Backbone.Marionette.ItemView.extend({
    _sensorRowViews: [],
    tagName: 'table',
    template: null,
    sortUpIcon: 'up',
    sortDnIcon: 'down',
    behaviors: {
        Render:{}
    },
    initialize: function() {
        this.template = _.template($('#sensors-table').html());
        this.listenTo(this.collection, "sort", this.updateTable);
    },
    render: function() {

        this.$el.html(this.template());

        // Setup the sort indicators
        this.$('th div')
                .append($('<span>'))
                .closest('thead')
                .find('span')
                .addClass('ui-icon none')
                .end()
                .find('[column="' + this.collection.sortAttribute + '"] span')
                .removeClass('none').addClass(this.sortUpIcon);

        this.updateTable();

        return this;
    },
    updateTable: function() {

        var ref = this.collection,
                $table;

        _.invoke(this._sensorRowViews, 'remove');

        $table = this.$('tbody');

        this._sensorRowViews = this.collection.map(
                function(obj) {
                    var v = new SensorRow({model: ref.get(obj)});

                    $table.append(v.render().$el);

                    return v;
                });
    }

});
var SensorRow = Backbone.Marionette.CollectionView.extend({
    tagName: 'tr',
    template: null,
    initialize: function() {
        this.template = _.template($('#sensor-row').html());
        this.listenTo(this.model, 'change', this.render);
        this.model.collection.forEach(function(Sensor) {
            var temp = Sensor.get('LastTemp');
            var humid = Sensor.get('LastHumid');
            var volt = Sensor.get('LastVolt');
            var level = '';
            if(volt !== null){
                level = Math.min(Math.max(Math.ceil((volt-2.0)*100), 1), 100);
                //define battery cell color
                Sensor.set('voltLevel', level);
                if (level >= 76) {
                    Sensor.set('voltColor', 'comfort');
                } else if (level >= 51) {
                    Sensor.set('voltColor', 'yellow');
                } else if (level >= 26) {
                    Sensor.set('voltColor', 'orange');
                } else if (level <= 25) {
                    Sensor.set('voltColor', 'pink');
                }
            }
            if(temp !== null){
                //define temperature cell color
                if (temp > Sensor.get('maxTemp') && Sensor.get('maxTemp') !== '0.0') {
                    Sensor.set('tempColor', 'red');
                } else if (temp < Sensor.get('minTemp') && Sensor.get('minTemp') !== '0.0') {
                    Sensor.set('tempColor', 'blue');
                } else{
                    Sensor.set('tempColor', 'normal');
                }
            }
            if(humid !== null){
                //define humidity cell color
                if (humid > 60) {
                    Sensor.set('humidColor', 'red');
                } else if (humid < 25) {
                    Sensor.set('humidColor', 'blue');
                }
            }
            
        });
    },
    render: function() {
        if (this.model.get('state') === 'edit') {
            this.template = _.template($('#sensor-row-edit').html());
        } else if (this.model.get('state') === 'delete') {
            this.template = _.template($('#sensor-row-delete').html());
        } else if (this.model.get('state') === 'add') {
            this.template = _.template($('#sensor-row-add').html());
        } else if (this.model.get('state') === 'default') {
            this.template = _.template($('#sensor-row').html());
        }
        this.$el.html(this.template(this.model.toJSON()));

        return this;
    }
});
var SensorsRouter = Backbone.Router.extend({
    routes: {
        'edit/:id': 'edit',
        '*path':  'defaultRoute',
    },
    edit: function(id){
        this.collection = new Sensors();
        this.collection.fetch({success: function(response){
            response.get(id).set({'state': 'edit'});
            $('.wrapper').html(new SensorsTable({collection: response}).render().$el.attr('id', 'sensors'));
        }});
    },
    defaultRoute: function(){
        this.collection = new Sensors();
        this.collection.fetch();
        var sensorsView = new SensorsTable({collection: this.collection});
        $('.wrapper').html(sensorsView.render().$el.attr('id', 'sensors'));
    }
});
$(function() {
    new SensorsRouter();
    Backbone.history.start();
});
</script>        
  
<div class="container">
    <div class="wrapper"></div>
</div>
        

