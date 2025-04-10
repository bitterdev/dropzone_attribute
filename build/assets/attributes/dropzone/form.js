import {Dropzone} from "dropzone";

window.initDropzoneField = function (field) {
    var previewElement = $(field).attr("data-preview-element");

    var dropzone = new Dropzone(field, {
        url: CCM_DISPATCHER_FILENAME + "/api/v1/dropzone/upload_file",
        previewTemplate: document.getElementById(previewElement).innerHTML,

        success: function (file, response) {
            const $fileElement = $(file.previewElement)
            $fileElement.removeClass('in-progress')
            $fileElement.find("input").val(response.file.fID);
        },

        uploadprogress: function (file, progress) {
            this.isUploadInProgress = true

            const $fileElement = $(file.previewElement)
            const circle = $fileElement.find('circle').get(0)
            const radius = circle.r.baseVal.value
            const circumference = radius * 2 * Math.PI

            circle.style.strokeDasharray = `${circumference} ${circumference}`
            circle.style.strokeDashoffset = `${circumference}`
            circle.style.strokeDashoffset = circumference - progress / 100 * circumference

            $fileElement.find('.ccm-file-upload-progress-text-value').html(parseInt(progress) + '%')
            $fileElement.addClass('in-progress')
        }
    });
}

window.initDropzoneFields = function () {
    $(".ccm-file-upload").each(function () {
        if (!$(this).hasClass("processed")) {
            window.initDropzoneField(this);
            $(this).addClass("processed")
        }
    });
}

window.initDropzoneFields ();