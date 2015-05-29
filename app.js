var express = require('express');
var bodyParser = require('body-parser');
var app = express();

var connection = require('mysql').createConnection({
    host:'localhost',
    database:'mycounters',
    user:'root',
    password:'gadget',
    typeCast: false
});
console.log(connection.state);
connection.connect(function(err) {
  if (err) {
    console.error('error connecting: ' + err.stack);
    return;
  }
  console.log(connection.state);
  console.log('connected as id ' + connection.threadId);
});

var vsprintf = require('format').vsprintf;

/*connection.query('SELECT * FROM mycounters', function(err, rows) {
  // connected! (unless `err` is set) 
  if (err) {
    console.error('error quering: ' + err);
    return;
  }
  console.log('rows ');
  //console.log(rows[0]);
  //console.log(rows[1]);
  //console.log(rows[2]);
  //console.log(rows[3]);
  for(var i=0; i<rows.length; i++) {
	  //console.log(rows[i]);
  }
  return rows;
});*/

//console.log(result);

var counters = [
    {
		type: "electricity",
        id: 1,
        title: "Электроэнергия",
        initValue: 0,
        unit: ""
    },
    {
		type: "water",
        id: 2,
        title: "Вода",
        initValue: 0,
        unit: ""
    }
];

var nextId = 3;

app.use(express.static(__dirname));
app.use(bodyParser.json());
app.use(function (req, res, next) {
    if(req.url.indexOf("/api") === 0 ||
        req.url.indexOf("/bower-components") === 0 ||
        req.url.indexOf("/scripts") === 0) {
        return next();
    }

    res.sendFile(__dirname + '/index.html');
});

app.get('/api/counters', function(req, res) {
	console.log('GET /api/counters');
	//var rows;
	function queryCallback(err, rows) {
		
	}
	if(connection.state === 'authenticated') {
	    connection.query('SELECT * FROM mycounters', function(err, rows) {
            if (err) {
                console.error('error quering: ' + err);
                return;
            }
            console.log('rows ');
            //console.log(rows[0]);
            var result = [];
            for(var i=0; i<rows.length; i++) {
        	    console.log(rows[i]);
			    result[i] = rows[i];
            }
            res.json(result);
        });
	}
});

app.get('/api/counters/:id', function(req, res) {
    var counter = counters.filter(function(counter) { return counter.id == req.params.id; })[0];

    if(!counter) {
        res.statusCode = 404;
        return res.json({ msg: "Counter does not exist" });
    }

    res.json(counter);
});

app.post('/api/counters', function(req, res) {
    if(!req.body.type) {
        res.statusCode = 400;
        return res.json({ msg: "Invalid params sent" });
    }
	if(connection.state === 'authenticated') {
		var query = "SELECT MAX(id) FROM mycounters";
	    connection.query(query, function(err, rows) {
            if (err) {
                console.error('error quering: ' + err);
                return;
            };
			var newCounter = {
                id: (parseInt(rows[0]['MAX(id)'])+1),
                type : req.body.type,
                title : req.body.title || "",
                initValue : req.body.initValue,
                curValue : req.body.curValue,
        		unit: req.body.unit,
        		active: req.body.active || 1
            };

		    query = "INSERT INTO mycounters (id, type, initValue, curValue, unit, title, active) VALUES ";
            query += "('"+newCounter.id+"','"+newCounter.type+"','"+newCounter.initValue+"','"+newCounter.curValue+"','"+newCounter.unit+"','"+newCounter.title+"','"+newCounter.active+"')";
	        connection.query(query, function(err, rows) {
            if (err) {
                console.error('error quering: ' + err);
                return;
            }
            console.log('INSERT OK');
		    res.json(newCounter);
        });
        });
	} else {
        res.statusCode = 400;
        return res.json({ msg: "Can't connect to database" });
	}
});

app.put('/api/counters/:id', function(req, res) {
    if(!req.body.id) {
        res.statusCode = 400;
        return res.json({ msg: "Invalid params sent" });
    }
	if(connection.state === 'authenticated') {
		var query = vsprintf("SELECT * FROM mycounters WHERE id='%s'", req.body.id);
	    connection.query(query, function(err, rows) {
            if (err) {
                console.error('error quering: ' + err);
                return;
            }
			var counter = {};
			for(var i in req.body) {
				counter[i] = req.body[i];
			}
		    query = vsprintf("UPDATE mycounters SET type='%s', initValue='%s', curValue='%s', unit='%s', title='%s', active='%s' WHERE id='%s'",
			        [counter.type, counter.initValue, counter.curValue, counter.unit, counter.title, counter.active, counter.id]);
	        connection.query(query, function(err, rows) {
                if (err) {
                    console.error('error quering: ' + err);
                    return;
                }
                console.log('UPDATE OK');
		        res.json(counter);
            });
        });
	} else {
        res.statusCode = 400;
        return res.json({ msg: "Can't connect to database" });
	}
});

app.delete('/api/counters/:id', function(req, res) {
    if(!req.params.id) {
        res.statusCode = 400;
        return res.json({ msg: "Invalid params sent" });
    }
	if(connection.state === 'authenticated') {
		var query = vsprintf("SELECT * FROM mycounters WHERE id='%s'", req.params.id);
	    connection.query(query, function(err, rows) {
            if (err) {
                console.error('error quering: ' + err);
                return;
            }
			if(rows.length === 0) {
                res.statusCode = 404;
                return res.json({ msg: "Counter does not exist" });
            }
		    query = vsprintf("DELETE FROM mycounters WHERE id='%s'", req.params.id);
	        connection.query(query, function(err, rows) {
                if (err) {
                    console.error('error quering: ' + err);
                    return;
                }
                console.log('DELETE OK');
				return res.json({});
            });
        });
	} else {
        res.statusCode = 400;
        return res.json({ msg: "Can't connect to database" });
	}

});

app.listen(8001);