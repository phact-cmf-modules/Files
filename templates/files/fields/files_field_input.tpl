{set $instance = $field->getForm()->getInstance()}
{if $instance->id}
    <div class="files-input" id="{$id}">
        <section class="files-drop">
            <p class="info">
                {t "Files.main" "Drag files here or click for upload"}
            </p>
            <div class="progress">
                <div class="meter progress_bar"></div>
            </div>
        </section>

        {if $field->sortUrl && $field->sortField}
            {set $value = $value->order([$field->sortField])}
        {/if}
        {set $items = $value->all()}

        <section class="files-content">
            {block 'list'}
                <ul class="files-list">
                    {foreach $items as $item}
                        <li data-pk="{$item->id}">
                            <span class="name">
                                {$item}
                            </span>

                            <span class="remove-link">
                                <span class="remove">&times;</span>
                                <span class="text">
                                    {t "Files.main" "Delete"}
                                </span>
                            </span>
                        </li>
                    {/foreach}
                </ul>
            {/block}

            <p class="empty-info {if $items}hide{/if}">
                {t "Files.main" "There are no files here yet"}
            </p>
        </section>

        {inline_js}
        <script type='text/javascript'>
            $('#{$id}').data('field', $('#{$id}').filesField({raw $field->getFieldData()}));
        </script>
        {/inline_js}

        {block 'js'}
        {/block}

    </div>
{else}
    <div class="files-input unavailable">
        <div class="info">
            {t "Files.main" "Please save the object to work with this field"}
        </div>
    </div>
{/if}
