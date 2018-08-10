
/* JS Document 
* Chenyang Zhang CSCI 571 homework8
*/

var myApp = angular.module("congressApp", ['angularUtils.directives.dirPagination', 'ui.bootstrap']);

myApp.controller("congressCtr", function ($scope, $http) {
	$scope.favorite = {
		ids: [],
		legis: [],
		bills: [],
		comms: []
	};
	$scope.requestFor = function(keyword, opts){
		$scope.keyword = keyword;
		var FormData = {
			keyword: $scope.keyword
		};
		if(keyword == 'bills') {
			FormData['bills'] = opts;
		}
		if(keyword == 'committees') {
			FormData['committees'] = opts;
		}
		$http({
	      method: 'POST',
	      url: 'congress.php',
	      data: $.param(FormData),
	      headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
	    }).
		success(function(response) {
			var result = angular.fromJson(response);
			var result = angular.fromJson(result);
			switch(keyword){
				case 'legislators':
					$scope.legis = result.results;
					$.each($scope.legis, function (i, d) {
		            	d['full_name'] = d.last_name + ', ' + d.first_name;
		        	});
		        	break;
		        case 'bills':
					$scope.bills = result.results;
		        	break;
		        case 'committees':
					$scope.comms = result.results;
		        	break;
			}
			$scope.total = result.count;
		}).
	    error(function(response) {
	        alert("Request failed!" + response);
	    });
	};
	$scope.viewDetails = function(keyword, id){
		if(keyword == 'legislators'){
			var legidetails = {};
		    legidetails['legi'] = id;
		    //console.log(id);
		    var FormData = {
				keyword: 'bills',
				bio_id: id.bioguide_id
			};
			$http({
		      method: 'POST',
		      url: 'congress.php',
		      data: $.param(FormData),
		      headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
		    }).
			success(function(response) {
				var result = angular.fromJson(response);
				var result = angular.fromJson(result);
				legidetails['bills'] = result.results;
				//console.log(legidetails['bills']);
			}).
		    error(function(response) {
		        alert("Request failed!" + response);
		    });
		    FormData = {
				keyword: 'committees',
				bio_id: id.bioguide_id
			};
			$http({
		      method: 'POST',
		      url: 'congress.php',
		      data: $.param(FormData),
		      headers : { 'Content-Type': 'application/x-www-form-urlencoded' }
		    }).
			success(function(response) {
				var result = angular.fromJson(response);
				var result = angular.fromJson(result);
				legidetails['comms'] = result.results;
			}).
		    error(function(response) {
		        alert("Request failed!" + response);
		    });
		    $scope.legidetails = legidetails;
		}
		if(keyword =='bills') {
			$scope.billdetails = id;
		}
	};
	$scope.getProgress = function(start, end){
		var t = new Date();
		var s = new Date(start);
		var e = new Date(end);
		var cur = t.getTime() - s.getTime();
		var full = e.getTime() - s.getTime();
		var percent = cur / full;
		percent *= 100;
		percent = Math.round(percent);
		return percent;
	};

	$scope.addFavorite = function(key, obj){
		var list;
		var oid;
		switch(key){
			case 'legislators':
				oid = obj.bioguide_id;
				list = $scope.favorite.legis;
				break;
			case 'bills':
				oid = obj.bill_id;
				list = $scope.favorite.bills;
				break;
			case 'committees':
				oid = obj.committee_id
				list = $scope.favorite.comms;
				break;
		}
		list.push(obj);
		$scope.favorite.ids.push(oid);
	};

	$scope.removeFavorite = function(key, obj){
		var list;
		var oid;
		switch(key){
			case 'legislators':
				oid = obj.bioguide_id;
				list = $scope.favorite.legis;
				break;
			case 'bills':
				oid = obj.bill_id;
				list = $scope.favorite.bills;
				break;
			case 'committees':
				oid = obj.committee_id
				list = $scope.favorite.comms;
				break;
		}
		list.splice(list.indexOf(obj), 1);
		$scope.favorite.ids.splice($scope.favorite.ids.indexOf(oid), 1);
	};

	$scope.deleteFavorite = function(key, oid){
		var list;
		switch(key){
			case 'legislators':
				list = $scope.favorite.legis;
				break;
			case 'bills':
				list = $scope.favorite.bills;
				break;
			case 'committees':
				list = $scope.favorite.comms;
				break;
		}
		$scope.favorite.ids.splice($scope.favorite.ids.indexOf(oid), 1);
		var index, id;
		for(index = 0; index < list.length; index++){
			switch(key){
			case 'legislators':
				id = list[index].bioguide_id;
				break;
			case 'bills':
				id = list[index].bill_id;
				break;
			case 'committees':
				id = list[index].committee_id;
				break;
			}
			if(id == oid) break;
		}
		if(index != list.length){
			list.splice(index, 1);
		}
	};
});

