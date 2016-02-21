var Message = function(chatroomId, username, text, timestamp) {
    this.chatroomid = chatroomId;
    this.username = username;
    this.text = text;
    this.timestamp = (timestamp) ? timestamp : moment().utc().format('MMM D, YYYY h:mm a');
}

Message.prototype.deserialize = function(json) {
    this.chatroomId = json.chatroomId;
    this.username = json.username;
    this.text = json.text;
    if (json.timestamp)
        this.timestamp = json.timestamp;
    else
        this.timestamp = moment().utc().format('MMM D, YYYY h:mm a');       
    return this;
}

Message.prototype.serialize = function() {
    return JSON.stringify(this);
}

Message.isValid = function(json) {
    if (typeof json.chatroomId !== "string" 
        || typeof json.text !== "string" 
        || typeof json.username !== "string")
        return false;
    return true;
}
