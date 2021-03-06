var MessageBoard = {
    
    t : null,
    socket : null,
    messages: [],
    textField: null,
    messageArea: null,

    init:function(e)
    {
        if(MessageBoard.initSocket()){
            MessageBoard.socketSend();
        }
        else{
            MessageBoard.messageChecker();
            MessageBoard.BindSend();
        }
        /** Utan long polling */
        //MessageBoard.getMessages();
	    MessageBoard.textField = document.getElementById("inputText");
        MessageBoard.messageArea = document.getElementById("messagearea");

        

        $('#messagearea').on('click', '.show-time', function(e){
            var id = $(e.target).closest('.message').data('message-id');
            MessageBoard.showTime(id);
        });
        /*
        $('#messagearea').on('click', '.delete', function(e){
            var id = $(e.target).closest('.message').data('message-id');
            if(window.confirm("Vill du verkligen radera meddelandet?")){
                var elem = $(e.target).closest('.message');
                MessageBoard.deleteMessage(id, elem);
            }
        });
        */
    },
    BindSend : function(){
        $('buttonSend').on('click', function(e){
            MessageBoard.sendMessage();
            return false;
        });
        $('#inputText').on('keypress', function(e){
            if(!e) var e = window.event;
            if(e.keyCode == 13 && !e.shiftKey){
                MessageBoard.sendMessage(); 
                return false;
            }
        });
    },
    initSocket : function(){
        /** Låtsas-fail */
        return false;
        var host = "ws://localhost:9000/"; // SET THIS TO YOUR SERVER
        try {
            this.socket = new WebSocket(host);

            console.log('WebSocket - status '+ this.socket.readyState);

            this.socket.onopen = function(msg){ 
                console.log("Welcome - status "+ this.readyState); 
                MessageBoard.socketGet();
            };
            this.socket.onmessage = function(msg) { 
                //console.log(msg.data);
                MessageBoard.socketCallback(msg.data); 
            };
            this.socket.onclose   = function(msg) { 
               console.log("Disconnected - status "+this.readyState); 
           };
           return true;
        }
        catch(ex){ 
            return false;
        }
        return false;
    },
    socketGet : function(){
        var data = {
            'action' : 'get'
        };
        this.socket.send(JSON.stringify(data));
    },
    socketSend : function(){
        var self = this;
        document.getElementById("buttonSend").onclick = function(e) {
            var mess = '' + MessageBoard.textField.value;

            var data = {
                'action' : 'post',
                'message' : mess
            };
            self.socket.send(JSON.stringify(data));
        }
    },
    socketCallback : function(data){
        var json = JSON.parse(data)
        console.log(json);
        for(var i in json.messages){
            var msg = json.messages[i];

            var mess = new Message(msg.id, msg.name, msg.message, msg.created);
            MessageBoard.messages.push(mess);
            MessageBoard.renderMessage(mess);
        }
    },
    messageChecker:function(){
        var lastId = 0;
        if(MessageBoard.messages.length > 0){
            lastId = MessageBoard.messages[MessageBoard.messages.length - 1].getId();
        }
        $.ajax({
            type:'POST',
            url:'ajax.php',
            data:{'action':'check', 'vatoken' : getVaToken(), 'lastId' : lastId},
            dataType:'json'
        }).done(function(json){
            if(json.messages.length > 0){
                for(var i in json.messages){
                    var msg = json.messages[i];
                    var mess = new Message(msg.id, msg.name, msg.message, msg.created);
                    MessageBoard.messages.push(mess);
                    MessageBoard.renderMessage(mess);
                }
            }
            clearInterval(MessageBoard.t);
            MessageBoard.t = setTimeout( function(){
                MessageBoard.messageChecker();
            }, 1000 );
        }).fail(function(){
            clearInterval(MessageBoard.t);
            alert('Något failade...');
            MessageBoard.t = setTimeout( function(){
                MessageBoard.messageChecker();
            }, 15000 );
        });
    },
    getMessages:function() {
        $.ajax({
			type: 'POST',
			url: 'ajax.php',
			data: {'action': 'getMessages', 'vatoken' : getVaToken()},
            dataType : 'json'
		}).done(function(json){
            MessageBoard.getMessagesDone(json);
		});
    },
    getMessagesDone : function(json){
        MessageBoard.messages = [];
        for(var i in json.messages){
            var msg = json.messages[i];
            var mess = new Message(msg.id, msg.name, msg.message, msg.created);
            MessageBoard.messages.push(mess);
        }
        MessageBoard.renderMessages();
        MessageBoard.updateMessageCount();
    },
    sendMessage:function(){
        if(MessageBoard.textField.value == '') return;
        var text = MessageBoard.textField.value;
        MessageBoard.textField.value = '';
        $.ajax({
			type: 'POST',
		  	url: 'ajax.php',
		  	data: {'action' : 'add', 'vatoken' : getVaToken(), 'message' : text},
            dataType: 'json'
		}).done(function(json) {
            //MessageBoard.sendMessageDone(json);
		});
    },
    sendMessageDone : function(json){
        //Behövs inte mer då long polling hämtar in senaste
        /*
        var mess = new Message(json.id, json.name, json.message, json.created);
        MessageBoard.messages.push(mess);
        MessageBoard.renderMessage(mess);
        MessageBoard.updateMessageCount();
        */
    },

    renderMessages: function(){
        MessageBoard.messageArea.innerHTML = "";
        for(var i=0; i < MessageBoard.messages.length; ++i){
            MessageBoard.renderMessage(MessageBoard.messages[i]);
        }
        MessageBoard.updateMessageCount();
    },
    renderMessage: function(mess){
        // Message div
        var div = document.createElement("div");
        div.className = "message";
        div.setAttribute('data-message-id', mess.getId());
       
        // Clock button
        clockTag = document.createElement("span");
        clockTag.setAttribute('class', 'show-time');
        var imgClock = document.createElement("img");
        imgClock.src="pic/clock.png";
        imgClock.alt="Show creation time";
        clockTag.appendChild(imgClock);
        div.appendChild(clockTag);
       
        // Message text
        var text = document.createElement("p");
        text.innerHTML = mess.getHTMLText();        
        div.appendChild(text);
            
        // Time - Should fix on server!
        var spanDate = document.createElement("span");
        spanDate.appendChild(document.createTextNode(mess.getDateText()))

        div.appendChild(spanDate);        
        
        var spanClear = document.createElement("span");
        spanClear.className = "clear";

        div.appendChild(spanClear);        
        
        document.getElementById('messagearea').insertBefore(div, document.getElementById('messagearea').firstChild);
        //MessageBoard.messageArea.appendChild(div);       
    },
    /*
    deleteMessage: function(id, elem){
		$.ajax({
            type: 'POST',
            url: 'ajax.php',
            data: {'action' : 'delete', 'vatoken' : getVaToken(), 'id' : id}
        }).done(function(json){
            MessageBoard.deleteMessageDone(json, elem);
        });
    },
    
    deleteMessageDone : function(json, elem){
        if(!json.error){
            elem.remove();
        }
    }
    */
    showTime: function(messageID){
        for(var i = 0; i < MessageBoard.messages.length; i++){
            if(MessageBoard.messages[i].getId() == messageID){
                var time = MessageBoard.messages[i].getDate();
                time = new Date(time);
                alert("Created "+time.toLocaleDateString()+" at "+time.toLocaleTimeString());
            }
        }
        
    },
    updateMessageCount : function(){
        document.getElementById("nrOfMessages").innerHTML = MessageBoard.messages.length;
    }
}

function getVaToken(){
    return $('meta[name="validation-token"]').attr('content');
}

function Message(id, name, message, date){
    date = parseInt(date) * 1000;
    this.getId = function() {return id;};
    this.getName = function() {return name;};
    this.getText = function() {return message;};
    this.getDate = function() {return date;}
}

Message.prototype.getHTMLText = function() {
    return '<strong>' + this.getName() + " said:</strong><br />" + this.getText().replace(/[\n\r]/g, "<br />");
}

Message.prototype.getDateText = function() {
    var d = new Date(this.getDate());
    return d.toLocaleTimeString();
}

window.onload = MessageBoard.init;