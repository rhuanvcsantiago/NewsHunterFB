<?php
    $this->title = 'Buscador';
    $this->params['breadcrumbs'][] = $this->title;
?>

<style>
    @import url("http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css");
    .chat
    {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .chat li
    {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #B3A9A9;
    }

    .chat li.left .chat-body
    {
        margin-left: 5px;
    }

    .chat li.right .chat-body
    {
        margin-right: 60px;
    }


    .chat li .chat-body p
    {
        margin: 0;
        color: #777777;
    }

    .panel .slidedown .glyphicon, .chat .glyphicon
    {
        margin-right: 5px;
    }

    .body-panel
    {
        overflow-y: scroll;
        height: 400px;
    }

    ::-webkit-scrollbar-track
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar
    {
        width: 12px;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-thumb
    {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #555;
    }
</style>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <span class="glyphicon glyphicon-transfer"></span> Console do Buscador
                    <div class="btn-group pull-right">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            opções
                            <span class="glyphicon glyphicon-chevron-down"></span>
                        </button>
                        <ul class="dropdown-menu slidedown">
                            <li><a href="#" onClick="webSocketConn.send('fetch');"><span class="glyphicon glyphicon-refresh">
                            </span>Fetch All</a></li>
                            <!-- <li><a href="#"><span class="glyphicon glyphicon-ok-sign">
                            </span>Available</a></li> -->
                           <!--  <li><a href="#"><span class="glyphicon glyphicon-time"></span>
                                Away</a></li> -->
                            <li class="divider"></li>
                            <li><a href="#" onClick="clearConsole();"><span class="glyphicon glyphicon-remove">
                            </span>Clear console</a></li>
                            <!-- <li><a href="#"><span class="glyphicon glyphicon-off"></span>
                                Sign Out</a></li> -->
                        </ul>
                    </div>
                </div>
                <div class="panel-body body-panel">
                    <ul id="chatwindow" class="chat">
                    </ul>
                </div>
            </div>

 <script>

    function createChatRow(text){

        var rowText = text || "";
        var chatRow = '<li class="left">'
                        + '<div class="chat-body clearfix"'
                        + '     <>' +  rowText.replace(/ /g, "&nbsp")  +  '</p>'
                        + '</div>'
                     +'</li>';    

        return chatRow;         
    }
    
    
    function connectToFetcher(){

        webSocketConn = new WebSocket('ws://localhost:8387');

        webSocketConn.onopen = function () {
            $("#chatwindow").append( createChatRow("conectado ao servidor socket") );
            //webSocketConn.send('Ping'); // Send the message 'Ping' to the server
        };

        // Log errors
        webSocketConn.onerror = function(error) {
            $("#chatwindow").append( createChatRow("erro na conexão;") );
        };

        // Log messages from the server
        webSocketConn.onmessage = function (e) {
            $("#chatwindow").append( createChatRow(e.data) );
            console.log(e.data);
            //$("#chatwindow").children().last().focus();
        };    

    }

    
    
    function checkWebSocketConnection(socket){
       
        if( socket.readyState == socket.CLOSED ){                        
            connectToFetcher();      
        }
    }

    function clearConsole() {
        $("#chatwindow").empty();
    }

    connectToFetcher();
    setInterval( "checkWebSocketConnection(webSocketConn)", 5000);


 </script>           
 
