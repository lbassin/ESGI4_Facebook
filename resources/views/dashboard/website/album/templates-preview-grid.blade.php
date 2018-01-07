<?php /** @var \App\Model\Template $template */ ?>
@foreach($templates as $template)
<div class="preview" data-target="modal-preview" data-id="{{ $template->getId() }}">
    <img src="{{ $template->getDesktopPreview() }}" alt="">
</div>
@endforeach