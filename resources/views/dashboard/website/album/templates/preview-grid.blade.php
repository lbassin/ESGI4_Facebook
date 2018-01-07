<?php /** @var \App\Model\Template $template */ ?>
@foreach($templates as $template)
<div class="preview" data-target="preview-modal" data-id="{{ $template->getId() }}">
    <img src="{{ $template->getDesktopPreview() }}" alt="">
</div>
@endforeach