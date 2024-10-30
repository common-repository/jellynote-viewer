(function() {
	tinymce.create('tinymce.plugins.JellynoteEmbedPlugin', {
	  init : function(ed, url) {
      ed.addButton('jellynoteEmbed', {
      	title: 'jellynoteEmbed.jellynote',
      	image: url+'/jellynote-mce.png',
      	onclick: function() {
          ed.windowManager.open ({
            title: 'Embed a jellynote music score', 
            file: url + '/prompt.htm',
            width: 345,
            height: 190,
            inline: 1
          }, {
            plugin_url : url
          });
      	}
      });
    },
    createControl: function(n, cm) {
    	return null;
    },
    getInfo: function() {
    	return {
    		longname: 'Jellynote viewer shortcode',
    		author: 'Jellynote',
    		authorurl: 'http://jellynote.com',
    		infourl: 'http://jellynote.com',
    		version: '0.1'
    	};
    }
  });
  tinymce.PluginManager.add('jellynoteEmbed', tinymce.plugins.JellynoteEmbedPlugin);
 })();
