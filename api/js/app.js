var app = angular.module('myApp', [])
    .run(function ($rootScope) {
        $rootScope.Toggle = function () {
            $("#wrapper").toggleClass("toggled");
        };
    })
    .service('API', function($http) {
        var baseAPI = './api/php/';
        return {				
            getProgress: function() {
                return  $http.get(baseAPI + 'progress');
            },
            getTimeStamp: function() {			
                return $http.get(baseAPI + 'timestamp');                
            }  ,
            getNavBar: function() {
                return  $http.get('./static/navbar.html');
            },     
            getSideBar: function() {
                return  $http.get('./static/sidebar.html');
            },                        
        };     
    })
    .directive('compileTemplate', function($compile, $parse){
        return {
            link: function(scope, element, attr){
                var parsed = $parse(attr.ngBindHtml);
                function getStringValue() { return (parsed(scope) || '').toString(); }

                //Recompile if the template changes
                scope.$watch(getStringValue, function() {
                    $compile(element, null, -9999)(scope);  //The -9999 makes it skip directives so that we do not recompile ourselves
                });
            }         
        }
    });



    