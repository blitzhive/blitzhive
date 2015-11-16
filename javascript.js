
function addtag(tag,q) {
document.getElementById("txtE").focus();
	    var selected = "";
    if (typeof window.getSelection != "undefined") {
        var sel = window.getSelection();
        if (sel.rangeCount) {
            var container = document.createElement("div");
            for (var i = 0, len = sel.rangeCount; i < len; ++i) {
                container.appendChild(sel.getRangeAt(i).cloneContents());
            }
            selected = container.innerHTML;
        }
    } else if (typeof document.selection != "undefined") {
        if (document.selection.type == "Text") {
            selected = document.selection.createRange().htmlText;
        }
    }
	
var txt = document.getElementById('txtE');
var url = document.getElementById('linkUrl').value;	
var urlT = document.getElementById('linkUrlTag').value;	
var urlFile =  tag.substring(tag.lastIndexOf('/')+1); 
//var urlFile =  selected.substring(selected.lastIndexOf('/')+1); 
if(q==1){
if(document.getElementById('poimg'))document.getElementById('poimg').value=tag;
}

	var strTag=tag;
	
	if(q==1){		
	//alert("-"+urlFile+"-"+tag)
	document.execCommand('insertHTML', false, '<a href="'+tag+'" target="_blank" title="'+urlFile+'"><img class="imgUploadText"  src="'+tag+'"/></a>');
	//subtag="</a>";
	} else if(q==2){
		document.execCommand('insertHTML', false, '<video class="videoUploadText" src="'+tag+'" controls>Tag video not supported</video>');
		/*strTag='<video class="videoUploadText" src="'+selected+'" controls>Tag video not supported';
	subtag='</video>';*/
	} else if(q==3){
		/*strTag='<a href="'+selected+'" target="_blank" title="Download '+urlFile+'"><b><u>'+urlFile+'</u></b>(↓)';
	subtag="</a>";	*/
	document.execCommand('insertHTML', false, '<a href="'+tag+'" target="_blank" title="Download '+urlFile+'"><b><u>'+urlFile+'</u></b>(↓)</a>');
	}
	
	if(tag=="a"){
		document.execCommand('insertHTML', false, '<a href="'+url+'" target="_blank" title="'+urlFile+'">'+selected+'</a>');

        //document.execCommand("createLink", false, url);
		/*strTag='<a target="_blank" rel="nofollow"';
	subtag='</a>';*/
	}else if(tag=="at"){
		document.execCommand('insertHTML', false, '<a href="'+urlT+'" target="_blank" title="'+urlFile+'">'+selected+'</a>');
		 //document.execCommand("createLink", false, urlT);
		/*strTag='<a target="_blank" rel="nofollow"';
	subtag='</a>';*/
	}else if(tag=="img"){
		document.execCommand('insertHTML', false, '<a href="'+selected+'" target="_blank" title="'+urlFile+'"><img class="imgUploadText"  src="'+selected+'"/></a>');
	
		//document.execCommand ('insertImage', false, selected);	
		/*strTag='<img ';
	subtag='</img>';*/
	}else if(tag=="video"){
	document.execCommand('insertHTML', false, '<video class="videoUploadText" src="'+selected+'" controls>Tag video not supported</video>');
	
	
	}	
	else if(tag=="code"){
		
		//document.execCommand('formatBlock', false, 'BLOCKQUOTE');
		document.execCommand('insertHTML', false, '<pre><code onclick="fSelCode(this)" >'+selected+'</code></pre>');
		/*strTag='<pre><code onclick="fSelCode(this)" >';
	subtag='</code></pre>';*/
	}else if(tag=="u")
	{
		document.execCommand("underline", false, null);
	
		
	/*strTag='<'+strTag+'>';
	subtag='</'+tag+'>';*/	
	}else if(tag=="b"){
	document.execCommand ('bold', false, null);	
	}else if(tag=="i"){
	document.execCommand ('italic', false, null);	
	}else if(tag=="s"){
	document.execCommand ('strikeThrough', false, null);	
	}else if(tag=="h1"){
	//document.execCommand ('heading', false, 'h1');	
	document.execCommand('formatBlock', false, '<h1>');
	}
	else if(tag=="h2"){
	document.execCommand('formatBlock', false, '<h2>');
	}
	else if(tag=="h3"){
	document.execCommand('formatBlock', false, '<h3>');
	}/*else if(tag=="img"){		
	document.execCommand("InsertImage", false, null);
	}*/
	else if(tag=="remove"){	
	document.execCommand('removeFormat', false, "");	
	//document.execCommand('formatBlock', false, 'div')
	//document.execCommand("normal", false, null);
	//document.execCommand('Unselect');
	//document.execCommand('Undo');
	/*document.execCommand('formatBlock', false, 'div');*/
	//document.execCommand('removeFormat', false, null);	
	}else if(tag=="unlink"){	
	document.execCommand("unlink", false, null);
	//document.execCommand('insertparagraph')
	}else if(tag=="adsense"){	
	//q=document.getElementById('adsenseCode').value;		
	document.execCommand('insertHTML', false, "<h1>**Adsense Block**</h1>");
	}else if(tag=="youtube"){	
	//q=document.getElementById('adsenseCode').value;		
	//https://www.youtube.com/embed/QaqZn7g_59w" frameborder="0" allowfullscreen></iframe>
	selected=selected.replace("watch?v\=", "embed/"); 
	document.execCommand('insertHTML', false, '<iframe width="640" height="360" src=\"'+selected+'\"  frameborder="0" allowfullscreen></iframe>');
	}else if(tag=="vimeo"){		
	
	/*
	https://vimeo.com/96242634
	<iframe src="https://player.vimeo.com/video/96242634" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe> <p><a href="https://vimeo.com/96242634">Shakira - La La La</a> from <a href="https://vimeo.com/user28370580">Felipe Costa</a> on <a href="https://vimeo.com">Vimeo</a>.</p>
	**/
	//q=document.getElementById('adsenseCode').value;
	selected=selected.replace("vimeo.com", "player.vimeo.com/video"); 	
	document.execCommand('insertHTML', false, '<iframe src="'+selected+'" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
	}

	feChange(0);
	return;
}



function strip_tags(input, allowed) {

  allowed = (((allowed || '') + '')
    .toLowerCase()
    .match(/<[a-z][a-z0-9]*>/g) || [])
    .join('');
  var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
    commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	//commentsAndPhpTags='';
  return input.replace(commentsAndPhpTags, '')
    .replace(tags, function($0, $1) {
      return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '&lt'+$1.toLowerCase()+'&gt';
    });
}

var isCtrl = false;
var isAlt = false;
function feChange(event){
var q,w;
q=document.getElementById('txtE').innerHTML;
/*q = q.replace(/\n/g, "<br//>"); 
//q = q.replace(/(<([^>]+)>)/ig,"");*/
q=strip_tags(q,'<br><b><i><u><strike><s><a><img><iframe><div><code><h1><h2><h3><pre><video><adsense>');
/*document.getElementById('e').innerHTML=q;*/
//document.getElementById("txtE").innerHTML+=' ';
isCtrl = false;
if(event.which == 17)isCtrl=false;
isAlt = false;
if(event.which == 18)isAlt=false;
document.getElementById("e").textContent = document.getElementById("txtE").innerHTML;
document.getElementById('hide').value =document.getElementById("txtE").innerHTML;
//feSel();
fCount();
}
/************PROOOF DIV**************/
  
/******************/



/**************************************/





function keyDownTextarea(e){
	//alert(e.which);
    if(e.which == 17)isCtrl=true;
	if(e.which == 18)isAlt=true;
	if(isCtrl == true && isAlt==false){
 if (e.which === 13) {      
      document.execCommand('insertHTML', false, '<br><br>');
 }
    if(e.which == 66) {
		e.preventDefault();
		addtag("b",0);
    }else if(e.which == 73) {
		e.preventDefault();
		addtag("i",0);
    }else if(e.which == 85) {
			e.preventDefault();
		//addtag("u",0);
		underline();
    }
	else if(e.which == 83) {
			e.preventDefault();
		addtag("s",0);
    }else if(e.which == 49) {
			e.preventDefault();			
		addtag("h1",0);
    }
	else if(e.which == 50) {
			e.preventDefault();
		addtag("h2",0);
    }
	else if(e.which == 51) {
			e.preventDefault();
		addtag("h3",0);
	}
    else if(e.which == 52) {
			e.preventDefault();
		addtag("adsense",0);
    }
	else if(e.which == 69) {
			e.preventDefault();
		addtag("a",0);
    }
	else if(e.which == 77) {
			e.preventDefault();
		addtag("img",0);
    }
	else if(e.which == 78) {
			e.preventDefault();
		addtag("unlink",0);
    }
	else if(e.which == 81) {
			e.preventDefault();
		addtag("code",0);
    }
	else if(e.which == 82) {
			e.preventDefault();
		addtag("remove",0);
    }	
	else if(e.which == 71) {
			e.preventDefault();
		addtag("at",0);
	}	
    else if(e.which == 72) {
			e.preventDefault();
		addtag("video",0);
    }
	else if(e.which == 89) {
			e.preventDefault();
		addtag("youtube",0);
    }
	else if(e.which == 84) {
			e.preventDefault();
		addtag("vimeo",0);
    }
		
	}
}


function feChange2(event){
var q,w;
q=document.getElementById('e').innerHTML;
//alert(HtmlDecode(q));
q = q.replace(/\n/g, "<br//>"); 
//q = q.replace(/(<([^>]+)>)/ig,"");
q=strip_tags(q,'<br><b><i><u><strike><s><a><img><iframe><div><code><h3><pre>');
var ret = q.replace(/&gt;/g, '>');
ret = ret.replace(/&lt;/g, '<');
    ret = ret.replace(/&quot;/g, '"');
    ret = ret.replace(/&apos;/g, "'");
    ret = ret.replace(/&amp;/g, '&');
w=document.getElementById('txtE').innerHTML=ret;
//document.execCommand('insertHTML', false, q);
//document.getElementById('txtE').innerHTML=q;
/*feSel();
fCount();*/
}


function feChangeW(event){
var q,w,u,e="";
w=document.getElementById('w').value;
u=document.getElementById('u');

wArray = w.split(" ");
strAdded="";
for(q=0;q<wArray.length;q++){
if(wArray[q].length>3 && strAdded.indexOf(wArray[q]+"-")==-1){
	e+=wArray[q].replace(/([.*+?^=!:${}()¿¡|\[\]\/\\])/g, "")+",";
	strAdded+=wArray[q]+"-";
}
}
e=e.substring(0,e.length-1);
u.value=e.toLowerCase();
}



function fConfirmDelete(a,b,w){

w=w.replace(/"/g, "%22");
document.getElementById(a).innerHTML="";
document.getElementById('dc'+b).innerHTML= '<a href="'+w+'">¿Seguro que quieres eliminarlo?</a>';

}

function feSel(){
var txt = document.getElementById('txtE');
q="";
if(document.selection) {
txt.focus();
sel = document.selection.createRange();		
q=sel.text;
}else{
//q=(txt.value).substring(txt.selectionStart, txt.selectionEnd);
txt.focus();
}
if(q.indexOf(".")!=-1){
	//alert(q.indexOf("://"));
	if(q.indexOf("//")==-1)document.getElementById('linkUrl').value="http://"+q;
	else document.getElementById('linkUrl').value=q;
	}
}


function fEdit(v,g,l,s,a,r,w){
var q,e,t,y,u,i,o,p,d,f;
q=document.getElementById(a);
u=document.getElementById("submit");
y= document.getElementById('txtE');
i=document.getElementById("answer");
if(l==1)d=document.getElementById("d").checked=true;
else d=document.getElementById("d").checked=false;

if(g==1)f=document.getElementById("f").checked=true;
else f=document.getElementById("f").checked=false;


if(q.innerHTML!="Cancelar"){
q.innerHTML = "Cancelar";
t=document.getElementById("sec"+r).innerHTML;
t = t.replace(/(<p>|<\/p>)/g, "");
t = t.replace(/\n/g, "");
t = t.replace(/<br>/g, "\n");
y.value=t;
i.action=w;
if(r==0){
p=document.getElementById('u');
p.style.display='block'
p.value=s;
f=document.getElementById('blopin');
f.style.display='block'
}else{
f=document.getElementById('blopin');
f.style.display='none'

}

u.value="Terminar edición";
window.scrollTo(0,document.body.scrollHeight);
feChange();
}else{
w=w.replace("q=5", "q=2"); 
i.action=w;
q.innerHTML = "Editar el mensaje";
u.value="Responder";
y.value="";
feChange();
}
}


 function fSelCode(containerid) { 
        if (document.selection) {
            var range = document.body.createTextRange();
            range.moveToElementText(containerid);
            range.select();
        } else if (window.getSelection) {
            var range = document.createRange();
            range.selectNode(containerid);
            window.getSelection().addRange(range);
        }
		
    }
	
function fCount() {
var q,w,e;
q = document.getElementById('txtE');
w = document.getElementById('dvCont');
/*
e=q.value.split(' ').length+q.value.split('\n').length;
if(e>0&&e<200){
w.innerHTML=e+" palabras.";
}else if(e>199){
w.innerHTML=e+" palabras. Este mensaje te dará 2 puntos";
}*/
   var text = q.textContent,
count = text.trim().replace(/\s+/g, ' ').split(' ').length;
w.innerHTML=count+" palabras.";

}

function fSearch0(e,a,b){
if(e.which == 13)fSearch(a,b);
}
	

function fSearch(a,b){
var q="",w;	
w = encodeURIComponent(document.getElementById('googleSearch').value);
if(a==0){
q="https://www.google.com/?#q="+w+"+site:"+encodeURIComponent(b)+"";

}	
window.open(q,'_blank');
}

function fCookieAdv(a,b){
window.open(b+"?c=1&r="+a);
	
}


function linktag(){
	var urlT = document.getElementById('linkUrlTag').value;	
var q,w,e;
q = document.getElementById('u');
if(q!=""){
e=document.getElementById('txtE').innerHTML;
wArray = q.value.split(",");
var ret = e.replace(/&gt;/g, '>');
ret = ret.replace(/&lt;/g, '<');
    ret = ret.replace(/&quot;/g, '"');
    ret = ret.replace(/&apos;/g, "'");
    ret = ret.replace(/&amp;/g, '&');
//w=document.getElementById('txtE').innerHTML=ret;
for(w=0;w<wArray.length;w++){
var regex = new RegExp("(?:^|\\b)("+wArray[w]+")(?=\\b|$)", "gi");
//document.execCommand('insertHTML', false, '<a href="'+urlT+'" target="_blank" title="'+urlFile+'">'+selected+'</a>');
ret = ret.replace(regex, '<a href="'+urlT+wArray[w]+'" target="_blank" title="'+wArray[w]+'">'+wArray[w]+'</a>');
//wArray[w]
}
document.getElementById('txtE').innerHTML=ret;
}else{
alert("Please fill the tags.")	
}

	
}

function linkCat(category,url){
e=document.getElementById('txtE').innerHTML;
var ret = e.replace(/&gt;/g, '>');
ret = ret.replace(/&lt;/g, '<');
ret = ret.replace(/&quot;/g, '"');
ret = ret.replace(/&apos;/g, "'");
ret = ret.replace(/&amp;/g, '&');
ret = ret.replace(/&amp;/g, '&');
//(?:^|\b)(category.toLowerCase())(?=\b|$)
var regex = new RegExp("(?:^|\\b)("+category+")(?=\\b|$)", "gi");
ret = ret.replace(regex, '<a href="'+url+'" target="_blank" title="'+category+'">'+category+'</a>');
document.getElementById('txtE').innerHTML=ret;
}