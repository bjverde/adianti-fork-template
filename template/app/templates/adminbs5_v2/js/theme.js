Template.start = function() {
    $("#sidebar-toggle").click( function() {
        $("#sidebar").toggleClass("collapsed");
    });
    
    $('#side-menu a[generator="adianti"]').click(function(el) {
        $('#side-menu a[generator="adianti"]').removeClass('active');
        $(el.target).closest('a').addClass('active');
        
        if ($(window).width() < 767) {
            $("#sidebar").addClass("collapsed");
        }
    });
    
    if (Template.navbarOptions['has_menu_mode_switch'] == '1') {
        document.documentElement.setAttribute('data-menu-theme', Template.getMenuTheme());
        $('#menu_theme_switch').prop('checked', (Template.getMenuTheme() == 'dark'));
    }
    
    if (Template.navbarOptions['has_main_mode_switch'] == '1') {
        document.documentElement.setAttribute('data-bs-theme', Template.getGlobalTheme());
        $('#global_theme_switch').prop('checked', (Template.getGlobalTheme() == 'dark'));
    }
    
    $(window).resize(__adianti_debounce(function(){
        if (Template.width !== $(window).width()) {
            Template.adjustMenu();
        }
        Template.width = $(window).width();
    }, 100));
    
    Template.adjustMenu();
    
    Template.width = $(window).width();
    
    // compatibility for jquery-ui with new BS5
    var bootstrapButton = $.fn.button.noConflict()
    $.fn.bootstrapBtn = bootstrapButton;
    
    // customize edit in line colors
    $.fn.editInPlace.defaults['bg_over'] = 'var(--bs-secondary-bg-subtle)';
}

Template.adjustMenu = function() {
  if ($(window).width() > 767) {
      $("#sidebar").removeClass("collapsed");
  }
  else {
      $("#sidebar").addClass("collapsed");
  }
}

Template.toggleGlobalTheme = function(generator) {
    let next = ($(generator).is(":checked")) ? 'dark' : 'light';
    document.documentElement.setAttribute('data-bs-theme', next);
    localStorage.setItem("bs-theme", next);
}

Template.toggleMenuTheme = function(generator) {
    let next = ($(generator).is(":checked")) ? 'dark' : 'light';
    document.documentElement.setAttribute('data-menu-theme', next);
    localStorage.setItem("menu-theme", next);
}

Template.getGlobalTheme = function() {
    return ( localStorage.getItem("bs-theme") !== null ) ? localStorage.getItem("bs-theme") : $(document.documentElement).data('bs-theme');
}

Template.getMenuTheme = function() {
    return ( localStorage.getItem("menu-theme") !== null ) ? localStorage.getItem("menu-theme") : $(document.documentElement).data('menu-theme');
}

Template.setNavbarOptions = function(options) {
    Template.navbarOptions = options;
    
    if (options['has_program_search'] == '1' && $("#navbar-wrapper").length > 0) {
        $.get("engine.php?class=SearchBox", function(data) {
            $("#navbar-wrapper").append(data);
        });
    }
    else {
        $("#navbar-wrapper").remove();
    }
    
    if (options['has_notifications'] == '1') {
        $('#has_notifications').css('display', 'inline-block');
    }
    
    if (options['has_messages'] == '1') {
        $('#has_messages').css('display', 'inline-block');
    }
    
    if (options['has_docs'] == '1') {
        $('#has_docs').css('display', 'inline-block');
    }
    
    if (options['has_contacts'] == '1') {
        $('#has_contacts').css('display', 'inline-block');
    }
    
    if (options['has_support_form'] == '1') {
        $('#has_support_form').css('display', 'inline-block');
    }
    
    if (options['has_news'] == '1') {
        $('#has_news').css('display', 'inline-block');
    }
    
    if (options['has_wiki'] == '1') {
        $('#has_wiki').css('display', 'inline-block');
    }
    
    if (options['has_menu_mode_switch'] == '1') {
        $('#has_menu_mode_switch').css('display', 'inline');
    }
    
    if (options['has_main_mode_switch'] == '1') {
        $('#has_main_mode_switch').css('display', 'inline-block');
    }
}

Template.setThemeOptions = function(options) {
    Template.themeOptions = options;
    
    if (options['menu_dark_color']) {
        $('head').append('<style id="customCSS">[data-menu-theme=dark] #sidebar { background: '+options['menu_dark_color']+'; }</style>');
    }
    
    if (options['menu_light_color']) {
        $('head').append('<style id="customCSS">[data-menu-theme=light] #sidebar { background: '+options['menu_light_color']+'; }</style>');
    }
    
    if (options['menu_mode']) {
        if (localStorage.getItem("menu-theme") == null || Template.navbarOptions['has_menu_mode_switch'] == '0') {
            document.documentElement.setAttribute('data-menu-theme', options['menu_mode']);
            $('#menu_theme_switch').prop('checked', (options['menu_mode'] == 'dark'));
        }
    }
    
    if (options['main_mode']) {
        if (localStorage.getItem("bs-theme") == null || Template.navbarOptions['has_main_mode_switch'] == '0') {
            document.documentElement.setAttribute('data-bs-theme', options['main_mode']);
            $('#global_theme_switch').prop('checked', (options['main_mode'] == 'dark'));
        }
    }
}

Template.setDialogOptions = function(options) {
    if (options['use_swal'] == '1') {
        window.__adianti_message = function(title, message, callback) {
            __adianti_dialog( { type: 'success', title: title, message: message, callback: callback} );
        }
        
        window.__adianti_dialog = function( options ) {
            setTimeout( function() {
                Swal.fire({
                  title: options.title,
                  html: options.message,
                  icon: options.type,
                  allowEscapeKey: true, /**(typeof options.callback == 'undefined'),*/
                  allowOutsideClick: true /** (typeof options.callback == 'undefined') **/
                }).then((result) => {
                  if (result.isConfirmed || typeof (result.dismiss !== 'undefined')) {
                    if (typeof options.callback != 'undefined') {
                        options.callback();
                    }
                  }
                });
            }, 100);
        }
        
        window.__adianti_question = function(title, message, callback_yes, callback_no, label_yes, label_no) {
            setTimeout( function() {
                Swal.fire({
                  title: title,
                  html: message,
                  icon: 'question',
                  showDenyButton: true,
                  confirmButtonText: label_yes,
                  denyButtonText: label_no
                }).then((result) => {
                  if (result.isConfirmed) {
                    if (typeof callback_yes != 'undefined') {
                        callback_yes();
                    }
                  } else if (result.isDenied) {
                    if (typeof callback_no != 'undefined') {
                        callback_no();
                    }
                  }
                });
            }, 100);
        }
    }
}

Template.configure = function(options, context = 'main') {
    if (context !== 'login') {
        Template.setNavbarOptions(options['navbar'] ?? {});
        Template.setThemeOptions(options['theme'] ?? {});
    }
    else {
        Template.navbarOptions = {};
        Template.themeOptions = {};
    }
    
    Template.setDialogOptions(options['dialogs'] ?? {});
}
