var system = require('system');
var page = require('webpage').create();
var fs = require('fs');

var url = system.args[1];  


page.onResourceRequested = function(request) {    
     if(request.url.indexOf('interface.bilibili') >= 0 && request.url.indexOf('sign') >= 0){
           console.log('Receive :' + JSON.stringify(request.url, undefined, 4)); 
           var content = url+">"+request.url+"\r\n";
		       fs.write('/data/video/bilibili/log/interface.log', content, 'a+');
		       fs.close();
        } 
};

page.onResourceReceived = function(response) {
	 
};
 

 
page.open(url, function(status) { 
  if (status != 'success') {
      console.log('Unable to access network');
  }
  page.title =  page.evaluate(function (){
                 return  '';
     });   
  phantom.exit();
});

phantom.onError = function(msg, trace) {
  var msgStack = ['PHANTOM ERROR: ' + msg];
  if (trace && trace.length) {
    msgStack.push('TRACE:');
    trace.forEach(function(t) {
      msgStack.push(' -> ' + (t.file || t.sourceURL) + ': ' + t.line + (t.function ? ' (in function ' + t.function +')' : ''));
    });
  }
  console.error(msgStack.join('\n'));
  phantom.exit();
};

page.onResourceError = function(resourceError) {
  console.log('Unable to load resource (#' + resourceError.id + 'URL:' + resourceError.url + ')');
  console.log('Error code: ' + resourceError.errorCode + '. Description: ' + resourceError.errorString);
};




