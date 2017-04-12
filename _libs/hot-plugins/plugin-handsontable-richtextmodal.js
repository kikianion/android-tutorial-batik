/// select2 plugin
(function (Handsontable) {
    "use strict";

    var RichTextEditor = Handsontable.editors.TextEditor.prototype.extend();
    document.hotckeditor1_created=false;
    var hotparent;

    RichTextEditor.prototype.createElements = function () {
        // Call the original createElements method
        Handsontable.editors.TextEditor.prototype.createElements.apply(this, arguments);

        $("body").append("<div id='divckholder99'>"+
            "<textarea id='hotckmodal99' class='handsontableInput'></textarea>"+

            "<input type='submit' tabindex='-1' style='position:absolute; top:-1000px'>"+
            "</div>"
        );

        var dialog1=$( "#divckholder99" ).dialog({
            autoOpen: false,
            height: 400,
            width: 550,
            xmodal: true,
            buttons: {
                "Batal": function(){
                    dialog1.dialog( "close" );
                },
                "Simpan": function() {
                    dialog1.dialog( "close" );
                    hotparent.instance.setDataAtCell(hotparent.row, hotparent.col, CKEDITOR.instances['hotckmodal99'].getData());
                }
            },
            close: function() {
                //dialog1.dialog( "close" );            
            }
        });
        CKEDITOR.config.height="100%";
        CKEDITOR.replace( 'hotckmodal99' );
        CKEDITOR.instances['hotckmodal99'].on("instanceReady", function(event){
            document.hotckeditor1_created=true;
        });

    };

    RichTextEditor.prototype.open = function() {
        hotparent=this;
        Handsontable.editors.TextEditor.prototype.open.apply(this, arguments);

        var val=this.instance.getDataAtCell(this.row, this.col)+'';

        if(document.hotckeditor1_created){
            CKEDITOR.instances['hotckmodal99'].setData(val);

            setTimeout(function(){
                CKEDITOR.instances['hotckmodal99'].focus();
                },300);
        }
        else{
            setTimeout(function(){
                CKEDITOR.instances['hotckmodal99'].setData(val);
                setTimeout(function(){
                    CKEDITOR.instances['hotckmodal99'].focus();
                    },300);
                },1000);
        }
        $( "#divckholder99" ).dialog('open');
    };    

    RichTextEditor.prototype.finishEditing= function(isCancelled, ctrlDown) {
        // Remember to invoke parent's method
        arguments[0]=true;

        //console.log('revert:'+arguments[0]);
        Handsontable.editors.TextEditor.prototype.finishEditing.apply(this, arguments);

    };

    Handsontable.editors.RichTextEditor = RichTextEditor;
    Handsontable.editors.registerEditor('richTextModal', RichTextEditor);

})(Handsontable);            
