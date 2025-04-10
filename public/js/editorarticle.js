var b = new Array();
content = document.getElementById('content');
if(content != null){ 
    var editor = CodeMirror.fromTextArea(content, {
        lineNumbers: true,
        matchBrackets: true,
        mode: 'text/html',
        indentUnit: 4,
        indentWithTabs: true,
        enterMode: "keep",
        tabMode: "shift",
        onChange: function(){
            editor.save();
            var textarea = editor.getTextArea();
            b['from'] = $(textarea);
            b['where'] = '.tresc';
            $(b['where']).html(b['from'].val());
        },
       
        lineWrapping:true,
        theme: 'rubyblue'
    });      
}