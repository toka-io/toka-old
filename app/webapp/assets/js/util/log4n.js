function Log4n() {
    this.info = function(message) {
        if (console) console.log("[INFO] " + (new Date()) + " - " + message);
    }
    
    this.warn = function(message) {
        if (console) console.warn("[WARN] " + (new Date()) + " - " + message);
    }
    
    this.error = function(message) {
        if (console) console.error("[ERROR] " + (new Date()) + " - " + message);
        console.trace();
    }
}