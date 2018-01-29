<div class="list-content">
    <div class="grid">
        <?php /** @var \App\Model\HomeBlock $block */ ?>
        @foreach($blocks as $block)
            <article class="module desktop-4 tablet-6">
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