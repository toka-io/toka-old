"use strict"

function SettingsApp(settings) {
    this.settings = settings;
    
    this.ini = function() {
        var self = this;

        for (var setting in self.settings) {
            $('#'+setting+'-tag').text(self.settings[setting]);
        }

        // Resize the divs
        self.resize();

        $(window).on("resize", function() {
            self.resize();
        });
    }
    
    this.resize = function() {
        $("#settings-body-general").css("min-height", $("#site").height() - $("#site-menu").height()-50);
        $("#settings-body-email").css("min-height", $("#site").height() - $("#site-menu").height()-50);
        $("#settings-body-billing").css("min-height", $("#site").height() - $("#site-menu").height()-50);
    }
    
    this.update = function(data, loadingOptions) {
        var self = this;
        
        if (typeof loadingOptions === "undefined")
            loadingOptions = {};
        
        $.ajax({
            url: "/settings/update",
            type: "put",
            data: JSON.stringify(data),
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            timeout: 8000,
            beforeSend: (loadingOptions.hasOwnProperty("beforeSend")) ? loadingOptions["beforeSend"] : function() {},
            complete: (loadingOptions.hasOwnProperty("complete")) ? loadingOptions["complete"] : function() {},
            success: function(response) {
                self.responseHandler(data, response);
            },
            error: function(jqXHR, status, error) {
                self.errorHandler(status, error, data);
            }
        });
    };

    this.responseHandler = function(data, response) {
        var self = this;
        
        if (response.result) {
        }
    };
    
    this.errorHandler = function(status, error, data) {
        var self = this;
    }

    this.settingsTab = function(name, tag) {
        if ($('#'+name+'-settings').css("display") == "none") {
            $('#'+name+'-settings').css("display", "flex");
            $('#'+name+'-tag').addClass("settings-orange");
        } else {
            $('#'+name+'-settings').css("display", "none");
            $('#'+name+'-tag').removeClass("settings-orange");
        }
    }

    this.settingsTag = function(name, value, type) {
        var self = this;

        $('#'+name+'-tag').text(type);

        var setting = {};
        setting[name] = value
        self.update(setting);
    }
}