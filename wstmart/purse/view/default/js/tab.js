/**
 * Created by Me on 2017/4/1.
 */
var spans = tab_header.children;
var divs = content.children;
function resetStyle() {
    for(var j= 0,len=spans.length; j<len; j++) {
        spans[j].className = "";//清除选中样式
        divs[j].className = "";//让所有的div不显示
    }
}
for(var i= 0,len = spans.length; i<len ; i++) {
    spans[i].onmouseover = function () {
        resetStyle();
        var index = getIndexOf(this);
        spans[index].className = "select";
        divs[index].className = "show txt";
    }
}
function  getIndexOf(sp) {
    for(var i= 0,len=spans.length;i<len;i++) {
        if(sp==spans[i]) {
            return i;
        }
    }
    return -1;
}
