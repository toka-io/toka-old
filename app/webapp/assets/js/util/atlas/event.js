var Event = function(ns, id, data) {
    this.ns = ns;
    this.id = id;
    this.data = data;
    this.uid = (function() {
        var uid = "";
        var charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_";

        for(var i=0; i<30; i++)
            uid += charset.charAt(Math.floor(Math.random() * charset.length));

        return uid;
    }());
}

Event.prototype.deserialize = function(json) {
    this.ns = json.ns;
    this.id = json.id;
    this.data = json.data;
    this.uid = json.uid;
    return this;
}

Event.prototype.serialize = function() {
    return JSON.stringify(this);
}

Event.isValid = function(json) {
    if (typeof json.ns !== "string"
        || typeof json.id !== "string"
        || typeof json.uid !== "string")
        return false;
    if (json.uid.length != 30)
        return false;
    return true;
}
