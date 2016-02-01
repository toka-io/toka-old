var Message = function(chatroomId, username, text, timestamp) {
    this.chatroomId = chatroomId;
    this.username = username;
    this.text = text;
    this.timestamp = (timestamp) ? timestamp() : timestamp;
}

Message.prototype.deserialize = function(json) {
    if (this.validate(json)) {
        this.chatroomId = json.chatroomId.trim();
        this.username = json.username.trim();
        this.text = json.text;
        if (json.timestamp)
            this.timestamp = json.timestamp.trim();
        else
            this.timestamp = timestamp();
    } else
        return false;
}

Message.prototype.serialize = function() {
    return JSON.stringify(this);
}

Message.prototype.validate = function(json) {
    if (typeof json.chatroomId !== "string" 
        || typeof json.text !== "string" 
        || typeof json.username !== "string")
        return false;
    return true;
}