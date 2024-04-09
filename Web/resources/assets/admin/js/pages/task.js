const { Dropzone } = require("dropzone");
const TYPE_CHECKIN = 1
const TYPE_INSTALL_APP = 2
const TYPE_VIDEO_WATCH = 3
const TYPE_SOCIAL = 4
Dropzone.autoDiscover = false;
require('jquery-repeater-form');

$(document).ready(function () {
  const taskController = new TaskControls();
});

class TaskControls {
  constructor() {
    this._initSingleImageUpload();
    this._initGalleries();
    this._initLocation();
    this._changeTaskType();
  }
  /**
   *
   * @private
   */
  _changeTaskType() {
    const _this = this;
    $('.task-type select').on('change', function (event) {
      const type = $(event.currentTarget).val()
      _this._actionTaskType(type)
    });

    // Get first inital task type
    const typeInit = $('.task-type select option:selected').val();
    if(typeInit) {
      _this._actionTaskType(typeInit)
    }
  }
  /**
   *
   * @private
   */
   _actionTaskType(type) {
      switch(parseInt(type)) {
        case TYPE_CHECKIN:
          $('.wrap-type-checkin').show();
          $('.wrap-type-social').hide();
          break;
        case TYPE_INSTALL_APP:
          break;
        case TYPE_VIDEO_WATCH:
          break;
        case TYPE_SOCIAL:
          $('.wrap-type-social').show();
          $('.wrap-type-checkin').hide();
          break;
        default:
          console.log(type, TYPE_CHECKIN, type == TYPE_CHECKIN)
      }
  }
  /**
   *
   * @private
   */
  _initLocation() {
    let _this = this;
    $('.js-repeater').repeater({
      isFirstItemUndeletable: true,
      show: function () {
        let $this = $(this);
        $this.attr('data-repeater-item', '');
        let btnDelete = $this.find('.js-delete');
        if (btnDelete.length) {
          btnDelete.attr('data-repeater-delete', '');
          btnDelete.attr('data-id', '');
          btnDelete.removeClass('js-delete');//always set bottom of condition
        }
        $this.slideDown();
      },
    });

    $('.js-delete').on('click', function () {
      _this._deleteLocationItem($(this));
    });
  }

  /**
   *
   * @param $this jquery element
   * @private
   */
  _deleteLocationItem($this) {
    let locationId = $this.attr('data-id');
    let eFrom = $this.closest('form');

    $this.closest('div[data-repeater-item=' + locationId + ']').remove();
    eFrom.append('<input type="hidden" name="list_delete[]" value="'+ locationId +'">');
    return true;
  }

  // Single Image Upload initialization
  _initSingleImageUpload() {
    this._singleImageUploadExample = document.getElementById('taskImgCover');
    const singleImageUpload = new SingleImageUpload(this._singleImageUploadExample);
  }

  _initGalleries() {
    new Dropzone('#taskGallery', {
      autoProcessQueue: false,
      maxFilesize: 2,
      dictDefaultMessage: "Drop your files here!",
      url: 'https://httpbin.org/pos', // api post file
      removedfile: function(file) {
        console.log(file.upload.filename);
      },
      init: function () {
        let myDropzone = this;
        document.querySelector("button[type=submit]").addEventListener("click", function(e) {
          // e.preventDefault();
          // e.stopPropagation();

          // document.querySelector('input[name="gallery"]').files = myDropzone.getQueuedFiles();
          // let pendingFiles = myDropzone.getQueuedFiles();
          // pendingFiles.forEach($file => {
          //   const reader  = new FileReader();
          //   reader.readAsDataURL($('input[name="gallery"]').prop("files"));
          //   console.log($file);
          // });

          //console.log(myDropzone.getQueuedFiles());
          //console.log(myDropzone.getAcceptedFiles());
          //myDropzone.processQueue();
        });
        this.on('success', function (file, responseText) {
          console.log(responseText);
        });
      },
      acceptedFiles: 'image/*',
      thumbnailWidth: 160,
      previewTemplate: DropzoneTemplates.previewTemplate,
    });
  }
}
