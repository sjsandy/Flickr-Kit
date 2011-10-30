function fkset() {
    return "[fkimage_info url=\"\"]";

}

(function() {

    tinymce.create('tinymce.plugins.fkset', {

        init : function(ed, url){
            ed.addButton('fkset', {
                title : 'Insert Flickr Sets',
                onclick : function() {
                    var prompt_text = prompt("Flickr Set", "Set page url");
					var caret = " ";
					//var insert = "<div>" + prompt_text + "</div><span id="+caret+"></span>";
                                        var insert = "[fkset_info url=\""+prompt_text+"\"]";
                    if (prompt_text != null && prompt_text != 'undefined')
					{
                        ed.execCommand('mceInsertContent', false, insert);
						ed.selection.select(ed.dom.select('span#caret_pos_holder')[0]); //select the span
 						ed.dom.remove(ed.dom.select('span#caret_pos_holder')[0]); //remove the span
					}
                },
                image: url + "/graphic-design.png"
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

    tinymce.PluginManager.add('fkset', tinymce.plugins.fkset);

})();
