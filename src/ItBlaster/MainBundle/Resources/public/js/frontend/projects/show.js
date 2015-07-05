function project_link_delete(e)
{
    if (confirm('Вы действительно хотите удалить ссылку?')){
        return true;
    }
    return false;
}


$(function(){
    $(document).on('click','.project_link_delete',project_link_delete);
});