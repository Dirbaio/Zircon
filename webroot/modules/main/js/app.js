"use strict";

angular.module('app', [
	'monospaced.elastic',
	'angularFileUpload'
])

.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('{[').endSymbol(']}');
})

.factory('ajax', function($http, urlStyle) {
	function getCsrfToken() {
		var metas = document.getElementsByTagName('meta'); 
		for (var i=0; i < metas.length; i++) { 
			if (metas[i].getAttribute("name") == "csrftoken") { 
				return metas[i].getAttribute("content"); 
			}
		}
		return '';
	}

	return function (path, param, callback, error) {
		param.csrftoken = getCsrfToken();
		$http({
			method: 'POST',
			url: urlStyle(path),
			data: angular.toJson(param),
			transformResponse: []
		})
		.success(function(data, status, headers, config) {
			if(callback)
				callback(angular.fromJson(data));
			else
				window.location = angular.fromJson(data);
		})
		.error(function(data, status, headers, config) {
			if(error)
				error(data);
			else
				alert(data);
		});
	};
})
