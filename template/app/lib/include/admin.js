function __adianti_input_fuse_search(input_search, attribute, selector)
{
    setTimeout(function() {
        var stack_search = [];
        var search_attributes = [];
        attribute.split(',').forEach( function(v,k) {
            search_attributes.push( v );
        });
        
        $(selector).each(function() {
            var row = $(this);
            var search_data = {};
            
            search_data['id'] = $(this).attr('id');
            search_attributes.forEach( function(v,k) {
                search_data[v] = $(row).attr(v);
            });
            stack_search.push(search_data);
        });
        
        var fuse = new Fuse(stack_search, {
            keys: search_attributes,
            id: 'id',
            threshold: 0.2
        });
            
        $(input_search).on('keyup', function(){
            if ($(this).val()) {
                var result = fuse.search($(this).val());
                
                search_attributes.forEach( function(v,k) {
                    $(selector + '['+v+']').hide();
                });
                
                if(result.length > 0) {
                    for (var i = 0; i < result.length; i++) {
                        var query = '#'+result[i].item.id;
                        $(query).show();
                    }
                }
            }
            else {
                search_attributes.forEach( function(v,k) {
                    $(selector + '['+v+']').show();
                });
            }
        });
    }, 10);
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

var pretty = {};
pretty.json = {
   replacer: function(match, pIndent, pKey, pVal, pEnd) {
      var key = '<span class=json-key>';
      var val = '<span class=json-value>';
      var str = '<span class=json-string>';
      var r = pIndent || '';
      if (pKey)
         r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
      if (pVal)
         r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
      return r + (pEnd || '');
      },
   print: function(obj) {
      var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
      return JSON.stringify(obj, null, 3)
         .replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
         .replace(/</g, '&lt;').replace(/>/g, '&gt;')
         .replace(jsonLine, pretty.json.replacer);
      }
   };

Template.updateDebugPanel = function() {
    try {
        var url  = Adianti.requestURL;
        var body = Adianti.requestData;
        url = url.replace('engine.php?', '');
        $('#request_url_panel').html( pretty.json.print(__adianti_query_to_json(urldecode(url)), undefined, 4) );
        $('#request_data_panel').html( pretty.json.print(__adianti_query_to_json(urldecode(body)), undefined, 4) );
    }
    catch (e) {
        console.log(e);
    }
}

Template.onAfterLoad = function(url, data) {
    Template.updateDebugPanel();
    
    let view_width     = $( window ).width() >= 800 ? 780 : ($( window ).width());
    let curtain_offset = $( window ).width() >= 800 ? 20  : 0;
    
    let url_container = url.match('target_container=([0-z-]*)');
    let dom_container = data.match('adianti_target_container\\s?=\\s?"([0-z-]*)"');
    let into_right_panel = false;

    if (url_container || dom_container) {
        let id = '';
        if (url_container) {
            id = url_container[1];
        }
        else if (dom_container) {
            id = dom_container[1];
        }

       into_right_panel = $('#' + id).closest('#adianti_right_panel').length > 0;
    }

    if ((url.indexOf('target_container=adianti_right_panel') !== -1) || (data.indexOf('adianti_target_container="adianti_right_panel"') !== -1) ) {
        
        if (data.indexOf('override="true"') !== -1) {
            $('#adianti_right_panel').find('[page_name]').not(':last').remove();
        }
        
        var right_panels = Math.max($('#adianti_right_panel').find('[page_name]').length,1);
        
        $('#adianti_right_panel').css('width', (view_width + curtain_offset) + 'px');
        
        if ($('#adianti_right_panel').is(":visible") == false) {
            $('body').css("overflow", "hidden");
            $('#adianti_right_panel').show('slide',{direction:'right'}, 320);
        }
        
        $('#adianti_right_panel').css('width', (view_width + (curtain_offset * right_panels)) + 'px');
        
        if (curtain_offset > 0) {
            $('#adianti_right_panel').find('[page_name]').each(function(k,v) {
                $(v).css('left', (k * 20) + 'px');
            });
        }
        
        if (right_panels > 1) {
            let current_page_name = ($('#adianti_right_panel').find('[page_name]').last().attr('page_name'));
            if (data.indexOf('page_name="'+current_page_name+'"') == -1) // avoid slide again if top curtain = requested one
            {
                $('#adianti_right_panel').find('[page_name]').last().hide();
                $('#adianti_right_panel').find('[page_name]').last().show('slide',{direction:'right'}, 320);
            }
        }
        
        var warnings = $('#adianti_right_panel').clone().find('div[page_name],script').remove().end().html();
        $('#adianti_right_panel').find('[page_name]').last().prepend(warnings);
        $("#adianti_right_panel").animate({ scrollTop: 0 }, "fast");
    }
    else if ( (url.indexOf('&static=1') == -1) && (data.indexOf('widget="TWindow"') == -1) && ! into_right_panel ) {
        if ($('#adianti_right_panel').is(":visible")) {
            $('#adianti_right_panel').hide();
            $('#adianti_right_panel').html('');
            $('body').css('overflow', 'unset');
        }
    }
};

Template.onAfterPost = Template.onAfterLoad;

Template.closeRightPanel = function () {
    let view_width     = $( document ).width() >= 800 ? 780 : ($( document ).width());
    let curtain_offset = $( document ).width() >= 800 ? 20  : 0;
    
    if ($('#adianti_right_panel > [page_name]').length > 0) {
        if ($('#adianti_right_panel > [page_name]').length == 1) {
            $('#adianti_right_panel').hide('slide',{direction:'right', complete: function() {
                $('body').css('overflow', 'unset');
                $('#adianti_right_panel').html('');
            }},320)
        }
        
        $('#adianti_right_panel > [page_name]').last().hide('slide',{direction:'right', complete: function() {
            $('#adianti_right_panel > [page_name]').last().remove();
            var right_panels = Math.max($('#adianti_right_panel > [page_name]').length,1);
            $('#adianti_right_panel').css('width', (view_width + (curtain_offset * right_panels)) + 'px');
        }}, 320);
    }
}

Template.closeRightPanels = function() {
    $('#adianti_right_panel').hide('slide',{direction:'right', complete: function() {
        $('body').css('overflow', 'unset');
        $('#adianti_right_panel').html('');
    }},320)
}

Template.closeWindows = function() {
    $('[widget="TWindow"]').remove();
}

Template.updateMessageDropdown = function() {
    $.get('engine.php?class=SystemMessageDropdown', function(data) {
        $('#envelope_messages').html(data);
    });
}

Template.updateNotificationDropdown = function() {
    $.get('engine.php?class=SystemNotificationDropdown', function(data) {
        $('#envelope_notifications').html(data);
    });
}

Template.wikiPagePicker = function(){
    __adianti_load_page('index.php?class=SystemWikiPagePicker');
};