// overide ckeditor.getUrl function
function CKEDITOR_GETURL( resource )
{
    return '/system/assets/ckeditor/'+resource;
}