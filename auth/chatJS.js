
jQuery(document).ready(function($){

    var socket = new WebSocket("ws://chatepam.h1n.ru/auth/server.php");

    socket.onopen = function() {
        inf ("Соединение установлено");
      };
    
    socket.onerror = function(error) {
        inf ("Ошибка соединения" + (error.message ? error.message : ""));
      };

    socket.onclose = function() {
        inf ('Соединение закрыто');
      };
      
    socket.onmessage = function(event) {
        var data = JSON.parse(event.data);
         inf (data.type + " - " + data.message);
      };
});

function inf (text){
    
    $(document).ready(function() {
        var id1 = $("#box");
        id1.text(text);
    });
    $('.box').fadeIn('slow');
    setTimeout(function(){$('.box').fadeOut('slow')},1000);
}