myApp.filter('districtFilter', function() {
    return function(x) {
        var txt = "";
        if(x == "" || x == null) 
        	txt = "N.A";
        else
        	txt = "District " + x;
        return txt;
    }
});

myApp.filter('chamberFilter', function() {
    return function(x) {
        var txt = "";
        switch(x){
        	case 'house':
        		txt = "h.png";
        		break;
        	case 'senate':
        		txt = "s.svg";
        		break;
        	case 'joint':
        		txt = "s.svg";
        		break;
        }
        return txt;
    }
});

myApp.filter('partyFilter', function() {
    return function(x) {
        var txt = "";
        switch(x){
        	case 'D':
        		txt = "Democrat";
        		break;
        	case 'R':
        		txt = "Republican";
        		break;
        	case 'I':
        		txt = "Independent";
        		break;
        }
        return txt;
    }
});

myApp.filter('billFilter', function() {
    return function(x) {
        var txt = "";
        if(x)
        	txt = "Active";
        else
        	txt = "New";
        return txt;
    }
});

myApp.filter('capitalize', function(){
  return function(input, char){
  	if(input == null ) return input;
    if(isNaN(input)){
      var char = char - 1 || 0;
      var letter = input.charAt(char).toUpperCase();
      var out = [];
      for(var i = 0; i < input.length; i++){
        if(i == char){
          out.push(letter);
        } else {
          out.push(input[i]);
        }
      }
      return out.join('');
    } else {
      return input;
    }
  }
});



// Menu Toggle Script
$("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggle();
        if($("#page-wrapper").hasClass("col-xs-11 col-sm-10")){
        	$("#page-wrapper").removeClass("col-xs-11 col-sm-10");
        }
        else{
        	$("#page-wrapper").addClass("col-xs-11 col-sm-10");
        }
});

$(document).on('click','.viewLegi', function(e) {
        e.preventDefault();
        var scope = angular.element('#main-wrapper').scope();
        scope.keyword ="legislators";
        scope.viewDetails('legislators', $(this).data("legi"));
        scope.$apply();
});

$(document).on('click','.viewBill', function(e) {
        e.preventDefault();
        var scope = angular.element('#main-wrapper').scope();
        scope.keyword ="bills";
        scope.viewDetails('bills', $(this).data("bill"));
        scope.$apply();
});

$(document).on('click','.favorite', function(e) {
        e.preventDefault();
        var scope = angular.element('#main-wrapper').scope();
        scope.addFavorite($(this).data("key"), $(this).data("obj"));
        scope.$apply();
        if($(this).data("key") == "committees") {
        	$(this).find(".fa-stack > .fa-star").show();
        }
        $(this).removeClass("favorite").addClass("favorited");
});

$(document).on('click','.favorited', function(e) {
        e.preventDefault();
        var scope = angular.element('#main-wrapper').scope();
        scope.removeFavorite($(this).data("key"), $(this).data("obj"));
        scope.$apply();
        if($(this).data("key") == "committees") {
        	$(this).find(".fa-stack > .fa-star").hide();
        }
        $(this).removeClass("favorited").addClass("favorite");
});

$(document).on('click','.delete', function(e) {
        e.preventDefault();
        var scope = angular.element('#main-wrapper').scope();
        scope.deleteFavorite($(this).data("key"), $(this).data("id"));
        scope.$apply();
});