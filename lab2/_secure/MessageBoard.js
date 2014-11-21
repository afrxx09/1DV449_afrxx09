var MessageBoard = {

    messages: [],
    textField: null,
    messageArea: null,

    init:function(e)
    {
        MessageBoard.getMessages();
	    MessageBoard.textField = document.getElementById("inputText");
        MessageBoard.messageArea = document.getElementById("messagearea");

        document.getElementById("buttonSend").onclick = function(e) {MessageBoard.sendMessage(); return false;}
        MessageBoard.textField.onkeypress = function(e){ 
            if(!e) var e = window.event;
            if(e.keyCode == 13 && !e.shiftKey){
                MessageBoard.sendMessage(); 
                return false;
            }
        }
        
        $('#messagearea').on('click', 'a', function(e){
            var id = $(e.target).closest('.message').data('message-id');
            MessageBoard.showTime(id);
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
        for(var i in json.messages){
            var msg = json.messages[i];
            var text = msg.name +" said:\n" + msg.message;
            var mess = new Message(text, new Date());
            var messageID = MessageBoard.messages.push(mess)-1;
            MessageBoard.renderMessage(messageID);
        }
        MessageBoard.updateMessageCount();
    },
    sendMessage:function(){
        
        if(MessageBoard.textField.value == '') return;
        
        // Make call to ajax
        $.ajax({
			type: 'POST',
		  	url: 'ajax.php',
            //Använd namnet från sessionen istället för att posta det.
		  	data: {'action' : 'add', 'vatoken' : getVaToken(), 'message' : MessageBoard.textField.value}
		}).done(function(json) {
            MessageBoard.sendMessageDone(json);
		});
    },

    sendMessageDone : function(){
        var text = json.by +" said:\n" + json.message;
        var mess = new Message(text, new Date());
        var messageID = MessageBoard.messages.push(mess)-1;
        MessageBoard.renderMessage(messageID);
        MessageBoard.updateMessageCount();
    },

    renderMessages: function(){
        // Remove all messages
        MessageBoard.messageArea.innerHTML = "";
     
        // Renders all messages.
        for(var i=0; i < MessageBoard.messages.length; ++i){
            MessageBoard.renderMessage(i);
        }        
        
        MessageBoard.updateMessageCount();
    },
    renderMessage: function(messageID){
        // Message div
        var div = document.createElement("div");
        div.className = "message";
        div.setAttribute('data-message-id', messageID);
       
        // Clock button
        aTag = document.createElement("a");
        aTag.href = "#";
        /*
        aTag.onclick = function(){
			MessageBoard.showTime(messageID);
			return false;			
		}
        */
        var imgClock = document.createElement("img");
        imgClock.src="pic/clock.png";
        imgClock.alt="Show creation time";
        
        aTag.appendChild(imgClock);
        div.appendChild(aTag);
       
        // Message text
        var text = document.createElement("p");
        text.innerHTML = MessageBoard.messages[messageID].getHTMLText();        
        div.appendChild(text);
            
        // Time - Should fix on server!
        var spanDate = document.createElement("span");
        spanDate.appendChild(document.createTextNode(MessageBoard.messages[messageID].getDateText()))

        div.appendChild(spanDate);        
        
        var spanClear = document.createElement("span");
        spanClear.className = "clear";

        div.appendChild(spanClear);        
        
        MessageBoard.messageArea.appendChild(div);       
    },
    /*
    removeMessage: function(messageID){
		if(window.confirm("Vill du verkligen radera meddelandet?")){
        
			MessageBoard.messages.splice(messageID,1); // Removes the message from the array.
        
			MessageBoard.renderMessages();
        }
    },
    */
    showTime: function(messageID){
         
         var time = MessageBoard.messages[messageID].getDate();
         
         var showTime = "Created "+time.toLocaleDateString()+" at "+time.toLocaleTimeString();

         alert(showTime);
    },
    updateMessageCount : function(){
        document.getElementById("nrOfMessages").innerHTML = MessageBoard.messages.length;
    }
}

function getVaToken(){
    return $('meta[name="validation-token"]').attr('content');
}

function Message(message, date){

    this.getText = function() {
        return message;
    }

    this.setText = function(_text) {
        message = text;
    }

    this.getDate = function() {
        return date;
    }

    this.setDate = function(_date) {
        date = date;
    }

}

Message.prototype.toString = function(){
    return this.getText()+" ("+this.getDate()+")";
}

Message.prototype.getHTMLText = function() {
      
    return this.getText().replace(/[\n\r]/g, "<br />");
}

Message.prototype.getDateText = function() {
    return this.getDate().toLocaleTimeString();
}

window.onload = MessageBoard.init;