function fkimage() {
    return "[fkimage_info url=\"\"]";

}

(function() {

    tinymce.create('tinymce.plugins.fkimage', {

        init : function(ed, url){
            ed.addButton('fkimage', {
                title : 'Insert Flickr Image',
                onclick : function() {
                    var prompt_text = prompt("Flickr Image", "Image page url");
					var caret = " ";
					//var insert = "<div>" + prompt_text + "</div><span id="+caret+"></span>";
                                        var insert = "[fkimage_info url=\""+prompt_text+"\"]";
                    if (prompt_text != null && prompt_text != 'undefined')
					{
                        ed.execCommand('mceInsertContent', false, insert);
						ed.selection.select(ed.dom.select('span#caret_pos_holder')[0]); //select the span
 						ed.dom.remove(ed.dom.select('span#caret_pos_holder')[0]); //remove the span
					}
                },
                image: url + "/fk_image.png"
            });
        },

        getInfo : function() {
            return {
                longname : 'Flickr image plugin',
                author : 'shawnsandy',
                authorurl : 'http://shawnsandy.com',
                infourl : '',
                version : "1.0"
            };
        }
    });

    tinymce.PluginManager.add('fkimage', tinymce.plugins.fkimage);

})();
