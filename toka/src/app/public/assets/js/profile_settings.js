function Settings() {}

Settings.prototype.ini = function () {
    /* Setting Bindings */
    $('#settings-email').on('click', function() {
        if ($('#settings-email').hasClass('toka-settings-bar-inactive')) {
            settingsBar('email');
        }
    });
    $('#settings-billing').on('click', function() {
        if ($('#settings-billing').hasClass('toka-settings-bar-inactive')) {
            settingsBar('billing');
        }
    });
    $('#settings-general').on('click', function() {
        if ($('#settings-general').hasClass('toka-settings-bar-inactive')) {
            settingsBar('general');
        }
    });
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
    $('#settings-chat-notifications-on').on('click', function() {
        if ($('#settings-chat-notifications-on').hasClass('settings-button-inactive')) {
            $('#settings-chat-notifications-on').removeClass('settings-button-inactive').addClass('settings-button-active');
            $('#settings-chat-notifications-off').removeClass('settings-button-active').addClass('settings-button-inactive');
            //Add in CHAT-ON functions here!//
        }
    });
    $('#settings-chat-notifications-off').on('click', function() {
        if ($('#settings-chat-notifications-off').hasClass('settings-button-inactive')) {
            $('#settings-chat-notifications-off').removeClass('settings-button-inactive').addClass('settings-button-active');
            $('#settings-chat-notifications-on').removeClass('settings-button-active').addClass('settings-button-inactive');
            //Add in CHAT-OFF functions here!//
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