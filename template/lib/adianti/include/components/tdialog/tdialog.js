function tdialog_start(id, callback)
{
    let modal = new bootstrap.Modal(id, {keyboard:true});
    modal.show();
    
    if (typeof callback != 'undefined')
    {
        $( id ).on("hidden.bs.modal", function(){ callback(); tdialog_close(id.substring(1));  } );
    }
    else
    {
        $( id ).on("hidden.bs.modal", function(){ tdialog_close(id.substring(1));  } );

    }
}

function tdialog_close(id)
{
    $('#'+id).modal('hide');
    setTimeout(function(){ $('#'+id).remove(); }, 300);
}