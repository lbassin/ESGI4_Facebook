<div class="list-content">
    <div class="grid">
        <?php /** @var \App\Model\HomeBlock $block */ ?>
        @foreach($blocks as $block)
            <article class="module desktop-4 tablet-6" data-id="{{ $block->getId() }}">
                <div class="element-image zoom"
                     style="background: url('{{ $block->getPreview() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">
                </div>
                <div class="element-name">
                    <span>{{ $block->getLabel() }}</span>
                </div>
            </article>
        @endforeach
    </div>
</div>
<script>
    $('#blocks-modal').find('.element-image').click(function () {
        showLoader('loader');
        setTimeout(hideModal, 350, 'blocks-modal');

        let updatedDiv = $('#config-modal').find('.md-content');
        let url = '{{ route('dashboard.website.home.block.config', ['subdomain' => $subdomain]) }}';
        let blockId = $(this).parent().data('id');

        $.post(url, {block_id: blockId}).done(
            function (response) {
                updatedDiv.html(response);

                showModal('config-modal');
                setTimeout(hideLoader, 350, 'loader');
            }
        ).fail(errorAjax);
    });
</script>