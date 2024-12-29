@extends('layouts.app')

@push('styles')
    <style>
        /* Header */
        .header-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
        }

        /* Card Container */
        .card-container {
            display: flex;
            flex-direction: column;
            gap: 35px;
        }

        /* Card */
        .card {
            display: flex;
            flex-direction: row;
            max-height: 250px;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 0;
        }

        /* Card Image Section */
        .card-image {
            position: relative;
            flex: 1;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-image .badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            padding: 5px 10px;
            font-size: 12px;
            border-radius: 4px;
        }

        /* Card Details */
        .card-details {
            flex: 2;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
        }

        .card-title {
            font-size: 20px;
            font-weight: bold;
        }

        .card-text {
            font-size: 14px;
            color: #555;
            margin: 0px;
        }

        /* Rating Section */
        .rating {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin: 0px;
        }

        .card-text+.rating {
            margin: 0px;
        }

        .rating .stars {
            font-size: 18px;
            color: #ffcc00;
        }

        .rating .score-reviews {
            display: flex;
            align-items: center;
        }

        .rating .score {
            background-color: #eaf5ea;
            color: #4caf50;
            font-size: 14px;
            padding: 5px 8px;
            border-radius: 4px;
            margin-right: 10px;
        }

        .rating .reviews {
            font-size: 12px;
            color: #888;
        }

        /* Button */
        .view-place-btn {
            background-color: #4caf50;
            color: #fff;
            text-decoration: none;
            text-align: center;
            font-size: 14px;
            padding: 10px 20px;
            border-radius: 4px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .view-place-btn:hover {
            background-color: #45a049;
        }
    </style>
@endpush

@section('content')
    <!-- Main Content -->
    <div class="container-fluid w-100 py-5" style="padding-left: 5%; padding-right: 5%;">
        <h3 class="pb-4 fw-bold text-center">Bookmarks</h3>
        @if (Auth::check())
            <div class="card-container">
                @forelse ($bookmarks as $bookmark)
                    @php
                        $place = $bookmark->destination;
                        $rating = floatval($place->ratings);
                        $fullStars = floor($rating);
                        $halfStar = $rating - $fullStars >= 0.5 ? true : false;
                        $imagePath = Storage::disk('public')->files($place->image_folder_path)[0] ?? null;
                    @endphp
                    <!-- Card -->
                    <div class="card mb-4 shadow-sm">
                        <div class="row g-0 w-100">
                            <div class="col-md-3 p-0">
                                <div class="card-image">
                                    @if ($imagePath)
                                        <img class="img-fluid rounded-start" src="{{ asset('storage/' . $imagePath) }}" alt="{{ $place->name }}" />
                                    @endif
                                    <p class="badge">9 images</p>
                                </div>
                            </div>
                            <div class="col-md-9 px-2">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $place->name }}</h5>
                                    <p class="card-text"><i class="fas fa-map-marker-alt me-2"></i>{{ $place->address }}</p>
                                    <div class="rating">
                                        <p class="stars p-0 m-0 pb-1" style="color: #ff8682">
                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <i class="fas fa-star fs-6"></i>
                                            @endfor
                                            @if ($halfStar)
                                                <i class="fas fa-star-half-alt fs-6"></i>
                                            @endif
                                            @for ($i = $fullStars + $halfStar; $i < 5; $i++)
                                                <i class="far fa-star fs-6"></i>
                                            @endfor
                                        </p>
                                        <div class="score-reviews">
                                            <p class="score">{{ $place->ratings }}</p>
                                            <p class="reviews">
                                                @php
                                                    $ratingText = match(true) {
                                                        $place->ratings == 5.0 => 'Excellent',
                                                        $place->ratings >= 4.0 => 'Good',
                                                        $place->ratings >= 3.0 => 'Fair',
                                                        $place->ratings >= 2.0 => 'Poor',
                                                        $place->ratings >= 1.0 => 'Very Poor',
                                                        default => 'Terrible'
                                                    };
                                                @endphp
                                                <strong>{{ $ratingText }}</strong> - <strong>{{ $place->review_count }} reviews</strong>
                                            </p>
                                        </div>
                                    </div>
                                    <hr style="margin-top: 0px; border-top: 1px solid #000000; width: 100%;">
                                    <div class="d-flex w-100 gap-2">
                                        <form action="{{ route('bookmarks.destroy', ['bookmark' => $bookmark->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-2 flex-grow-0 btn btn-outline-secondary" type="submit" style="width: 44px; height: 44px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-bookmark text-dark"></i>
                                            </button>
                                        </form>
                                        <a class="btn flex-grow-1 fw-semibold align-content-center" href="{{ route('place.detail', ['id' => $place->id]) }}" style="background-color: #8dd3bb">View Place</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="m-0 py-3 px-2">
                        <div class="alert alert-warning text-center shadow-sm" role="alert">
                            Belum ada tempat yang di-bookmark. Silahkan lihat <a class="alert-link" href="{{ route('recommendations') }}">Rekomendasi</a> untuk menambahkan tempat favoritmu.
                        </div>
                    </div>
                @endforelse
            </div>
        @else
            <div class="m-0 py-3 px-2">
                <div class="alert alert-warning text-center shadow-sm" role="alert">
                    Silakan <a class="alert-link" href="{{ route('login') }}">Login</a> terlebih dahulu untuk melihat bookmark Anda.
                </div>
            </div>
        @endif
    </div>

    @include('components.footer')
@endsection
