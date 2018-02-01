<?php /** @var \App\Model\HomeBlock $block */ ?>
<h2>Configuration</h2>
<form action="" name="block-config">
    @include('dashboard.website.home.elements.config.' . $block->getConfigPath())
    <input type="hidden" name="block_id" value="{{ $block->getId() }}">
    @if(isset($blockPosition))
        <input type="hidden" name="position" value="{{ $blockPosition }}">
    @endif
    <button>Valider</button>
</form>

<script>
    $('form[name="block-config"]').on('submit', function (event) {
        event.preventDefault();

        showLoader('loader');
        setTimeout(hideModal, 350, 'config-modal');

        addBlock($(this).serializeArray(), '{{ base64_encode($block->getSvgPreview()) }}');
        hideLoader('loader');
    });
</script>