@extends('layouts/app')

@section('title', 'Support')

@section('content')
    <h1>Support</h1>
    <form name="contact">
        <p>
            <label>
                Nom <br>
                <input type="text" name="name" required>
            </label>
        </p>
        <p>
            <label>
                Email <br>
                <input type="email" name="email" required>
            </label>
        </p>
        <p>
            <label>
                Object <br>
                <input type="text" name="subject" required>
            </label>
        </p>
        <p>
            <label>
                Message <br>
                <textarea name="content" id="" cols="30" rows="10" required></textarea>
            </label>
        </p>
        <button>Envoyer</button>
    </form>

    <script>
        $('form[name="contact"]').on('submit', function (event) {
            event.preventDefault();

            let data = $(this).serializeArray();

            $.post('{{ route('support.submit') }}', data).done(
                function (response) {
                    if (response.error) {
                        addError(response.message);
                        return;
                    }

                    addSuccess(response.message);
                }
            ).fail(errorAjax);

        });
    </script>
@endsection

