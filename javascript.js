function addtag(tag,q) {
var txt = document.getElementById('txtE');
var url = document.getElementById('linkUrl').value;	
var urlT = document.getElementById('linkUrlTag').value;	

	var strTag=tag;
	if(q==1){strTag='<a href="'+tag+'" target="_blank" title="'+tag+'"><img src="'+tag+'"/>';
	subtag="</a>";
	}else if(tag=="a"){strTag='<a target="_blank" rel="nofollow"';
	subtag='</a>';
	}else if(tag=="at"){strTag='<a target="_blank" rel="nofollow"';
	subtag='</a>';
	}else if(tag=="img"){strTag='<img ';
	subtag='</img>';
	}else if(tag=="code"){strTag='<pre><code onclick="fSelCode(this)" >';
	subtag='</code></pre>';
	}else{
	strTag='<'+strTag+'>';
	subtag='</'+tag+'>';
	}	
	
	if(document.selection) {	
	
		txt.focus();
		sel = document.selection.createRange();		
		if(tag=="code")sel.text = strTag+sel.text.replace(/</g, "&lt;")+subtag;
		else if(tag=='at')sel.text = strTag+' href="'+urlT+sel.text+'/" title="'+sel.text+'" />'+sel.text+subtag;
		else if(tag=='a')sel.text = strTag+' href="'+url+sel.text+'/" title="'+sel.text+'" />'+sel.text+subtag;
		else if(tag=='img')sel.text = strTag+' src="'+sel.text+'/" alt="'+sel.text+'" />'+subtag;		
		else sel.text = strTag+sel.text+subtag;
	} else if(txt.selectionStart || txt.selectionStart == '0') {	
	
	//replace(/</g, "&lt;")
	strSelected=(txt.value).substring(txt.selectionStart, txt.selectionEnd);
		if(tag=='at')txt.value = (txt.value).substring(0, txt.selectionStart) + strTag+' href="'+urlT+strSelected+'/"  title="'+strSelected+'">'+strSelected+''+ subtag  + (txt.value).substring(txt.selectionEnd, txt.textLength);
		else if(tag=='a')txt.value = (txt.value).substring(0, txt.selectionStart) + strTag+' href="'+url+strSelected+'/"  title="'+strSelected+'">'+strSelected+''+ subtag  + (txt.value).substring(txt.selectionEnd, txt.textLength);
		else if(tag=='img')txt.value = (txt.value).substring(0, txt.selectionStart) + strTag+' src="'+strSelected+'"  alt="'+strSelected+'">'+ subtag  + (txt.value).substring(txt.selectionEnd, txt.textLength);
		else txt.value = (txt.value).substring(0, txt.selectionStart) +strTag+strSelected+ subtag  + (txt.value).substring(txt.selectionEnd, txt.textLength);
		
	} else {
	txt.value = strTag+subtag;
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
function feChange(event){
var q,w;
q=document.getElementById('txtE').value;
q = q.replace(/\n/g, "<br//>"); 
//q = q.replace(/(<([^>]+)>)/ig,"");
q=strip_tags(q,'<br><b><i><u><strike><s><a><img><iframe><div><code><h1><h2><h3><pre>');
document.getElementById('e').innerHTML=q;
isCtrl = false;
if(event.which == 17)isCtrl=false;
feSel();
fCount();
}

function keyDownTextarea(e){
    if(e.which == 17)isCtrl=true;
	if(isCtrl == true){
		
    if(e.which == 66) {
		e.preventDefault();
		addtag("b",0);
    }else if(e.which == 73) {
		e.preventDefault();
		addtag("i",0);
    }else if(e.which == 85) {
			e.preventDefault();
		addtag("u",0);
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
	else if(e.which == 65) {
			e.preventDefault();
		addtag("a",0);
    }
	else if(e.which == 77) {
			e.preventDefault();
		addtag("img",0);
    }
	else if(e.which == 81) {
			e.preventDefault();
		addtag("code",0);
    }
	else if(e.which == 84) {
			e.preventDefault();
		addtag("at",0);
    }

	
	}
}


function feChange2(event){
var q,w;
q=document.getElementById('e').innerText;
q = q.replace(/\n/g, "<br//>"); 
//q = q.replace(/(<([^>]+)>)/ig,"");
q=strip_tags(q,'<br><b><i><u><strike><s><a><img><iframe><div><code><h3><pre>');
document.getElementById('e').innerHTML=q;
/*feSel();
fCount();*/
}


function feChangeW(event){
var q,w,u,e="";
w=document.getElementById('w').value;
u=document.getElementById('u');

wArray = w.split(" ");
for(q=0;q<wArray.length;q++){
if(wArray[q].length>3)e+=wArray[q].replace(/([.*+?^=!:${}()¿¡|\[\]\/\\])/g, "")+",";
}
e=e.substring(0,e.length-1);
u.value=e;
}



function fConfirmDelete(a,b,w){
document.getElementById(a).innerHTML="";
document.getElementById('dc'+b).innerHTML= '<a href="'+w+'">¿Seguro que quieres eliminarlo?</a>';
  
//q.removeEventListener ("mouseup", fConfirmDelete, true);
}

function feSel(){
var txt = document.getElementById('txtE');
q="";
if(document.selection) {
txt.focus();
sel = document.selection.createRange();		
q=sel.text;
}else{
q=(txt.value).substring(txt.selectionStart, txt.selectionEnd);
}
if(q.indexOf(".")!=-1)document.getElementById('linkUrl').value=q;
}


function fEdit(g,l,s,a,r,w){
//alert(r);
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
  //q.setAttribute('href',w);
//t=document.getElementById("body"+r).innerHTML;
t=document.getElementById("sec"+r).innerHTML;
t = t.replace(/(<p>|<\/p>)/g, "");
t = t.replace(/\n/g, "");
t = t.replace(/<br>/g, "\n");
//t = t.replace(/ /g,"");
y.value=t;
i.action=w;
if(r==0){
t=document.getElementById("h2Title").innerHTML;
o=document.getElementById('w');
o.style.display='block'
o.value=t;
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
//e=q.value.split(' ').length;
e=q.value.split(' ').length+q.value.split('\n').length;
if(e>0&&e<200){
w.innerHTML=e+" palabras.";
}else if(e>199){
w.innerHTML=e+" palabras. Este mensaje te dará 2 puntos";
}
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



