@extends('/layouts.app')


@section('content')
<form method="POST" action="{{ route('templates.store') }}">

    @csrf

    <div class="mb-4">

        <label>
            Nama Template
        </label>

        <input
            type="text"
            name="name"
            class="border w-full p-2"
        >

    </div>

    <div class="mb-4">

        <label>
            Upload Word
        </label>

        <input
            type="file"
            id="upload"
            accept=".docx"
            class="border w-full p-2"
        >

    </div>

    <div class="mb-4">

        <label>
            Content
        </label>

        <textarea
            id="template_format"
            name="content_html"
        ></textarea>

    </div>

    <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2"
    >
        Save
    </button>

</form>

<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>

<script>
    let editor;

    ClassicEditor
        .create(document.querySelector('#template_format'))
        .then(newEditor => {

            editor = newEditor;

        })
        .catch(error => {
            console.error(error);
        });

    document.getElementById('upload')
        .addEventListener('change', async function(e) {

            const file = e.target.files[0];

            if (!file) return;

            const formData = new FormData();

            formData.append('file', file);

            try {

                const response = await fetch(
                    "{{ route('templates.import-word') }}",
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },

                        body: formData
                    }
                );

                const result = await response.json();

                editor.setData(result.html);

            } catch (error) {

                console.error(error);

                alert('Gagal import Word');

            }
        });
</script>
@endsection