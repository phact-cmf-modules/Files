require('./filesfield.scss');
const Flow = require('@flowjs/flow.js');
(function ($) {
  const filesField = {
    options: {
      url: undefined,
      uploadUrl: undefined,
      sortUrl: undefined,
      deleteUrl: undefined,

      flowData: {},
      sortData: {},
      deleteData: {},

      limit: 20,
      limitMessage: 'Sorry, you can upload up to 20 files at a time',
      maxSizeMessage: 'Sorry, uploaded file size exceeded',
      notAllowedMessage: 'Sorry, only specified file types can be uploaded',
      accept: '*', // For images: 'image/*'
      types: [], // For images: ["image/gif", "image/jpeg", "image/png"],

      /* 5 mb */
      maxFileSize: 5242880,
    },
    element: undefined,
    counter: 0,
    init(element, options) {
      if (element === undefined) return;

      this.element = element;
      this.options = $.extend(this.options, options);

      this.bind();
      this.initUploader();
      this.initList();

      return this;
    },

    bind() {
      const me = this;
      $(document).on('click', `#${$(this.element).attr('id')} .remove-link`, function (e) {
        e.preventDefault();
        const $item = $(this).closest('li');
        if ($item.data('pk')) {
          me.remove($item.data('pk'));
        }
        return false;
      });
    },

    initUploader() {
      const me = this;

      const flow = new Flow({
        target: me.options.uploadUrl,
        testChunks: false,
        query: me.options.flowData,
        allowDuplicateUploads: true,
      });

      flow.assignBrowse(this.element.find('.files-drop')[0], false, false, this.options.accepts);
      flow.assignDrop(this.element.find('.files-drop')[0]);

      flow.on('filesSubmitted', () => {
        me.counter = 0;
        flow.upload();
      });

      flow.on('fileAdded', (file, event) => {
        const fileObject = file.file;

        if (me.options.types && me.options.types.length && $.inArray(fileObject.type, me.options.types) < 0) {
          alert(me.options.notAllowedMessage);
          return false;
        }


        if (me.options.maxFileSize < fileObject.size) {
          alert(me.options.maxSizeMessage);
          return false;
        }

        if (me.counter < me.options.limit) {
          me.counter++;

          if (me.counter > me.options.limit) {
            alert(me.options.limitMessage);
          }
        } else {
          return false;
        }
      });

      flow.on('uploadStart', () => {
        $(me.element).find('.progress_bar').css({
          width: 0,
        });
      });

      flow.on('progress', () => {
        const width = `${flow.progress() * 100}%`;
        $(me.element).find('.progress_bar').css({
          width,
        });
      });

      flow.on('complete', () => {
        $(me.element).find('.progress_bar').css({
          width: '0',
        });
        me.updateList();
      });
    },
    checkEmpty() {
      const $list = $(this.element.find('.files-list'));
      const $empty = $list.next('.empty-info');
      if ($list.find('li').length > 0) {
        $empty.addClass('hide');
      } else {
        $empty.removeClass('hide');
      }
    },
    updateList() {
      const me = this;
      $.ajax({
        url: me.options.url,
        dataType: 'html',
        success(data) {
          const wrapped_data = $('<div/>').append(data);
          const selector = `#${$(me.element).attr('id')} .files-content`;
          $(selector).replaceWith(wrapped_data.find(selector));
          me.initList();
          me.checkEmpty();
        },
      });
    },
    initList() {
      const me = this;
      if (me.options.sortUrl) {
        const $list = $(this.element).find('.files-list');

        $list.sortable({
          axis: $list.data('axis') ? $list.data('axis') : false,
          placeholder: 'highlight',
          start(e, ui) {
            ui.placeholder.height(ui.item.height());
          },
          update(event, ui) {
            const pkList = $(this).sortable('toArray', {
              attribute: 'data-pk',
            });

            me.sort(pkList);
          },
        });
      }
    },
    sort(pkList) {
      const me = this;
      const data = me.options.sortData;
      data.pkList = pkList;
      $.ajax({
        type: 'post',
        url: me.options.sortUrl,
        data,
      });
    },
    remove(pk) {
      const me = this;
      const data = me.options.deleteData;
      data.deletePk = pk;
      $.ajax({
        type: 'post',
        url: me.options.deleteUrl,
        data,
        success() {
          $(me.element).find(`[data-pk="${pk}"]`).fadeOut(300, function () {
            $(this).remove();
            me.checkEmpty();
          });
        },
      });
    },
  };
  return $.fn.filesField = function (options) {
    return $.extend(true, {}, filesField).init(this, options);
  };
})(jQuery);
