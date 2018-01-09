<?php /** @var \App\Http\Api\Review $review */ ?>
<h1>{{ $review->getText() }}</h1>
<h2>{{ $review->getReviewerName() }} - {{ $review->getRating() }} / 5</h2>