@props(['name' => 'avatar'])
<div wire:ignore>
    <input {{ $attributes }} type="file" class="image-resize-filepond">
    <span class="text-danger">
       @error($name) {{ $message }} @enderror
   </span>
</div>


@once
    @push('head')
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
              rel="stylesheet">
    @endpush

    @push('script')

        <!-- filepond validation -->
        <script
            src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
        <script
            src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

        <!-- image editor -->
        <script
            src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-filter/dist/filepond-plugin-image-filter.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>

        <!-- toastify -->
        <script src="assets/vendors/toastify/toastify.js"></script>

        <!-- filepond -->
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

        <script>
            FilePond.setOptions({
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    @this.upload('{{$name}}', file, load, error, progress);
                    },
                    revert : (filename, load) => {
                    @this.removeUpload('{{$name}}', filename, load);
                    }
                },

            });

            FilePond.registerPlugin(
                // validates the size of the file...
                FilePondPluginFileValidateSize,
                // validates the file type...
                FilePondPluginFileValidateType,

                // calculates & dds cropping info based on the input image dimensions and the set crop ratio...
                FilePondPluginImageCrop,
                // preview the image file type...
                FilePondPluginImagePreview,
                // filter the image file
                FilePondPluginImageFilter,
                // corrects mobile image orientation...
                FilePondPluginImageExifOrientation,
                // calculates & adds resize information...
                FilePondPluginImageResize,
            );

            // Filepond: Image Resize
            FilePond.create(document.querySelector('.image-resize-filepond'), {
                allowImagePreview         : true,
                allowImageFilter          : false,
                allowImageExifOrientation : false,
                allowImageCrop            : false,
                allowImageResize          : true,
                imageResizeTargetWidth    : 200,
                imageResizeTargetHeight   : 200,
                imageResizeMode           : 'cover',
                imageResizeUpscale        : true,
                acceptedFileTypes         : ['image/png', 'image/jpg', 'image/jpeg'],
                fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                    // Do custom type detection here and return with promise
                    resolve(type);
                })
            });
        </script>
    @endpush
@endonce
