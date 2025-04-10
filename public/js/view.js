function podglad(){
    //div.innerHTML = '<h2>' + $("#title").val() +'</h2><p class="tresc">' + $("#content").val() + '<p class="tresc>"';     
    div.html('<div class="artukul">\n\
                    <h2> '+$("#title").val() + '</h2>\n\
                    <div class="data">\n\
                        <small class="dzien">' + Today.getDate() + '</small>\n\
                        <small class="miesac">'+ (Today.getMonth()+1) + '</small>\n\
                        <small class="rok">' + Today.getFullYear() + '</small>\n\
                    </div>\n\
                    <div class="tresc">' + $("#content").val() + '</div>\n\
                 </div>');

}
var div = null;
var Today = new Date();
jQuery(function($){
    $('#telefon').mask("999-999-999");
    $('#pesel').mask("99999999999");
    
});

$(document).ready(function(){
    
    
    tablesort();
    var view = document.getElementById('view');
    if(view != null){
        view.onclick = up;
    }
    var pola = $(".element-group").children().find('textarea, input');
    pola.click(function(){
        pola.css('background','#F0F0F0');
        $(this).css('background','#f1f7ff'); 
    });
    $('form').submit(function(){
        var telefon = $('#telefon');
        telefon.val(telefon.val().replace(/-/g, ""));
    });
  //  $('.CodeMirror-wrap').css( {'height': '130px','width': '548px'});
});
function up(){
    viewPodglad()
    podglad();
    $(document).keypress(podglad);
    $(document).keydown(podglad);
    $(document).keyup(podglad);
}
function viewPodglad(){
    div= $('<div>');
    div.css({
        'min-height':'600px',
        'max-height':'600px',
        'overflow-y':'scroll',
        'margin':'20px',
        'background':'#fff',
        'padding':'10px 10px 10px 20px',
        'width':'615px',
        'margin-left':'0'
    }); 
    $('.CodeMirror-scroll').css( {'height': '400px', 'width': '100%'});
    $('.CodeMirror-gutter').css( {'height': '400px'});
    var tlo = $('<div>').attr('id','tlo');
    var lewo = $('<div>').attr('id','lewo');
    var prawo =$('<div>').attr('id','prawo');
    var form = $('#article_form').parent();
    tlo.append(lewo);
    tlo.append(prawo);
   
    form.css({
        'margin':'20px',
        'background':'#fff',
        'height':'580px',
        'padding':'20px'
    });
    tlo.insertBefore('#container');
    $('html').css( 'overflow','hidden !important');
    $('#article_form textarea').css('height','400px');
    $('#article_form textarea').css('width','100%');
    tlo = $("#tlo"); 
    tlo.css({
        'background':'rgba(119,119,119,0.7)',
        'width':'100%',
        'height':'100%',
        'position':'fixed',
        'z-index':'50',
        'overflow':'hidden'
    });
  
    lewo.append(form);
    prawo.append(div);
    var lp = $("#lewo, #prawo");
    lp.css({
        'float':'left',
        'width':'50%',
        'height':'100%'
    });
    document.getElementById('view').onclick= function(oEvent){
        $('#article_form textarea').css('height','130px');
        $('#article_form textarea').css('width','448px');
        $('#left').append(form);
        form.css('margin','0').css('padding','0');
        $('#lewo, #prawo').remove();
        //div.parentNode.removeChild(div);
        $('#tlo').remove();
        document.getElementById('view').onclick = up;
        $('html').css( 'overflow','');
        $('.CodeMirror-scroll').css( {'height': '', 'width': '448px'});
        $('.CodeMirror-gutter').css( {'height': '150px'});
    };
    
   
    
   
}