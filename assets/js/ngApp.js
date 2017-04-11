var ngApp = angular.module('ngApp', []);
ngApp .config(function ($interpolateProvider) {
    //$interpolateProvider.startSymbol('{$');
    //$interpolateProvider.endSymbol('$}');
});
ngApp.controller('albumCtrl', function ($scope, $http, $rootScope) {
    $scope.makeFacebookPhotoURL=makeFacebookPhotoURL;
    $scope.loadData = function () {
        getAlbums(function(resp){
            $scope.$apply(function(){
               $scope.albumsData=resp.data;
            });
        });
    }
    $scope.openAlbum=function(id){
        getPhotosForAlbumId(id, function (albumId, albumPhotosResponse) {
            var i, facebookPhoto;
            var albumPhotos=[];
            for (i = 0; i < albumPhotosResponse.data.length; i++) {
                facebookPhoto = albumPhotosResponse.data[i];
                albumPhotos.push({
                    'id': facebookPhoto.id,
                    'added': facebookPhoto.created_time,
                    'url': makeFacebookPhotoURL(facebookPhoto.id, accessToken)
                });
            }
            $scope.$apply(function(){
                $scope.albumPhotos=albumPhotos;
            });
            //deferreds[albumId].resolve();
        });

    }
    //$scope.loadData();
});

function parseDetectionStock(res) {
    var data = [],
        row;
    res.data1 = hotDataSortBy(res.data1, "localCreated");
    res.data2 = hotDataSortBy(res.data2, "localCreated");

    var dataAll = [];
    for (var i = 0; i < res.data1.length; i++) {
        dataAll.push({
            "created": res.data1[i].created,
            "data1": res.data1[i].from_ + ":" + res.data1[i].cnt
        });

    }

    for (var i = 0; i < res.data2.length; i++) {
        dataAll.push({
            "created": res.data2[i].created,
            "data1": res.data2[i].target + ":" + res.data2[i].cnt
        });

    }

    res.data3 = hotDataSortBy(res.data3, "name");

    var dataResult = [];
    for (var i = 0; i < res.data3.length; i++) {
        var keyw = res.data3[i].keyByComa;
        var splitKw = keyw.split(",");
        var like_ = "";
        for (var j = 0; j < splitKw.length; j++) {
            if (j == splitKw.length - 1) {
                like_ = like_ + "lcase(data1) like '%" + splitKw[j] + "%' ";
            } else {
                like_ = like_ + "lcase(data1) like '%" + splitKw[j] + "%' and ";
            }
        }
        var a1 = alasql("select * from ? where " + like_ + " order by created desc limit 1", [dataAll]);
        var last_ = "-";
        var created_ = "-";
        var data1_ = "-";
        if (a1.length > 0) {
            var idx1 = a1[0].data1.indexOf(res.data3[i].startFind);
            var idx2 = a1[0].data1.lastIndexOf(res.data3[i].endFind);
            last_ = a1[0].data1.substring(idx1 + res.data3[i].startFind.length, idx2);
            created_ = a1[0].created;
            data1_ = a1[0].data1;
        }
        dataResult.push({
            "id": i,
            "created": created_,
            "last_": last_,
            "data1": data1_,
            "name": res.data3[i].name
        })
    }

    dataResult = hotDataSortBy(dataResult, "name asc, created asc");

    //remove dup by last date
    for (var k = 0; k < dataResult.length; k++) {
        for (var i = 0; i < dataResult.length; i++) {
            if (k != i && dataResult[k].name == dataResult[i].name) {
                if (dataResult[k].created > dataResult[i].created) {
                    dataResult.splice(i, 1);
                } else {
                    dataResult.splice(k, 1);
                }
            }
        }
    }

    for (var i = 0, ilen = dataResult.length; i < ilen; i++) {
        row = [];
        row[0] = parseInt(dataResult[i].id + 1);
        row[1] = dataResult[i].name;
        row[2] = dataResult[i].last_;
        row[3] = UTCtoJakarta(dataResult[i].created);
        row[4] = dataResult[i].data1;

        data[i] = row;
    }
    return dataResult;
}