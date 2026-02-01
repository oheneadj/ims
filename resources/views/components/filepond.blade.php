@props(['maxFileSize' => '5MB', 'acceptedFileTypes' => ['image/*']])

<div wire:ignore x-data="{
        pond: null,
        init() {
            this.pond = FilePond.create($refs.input, {
                allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
                acceptedFileTypes: {{ json_encode($acceptedFileTypes) }},
                maxFileSize: '{{ $maxFileSize }}',
                server: {
                    process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                    },
                },
                labelIdle: 'Drag & Drop your file or <span class=\'filepond--label-action\'>Browse</span>',
            });
        }
    }">
    <input type="file" x-ref="input" {{ $attributes }} />
</div>