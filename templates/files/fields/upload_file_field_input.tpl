{set $instance = $field->getForm()->getInstance()}
{if $instance->id}
    <div class="files-input" id="{$id}">
        <section class="files-drop">
            <p class="info">Перетащите файл сюда или нажмите для загрузки</p>
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
                                Текущий файл:

                                <a class="name" href="{$link}" target="_blank">
                                    {$link}
                                </a>
                            </span>


                            <span class="remove-link">
                                <span class="remove">&times;</span>
                                <span class="text">
                                    Удалить
                                </span>
                            </span>
                        </li>
                    {/if}
                </ul>
            {/block}

            <p class="empty-info {if $link}hide{/if}">
                Файл еще не загружен
            </p>
        </section>

        <script type='text/javascript'>
            $('#{$id}').data('field', $('#{$id}').filesField({raw $field->getFieldData()}));
        </script>

        {block 'js'}
        {/block}
    </div>
{else}
    <div class="files-input unavailable">
        <div class="info">
            Пожалуйста, сохраните объект для загрузки файла
        </div>
    </div>
{/if}
