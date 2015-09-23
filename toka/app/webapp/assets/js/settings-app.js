"use strict"

function SettingsApp(options) {
    this.options = options;
    this.openTab = 'general';
    this.settings = {
        'general':{ 
            'soundNotification': {
                'name': 'Sound Notifications',
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
                }
            }
        },
        'email': {
            'emailNotification': {
                'name': 'Email Notifications',
                'value': {
                    0: {
                        'name': 'Off',
                        'title': 'Never send me emails'
                    },
                    1: {
                        'name': 'All',
                        'title': 'Send me important information and updates'
                    },
                    2: {
                        'name': 'Important Only',
                        'title': 'Only send me important information for Toka'
                    },
                    3: {
                        'name': 'Updates Only',
                        'title': 'Only send me chatroom updates for rooms I follow'
                    }
                }
            }
        },
        'billing': {
        }
    };
    
    this.ini = function() {
        var self = this;

        self.createSettings(this.settings);

        for (var option in self.options) {
            self.settingsTag(option, self.options[option], false);
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

    this.settingsTag = function(name, value, update) {
        var self = this;

        $('#'+name+'-tag').text(self.settingType(name, value));

        var setting = {};
        setting[name] = value;
        if (update) {
            self.update(setting);
        }
    }

    this.settingsBar = function(item) {
        var self = this;

        $('#'+self.openTab).removeClass('settings-orange');
        item.classList.add('settings-orange');

        $('#settings-body-'+self.openTab).removeClass('settings-active');
        $('#settings-body-'+item.id).addClass('settings-active');

        self.openTab = item.id;
    }

    this.settingType = function(name, value) {
        var self = this;

        for (var setting in self.settings) {
            try {
                return self.settings[setting][name].value[value].name;
            } catch(err) {
            }
        }
    }

    this.createSettings = function(settings) {
        for (var type in settings) {
            for (var setting in settings[type]) {
                var name = settings[type][setting].name;
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
                for (var value in settings[type][setting].value) {
                    var valueName = settings[type][setting].value[value].name;
                    var title = settings[type][setting].value[value].title;
                    divOptions.append($('<a></a>', {
                        'text': valueName,
                        'onclick': "settings.settingsTag('"+setting+"', "+value+", true); settings.settingsTab('"+setting+"');",
                        'title': title
                    }));
                }
                divSetting.append(divOptions);
                $('#'+type+'-settings').append(divSetting);
            }
        }
    }
}