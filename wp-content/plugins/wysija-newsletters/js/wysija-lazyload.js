var wysijaLazyLoad={};wysijaLazyLoad.options={data:[],task:null,successCallback:null,errorCallback:null,targetContainer:null,filter:null,spinner:".spinner"},wysijaLazyLoad.jqXHR=null,wysijaLazyLoad.init=function(a,o){options=wysijaLazyLoad.options,"undefined"!=typeof options.data&&"undefined"!=typeof options.task&&jQuery.isArray(options.data)&&("undefined"!=typeof options.filter&&null!==options.filter&&(wysijaAJAX.filter=options.filter),wysijaAJAX.task=options.task,blockNames=wysijaLazyLoad.options.data.splice(0,1),parseInt(blockNames.length)>0&&(wysijaAJAX.block=blockNames[0],wysijaLazyLoad.lazyLoad(a,o,function(){})))},wysijaLazyLoad.lazyLoad=function(a){wysijaLazyLoad.jqXHR=jQuery.ajax({type:"POST",url:wysijaAJAX.ajaxurl,data:wysijaAJAX,dataType:"json",beforeSend:function(){jQuery(wysijaLazyLoad.options.spinner).show()},success:function(o){blockNames=wysijaLazyLoad.options.data.splice(0,1),parseInt(blockNames.length)>0?(wysijaAJAX.block=blockNames[0],wysijaLazyLoad.lazyLoad(a)):jQuery(wysijaLazyLoad.options.spinner).hide(),a(o,parseInt(blockNames.length))},error:function(){blockNames=wysijaLazyLoad.options.data.splice(0,1),parseInt(blockNames.length)>0&&(wysijaAJAX.block=blockNames[0],wysijaLazyLoad.lazyLoad(a))}})},wysijaLazyLoad.terminate=function(){try{wysijaLazyLoad.options.data=new Array,wysijaLazyLoad.jqXHR.abort()}catch(a){}};
