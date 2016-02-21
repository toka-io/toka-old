"use strict"

var AtlasClient = function() {
    this.events = {};
    this.endpoint = "dev.toka.io:1337";
    this.nodeList = ['dev.toka.io:1337'];
    this.protocol = "ws";
    this.socket;
}

AtlasClient.prototype.connect = function(secure, endpoint) {
    var self = this;
    this.protocol = (secure) ? "wss" : "ws"; 
    this.endpoint = endpoint;
    this.socket = new WebSocket(this.protocol + "://" + this.endpoint);
    
    this.socket.onmessage = function(event) {        
        var event = JSON.parse(event.data);
        if (self.events.hasOwnProperty(event.id)) self.events[event.id](event);
    }
}

AtlasClient.prototype.retry = function() {
    if (this.nodeList.length === 1) return;
    for (i = 0; i < this.nodeList.length; i++) {
        if (this.nodeList[i] !== this.endpoint) { 
            this.socket = new WebSocket(this.protocol + "://" + this.endpoint);
        }
    }
}

AtlasClient.prototype.on = function(eventId, fn) {
    if (eventId === 'connect')
        this.socket.onopen = fn;
    else
        this.events[eventId] = fn;
}

AtlasClient.prototype.send = function(event) {
    this.socket.send(event);
}