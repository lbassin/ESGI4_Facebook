<div class="list-content">
    <div class="grid">
        <?php /** @var \App\Model\HomeCategory $category */ ?>
        @foreach($categories as $category)
            <a href="#">
                <article class="module desktop-4 tablet-6">
                    <div class="element-image zoom"
                         style="background: url('{{ $category->getPreview() }}');background-repeat: no-repeat;background-position: center center;background-size: cover;">

                    </div>
                    <div class="element-name">
                        <span>{{ $category->getLabel() }}</span>
                    </div>
                </article>
            </a>
        @endforeach
    </div>
</div>