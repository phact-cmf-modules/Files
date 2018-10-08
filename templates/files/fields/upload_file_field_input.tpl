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

        {set $link = $field->getCurrentFileUrl()}

        <section class="files-content">
            {block 'list'}
                <ul class="files-list">
                    {if $link}
                        <li data-pk="{$instance->id}">
                            <span class="name">
                                {t "Files.main" "Current file"}:

                                <a class="name" href="{$link}" target="_blank">
                                    {$link}
                                </a>
                            </span>


                            <span class="remove-link">
                                <span class="remove">&times;</span>
                                <span class="text">
                                    {t "Files.main" "Delete"}
                                </span>
                            </span>
                        </li>
                    {/if}
                </ul>
            {/block}

            <p class="empty-info {if $link}hide{/if}">
                {t "Files.main" "File not loaded yet"}
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
            {t "Files.main" "Please save the object to load the file"}
        </div>
    </div>
{/if}
