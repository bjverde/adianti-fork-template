window.Wiki = (function(){
    const applyLink = function(context) {

        const insertLabel = Application.translation[Adianti.language]['insert'];
        const newTabLabel = Application.translation[Adianti.language]['open_new_tab'];
        
        context.invoke('editor.saveRange');

        __adianti_get_page('class=SystemWikiForm&method=getWikiCombo&static=1' , function(wikis) {
            $('#adianti_online_content').append(`
                <div class="modal"  style="display: block" id="wiki_link" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header"><button onclick="$('#wiki_link').remove();" type="button" class="close"><span>&times;</span></button><h4 class="modal-title">Link</h4></div>
                            <div class="modal-body">
                            <form id="form_link_wiki" name="form_link_wiki">
                                <div class="form-group">
                                    <label>Label</label>
                                    <input class="form-control" type="text" name="wiki_label">
                                </div>
                                <div class="form-group">
                                    <label>Wiki</label>
                                    <div class="form-control" style="padding: 0">${wikis}</div>
                                </div>
                                <div class="form-group">
                                    <input class="" type="checkbox" value=1 name="wiki_open_new_tab">
                                    <span>${newTabLabel}</span>
                                </div>
                            </form>
                            </div>
                            <div class="modal-footer"><button data-dismiss="modal" id="wiki_link_button" type="button" class="btn btn-primary">${insertLabel}</button></div>
                        </div>
                    </div>
                </div>
            `);
            
            $(`#wiki_link_button`).on('click', function(){
                const form = $('#form_link_wiki');
                const array = form.serializeArray();
                const formData = {};
                $.each(array, function () { formData[this.name] = this.value || ""; });
                
                if (formData.wiki_link && formData.wiki_label) {
                    const link = 'index.php?class=SystemWikiView&method=onLoad&key='+formData.wiki_link;
                    let props = '';
                   
                    if (! formData.wiki_open_new_tab) {
                        props = "generator='adianti'";
                    } else {
                        props = "target='_blank'";
                    }
    
                    context.invoke('editor.restoreRange');
                    context.invoke('editor.focus');
                    context.invoke('editor.pasteHTML', `<a href='${link}' ${props}>${formData.wiki_label}</a>`);
                }

                $('#wiki_link').remove();
            });
        });
    }

    return {
        applyLink: applyLink
    }
})();