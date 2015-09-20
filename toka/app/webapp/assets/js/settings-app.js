"use strict"

function SettingsApp(options) {
    this.options = options;
    this.settings = {'soundNotification': {
        'name': "Sound Notifications",
        'value':{
            0:{
                'name': 'Off',
                'title': 'Never sound on a new message in an open chat'
            },
            1:{
                'name':'Always',
                'title': 'Always sound on a new message in an open chat'
            },
            2:{
                'name': 'Hidden Tabs',
                'title':'Always sound on a new message in an open chat'
            }
    }}};
    
    this.ini = function() {
        var self = this;

        self.createSettings(this.settings);

        for (var option in self.options) {
            self.settingsTag(option, self.options[option]);
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

    this.settingsTab = function(name) {
        if ($('#'+name+'-settings').css("display") == "none") {
            $('#'+name+'-settings').css("display", "flex");
            $('#'+name+'-tag').addClass("settings-orange");
        } else {
            $('#'+name+'-settings').css("display", "none");
            $('#'+name+'-tag').removeClass("settings-orange");
        }
    }

    this.settingsTag = function(name, value) {
        var self = this;

        $('#'+name+'-tag').text(self.settingType(name, value));

        var setting = {};
        setting[name] = value
        console.log(setting);
        self.update(setting);
    }

    this.settingsBar = function(item) {
    }

    this.settingType = function(name, value) {
        var self = this;

        return self.settings[name].value[value].name;
    }

    this.tabType = function(name) {
        switch (name) {
            case "general":
                break;
            case "email":
                break;
            case "billing":
                break;
        }
    }

    this.createSettings = function(settings) {
        for (var setting in settings) {
            console.log(setting);
            var name = settings[setting].name;
            var divSetting = $('<li>', {
                'id': setting})
                .append($('<h3>'+name+'<h3>'))
                .append($('<a></a>', {
                    'text': 'Off',
                    'class': 'settings-tag',
                    'id': setting+'-tag',
                    'onclick': "settings.settingsTab('"+setting+"');"
                }));
            var divOptions = $('<div></div>', {
                'class': 'settings-tab',
                'id': setting+'-settings'
            });
            for (var value in settings[setting].value) {
                var valueName = settings[setting].value[value].name;
                var title = settings[setting].value[value].title;
                divOptions.append($('<a></a>', {
                    'text': valueName,
                    'onclick': "settings.settingsTag('"+setting+"', "+value+"); settings.settingsTab('"+setting+"');",
                    'title': title
                }));
            }
            divSetting.append(divOptions);
            $('.settings-settings').append(divSetting);
        }
    }
}