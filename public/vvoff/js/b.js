
			 
		$(function () {
			 var header = document.getElementById("p_top")
			 var scrollFunc=function(e){ 
		
			e=e || window.event; 
			if(e.wheelDelta){//IE/Opera/Chrome 
				if(e.wheelDelta>=120)
				{
					header.style.display = "block";
					
				}else
				{ 
					header.style.display = "none";	
				} 
			}else if(e.detail){
				//Firefox 
				if(e.detail==-3) { 
					//向上滚动事件<br> 	
					header.style.display = "block";
				}else { 
					//向下滚动事件<br>	
					header.style.display = "none";	
				} 
			} 
		 };
		 if(document.addEventListener){ 
			//adding the event listerner for Mozilla
			 document.addEventListener("DOMMouseScroll" ,scrollFunc, false);
			 }
			 //IE/Opera/Chrome 
			 window.onmousewheel=document.onmousewheel=scrollFunc;
			 
		})
		