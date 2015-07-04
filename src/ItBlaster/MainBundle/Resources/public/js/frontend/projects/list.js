function project_delete(e)
{
    if (confirm('Вы действительно хотите удалить проект?')){
        return true;
    }
    return false;
}


$(function(){
    $(document).on('click','.project_delete',project_delete);
});