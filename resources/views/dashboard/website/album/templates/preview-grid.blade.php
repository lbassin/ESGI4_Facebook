<?php /** @var \App\Model\Template $template */ ?>
@foreach($templates as $template)
<div class="preview" data-target="preview-modal" data-id="{{ $template->getId() }}">
    @if($template->getId() == $selectedTemplate)
    <div class="overlay">
        <div class="selected"><i class="fa fa-check" aria-hidden="true"></i></div>
    </div>
    @endif
    <img src="{{ $template->getDesktopPreview() }}" alt="">
</div>
@endforeach