function project_delete(e)
{
    if (confirm('Вы действительно хотите удалить проект?')){
        return true;
    }
    return false;
}

function project_link_delete(e)
{
    if (confirm('Вы действительно хотите удалить ссылку?')){
        return true;
    }
    return false;
}

function project_link_update(e)
{
    e.preventDefault();

    var url = $(this).attr('href');
    var $loader = $(this).find('.loader');
    var $update_img = $(this).find('.update-img');
    var $link_tr = $(this).parents('tr');

    $update_img.hide();
    $loader.show();

    $.ajax({
        url: url,
        dataType: 'json',
        success: function(result){
            $link_tr.html(result.html);
        },
        error: function(){
            alert('Error while update link');
        },
        complete: function(){}
    });
}

$(function(){
    $(document).on('click','.project_delete',project_delete);
    $(document).on('click','.project_link_delete',project_link_delete);
    $(document).on('click','.project-link-update',project_link_update);
});