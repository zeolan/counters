'use strict';

/* Services */

var phonecatServices = angular.module('phonecatServices', ['ngResource']);

phonecatServices.factory('Tracks', ['$resource',
  function($resource){
    return $resource('tracks/tracks.json', {}, {
      query: {method:'GET', isArray:true}
    });
  }]);

phonecatServices.factory('Track', ['$resource',
  function($resource){
    return $resource('tracks/:year.json');
  }]);
