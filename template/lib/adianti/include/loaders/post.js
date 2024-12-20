/**
 * Post form data
 */
function __adianti_post_data(form, action, run_events, options)
{
    if (typeof run_events == "undefined") {
        run_events = true;
    }
    
    if (typeof options == "undefined") {
        options = {};
    }
    
    if (action.substring(0,4) == 'xhr-')
    {
        url = action;
    }
    else
    {
        if (action.substring(0,5) == 'class') {
            url = 'index.php?'+action;
            url = url.replace('index.php', 'engine.php');
        }
        else {
            var url = 'xhr-' + action; // use routes por post
        }
    }

    if (document.querySelector('#'+form) instanceof Node && (action.indexOf('novalidate') === -1))
    {
        if (!document.querySelector('#'+form).hasAttribute('novalidate') && document.querySelector('#'+form).checkValidity() == false)
        {
            document.querySelector('#'+form).reportValidity();
            return;
        }
    }
    
    if (typeof options.blockui == "undefined" || options.blockui == true) {
        __adianti_block_ui();
    }
    
    if (typeof options.wrapper_variable !== "undefined") {
        var data = {};
        data[options.wrapper_variable] = __adianti_query_to_json( $('#'+form).serialize() );
    }
    else
    {
        var data = $('#'+form).serialize();
    }
    
    if (run_events) {
        __adianti_run_before_posts(url);
    }
    
    if ( (url.indexOf('&static=1') > 0) || (url.indexOf('?static=1') > 0) || (action.substring(0,4) == 'xhr-'))
    {
        $.post(url, data)
        .done(function(result) {
            __adianti_parse_html(result, null, url);
            if (typeof options.blockui == "undefined" || options.blockui == true) {
                __adianti_unblock_ui();
            }
            Adianti.requestURL  = url;
            Adianti.requestData = data;
            
            if (run_events) {
                __adianti_run_after_posts(url, result);
            }
            
        }).fail(function(jqxhr, textStatus, exception) {
            if (typeof options.blockui == "undefined" || options.blockui == true) {
                __adianti_unblock_ui();
            }
            __adianti_failure_request(jqxhr, textStatus, exception);
            Adianti.loading = false;
        });
    }
    else
    {
        $.post(url, data)
        .done(function(result) {
            Adianti.currentURL  = url;
            Adianti.requestURL  = url;
            Adianti.requestData = data;
            
            if (run_events) {
                __adianti_load_html(result, __adianti_run_after_posts, url);
            }
            else {
                __adianti_load_html(result, null, url);
            }
            if (typeof options.blockui == "undefined" || options.blockui == true) {
                __adianti_unblock_ui();
            }

        }).fail(function(jqxhr, textStatus, exception) {
            if (typeof options.blockui == "undefined" || options.blockui == true) {
                __adianti_unblock_ui();
            }
            __adianti_failure_request(jqxhr, textStatus, exception);
            Adianti.loading = false;
        });
    }
}

function __adianti_post_exec(action, data, callback, static_call, automatic_output)
{
    if (action.substring(0,5) == 'class') {
        var uri = 'engine.php?' + action;
    }
    else {
        var uri = 'xhr-' + action;
    }
    
    var automatic_output = (typeof automatic_output === "undefined") ? false : automatic_output;

    if (typeof static_call !== "undefined") {
        var uri = uri + ( (uri.indexOf('?') == -1) ? '?' : '&') + 'static='+static_call;
    }

    $.ajax({
      type: 'POST',
      url: uri,
      data: data,
      }).done(function( result ) {
        if (automatic_output) {
            __adianti_parse_html(result, callback, uri);
            __adianti_run_after_loads(uri, result);
        }
        else if (callback && typeof(callback) === "function") {
            return callback(result);
        }
      }).fail(function(jqxhr, textStatus, exception) {
         __adianti_failure_request(jqxhr, textStatus, exception);
      });
}


function __adianti_post_lookup(form, action, field, callback) {
    if (typeof field == 'string') {
        field_obj = $('#'+field);
    }
    else if (field instanceof HTMLElement) {
        field_obj = $(field);
    }

    var formdata = $('#'+form).serializeArray();
    formdata.push({name: '_field_value', value: field_obj.val()});

    if (action.substring(0,5) == 'class') {
        var uri = 'engine.php?' + action +'&static=1';
    }
    else {
        //var uri = 'xhr-' + action +'&static=1';
        var uri = 'xhr-' + action + ( (action.indexOf('?') == -1) ? '?' : '&') + 'static=1';
    }

    formdata.push({name: '_field_id',   value: field_obj.attr('id')});
    formdata.push({name: '_field_name', value: field_obj.attr('name')});
    formdata.push({name: '_form_name',  value: form});
    formdata.push({name: '_field_data', value: $.param(field_obj.data(), true)});
    formdata.push({name: '_field_data_json', value: JSON.stringify(__adianti_query_to_json($.param(field_obj.data(), true)))});
    formdata.push({name: 'key',         value: field_obj.val()}); // for BC
    formdata.push({name: 'ajax_lookup', value: 1});

    $.ajax({
      type: 'POST',
      url: uri,
      data: formdata
      }).done(function( result ) {
          __adianti_parse_html(result, callback, uri);
      }).fail(function(jqxhr, textStatus, exception) {
         __adianti_failure_request(jqxhr, textStatus, exception);
      });
}

function __adianti_post_page_lookup(form, action, field, callback) {
    if (typeof field == 'string') {
        field_obj = $('#'+field);
    }
    else if (field instanceof HTMLElement) {
        field_obj = $(field);
    }

    var formdata = $('#'+form).serializeArray();
    formdata.push({name: '_field_value', value: field_obj.val()});

    if (action.substring(0,5) == 'class') {
        var uri = 'engine.php?' + action;
    }
    else {
        var uri = 'xhr-' + action;
    }

    formdata.push({name: '_field_id',   value: field_obj.attr('id')});
    formdata.push({name: '_field_name', value: field_obj.attr('name')});
    formdata.push({name: '_form_name',  value: form});
    formdata.push({name: '_field_data', value: $.param(field_obj.data(), true)});
    formdata.push({name: '_field_data_json', value: JSON.stringify(__adianti_query_to_json($.param(field_obj.data(), true)))});
    formdata.push({name: 'key',         value: field_obj.val()}); // for BC
    formdata.push({name: 'ajax_lookup', value: 1});

    $.ajax({
      type: 'POST',
      url: uri,
      data: formdata
      }).done(function( result ) {
          __adianti_load_html(result, callback, uri);
      }).fail(function(jqxhr, textStatus, exception) {
         __adianti_failure_request(jqxhr, textStatus, exception);
      });
}