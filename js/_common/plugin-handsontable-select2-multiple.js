/// select2 plugin
(function (Handsontable) {
    "use strict";

    var RichTextEditor = Handsontable.editors.TextEditor.prototype.extend();

    var hotparent_multisel2_99;

    var optionsmultisel2 ;

    RichTextEditor.prototype.prepare = function (row, col, prop, td, originalValue, cellProperties) {
        Handsontable.editors.TextEditor.prototype.prepare.apply(this, arguments);

        optionsmultisel2 = {
            multiple:true,
            data: null,
        };

        if (cellProperties.select2Options) {
            optionsmultisel2 = $.extend(optionsmultisel2 , cellProperties.select2Options);
        }
    }

    RichTextEditor.prototype.createElements = function () {
        Handsontable.editors.TextEditor.prototype.createElements.apply(this, arguments);

        $("body").append("<div id='div-multisel2-holder99' title='#'>"+
            "<select type='text' id='multisel2-99'></select>"+
            "</div>"
        );

        var dialog1=$( "#div-multisel2-holder99" ).dialog({
            autoOpen: false,
            height: 400,
            width: 650,
            modal: true,
            buttons: {
                "Batal": function(){
                    dialog1.dialog( "close" );
                },
                "Simpan": function() {
                    dialog1.dialog( "close" );
                    hotparent_multisel2_99.instance.setDataAtCell(hotparent_multisel2_99.row, hotparent_multisel2_99.col, $("#multisel2-99").val().join());
                }
            },
            close: function() {
            }
        });
    };

    RichTextEditor.prototype.beginEditing = function(initialValue) {
        Handsontable.editors.TextEditor.prototype.beginEditing.apply(this, arguments);
        hotparent_multisel2_99=this;

        var val=this.instance.getDataAtCell(this.row, this.col);
        val=val.split(',');
        if(val==null){
            val='';
        }; 
        //empty inside html select, 
        $("#multisel2-99").html('');
        $( "#div-multisel2-holder99" ).dialog('open');
        if(optionsmultisel2.dlg_title)
            $( "#div-multisel2-holder99" ).dialog('option','title',optionsmultisel2.dlg_title);

        if($("#multisel2-99").data('select2')){
            $("#multisel2-99").select2('destroy');
        }
        
        $("#multisel2-99").select2(optionsmultisel2);
        $('#multisel2-99').val(val);
        $('#multisel2-99').trigger('change');
        $("#multisel2-99").focus();
        $("#multisel2-99").select2('open');
        $("#multisel2-99").select2('close');

    };    

    RichTextEditor.prototype.finishEditing= function(isCancelled, ctrlDown) {
        // Remember to invoke parent's method
        arguments[0]=true;

        //console.log('revert:'+arguments[0]);
        Handsontable.editors.TextEditor.prototype.finishEditing.apply(this, arguments);

    };

    Handsontable.editors.RichTextEditor = RichTextEditor;
    Handsontable.editors.registerEditor('select2multiple', RichTextEditor);

})(Handsontable);            
