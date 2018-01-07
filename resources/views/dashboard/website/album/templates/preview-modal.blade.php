<?php /** @var \App\Model\Template $template */ ?>
<h1>Mise en page</h1>

<div class="container-preview">
    <div class="desktop-preview">
        <img src="{{ $template->getDesktopPreview() }}" alt="">
    </div>
    <div class="mobile-preview">
        <img src="{{ $template->getMobilePreview() }}" alt="">
    </div>
</div>

<div class="controls">
    <span id="template-cancel">Cancel</span>
    <span id="template-submit">Valider</span>
</div>