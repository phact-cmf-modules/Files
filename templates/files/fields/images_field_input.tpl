{extends 'files/fields/files_field_input.tpl'}

{block 'list'}
    {set $itemAdmin = $field->getItemAdmin()}
    <ul class="files-list images large-block-grid-6 clearfix">
        {foreach $items as $item}
            <li data-pk="{$item->id}">
                <div class="item-wrapper">
                    <span class="remove-link">
                        <i class="icon-delete_in_filter"></i>
                    </span>

                    {if $itemAdmin}
                        <a href="{$itemAdmin->getUpdateUrl($item->id)}" class="edit-link">
                            <i class="icon-edit"></i>
                        </a>
                    {/if}

                    <div class="image-wrapper">
                        <img src="{$item->getField($field->fileField)->url}">
                    </div>
                </div>
            </li>
        {/foreach}
    </ul>
{/block}

{block 'js'}
    <script>
        $(function () {
            $(document).on('click', '#{$id} .edit-link', function (e) {
                e.preventDefault();
                var $link = $(this);
                $link.modal({
                    theme: 'related',
                    useAjaxForm: true,
                    closeOnSuccess: true,
                    closeOnSuccessDelay: 1000,
                    onFormSuccess: function() {
                        var list = $link.closest('.files-input');
                        list.data('field').updateList();
                    }
                });
                return false;
            });
        });
    </script>
{/block}