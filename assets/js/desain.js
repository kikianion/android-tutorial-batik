function addImage(src) {
    var imgOvr = new fabric.Image.fromURL(src, function (oImg) {
        oImg.scaleToWidth(200);
        canvas.insertAt(oImg, 0);
    });
}

function loadPage(id) {
    var i2 = $("#" + id);
    var src = $("#" + id)[0].src;

    var id = new Date().getTime();

    var s = $("#editor-page").html();
    s = s.replace('==::idrandom::==', id);
    $("#box-thumbs").html(s);

    var w = $("#canvas-div").innerWidth();

//        debugger;
    canvas = new fabric.Canvas('c-' + id);
    canvas.setHeight(400);
    canvas.setWidth(w);
    canvas.controlsAboveOverlay = true;

    //var img1 = new fabric.Image.fromURL("assets/images/frame/Baby/Baby Face/IMG-20160816-WA0013.jpg", function (oImg) {
    //    oImg.hasBorders = true;
    //    oImg.transparentCorners = false;
    //    oImg.scaleToWidth(300);
    //    //canvas.insertAt(oImg, 0);
    //});
    //
    canvas.setBackgroundColor({source: 'assets/images/stripe1.gif', repeat: 'repeat'}, function () {
        canvas.renderAll();
    });

    var imgOvr = new fabric.Image.fromURL(src, function (oImg) {
        //oImg.hasBorders = true;
        //oImg.transparentCorners = false;
        oImg.scaleToWidth(w);
        //canvas.insertAt(oImg, 0);
        canvas.setOverlayImage(oImg, canvas.renderAll.bind(canvas), {
            //left: 100,
            //top: 100,
        });
    });

    document.getElementById('imgLoader').onchange = function handleImage(e) {
        var reader = new FileReader();
        reader.onload = function (event) {
            console.log('fdsf');
            var imgObj = new Image();
            imgObj.src = event.target.result;
            imgObj.onload = function () {
                // start fabricJS stuff

                var image = new fabric.Image(imgObj);
                image.scaleToWidth(200);
                image.set({
                    //left: 250,
                    //top: 250,
                    //angle: 20,
                    //padding: 10,
                    //cornersize: 10
                });
                //image.scale(getRandomNum(0.1, 0.25)).setCoords();
                canvas.add(image);
                // end fabricJS stuff
            }
        }
        reader.readAsDataURL(e.target.files[0]);
    }
    canvas.renderAll();
}

var rect;
var canvas;

$(function () {
    canvas = new fabric.Canvas('c');
    canvas.setHeight(400);
    canvas.setWidth(500);
    canvas.controlsAboveOverlay = true;

    //var img1 = new fabric.Image.fromURL("images/frame/Baby/Baby Face/IMG-20160816-WA0013.jpg", function (oImg) {
    //    oImg.hasBorders = true;
    //    oImg.transparentCorners = false;
    //    canvas.insertAt(oImg, 0);
    //});
    //
    //canvas.setOverlayImage('images/frame/baby1-3.png', canvas.renderAll.bind(canvas));

    canvas.renderAll();
});


var currentStep = 1;

function btnStep(step) {
    if (step == 4) {
        step = 3;
    }
    currentStep = step;
    window.location = '#step=' + currentStep;

    $(".markStep").removeClass("active");
    $(".markStep-" + currentStep).addClass("active");

    var id = new Date().getTime();

    console.log("current step: " + currentStep);

    $("#box-thumbs").html("");

    if (currentStep == 1) {
        console.log("pilih template");
        $("#box-thumbs").html("Pilih template...");
    }
    else if (currentStep == 2) {
        $("#box-thumbs").html("Pilih salah satu halaman di kanan...");
        //var s = $("#editor-page").html();
        //s = s.replace('==::idrandom::==', id);
        //$("#box-thumbs").html(s);
        //
        //canvas = new fabric.Canvas('c-' + id);
        //canvas.setHeight(400);
        //canvas.setWidth(500);
        //canvas.controlsAboveOverlay = true;
        //
        //debugger;
        ////var img1 = new fabric.Image.fromURL("images/frame/Baby/Baby Face/IMG-20160816-WA0013.jpg", function (oImg) {
        ////    oImg.hasBorders = true;
        ////    oImg.transparentCorners = false;
        ////    oImg.scaleToWidth(300);
        ////    //canvas.insertAt(oImg, 0);
        ////});
        ////
        ////var imgOvr = new fabric.Image.fromURL("images/frame/Baby/Baby Face/IMG-20160816-WA0013.jpg", function (oImg) {
        ////    //oImg.hasBorders = true;
        ////    //oImg.transparentCorners = false;
        ////    oImg.scaleToWidth(500);
        ////    //canvas.insertAt(oImg, 0);
        ////    canvas.setOverlayImage(oImg, canvas.renderAll.bind(canvas), {
        ////        //left: 100,
        ////        //top: 100,
        ////    });
        ////});
        //
        //
        //canvas.renderAll();
    }
    else if (currentStep == 3) {
        $("#box-thumbs").html("Nota");
    }
}
function setActiveTemplate(id) {
    $(".cal-template-item").removeClass('active');
    $("#" + id).addClass('active');
}

function listFramePage(cat, page) {
    $("#box-pages").html("Loading...");
    var path = cat + '/' + page;
    $.ajax({
        url: 'frames/page/list/' + path,
        dataType: 'json',
        type: 'GET',
        success: function (response) {
            if (response.result == 'ok') {
                $("#box-pages").html('');
                if (response.data.length > 0) {
                    for (var i = 0; i < response.data.length; i++) {
                        var s = $("#tmpl-thumb-page").html();
                        s = s.replace(/==::cat::==/g, cat);
                        s = s.replace(/==::page::==/g, page);
                        s = s.replace(/==::name::==/g, response.data[i]);
                        s = s.replace(/==::id::==/g, i);
                        $("#box-pages").append(s);
                    }
                }
                else {
                    $("#box-pages").html("Kosong");

                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    });

}

function setActive(cat, page) {
    $(".thumb-all").removeClass('active');
    $("#" + cat + "-" + page).addClass('active');

}

function listCatFrames(cat) {
    $("#box-thumbs").html('Loading...');
    $.ajax({
        url: 'frames/list/' + cat,
        dataType: 'json',
        type: 'GET',
        success: function (response) {
            if (response.result == 'ok') {
                $("#box-thumbs").html('');
                if (response.data.length > 0) {
                    for (var i = 0; i < response.data.length; i++) {
                        var s = $("#tmpl-thumb-cat").html();
                        s = s.replace(/==::item::==/g, cat);
                        s = s.replace(/==::page::==/g, response.data[i]);
                        $("#box-thumbs").append(s);
                    }
                }
                else {
                    $("#box-thumbs").html('Kosong');

                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    });

}

$(function () {
    btnStep(1);

})
function openSave() {
}

var dialog1;
function openUploadImg() {
    dialog1 = $("#dialog1").dialog({
        uiLibrary: 'bootstrap',
        resizable: true,
        minWidth: 200,
        maxWidth: 900,
        minHeight: 200,
        maxHeight: 650,
        height: 350,
        modal: true
    });
}