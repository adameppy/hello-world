(function() {

  'use strict';
  var express = require('express');
  var router = express.Router();
  var mongojs = require('mongojs');
  var db = mongojs('cproject', ['todos']);
  var db2 = mongojs('cproject', ['users']);

  /* GET home page. */
  router.get('/', function(req, res) {
    res.render('index');
  });

  router.get('/api/todos', function(req, res) {
    db.todos.find(function(err, data) {
      res.json(data);
    });
  });
  
   router.get('/api/usernames', function(req, res) {
    db2.users.find(function(err, data) {
		console.log(data);
      res.json(data);
    });
  });
  
   router.get('/api/usernames/:name', function(req, res) {
	//console.log("name:" + req.params.name);
    db2.users.findOne({
	username: req.params.name
	}, function(err, data) {
	console.log(data);
      res.json(data);
    });
  });
  
   router.post('/api/usernames', function(req, res) {
    db2.users.insert(req.body, function(err, data) {
      res.json(data);
    });

  });
  
 

  router.post('/api/todos', function(req, res) {
    db.todos.insert(req.body, function(err, data) {
      res.json(data);
    });

  });

  router.put('/api/todos', function(req, res) {

    db.todos.update({
      _id: mongojs.ObjectId(req.body._id)
    }, {
      isCompleted: req.body.isCompleted,
      todo: req.body.todo,
	  name: req.body.name
    }, {}, function(err, data) {
	console.log(data);
      res.json(data);
    });

  });
  
  router.get('/api/username/getWhoFollowing/:name', function(req,res){
    db2.users.find({
      username: req.params.name
    }, function(err, data){
      console.log(data.following);
      res.json(data.following);
    });
  });
  
  
  router.get('/api/todos/nowFollowing/:Follower/:toBeFollowed', function(req, res){
    db2.users.update({
		  //thing we search for to update
		  username: req.params.Follower
	  }, {
		  //thing we are updating
		 $push:{ following: toBeFollowed}
	  }, {}, function(err, data) {
		  console.log(data);
		  res.json(data);
	  });
  });
  
  router.post('/api/todos/following', function(req, res){
    
    
    db2.users.find({
      username: req.body.name
    }, function(err, data){
      var whoUserFollows = data;
    });
    
    db.todos.find({
      $or: [{},{}]
      }, function(err,data){});
    
    
  
    
    db.todos.find({followers: "username"},
		  function(err, data){
		    console.log(data);
		    res.json(data);
    });
    
  });
  
  router.put('/api/usernames', function(req, res){
	  db2.users.update({
		  //thing we search for to update
		  username: req.body.name
	  }, {
		  //thing we are updating
		 $set:{ picture: req.body.pic}
	  }, {}, function(err, data) {
		  console.log(data);
		  res.json(data);
	  });
  });

  router.delete('/api/todos/:_id', function(req, res) {
    db.todos.remove({
      _id: mongojs.ObjectId(req.params._id)
    }, '', function(err, data) {
      res.json(data);
    });
    
    
  });

  module.exports = router;

}());
