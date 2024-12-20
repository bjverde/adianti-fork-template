function __adianti_process_tooltips()
{
    $("[title]").each(function(k,v) {
        tippy(v, {
          content: $(v).attr('title'),
          allowHTML: true,
          theme: 'light-border',
          arrow: true
        });
        $(v).removeAttr('title');
    });
}