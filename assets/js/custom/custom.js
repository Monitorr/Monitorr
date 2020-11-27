if (window.top!=window.self)
{
   var cssURL = 'assets/data/css/custom.iframe.css';
}else{
  var cssURL = 'assets/data/css/custom.css';
} 
$('head').append('<link rel="stylesheet" href="' + cssURL + '"/>');