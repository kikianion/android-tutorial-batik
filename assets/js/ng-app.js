/**
 * Created by super1 on 028, 28/05/2016.
 */

var ngApp = angular.module('tanimap', ['ngRoute', 'ngAnimate', 'ngSanitize']);

ngApp.config(function ($routeProvider, $locationProvider, $httpProvider) {
    $routeProvider
        .when('/view/:path_module', {
            templateUrl: function (params) {
                r = Math.random();
                //$("#view1").html("<div class='loading'>Memuat...<i class='fa fa-spinner fa-pulse fa-2x'></i></div>");

                return 'view/' + params.path_module + '?r=' + r;
            },
            //controller: 'modController'
        })
        .otherwise({
            redirectTo: '/view/Home'
        });


    //$locationProvider.html5Mode(true);
    //$httpProvider.interceptors.push('myHttpResponseInterceptor');
    $httpProvider.interceptors.push('responseObserver');
});

ngApp.factory('responseObserver', function responseObserver($q, $window) {
    return {
        'response': function (response) {
            //debugger;

            if (response.data && response.data.indexOf('access-denied') != -1) {
                //response.data="<script type='text/javascript'>alert('Sesi login telah habis, silahkan login kembali');window.location='_auth/logout.php';</script>"+response.data;
            }

            return response;
        },
        'responseError': function (errorResponse) {
            //debugger;
            switch (errorResponse.status) {
                case 403:
                    //$window.location = './403.html';
                    break;
                case 500:
                    //$window.location = './500.html';
                    break;
            }
            return $q.reject(errorResponse);
        }
    };
});


ngApp.controller('homeController', function ($scope, $location, $route) {
    $scope.message = "Everyone come and see how good i look, hehe";
    $scope.pageClass = "page-home";
    $scope.routeName = $location.path();

    $scope.data.header_big = menu_group;
    $scope.data.header_small = menu_text;

    document.title = menu_group + " > " + menu_text + " - " + MAIN_TITLE;

    showTopLoading(false);
    //attach_ga_track();
    $scope.$on('$viewContentLoaded', function () {
        //console.log('content loaded');
        //attach_ga_track();
        //$scope.msg= $route.current.templateUrl + ' is loaded !!';
        var path = window.location.href;
        var title = document.title;

        gaTrack(path, title);

    });


});

ngApp.controller('modController',
    ['$scope', '$rootScope', '$location', '$route',
        function ($scope, $rootScope, $location, $route) {
            $scope.pageClass = 'page-home';

            $scope.data.header_big = menu_group;
            $scope.data.header_small = menu_text;

            if (page_icon == null) {
                page_icon = "";
            }
            $scope.data.page_icon_decode = decode_base64(page_icon);
            //$scope.data.page_icon_decode= $sce.trustAsHtml("<b>aaaaaaa</b>");;

            //document.title=MAIN_TITLE+" > "+menu_group+" > "+menu_text;

            $scope.$on('$viewContentLoaded', function () {
                //console.log('content loaded');
                //attach_ga_track();
                //$scope.msg= $route.current.templateUrl + ' is loaded !!';
                var path = window.location.href;
                var title = document.title;

            });

        }
    ]
);

function reload_route(){
    var appElement = document.querySelector('[ng-app="tanimap"]');
    var appScope = angular.element(appElement).scope();

    var e = appElement;

    var $injector = angular.element(e).injector();

    var $location = $injector.get('$location');
    var $route = $injector.get('$route');
    var $templateCache = $injector.get('$templateCache');

    var currentPageTemplate = $route.current.templateUrl;
    $templateCache.remove(currentPageTemplate);        

    $route.reload();
}
