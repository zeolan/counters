'use strict';

/* Controllers */

var phonecatControllers = angular.module('phonecatControllers', []);

phonecatControllers.controller('TrackListCtrl', ['$scope', 'Tracks',
  function($scope, Tracks) {
    $scope.tracks = Tracks.query();
	console.log(Tracks);
    $scope.orderProp = 'age';
	$scope.toogleTrack = function(index) {
		var trackDetails = $('.tracks-details#track-'+index);
		
		$('.col-md-10 > div').empty()
		                     .append('<img class="track-image" src='+$scope.tracks[index].imageUrl+'>')
		                     .append('<div class="track-wiki">'+$scope.tracks[index].wiki.join('')+'</div>');
		angular.forEach($scope.tracks, function(i, key) {
			console.log($scope.tracks[key]);
			if(key == index) {
				$(trackDetails).toggle();
				$( "li:eq("+index+")").toggleClass('selected');
			}
			else {
				$( "li:eq("+key+")").removeClass('selected');
				$('.tracks-details#track-'+key).hide();
			}
		});
		return true;
	}
	$scope.showMembers = function(index) {
		console.log("showMembers");
		var trackDetails = $('.tracks-details#track-'+index);
		$('.col-md-10 > div').empty()
		                     .append('<div class="row members"></div>');
        var memberTemplate = '<div class="col-md-3 track-member"><img src="/img/members/{0}.jpg" alt="{0}" width="100%" height="300px"></div>';
		angular.forEach($scope.tracks[index].members, function(i, key) {
			//console.log(memberTemplate.format(i));
			$('.row.members').append(memberTemplate.format(i));
			
		});
		return false;
	}
	$scope.showRoute = function(index) {
		console.log("showRoute");
		$('.col-md-10 > div').empty()
		                     .append('<iframe class="route-container" src='+ $scope.tracks[index].routeUrl +'></iframe>');
		return false;
	}
  }]);

phonecatControllers.controller('TrackRouteCtrl', ['$scope', '$routeParams', 'Tracks',
  function($scope, $routeParams, Tracks) {
    /*$scope.track = Tracks.get({year: $routeParams.year}, function(track) {
      //$scope.mainImageUrl = track.images[0];
	  console.log($routeParams.year);
    });*/

    $scope.setImage = function(imageUrl) {
      $scope.mainImageUrl = imageUrl;
    }
  }]);

  phonecatControllers.controller('MembersCtrl', ['$scope', '$routeParams', 'Track',
  function($scope, $routeParams, Track) {
    /*$scope.track = Track.get({year: $routeParams.year, isArray: true}, function(track) {
	  console.log(track);
	  console.log($routeParams.year);
    });*/
	console.log($routeParams);
	console.log($scope.track);
  }]);
