function SettingsApp(soundNotification) {
    this.settings = {};
    this.settings.soundNotification = soundNotification;
    
    this.ini = function() {
        var self = this;

        for (var setting in self.settings) {
            self.onOffButton(setting, self.settings[setting]);
        }

        function settingsBar(item) {
            $('#settings-general').removeClass('toka-settings-bar-active').addClass('toka-settings-bar-inactive');
            $('#settings-billing').removeClass('toka-settings-bar-active').addClass('toka-settings-bar-inactive');
            $('#settings-email').removeClass('toka-settings-bar-active').addClass('toka-settings-bar-inactive');
            $('#settings-'+item).removeClass('toka-settings-bar-inactive').addClass('toka-settings-bar-active');
            $('#settings-body-general').removeClass('toka-settings-body-active').addClass('toka-settings-body-inactive');
            $('#settings-body-billing').removeClass('toka-settings-body-active').addClass('toka-settings-body-inactive');
            $('#settings-body-email').removeClass('toka-settings-body-active').addClass('toka-settings-body-inactive');
            $('#settings-body-'+item).removeClass('toka-settings-body-inactive').addClass('toka-settings-body-active');
        }
        
        /* Settings(The Actual settings themselves) */
        $('#settings-email-notifications-on').on('click', function() {
            if ($('#settings-email-notifications-on').hasClass('settings-button-inactive')) {
                $('#settings-email-notifications-on').removeClass('settings-button-inactive').addClass('settings-button-active');
                $('#settings-email-notifications-off').removeClass('settings-button-active').addClass('settings-button-inactive');
                //Add in EMAIL-ON functions here!//
            }
        });
        $('#settings-email-notifications-off').on('click', function() {
            if ($('#settings-email-notifications-off').hasClass('settings-button-inactive')) {
                $('#settings-email-notifications-off').removeClass('settings-button-inactive').addClass('settings-button-active');
                $('#settings-email-notifications-on').removeClass('settings-button-active').addClass('settings-button-inactive');
                //Add in EMAIL-OFF functions here!//
            }
        });
        $('#settings-soundNotification-on').on('click', function() {
            if ($('#settings-soundNotification-on').hasClass('settings-button-inactive')) {
                self.onOffButton('soundNotification', true);
            }
        });
        $('#settings-soundNotification-off').on('click', function() {
            if ($('#settings-soundNotification-off').hasClass('settings-button-inactive')) {
                self.onOffButton('soundNotification', false);
            }
        });

        $("#settings-body-general").css("min-height", $("#site").height() - $("#site-menu").height()-50);
        $("#settings-body-email").css("min-height", $("#site").height() - $("#site-menu").height()-50);
        $("#settings-body-billing").css("min-height", $("#site").height() - $("#site-menu").height()-50);
        
        $(window).on("resize", function() {
            $("#settings-body-general").css("min-height", $("#site").height() - $("#site-menu").height()-50);
            $("#settings-body-email").css("min-height", $("#site").height() - $("#site-menu").height()-50);
            $("#settings-body-billing").css("min-height", $("#site").height() - $("#site-menu").height()-50);
        });
    }
    
    this.onOffButton = function(setting, value) {
        var self = this;

        if (value === true) {
            $('#settings-'+setting+'-on').removeClass('settings-button-inactive').addClass('settings-button-active');
            $('#settings-'+setting+'-off').removeClass('settings-button-active').addClass('settings-button-inactive');
        } else if (value === false) {
            $('#settings-'+setting+'-off').removeClass('settings-button-inactive').addClass('settings-button-active');
            $('#settings-'+setting+'-on').removeClass('settings-button-active').addClass('settings-button-inactive');
        } else {
            return false;
        }
        self.service("settings", "update","PUT",{"setting": setting, "value": value});
        self.settings[setting] = value;
        return true;
    }
    
    this.service = function(service, action, method, data, loadingOptions) {
        var self = this;
        
        if (typeof loadingOptions === "undefined")
            loadingOptions = {};
        
        $.ajax({
            url: service + "/" + action,
            type: method,
            data: data,
            dataType: "json",
            beforeSend: (loadingOptions.hasOwnProperty("beforeSend")) ? loadingOptions["beforeSend"] : function() {},
            complete: (loadingOptions.hasOwnProperty("complete")) ? loadingOptions["complete"] : function() {},
            success: function(response) {
                self.responseHandler(service, action, method, data, response);
            }
        });
    };

    this.responseHandler = function(service, action, method, data, response) {
    };
}