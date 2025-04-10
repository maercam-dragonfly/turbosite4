
/******************************************************************************/
function setCookie(name,s,days){
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else expires = "";
    document.cookie = name + "="+s+expires+";path=/";
}
/******************************************************************************/
function getCookie(nazwa) {
    if (document.cookie!="") {
    var toCookie=document.cookie.split("; ");
        for (i=0; i<toCookie.length; i++) {
            var nazwaCookie=toCookie[i].split("=")[0];
            var wartoscCookie=toCookie[i].split("=")[1];
            if (nazwaCookie==nazwa) return unescape(wartoscCookie)
        }
    }
    return null;
}
/******************************************************************************/
function changeStyle(x){
    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") ){
            a.disabled = true;
            if(a.getAttribute("title") == x) a.disabled = false;
        }
    }
    setCookie("style" + value,x,1);
}
/******************************************************************************/
function readCookie(value){
    var style = getCookie("style" + value);
    if(style != null){
        changeStyle(style);
    }
}
/******************************************************************************/
function getStyleSheets(styles) {
var i, a;
    for(i=0; (a = document.getElementsByTagName("link")[i]); i++) {
        if(a.getAttribute("rel").indexOf("style") != -1 && a.getAttribute("title") ){
            var name = a.getAttribute("title");
            styles[name] = name;
        }
    }
}
/******************************************************************************/
var styles = new Array();
getStyleSheets(styles);
var value = String(window.location.hostname);
readCookie(value);

function displayListStyle(){
   // alert(url);
    var ul = document.createElement("ul");
    for(st in styles){
            ul.innerHTML += '<li onclick = "changeStyle('+"\'"+ styles[st] +"\'"+')">' + styles[st] + '</li>';
    }
    var div = document.createElement('div');
    div.setAttribute('id', 'styles');
    div.appendChild(ul);
    var pa = document.getElementsByTagName('body')[0];
    if(pa.firstChild) {
        pa.insertBefore(div,pa.firstChild);
    }
    else {
        pa.appendChild(div);
    }

}
$(document).ready(function() 
    { 
    displayListStyle();
    //komunikatorInitalize();
})
/******************************************************************************/