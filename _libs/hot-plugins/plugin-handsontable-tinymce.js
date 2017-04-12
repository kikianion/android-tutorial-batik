/// select2 plugin
(function (Handsontable) {
    "use strict";

    var RichTextEditor = Handsontable.editors.TextEditor.prototype.extend();

    var hotparent99;



    RichTextEditor.prototype.createElements = function () {
        // Call the original createElements method
        Handsontable.editors.TextEditor.prototype.createElements.apply(this, arguments);

        $("body").append("<div id='divtmceholder99' title='Edit cell' style='background-color: gray'>"+
            "<textarea id='tmce99'></textarea>"+

            "<input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>"+
            "</div>"
        );

        var dialog1=$( "#divtmceholder99" ).dialog({
            autoOpen: false,
            height: 400,
            width: 650,
            xmodal: true,
            buttons: {
                "Batal": function(){
                    dialog1.dialog( "close" );
                },
                "Simpan": function() {
                    dialog1.dialog( "close" );
                    hotparent99.instance.setDataAtCell(hotparent99.row, hotparent99.col, tinymce.get('tmce99').getContent());
                }
            },
            close: function() {
                //dialog1.dialog( "close" );            
            }
        });

        // debugger;
        //return;
        tinymce.init({
            file_browser_callback: function(field, url, type, win) {
                tinyMCE.activeEditor.windowManager.open({
                    file: '../../libs/kcfinder/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
                    title: 'KCFinder',
                    width: 700,
                    height: 500,
                    inline: true,
                    close_previous: false
                    }, {
                        window: win,
                        input: field
                });
                return false;
            }
            ,          
            paste_data_images: true,  
            height: 135,
            images_upload_url: '../../libs/tinymce/php/upload.php',
            images_upload_base_path: '',
            images_upload_credentials: true,            
            selector: '#tmce99',
            plugins: [
                'advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker',
                'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                'save table contextmenu colorpicker directionality emoticons template paste textcolor'
            ],
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons'  });


    };

    RichTextEditor.prototype.open = function() {
        hotparent99=this;
        Handsontable.editors.TextEditor.prototype.open.apply(this, arguments);
        //Handsontable.editors.BaseEditor.prototype.open.apply(this, arguments);

        var val=this.instance.getDataAtCell(this.row, this.col)+'';

        if(tinymce.get('tmce99')){
            tinymce.get('tmce99').setContent(val);
        }
        else{
            setTimeout(function(){
                tinymce.get('tmce99').setContent(val);

                },1000);
        }

        $( "#divtmceholder99" ).dialog('open');
    };    

    RichTextEditor.prototype.finishEditing= function(isCancelled, ctrlDown) {
        // Remember to invoke parent's method
        arguments[0]=true;

        //console.log('revert:'+arguments[0]);
        Handsontable.editors.TextEditor.prototype.finishEditing.apply(this, arguments);

    };

    Handsontable.editors.RichTextEditor = RichTextEditor;
    Handsontable.editors.registerEditor('tinymce', RichTextEditor);

})(Handsontable);            
