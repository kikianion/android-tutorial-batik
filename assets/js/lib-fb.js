function makeFacebookPhotoURL(id, accessToken) {
    return 'https://graph.facebook.com/' + id + '/picture?access_token=' + accessToken;
}

function makeFacebookPhotoURL(id) {
    return 'https://graph.facebook.com/' + id + '/picture?access_token=' + accessToken;
}

function login(callback) {
    FB.login(function (response) {
        if (response.authResponse) {
            accessToken = response.authResponse.accessToken || '';
            if (callback) {
                callback(response);
            }
        } else {
            console.log('User cancelled login or did not fully authorize.');
        }
    }, {scope: 'user_photos'});
}


function getAlbums(callback) {
    function getAlbums_sub(callback){
        FB.api(
            '/me/albums',
            {fields: 'id,cover_photo,name'},
            function (albumResponse) {
                if (callback) {
                    callback(albumResponse);
                }
            }
        );

    }

    if (!accessToken || accessToken == "") {
        login(function(){
            getAlbums_sub(callback);
        });
        return;
    }
    getAlbums_sub(callback);
}

function getPhotosForAlbumId(albumId, callback) {
    FB.api(
        '/' + albumId + '/photos',
        {fields: 'id'},
        function (albumPhotosResponse) {
            console.log(' got photos for album ' + albumId);
            console.dir(albumPhotosResponse);
            if (callback) {
                callback(albumId, albumPhotosResponse);
            }
        }
    );
}

function getLikesForPhotoId(photoId, callback) {
    FB.api(
        '/' + albumId + '/photos/' + photoId + '/likes',
        {},
        function (photoLikesResponse) {
            if (callback) {
                callback(photoId, photoLikesResponse);
            }
        }
    );
}

var accessToken = '';

function getPhotos(callback) {
    var allPhotos = [];
    login(function (loginResponse) {
        accessToken = loginResponse.authResponse.accessToken || '';

        getAlbums(function (albumResponse) {
            var i, album, deferreds = {}, listOfDeferreds = [];

            for (i = 0; i < albumResponse.data.length; i++) {
                album = albumResponse.data[i];
                deferreds[album.id] = $.Deferred();
                listOfDeferreds.push(deferreds[album.id]);
                getPhotosForAlbumId(album.id, function (albumId, albumPhotosResponse) {
                    var i, facebookPhoto;
                    for (i = 0; i < albumPhotosResponse.data.length; i++) {
                        facebookPhoto = albumPhotosResponse.data[i];
                        allPhotos.push({
                            'id': facebookPhoto.id,
                            'added': facebookPhoto.created_time,
                            'url': makeFacebookPhotoURL(facebookPhoto.id, accessToken)
                        });
                    }
                    deferreds[albumId].resolve();
                });
            }

            $.when.apply($, listOfDeferreds).then(function () {
                if (callback) {
                    callback(allPhotos);
                }
            }, function (error) {
                if (callback) {
                    callback(allPhotos, error);
                }
            });
        });
    });
}


/**
 * This is the bootstrap / app script
 */

// wait for DOM and facebook auth
var docReady = $.Deferred();
var facebookReady = $.Deferred();

$(document).ready(docReady.resolve);

window.fbAsyncInit = function () {
    FB.init({
        appId: '148190185254766',
        channelUrl: '//conor.lavos.local/channel.html',
        status: true,
        cookie: true,
        xfbml: true
    });
    facebookReady.resolve();
};

$.when(docReady, facebookReady).then(function () {
    return;
    if (typeof getPhotos !== 'undefined') {
        getPhotos(function (photos) {
            console.log(photos);
//                    debugger;
            for (i = 0; i < photos.length; i++) {
                $("#fb-root").append("<img src='" + photos[i].url + "' >");

            }
        });
    }
});

// call facebook script
(function (d) {
    var js, id = 'facebook-jssdk';
    if (d.getElementById(id)) {
        return;
    }

    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "http://connect.facebook.net/en_US/all.js";
    d.getElementsByTagName('head')[0].appendChild(js);
}(document));


function getF() {
    getAlbums(function (albumResponse) {
        var i, album, deferreds = {}, listOfDeferreds = [];

        for (i = 0; i < albumResponse.data.length; i++) {

//                    album = albumResponse.data[i];
//                    deferreds[album.id] = $.Deferred();
//                    listOfDeferreds.push(deferreds[album.id]);
//                    getPhotosForAlbumId(album.id, function (albumId, albumPhotosResponse) {
//                        var i, facebookPhoto;
//                        debugger;
//                        console.dir(albumPhotosResponse);
////                        for (i = 0; i < albumPhotosResponse.data.length; i++) {
////                            facebookPhoto = albumPhotosResponse.data[i];
////                            allPhotos.push({
////                                'id': facebookPhoto.id,
////                                'added': facebookPhoto.created_time,
////                                'url': makeFacebookPhotoURL(facebookPhoto.id, accessToken)
////                            });
////                        }
////                        deferreds[albumId].resolve();
//                    });
        }
//                console.log("album");
//                console.dir(albumResponse)
//                $.when.apply($, listOfDeferreds).then(function () {
//                    if (callback) {
//                        callback(allPhotos);
//                    }
//                }, function (error) {
//                    if (callback) {
//                        callback(allPhotos, error);
//                    }
//                });
    });

}
