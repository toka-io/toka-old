function Settings(settings) {
    this.settings = settings;
}

Settings.prototype.ini = function () {
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
    
    // On Click

    /* Settings(The Actual settings themselves) */
    $('#settings-email-notifications-on').on('click', function() {
        if ($('#settings-email-notifications-on').hasClass('settings-button-inactive')) {
            //Add in EMAIL-ON functions here!//
        }
    });
    $('#settings-email-notifications-off').on('click', function() {
        if ($('#settings-email-notifications-off').hasClass('settings-button-inactive')) {
            //Add in EMAIL-OFF functions here!//
        }
    });
    $('#settings-soundNotification-on').on('click', function() {
        if ($('#settings-soundNotification-on').hasClass('settings-button-inactive')) {
            self.update({"setting": "soundNotification", "value": true});
        }
    });
    $('#settings-soundNotification-off').on('click', function() {
        if ($('#settings-soundNotification-off').hasClass('settings-button-inactive')) {
            self.update({"setting": "soundNotification", "value": false});
        }
    });

    // Resize the divs
    self.resize();

    $(window).on("resize", function() {
        self.resize();
    });
}

Settings.prototype.resize = function() {
    $("#settings-body-general").css("min-height", $("#site").height() - $("#site-menu").height()-50);
    $("#settings-body-email").css("min-height", $("#site").height() - $("#site-menu").height()-50);
    $("#settings-body-billing").css("min-height", $("#site").height() - $("#site-menu").height()-50);
}

// Toggle a button of the given Setting to the Value
Settings.prototype.onOffButton = function(setting, value) {
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
    self.settings[setting] = value;
    return true;
}

// AJAJ Service
Settings.prototype.update = function(data, loadingOptions) {
    var self = this;
    
    if (typeof loadingOptions === "undefined")
        loadingOptions = {};
    
    $.ajax({
        url: "/settings/update",
        type: "PUT",
        data: data,
        dataType: "json",
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
}

Settings.prototype.responseHandler = function(data, response) {
    var self = this;

    if (data.value === true || data.value === false) {
        self.onOffButton(data.setting, data.value);
    }
}

Settings.prototype.errorHandler = function(status, error, data) {
    var self = this;

    if (data.value === true) {
        $('#settings-'+data.setting+'-off').removeClass('settings-button-active').addClass('settings-button-error');
    } else if (data.value === false) {
        $('#settings-'+data.setting+'-on').removeClass('settings-button-active').addClass('settings-button-error');
    }
}