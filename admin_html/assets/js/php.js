
function natcasesort(inputArr){var valArr=[],k,i,ret,that=this,strictForIn=false,populateArr={};this.php_js=this.php_js||{};this.php_js.ini=this.php_js.ini||{};strictForIn=this.php_js.ini['phpjs.strictForIn']&&this.php_js.ini['phpjs.strictForIn'].local_value&&this.php_js.ini['phpjs.strictForIn'].local_value!=='off';populateArr=strictForIn?inputArr:populateArr;for(k in inputArr){if(inputArr.hasOwnProperty(k)){valArr.push([k,inputArr[k]]);if(strictForIn){delete inputArr[k];}}}
valArr.sort(function(a,b){return that.strnatcasecmp(a[1],b[1]);});for(i=0;i<valArr.length;i++){populateArr[valArr[i][0]]=valArr[i][1];}
return strictForIn||populateArr;}
function natsort(inputArr){var valArr=[],k,i,ret,that=this,strictForIn=false,populateArr={};this.php_js=this.php_js||{};this.php_js.ini=this.php_js.ini||{};strictForIn=this.php_js.ini['phpjs.strictForIn']&&this.php_js.ini['phpjs.strictForIn'].local_value&&this.php_js.ini['phpjs.strictForIn'].local_value!=='off';populateArr=strictForIn?inputArr:populateArr;for(k in inputArr){if(inputArr.hasOwnProperty(k)){valArr.push([k,inputArr[k]]);if(strictForIn){delete inputArr[k];}}}
valArr.sort(function(a,b){return that.strnatcmp(a[1],b[1]);});for(i=0;i<valArr.length;i++){populateArr[valArr[i][0]]=valArr[i][1];}
return strictForIn||populateArr;}
function nl2br(str,is_xhtml){var breakTag=(is_xhtml||typeof is_xhtml==='undefined')?'<br />':'<br>';return(str+'').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,'$1'+breakTag+'$2');}
function number_format(number,decimals,dec_point,thousands_sep){number=(number+'').replace(/[^0-9+\-Ee.]/g,'');var n=!isFinite(+number)?0:+number,prec=!isFinite(+decimals)?0:Math.abs(decimals),sep=(typeof thousands_sep==='undefined')?',':thousands_sep,dec=(typeof dec_point==='undefined')?'.':dec_point,s='',toFixedFix=function(n,prec){var k=Math.pow(10,prec);return''+Math.round(n*k)/k;};s=(prec?toFixedFix(n,prec):''+Math.round(n)).split('.');if(s[0].length>3){s[0]=s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,sep);}
if((s[1]||'').length<prec){s[1]=s[1]||'';s[1]+=new Array(prec-s[1].length+1).join('0');}
return s.join(dec);}
function octdec(oct_string){oct_string=(oct_string+'').replace(/[^0-7]/gi,'');return parseInt(oct_string,8);}
function ord(string){var str=string+'',code=str.charCodeAt(0);if(0xD800<=code&&code<=0xDBFF){var hi=code;if(str.length===1){return code;}
var low=str.charCodeAt(1);return((hi-0xD800)*0x400)+(low-0xDC00)+0x10000;}
if(0xDC00<=code&&code<=0xDFFF){return code;}
return code;}
