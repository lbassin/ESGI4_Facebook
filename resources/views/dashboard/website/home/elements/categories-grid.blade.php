<div class="list-content">
    <div class="grid">
        <?php /** @var \App\Model\HomeCategory $category */ ?>
        @foreach($categories as $category)
            <article class="module desktop-4 tablet-6" data-id="{{ $category->getId() }}">
                <div class="element-image zoom"
                     style="background: url('{{ $category->getPreview() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">
                </div>
                <div class="element-name">
                    <span>{{ $category->getLabel() }}</span>
                </div>
            </article>
        @endforeach
    </div>
</div>
<script>
    $('#categories-modal').find('.element-image').click(function () {
        showLoader('loader');
        setTimeout(hideModal, 350, 'categories-modal');

        let updatedDiv = $('#blocks-modal').find('.md-content');
        let url = '{{ route('dashboard.website.home.blocks', ['subdomain' => $subdomain]) }}';
        let categoryId = $(this).parent().data('id');

        $.post(url, {category: categoryId}).done(
            function (response) {
                updatedDiv.html(response);

                showModal('blocks-modal');
                setTimeout(hideLoader, 350, 'loader');
            }
        ).fail(errorAjax);
    });
</script>