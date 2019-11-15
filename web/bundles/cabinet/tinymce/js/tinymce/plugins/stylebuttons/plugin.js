tinyMCE.PluginManager.add('stylebuttons', function(editor, url) {
  ['pre', 'p', 'code', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'].forEach(function(name){
  caption = name.toUpperCase();
  if(name == 'h2')
	caption = 'Заголовок';
  if(name == 'p')
	caption = 'Параграф'; 

   editor.addButton("style-" + name, {
       tooltip: caption,
         text: caption,
         onClick: function() { editor.execCommand('mceToggleFormat', false, name); },
         onPostRender: function() {
             var self = this, setup = function() {
                 editor.formatter.formatChanged(name, function(state) {
                     self.active(state);
                 });
             };
             editor.formatter ? setup() : editor.on('init', setup);
         }
     })
  });
});